export default {
  link(notification) {
    return route('contributor.field-observations.show', notification.data.field_observation_id)
  },

  message(notification) {
    return trans('notifications.field_observations.edited', {
      causerName: notification.data.causer_name || trans('roles.curator')
    })
  }
}
