<template>
  <div class="citation">

    <!-- GENERAL CITATION OF THE SCIENTIFIC PUBLICATION -->

    <div class="container">
      <h1 class="title">{{ trans('about.citation_title') }}</h1>

      <p class="mb-3">{{ trans('about.citation_intro_text') }}</p>

      <div class="box has-background-white-bis" style="padding: 20px;">
        <p style="font-family: monospace; font-size: 0.95em;">
          Popović M., Vasić N., Koren T., Burić I., Živanović N., Kulijer D., Golubović A. (2020)
          Biologer: an open platform for collecting biodiversity data.
          <i>Biodiversity Data Journal</i> 8: e53014. DOI:
          <a href="https://doi.org/10.3897/BDJ.8.e53014">10.3897/BDJ.8.e53014</a>.
        </p>
      </div>

      <!-- CITATION OF THE BIOLOGER COMMUNITY -->

      <h2 class="title is-4">{{ trans('about.citation_subtitle1') }}</h2>

      <p class="mb-3">{{ trans('about.citation_intro_text_2') }}</p>

      <div class="box has-background-white-bis" style="padding: 20px;">
        <p style="font-family: monospace; font-size: 0.95em;">
          {{ communityName }} ({{ platformYear }})
          {{ trans('about.community_desc') }}.
          {{ trans('about.community_url') }}: <a v-bind:href="platformUrl" target="_blank">{{ platformUrl }}</a>.
          {{ trans('about.community_assessed') }}:
          {{ getCurrentDate() }}
        </p>
      </div>

      <!-- GENERAL CITATION OF THE TAXONOMIC GROUP -->

      <h2 class="title is-4">{{ trans('about.citation_subtitle2') }}</h2>

       <!-- Allow search if the user is logged in -->
      <div v-if="isLoggedIn">

        <p class="mb-3">{{ trans('about.citation_intro_text_3') }}</p>
        
        <nz-taxon-autocomplete
        v-model="form.taxon_suggestion"
        @select="onTaxonSelect"
        :taxon="form.taxon"
        autofocus
        ref="taxonAutocomplete"
        :label="''"
        :placeholder="trans('labels.field_observations.search_for_taxon')"
        />

        <div v-if="form.taxon && curators.length" class="box has-background-white-bis" style="padding: 20px;">
          <p style="font-family: monospace; font-size: 0.95em;">
            {{ curatorsList }}
            ({{ platformYear }})
            {{ trans('about.community_desc') }}
            ({{ trans('about.community_group', { SCIENTIFIC_NAME: form.taxon.name }) }}).
            {{ trans('about.community_url') }}: <a v-bind:href="platformUrl" target="_blank">{{ platformUrl }}</a>.
            {{ trans('about.community_assessed') }}:
            {{ getCurrentDate() }}
          </p>
        </div>

        <div v-else-if="form.taxon && !curators.length" class="box has-background-white-bis" style="padding: 20px;">
          <p style="font-family: monospace; font-size: 0.95em;">
              {{ communityName }}
              ({{ platformYear }})
              {{ trans('about.community_desc') }}
              ({{ trans('about.community_group', { SCIENTIFIC_NAME: form.taxon.name }) }}).
              {{ trans('about.community_url') }}: <a v-bind:href="platformUrl" target="_blank">{{ platformUrl }}</a>.
              {{ trans('about.community_assessed') }}:
              {{ getCurrentDate() }}
            </p>
        </div>
      </div>

      <!-- General message if the user is logged out -->
      <div v-else>
        <p class="mb-3"> {{ trans('about.login_required') }} </p>
        <div class="box has-background-white-bis" style="padding: 20px;">
          <p style="font-family: monospace; font-size: 0.95em;">
            {{ trans('about.editors') }}
            ({{ platformYear }})
            {{ trans('about.community_desc') }}
            ({{ trans('about.community_group_only') }}).
            {{ trans('about.community_url') }}: <a v-bind:href="platformUrl" target="_blank">{{ platformUrl }}</a>.
            {{ trans('about.community_assessed') }}:
            {{ getCurrentDate() }}
          </p>
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
    isLoggedIn: {
        type: Boolean,
        required: true,
    }
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

  computed: {
    curatorsList() {
      const names = this.curators.map(c => c.name); 
      return names.join(', ');
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
      this.form.taxon = taxon || null
      this.form.taxon_id = taxon ? taxon.id : null
      this.form.taxon_suggestion = taxon ? taxon.name : null

      if (this.form.taxon) {
        this.fetchLowerTaxaAndCurators(this.form.taxon.id)
      } else {
        // clear results
        this.curators = []
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
