export default {
  link(notification) {
    return route('contributor.field-observations.show', notification.data.field_observation_id)
  },

  message(notification) {
    return trans('notifications.field_observations.moved_to_pending', {
      curatorName: notification.data.curator_name || trans('roles.curator')
    })
  }
}
