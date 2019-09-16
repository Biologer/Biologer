<template>
  <button
    type="button"
    class="button has-text-hidden-tablet-only"
    @click="showModal"
    :title="trans('navigation.find_in_group')"
  >
    <b-icon
      icon="search"
      size="is-small"
      class="has-text-grey"
    />

    <span class="is-hidden-tablet-only">
      {{ trans('navigation.find_in_group') }}
    </span>
  </button>
</template>

<script>
import nzGroupTaxaSearch from './GroupTaxaSearch'

export default {
  name: 'nzGroupTaxaSearchButton',

  props: {
    group: {
      type: Number,
      required: true
    }
  },

  methods: {
    showModal() {
      const modal = this.$buefy.modal.open({
        parent: this,
        component: nzGroupTaxaSearch,
        hasModalCard: true,
        props: {
            group: this.group
        }
      })

      // Using the "nextTick" stopped working for some reason.
      setTimeout(() => {
        modal.$el.querySelector('input').focus()
      }, 500)
    }
  }
}
</script>
