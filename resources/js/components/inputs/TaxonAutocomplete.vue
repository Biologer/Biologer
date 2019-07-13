<template>
  <b-field :label="label" class="nz-taxon-autocomplete" :type="error ? 'is-danger' : null" :message="message">
    <b-field grouped>
      <img width="32" :src="this.selected.thumbnail_url" v-if="haveThumbnail">

      <b-autocomplete
        ref="autocomplete"
        :value="value"
        :data="data"
        field="name"
        :loading="loading"
        @input="onInput"
        @select="onSelect"
        :icon="icon"
        :placeholder="placeholder"
        expanded
        :autofocus="autofocus"
        @keydown.native.enter="enterPressed"
      >
        <template slot-scope="props">
          <div class="media">
            <div class="media-left">
              <img width="32" :src="props.option.thumbnail_url" v-if="props.option.thumbnail_url">
            </div>

            <div class="media-content">
              {{ props.option.name }}{{ props.option.native_name ? ` (${props.option.native_name})` : ''}}
            </div>
          </div>
        </template>

      </b-autocomplete>
    </b-field>
  </b-field>
</template>

<script>
import axios from 'axios'
import _debounce from 'lodash/debounce'
import _get from 'lodash/get'

export default {
  name: 'nzTaxonAutocomplete',

  props: {
    label: {
      type: String,
      default: 'Taxon'
    },
    placeholder: {
      type: String,
      default: 'Search for taxon...'
    },
    taxon: {
      type: Object,
      default: null
    },
    url: {
      type: String,
      default: () => route('api.taxa.index')
    },
    value: {
      type: String,
      default: ''
    },
    error: Boolean,
    message: {
      type: String,
      default: null
    },
    except: {},
    autofocus: Boolean
  },

  data() {
    return {
      data: [],
      selected: this.taxon || null,
      loading: false
    }
  },

  computed: {
    haveThumbnail() {
      return this.selected && this.selected.thumbnail_url
    },

    icon() {
      return this.selected ? 'check' : 'search'
    }
  },

  watch: {
    taxon(value) {
      this.selected = value
    }
  },

  methods: {
    fetchData: _debounce(function() {
      if (!this.value) return

      this.data = []
      this.loading = true

      let params = {
        name: this.value,
        limit: 20,
      }

      if (this.except) {
        params.except = this.except
      }

      axios.get(this.url, {
        params
      }).then(({
        data
      }) => {
        data.data.forEach(item => this.data.push(item))
        this.loading = false
      }, response => {
        this.loading = false
      })
    }, 500),

    onSelect(taxon) {
      this.selected = taxon

      this.$emit('select', taxon)
    },

    onInput(value) {
      const currentValue = this.getValue(this.selected)
      if (currentValue && currentValue !== value) {
        this.onSelect(null)
      }

      this.$emit('input', value)

      this.fetchData()
    },

    focusOnInput() {
      this.$el.querySelector('input').focus()
    },

    /**
     * Return display text for the input.
     * If object, get value from path, or else just the value.
     */
    getValue(option) {
      if (!option) return

      return typeof option === 'object'
        ? _get(option, 'name')
        : option
    },

    enterPressed() {
      if (!this.$refs.autocomplete.isActive) {
        this.$emit('enter')
      }
    }
  }
}
</script>
