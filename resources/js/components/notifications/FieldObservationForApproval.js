export default {
  link(notification) {
    return route('curator.pending-observations.show', notification.data.field_observation_id)
  },

  message(notification) {
    return trans('notifications.field_observations.for_approval', {
      contributorName: notification.data.contributor_name || trans('roles.contributor')
    })
  }
}
