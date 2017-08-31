<template>
  <b-field :label="label" class="nz-taxon-autocomplete">
    <b-field grouped>
      <img width="32" :src="this.selected.thumbnail_url" v-if="haveThumbnail">

      <b-autocomplete :value="name"
                      :data="data"
                      field="name"
                      :loading="loading"
                      @input="onInput"
                      @select="onSelect"
                      :keep-first="true"
                      :icon="icon"
                      :placeholder="placeholder"
                      expanded>

        <template scope="props">
          <div class="media">
            <div class="media-left">
              <img width="32" :src="props.option.thumbnail_url" v-if="props.option.thumbnail_url">
            </div>
            <div class="media-content">
              {{ props.option.name }}
            </div>
          </div>
        </template>

      </b-autocomplete>
    </b-field>
  </b-field>
</template>

<script>
import axios from 'axios';
import { debounce } from 'lodash-es';

export default {
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
      default: '/api/taxa'
    }
  },

  data() {
    return {
      data: [],
      selected: this.taxon || null,
      loading: false,
      name: null
    };
  },

  computed: {
    haveThumbnail() {
      return this.selected && this.selected.thumbnail_url;
    },

    icon() {
      return this.selected ? 'check' : 'search';
    }
  },

  methods: {
    fetchData: debounce(function() {
      if (!this.name) return;

      this.data = [];
      this.loading = true;

      axios.get(`${this.url}?name=${this.name}`).then(({ data }) => {
        data.data.forEach((item) => this.data.push(item))
        this.loading = false
      }, response => {
        this.loading = false
      });
    }, 500),

    onSelect(taxon) {
      this.selected = taxon;

      this.$emit('select', taxon);
    },

    onInput(value) {
      this.name = value;

      this.$emit('input', value);

      this.fetchData()
    }
  }
}
</script>
