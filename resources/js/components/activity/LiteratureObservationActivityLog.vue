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
import ActivityLiteratureObservationCreated from './LiteratureObservationCreated'
import ActivityLiteratureObservationUpdated from './LiteratureObservationUpdated'

export default {
  name: 'nzLiteratureObservationActivityLog',

  components: {
    [ActivityLiteratureObservationCreated.name]: ActivityLiteratureObservationCreated,
    [ActivityLiteratureObservationUpdated.name]: ActivityLiteratureObservationUpdated
  },

  props: {
    activities: {
      type: Array,
      default: () => []
    }
  },

  methods: {
    chooseActivityComponent(activity) {
      return `nz-activity-literature-observation-${activity.description.split('_').join('-')}`
    }
  }
}
</script>
