<template>
  <form :action="action" method="POST" class="modal-card">
    <header class="modal-card-head">
      <h3 class="modal-card-title">{{ trans('navigation.preferences.delete_account') }}</h3>
    </header>

    <input type="hidden" name="_token" :value="csrfToken" />
    <input type="hidden" name="_method" value="DELETE" />


    <section class="modal-card-body">
      <p>{{ trans('This action is irreversible, once an account is deleted it cannot be restored.') }}</p>

      <p>{{ trans('Are you sure you want to delete your account?') }}</p>

      <b-field class="mt-8">
        <b-checkbox name="delete_observations">{{ trans('labels.preferences.account.delete_observations') }}</b-checkbox>
      </b-field>
    </section>

    <footer class="modal-card-foot">
      <button class="button" type="button" @click="$parent.close()">{{ trans('buttons.cancel') }}</button>

      <button class="button is-danger" type="submit">{{ trans('labels.preferences.account.delete_account') }}</button>
    </footer>
  </form>
</template>

<script>
export default {
  name: 'nzDeleteAccounModal',

  props: {
    action: String,
    csrfToken: String
  }
}
</script>
