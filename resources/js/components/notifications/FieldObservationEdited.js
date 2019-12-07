export default {
  link(notification) {
    return route('contributor.field-observations.show', notification.data.field_observation_id)
  },

  message(notification) {
    const taxonName = notification.data.taxon_name
    const causerName = notification.data.causer_name || trans('roles.curator')

    return taxonName
      ? trans('notifications.field_observations.edited_with_taxon', { causerName, taxonName })
      : trans('notifications.field_observations.edited', { causerName })
  }
}
