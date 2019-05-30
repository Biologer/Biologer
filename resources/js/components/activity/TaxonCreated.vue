<template>
  <div class="activity-log-item">
    {{ activity.created_at | formatDateTime }} {{ activity.causer.full_name }} {{ trans('activityLog.added_record') }} {{ activity.subject_id }}
  </div>
</template>

<script>
export default {
  name: 'nzActivityTaxonCreated',

  props: {
    activity: {
      type: Object,
      required: true
    }
  }
}
</script>
