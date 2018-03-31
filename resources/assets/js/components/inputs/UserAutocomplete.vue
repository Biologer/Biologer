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
      >
        <template slot-scope="props">
          <div class="media">
            <div class="media-content">
              {{ props.option.full_name }} <small>&lt;{{ props.option.email }}&gt;</small>
            </div>
          </div>
        </template>

      </b-autocomplete>
    </b-field>
  </b-field>
</template>

<script>
import axios from 'axios';

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
        autofocus: Boolean
    },

    data() {
        return {
            data: [],
            selected: this.user || null,
            loading: false
        };
    },

    computed: {
        icon() {
            return this.selected ? 'check' : null;
        }
    },

    methods: {
        fetchData: _.debounce(function() {
            if (!this.value) return;

            this.data = [];
            this.loading = true;

            let params = {
                name: this.value,
            };

            if (this.except) {
                params.except = this.except;
            }

            axios.get(route(this.route), { params }).then(({ data }) => {
                data.data.forEach((item) => this.data.push(item));
                this.loading = false;
            }, response => {
                this.loading = false;
            });
        }, 500),

        onSelect(user) {
            this.selected = user;

            this.$emit('select', user);
        },

        onInput(value) {
            this.$emit('input', value);

            this.fetchData()
        },

        focusOnInput() {
          this.$el.querySelector('input').focus();
        }
    }
}
</script>
