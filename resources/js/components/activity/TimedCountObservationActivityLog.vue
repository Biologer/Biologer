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
import ActivityTimedCountObservationCreated from './TimedCountObservationCreated'
import ActivityTimedCountObservationUpdated from './TimedCountObservationUpdated'

export default {
  name: 'nzTimedCountObservationActivityLog',

  components: {
    [ActivityTimedCountObservationCreated.name]: ActivityTimedCountObservationCreated,
    [ActivityTimedCountObservationUpdated.name]: ActivityTimedCountObservationUpdated
  },

  props: {
    activities: {
      type: Array,
      default: () => []
    }
  },

  methods: {
    chooseActivityComponent(activity) {
      return `nz-activity-timed-count-observation-${activity.description.split('_').join('-')}`
    }
  }
}
</script>
