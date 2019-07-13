<template>
  <div class="spatial-input">
    <b-field :label="trans('labels.field_observations.map')" class="is-required">
      <gmap-map
        style="width: 100%; min-height: 400px"
        :center="centerCoordinates"
        :zoom="center.zoom"
        @click="setMarker"
        @zoom_changed="onZoomChanged"
        :options="{streetViewControl:false}"
      >
        <gmap-marker
          :position="position"
          :clickable="true"
          :draggable="true"
          @dragend="setMarker"
        />

        <gmap-circle
          v-if="position && newAccuracy"
          :center="position"
          :radius="newAccuracy"
          :editable="true"
          @radius_changed="updateRadius"
          :options="{ strokeWeight: .75 }"
        />
      </gmap-map>
    </b-field>

    <div class="has-text-right">
      <span v-if="mapHasErrors" class="has-text-danger is-size-7">{{ trans('Some fields have errors') }}</span>

      <button type="button" class="button is-white is-small is-text" :class="{'has-text-danger': mapHasErrors}" @click="showDetails = !showDetails">{{ trans('labels.field_observations.details') }}</button>
    </div>

    <div v-show="showDetails">
      <div class="field is-grouped">
        <div class="field is-expanded is-required">
          <label class="label is-small">{{ trans('labels.field_observations.latitude') }}</label>

          <div class="control is-fullwidth">
            <input class="input is-small" :class="{'is-danger': errors.has('latitude')}" :value="latitude" @input="onLatitudeInput" placeholder="f.e. 42.5234">
          </div>

          <p class="help is-danger" v-if="errors.has('latitude')" v-text="errors.first('latitude')"></p>
        </div>

        <div class="field is-expanded is-required">
          <label class="label is-small">{{ trans('labels.field_observations.longitude') }}</label>

          <div class="control is-fullwidth">
            <input class="input is-small" :class="{'is-danger': errors.has('longitude')}" :value="longitude" @input="onLongitudeInput" placeholder="f.e. 19.1234">
          </div>

          <p class="help is-danger" v-if="errors.has('longitude')" v-text="errors.first('longitude')"></p>
        </div>
      </div>

      <div class="field is-grouped">
        <div class="field is-expanded">
          <label class="label is-small">{{ trans('labels.field_observations.accuracy_m') }}</label>

          <div class="control is-fullwidth">
            <input class="input is-small" :value="accuracy" @input="onAccuracyInput" placeholder="f.e. 100">
          </div>

          <p class="help is-danger" v-if="errors.has('accuracy')" v-text="errors.first('accuracy')"></p>
        </div>

        <div class="field is-expanded is-required">
          <label class="label is-small">{{ trans('labels.field_observations.elevation_m') }}</label>

          <div class="control is-fullwidth">
            <input class="input is-small" :value="elevation" @input="onElevationInput" placeholder="f.e. 500">
          </div>

          <p class="help is-danger" v-if="errors.has('elevation')" v-text="errors.first('elevation')"></p>
        </div>
      </div>

      <div class="field is-expanded">
        <label class="label is-small">{{ trans('labels.field_observations.location') }}</label>

        <div class="control is-fullwidth">
          <input class="input is-small" :class="{'is-danger': errors.has('location')}" :value="location" @input="onLocationInput" placeholder="Location">
        </div>

        <p class="help is-danger" v-if="errors.has('location')" v-text="errors.first('location')"></p>
      </div>

      <slot />
    </div>
  </div>
</template>

<script>
import _debounce from 'lodash/debounce'

const ZOOM_ONE = 6144000

