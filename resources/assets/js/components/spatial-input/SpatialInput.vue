<template>
    <div class="">
        <div class="field is-expanded">
            <label class="label">Location</label>
            <div class="control is-fullwidth">
                <input class="input" :class="{'is-danger': errors.has('location')}" :value="location" @input="updateLocation" placeholder="Location">
            </div>
            <p class="help is-danger" v-if="errors.has('location')" v-text="errors.first('location')"></p>
        </div>
        <b-field label="Map">
            <gmap-map  style="width: 100%; min-height: 400px"
                :center="{lat: 45.0, lng: 20.0}"
                :zoom="8"
                @click="setMarker">
                <gmap-marker :position="position"
                             :clickable="true"
                             :draggable="true"
                             @dragend="setMarker">
                </gmap-marker>
                <gmap-circle v-if="position && accuracy"
                             :center="position"
                             :radius="accuracy"
                             :editable="true"
                             @radius_changed="updateRadius">
                </gmap-circle>
            </gmap-map>
        </b-field>
        <div class="has-text-right">
            <span v-if="mapHasErrors" class="has-text-danger is-size-7">Some fields have errors</span>
            <button type="button" class="button is-white is-small is-text" :class="{'has-text-danger': mapHasErrors}" @click="showDetails = !showDetails">Details</button>
        </div>
        <div v-show="showDetails">
            <div class="field is-grouped">
                <div class="field is-expanded">
                    <label class="label is-small">Latitude</label>
                    <div class="control is-fullwidth">
                        <input class="input is-small" :class="{'is-danger': errors.has('latitude')}" :value="latitude" @input="updateLatitudeDebounced" placeholder="f.e. 42.5234">
                    </div>
                    <p class="help is-danger" v-if="errors.has('latitude')" v-text="errors.first('latitude')"></p>
                </div>
                <div class="field is-expanded">
                    <label class="label is-small">Longitude</label>
                    <div class="control is-fullwidth">
                        <input class="input is-small" :class="{'is-danger': errors.has('longitude')}" :value="longitude" @input="updateLongitudeDebounced" placeholder="f.e. 19.1234">
                    </div>
                    <p class="help is-danger" v-if="errors.has('longitude')" v-text="errors.first('longitude')"></p>
                </div>
            </div>
            <div class="field is-grouped">
                <div class="field is-expanded">
                    <label class="label is-small">Accuracy/Radius (m)</label>
                    <div class="control is-fullwidth">
                        <input class="input is-small" :value="accuracy" @input="updateAccuracyDebounced" placeholder="f.e. 100">
                    </div>
                    <p class="help is-danger" v-if="errors.has('accuracy')" v-text="errors.first('accuracy')"></p>
                </div>
                <div class="field is-expanded">
                    <label class="label is-small">Altitude (m)</label>
                    <div class="control is-fullwidth">
                        <input class="input is-small" :value="altitude" @input="updateAltitude" placeholder="f.e. 500">
                    </div>
                    <p class="help is-danger" v-if="errors.has('altitude')" v-text="errors.first('altitude')"></p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'nz-spatial-input',

    props: {
        accuracy: {
            type: Number,
            default: 5
        },
        altitude: {
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
                return {};
            }
        }
    },

    data() {
        return {
            showDetails: false,
            elevationService: null
        };
    },

    computed: {
        position() {
            if (!this.coordinatesSet) {
                return null;
            }

            return {lat: this.latitude, lng: this.longitude};
        },

        coordinatesSet() {
            return !(isNaN(this.latitude)
                || this.latitude === null
                || isNaN(this.longitude)
                || this.longitude === null);
        },

        mapHasErrors() {
            return this.errors.has('latitude') ||
                this.errors.has('longitude') ||
                this.errors.has('altitude') ||
                this.errors.has('accuracy');
        }
    },

    methods: {
        castNumber(value) {
            return isNaN(Number(value)) ? null : Number(value);
        },
        updateLocation(event) {
            this.$emit('update:location', event.target.value);
        },
        updateLongitudeDebounced: _.debounce(function (event) {
            this.updateLongitude(event.target.value);
        }, 1000),
        updateLongitude(value) {
            this.$emit('update:longitude', this.castNumber(value));
        },
        updateLatitudeDebounced: _.debounce(function (event) {
            this.updateLatitude(event.target.value);
        }, 1000),
        updateLatitude(value) {
            this.$emit('update:latitude', this.castNumber(value));
        },
        updateAccuracy(value) {
            this.$emit('update:accuracy', this.castNumber(value));
        },
        updateAccuracyDebounced: _.debounce(function (event) {
            this.updateAccuracy(event.target.value);
        }, 1000),
        updateAltitude(value) {
            this.$emit('update:altitude', this.castNumber(value));
        },
        setMarker(position) {
            let lat = position.latLng.lat();
            let lng = position.latLng.lng();

            this.updateLatitude(lat);
            this.updateLongitude(lng);

            this.getElevation({ lat, lng });
        },
        updateRadius(value) {
            this.updateAccuracy(parseInt(value));
        },

        getElevation(position) {
            if (!this.elevationService) {
                this.elevationService = new google.maps.ElevationService();
            }

            this.elevationService.getElevationForLocations({
                locations: [position]
            }, (results, status) => {
                if (status != google.maps.ElevationStatus.OK || !results.length) {
                    return;
                }

                this.updateAltitude(parseInt(results[0].elevation));
            })
        }
    }
}
</script>
