<template>
  <div class="citation">
    <div class="container">
      <h1 class="title">{{ trans('about.citation_title') }}</h1>

      <p>{{ trans('about.citation_intro_text') }}</p>

      <div class="box has-background-white-bis" style="padding: 20px;">
        <p style="font-family: monospace; font-size: 0.95em;">
          Popović M, Vasić N, Koren T, Burić I, Živanović N, Kulijer D, Golubović A (2020)
          Biologer: an open platform for collecting biodiversity data.
          <i>Biodiversity Data Journal</i> 8: e53014. DOI:
          <a href="https://doi.org/10.3897/BDJ.8.e53014">10.3897/BDJ.8.e53014</a>.
        </p>
      </div>

      <hr>

      <h2 class="title is-4">{{ trans('about.citation_intro_text_2') }}</h2>

      <div class="box has-background-white-bis" style="padding: 20px;">
        <p style="font-family: monospace; font-size: 0.95em;">
          {{ communityName }} ({{ platformYear }})
          {{ trans('about.community_desc') }}.
          {{ trans('about.community_url') }}: <a v-bind:href="platformUrl" target="_blank">{{ platformUrl }}</a>
          {{ trans('about.community_assessed') }}:
          {{ getCurrentDate() }}
        </p>
      </div>

      <h2 class="title is-4">{{ trans('about.citation_intro_text_3') }}</h2>

      <!-- Taxon search -->
      <nz-taxon-autocomplete
        v-model="form.taxon_suggestion"
        @select="onTaxonSelect"
        :taxon="form.taxon"
        autofocus
        ref="taxonAutocomplete"
        :label="trans('labels.field_observations.taxon')"
        :placeholder="trans('labels.field_observations.search_for_taxon')"
      />

      <div v-if="curators.length" class="box is-shadowless has-background-light p-5">

        <div id="citation-results-container">
          <!-- Kartica za prikaz citata (inicijalno skrivena) -->
          <div class="card mt-4" id="citation-card">
            <div class="card-content">
              <!-- Lista kuratora -->
              <div class="mb-4">
                <h4>Curators for all lower taxa:</h4>
                <div v-if="curators.length">
                  <ul>
                    <li v-for=" c in curators" :key="c.id">
                      {{ c.name }}
                    </li>
                  </ul>
                </div>
              </div>

              <p id="curator-list" class="mb-3 has-text-weight-semibold"></p>

              <!-- Generisani citat -->
              <div class="box has-background-white-bis p-3">
                <p style="font-family: monospace; font-size: 0.95em;" id="generated-citation"></p>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import NzTaxonAutocomplete from '@/components/inputs/TaxonAutocomplete'
import NzUserAutocomplete from '@/components/inputs/UserAutocomplete'

export default {
  name: 'nzCitation',

  components: {
    NzTaxonAutocomplete,
    NzUserAutocomplete
  },

  props: {
    communityName: String,
    platformYear: String,
    platformUrl: String,
  },

  data() {
    return {
      form: {
        taxon: null,
        taxon_id: null,
        taxon_suggestion: '',
        curator: null,
        curator_id: null,
      },

      curators: [],
      descendantTaxa: []
    }

  },

  methods: {
    getCurrentDate() {
      const today = new Date();

      const year = today.getFullYear();
      const month = today.getMonth() + 1;
      const day = today.getDate();

      return `${day.toString().padStart(2, '0')}.${month.toString().padStart(2, '0')}.${year.toString().padStart(2, '0')}.`;
    },

    /**
     * Handle taxon being selected.
     *
     * @param {Object} taxon
     */
    onTaxonSelect(taxon) {
      this.taxon = taxon || null
      this.taxon_id = taxon ? taxon.id : null
      this.taxon_suggestion = taxon ? taxon.name : null

      if (this.taxon) {
        this.fetchLowerTaxaAndCurators(this.taxon.id)
      }
    },

    fetchLowerTaxaAndCurators(taxonId) {
      axios.get(`/taxa/${taxonId}/descendants-curators`)
        .then(response => {
          this.descendantTaxa = response.data.taxa;

          const curators = response.data.taxa.flatMap(t => t.curators);

          // Unique
          const map = {};
          for (const c of curators) map[c.id] = c;

          this.curators = Object.values(map);
        });
    },
  }

}
</script>
