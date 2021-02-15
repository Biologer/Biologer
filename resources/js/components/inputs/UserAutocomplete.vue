<template>
  <b-field :label="label" class="nz-user-autocomplete" :type="error ? 'is-danger' : null" :message="message">
    <b-field grouped>
      <b-autocomplete
        :value="value"
        :data="data"
        field="full_name"
        :loading="loading"
        @input="onInput"
        @select="onSelect"
        :icon="icon"
        :placeholder="placeholder"
        expanded
        :autofocus="autofocus"
        :disabled="disabled"
        :class="[selected ? 'has-icon-success' : '']"
      >
        <template #default="{ option }">
          <div class="media">
            <div class="media-content">
              {{ option.full_name }} <small>&lt;{{ option.email }}&gt;</small>
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
  name: 'nzUserAutocomplete',

  props: {
    label: {
      type: String,
      default: 'User'
    },
    placeholder: {
      type: String,
      default: 'Search for user...'
    },
    user: {
      type: Object,
      default: null
    },
    route: {
      type: String,
      default: 'api.autocomplete.users.index'
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
    autofocus: Boolean,
    disabled: Boolean
  },

  data() {
    return {
      data: [],
      selected: this.user || null,
      loading: false
    }
  },

  computed: {
    icon() {
      return this.selected ? 'check' : null
    },
  },

  watch: {
    user(value) {
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
      }

      if (this.except) {
        params.except = this.except
      }

      axios.get(route(this.route), { params }).then(({ data }) => {
        data.data.forEach((item) => this.data.push(item))
        this.loading = false
      }, response => {
        this.loading = false
      })
    }, 500),

    onSelect(user) {
      this.selected = user

      this.$emit('select', user)
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
        ? _get(option, 'full_name')
        : option
    }
  }
}
</script>
