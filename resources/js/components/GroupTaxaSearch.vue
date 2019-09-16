<template>
  <div class="modal-card group-taxa-search">
    <header class="modal-card-head">
      <p class="modal-card-title">{{ trans('navigation.find_in_group') }}</p>
    </header>

    <section class="modal-card-body">
      <nz-taxon-autocomplete
        v-model="name"
        @select="onTaxonSelect"
        autofocus
        :url="url"
        :label="trans('labels.field_observations.taxon')"
        :placeholder="trans('labels.field_observations.search_for_taxon')"
        @enter="visitLink"
      />
    </section>

    <footer class="modal-card-foot" style="justify-content: flex-end">
      <a :href="link" class="button is-primary" :disabled="!link">
        <i class="fa fa-arrow-right" />
      </a>
    </footer>
  </div>
</template>

<script>
import NzTaxonAutocomplete from '@/components/inputs/TaxonAutocomplete'

export default {
  name: 'nzGroupTaxaSearch',

  components: {
    NzTaxonAutocomplete
  },

  props: {
    group: {
      type: Number,
      required: true
    }
  },

  data() {
    return {
      name: '',
      selected: null
    }
  },

  computed: {
    link() {
      return this.selected && this.selected.first_species_id ? route('groups.species.show', {
        group: this.group,
        species: this.selected.first_species_id
      }) : null;
    },

    url() {
      return route('api.groups.taxa.index', { group: this.group });
    }
  },

  methods: {
    onTaxonSelect(taxon) {
      this.selected = taxon;
    },

    visitLink() {
      if (this.link) {
        window.location.href = this.link;
      }
    }
  }
}
</script>