export default {
  name: 'nzSpatialInput',

  props: {
    accuracy: Number,
    elevation: {
      type: Number,
      default: 100
    },
    latitude: {
      type: Number,
      default: 41.0
    },
    longitude: {
      type: Number,
      default: 19.0
    },
    location: {
      type: [String, Number],
      default: ''
    },
    errors: {
      type: Object,
      default() {
        return {}
      }
    },
    hasOtherErrors: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      emptyAccuracy: this.calculateEmptyAccuracy(window.App.gmaps.center.zoom),
      showDetails: false,
      elevationService: null,
      center: window.App.gmaps.center
    }
  },

  computed: {
    /**
     * Position object from coordinates.
     *
     * @return {Object}
     */
    position() {
      if (!this.coordinatesSet) {
        return null
      }

      return {lat: this.latitude, lng: this.longitude}
    },

    /**
     * Check if coordinate are set.
     *
     * @return {Boolean}
     */
    coordinatesSet() {
      return !(isNaN(this.latitude)||
        this.latitude === null ||
        isNaN(this.longitude) ||
        this.longitude === null
      )
    },

    /**
     * Check if any of the fields has an error.
     *
     * @return {Boolean}
     */
    mapHasErrors() {
      return this.hasOtherErrors ||
          this.errors.has('latitude') ||
          this.errors.has('longitude') ||
          this.errors.has('elevation') ||
          this.errors.has('accuracy') ||
          this.errors.has('location')
    },

    newAccuracy () {
      return this.accuracy || this.emptyAccuracy
    },

    centerCoordinates() {
      return this.position || {
        lat: this.center.latitude,
        lng: this.center.longitude
      }
    }
  },

  watch: {
    /**
     * Get elevation when coordinates change.
     */
    position(value) {
      if (this.coordinatesSet) {
        this.getElevation(value)
      }
    },
  },

  created() {
    this.setCurrentLocationAsMapCenter()
  },

  methods: {
    /**
     * Cast to number or null.
     *
     * @param  {any} value
     * @return {Number}
     */
    castNumber(value) {
      return isNaN(value) || value === '' ? null : Number(value)
    },

    /**
     * Handle location input.
     * @param {Object} event
     */
    onLocationInput(event) {
      this.updateLocation(event.target.value)
    },

    /**
     * Sync location property.
     *
     * @param {String} value
     */
    updateLocation(value) {
      this.$emit('update:location', value)
    },

    /**
     * Handle longitude input.
     * @param  {[type]} event [description]
     * @return {[type]}       [description]
     */
    onLongitudeInput: _debounce(function (event) {
      this.updateLongitude(event.target.value)
    }, 1000),

    /**
     * Sync longitude property.
     *
     * @param {Number} value
     */
    updateLongitude(value) {
      this.$emit('update:longitude', this.castNumber(value))
    },

    /**
     * Handle latitude input.
     *
     * @param {Object} event
     */
    onLatitudeInput: _debounce(function (event) {
      this.updateLatitude(event.target.value)
    }, 1000),

    /**
     * Sync latitude property.
     *
     * @param {Number} value
     */
    updateLatitude(value) {
      this.$emit('update:latitude', this.castNumber(value))
    },

    /**
    * Handle accuracy input.
    *
    * @param {Object} event
    */
    onAccuracyInput: _debounce(function (event) {
      this.updateAccuracy(event.target.value)
    }, 1000),

    /**
     * Sync accuracy property.
     *
     * @param {Number} value
     */
    updateAccuracy(value) {
      this.$emit('update:accuracy', this.castNumber(value))
    },

    /**
     * Handle elevation input.
     *
     * @param {Object} event
     */
    onElevationInput(event) {
      this.updateElevation(event.target.value)
    },

    /**
     * Sync elevation property.
     *
     * @param {Number} value
     */
    updateElevation(value) {
      this.$emit('update:elevation', this.castNumber(value))
    },

    /**
     * Update coordinates from marker position.
     *
     * @param {Object} position
     */
    setMarker(position) {
      let lat = position.latLng.lat()
      let lng = position.latLng.lng()

      this.updateLatitude(lat)
      this.updateLongitude(lng)
    },

    /**
     * Update accuracy from circle radius on gmaps.
     *
     * @param  {Number} value
     */
    updateRadius(value) {
      let radius = parseInt(value)

      if (this.accuracy === null && radius === this.emptyAccuracy) {
        return
      }

      this.updateAccuracy(radius)
    },

    /**
     * Get elevation using Google's service.
     *
     * @param  {Object} position
     */
    getElevation(position) {
      // Do nothing if we still don't have gmaps library loaded.
      if (!google || !google.maps || !google.maps.ElevationService) {
        return
      }

      // We don't initialize service when component is created
      // because Gmap library probably isn't loaded yet,
      // so we do it on first use of this method.
      if (!this.elevationService) {
        this.elevationService = new google.maps.ElevationService()
      }

      this.elevationService.getElevationForLocations({
        locations: [position]
      }, (results, status) => {
        if (status != google.maps.ElevationStatus.OK || !results.length) {
          return
        }

        const elevation = parseInt(results[0].elevation)

        this.$emit('elevation-fetched', elevation)

        this.updateElevation(elevation)
      })
    },

    /**
     * Recalculate default accuracy when zoom is changed.
     * @param  {Number} zoom
     */
    onZoomChanged(zoom) {
      this.emptyAccuracy = this.calculateEmptyAccuracy(zoom)
    },

    /**
     * Calculate accuracy based on map zoom.
     * @param  {Number} zoom
     * @return {Number}
     */
    calculateEmptyAccuracy(zoom) {
      return parseInt(ZOOM_ONE / Math.pow(2, zoom))
    },

    /**
     * Use browser location to set map center.
     */
    setCurrentLocationAsMapCenter() {
      // We don't want to use this feature if the marker is set.
      if (!navigator.geolocation || this.position) {
        return
      }

      navigator.geolocation.getCurrentPosition(position => {
        if (this.position) {
          return
        }

        this.center.latitude = position.coords.latitude
        this.center.longitude = position.coords.longitude
        this.center.zoom = 10
      })
    }
  }
}
</script>
