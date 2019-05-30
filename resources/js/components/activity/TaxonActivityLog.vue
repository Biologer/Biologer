<template>
  <div class="activity-log">
    <component
      v-for="activity in activities"
      :key="activity.id"
      :is="chooseActivityComponent(activity)"
      :activity="activity"
    />
  </div>
</template>

<script>
import ActivityTaxonCreated from './TaxonCreated'
import ActivityTaxonUpdated from './TaxonUpdated'

export default {
  name: 'nzTaxonActivityLog',

  components: {
    [ActivityTaxonCreated.name]: ActivityTaxonCreated,
    [ActivityTaxonUpdated.name]: ActivityTaxonUpdated,
  },

  props: {
    activities: {
      type: Array,
      default: () => []
    }
  },

  methods: {
    chooseActivityComponent(activity) {
      return `nz-activity-taxon-${activity.description.replace('_', '-')}`
    }
  }
}
</script>

<style lang="css">
</style>
