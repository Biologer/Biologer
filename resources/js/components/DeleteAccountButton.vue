<template>
  <button type="button" @click="openModal" class="button is-danger">
    {{ trans('labels.preferences.account.delete_account') }}
  </button>
</template>

<script>
import DeleteAccountModal from './DeleteAccountModal'

export default {
  name: 'nzDeleteAccountButton',

  props: {
    action: String,
    csrfToken: String
  },

  methods: {
    openModal() {
      this.$buefy.modal.open({
        parent: this,
        component: DeleteAccountModal,
        hasModalCard: true,
        props: {
          action: this.action,
          csrfToken: this.csrfToken
        }
      })
    }
  }
}
</script>
