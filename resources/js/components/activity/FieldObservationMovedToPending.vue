<template>
  <div class="activity-log-item">
    {{ activity.created_at | formatDateTime }} {{ activity.causer.full_name }} {{ trans('activityLog.moved_to_pending') }}: {{ activity.properties.reason }}
  </div>
</template>

<script>
export default {
  name: 'nzActivityFieldObservationMovedToPending',

  props: {
    activity: {
      type: Object,
      required: true
    }
  }
}
</script>
