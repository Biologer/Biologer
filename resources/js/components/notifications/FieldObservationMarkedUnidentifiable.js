export default {
  link(notification) {
    return route('contributor.field-observations.show', notification.data.field_observation_id)
  },

  message(notification) {
    const taxonName = notification.data.taxon_name
    const curatorName = notification.data.curator_name || trans('roles.curator')

    return taxonName
      ? trans('notifications.field_observations.marked_as_unidentifiable_with_taxon', { curatorName, taxonName })
      : trans('notifications.field_observations.marked_as_unidentifiable', { curatorName })
  }
}
