import Form from 'form-backend-validation'
import _pick from 'lodash/pick'
import _get from 'lodash/get'
import _isEqual from 'lodash/isEqual'


export default {
  name: 'nzFieldObservationForm',

  props: {
    action: {
      type: String,
      required: true
    },

    method: {
      type: String,
      default: 'POST'
    },

    redirectUrl: String,
    cancelUrl: String,

    submitMore: Boolean,
    shouldConfirmSubmit: Boolean,
    confirmSubmitMessage: {
      type: String,
      default() {
        return this.trans('You are about to submit')
      }
    },
    shouldAskReason: Boolean,
    shouldConfirmCancel: Boolean,
    submitOnlyDirty: Boolean,
    submitOnlyDirtyMessage: {
      type: String,
      default() {
        return this.trans('There are no changes, the data will not be saved.')
      }
    }
  },

  data() {
    return {
      form: this.newForm(),
      keepAfterSubmit: [],
      submittingWithRedirect: false,
      submittingWithoutRedirect: false,
      confirmingSubmit: false,
      confirmingCancel: false,
      locale: window.App.locale
    }
  },

  created() {
    document.addEventListener('keyup', this.registerKeyListener)
  },

  beforeDestroy() {
    document.removeEventListener('keyup', this.registerKeyListener)
  },

  methods: {
    /**
     * Create new form instance.
     *
     * @param  {Object} data default form data
     * @return {Form}
     */
    newForm(data = {}) {
      return new Form(data, {
        resetOnSuccess: false
      })
    },

    /**
     * Keyboard shortcuts.
     *
     * @param {Event} e
     */
    registerKeyListener(e) {
      const enter = 13 === (e.which || e.keyCode)

      if (e.ctrlKey && e.shiftKey && enter) {
        this.submitMore && this.submitWithoutRedirect()
      } else if (e.ctrlKey && enter) {
        this.submitWithRedirect()
      }
    },

    /**
     * Confirm submit.
     *
     * @param {Function} onConfirm
     */
    confirmSubmit(onConfirm) {
      if (this.confirmingSubmit) return

      this.confirmingSubmit = true

      const options = {
        message: this.confirmSubmitMessage,
        confirmText: this.trans('buttons.save'),
        cancelText: this.trans('buttons.cancel'),
        onConfirm,
        onCancel: () => { this.confirmingSubmit = false }
      }

      if (!this.shouldAskReason) {
        return this.$buefy.dialog.confirm(options)
      }

      return this.promptForReason(options)
    },

    /**
     * Prompt the user for reason of change.
     *
     * @param  {Object} options
     * @return {Vue}
     */
    promptForReason(options) {
      const dialog = this.$buefy.dialog.prompt({
        ...options,
        inputAttrs: {
            placeholder: this.trans('Reason'),
            required: true,
            maxlength: 255
        }
      })

      // Custom localized validation for the prompt input.
      dialog.$nextTick(() => {
        dialog.$refs.input.addEventListener('invalid', (e) => {
          e.target.setCustomValidity('')

          if (!e.target.validity.valid) {
            e.target.setCustomValidity(this.trans('This field is required and can contain max 255 chars.'))
          }
        })

        dialog.$refs.input.addEventListener('input', (e) => {
          dialog.validationMessage = null
        })
      })

      return dialog
    },

    /**
     * Notify the user there are no changes.
     */
    notifyNoChanges() {
      this.$buefy.toast.open({
        message: this.submitOnlyDirtyMessage,
        type: 'is-info'
      })
    },

    /**
     * Notify the user there are no changes and redirect.
     */
    notifyNoChangesAndRedirect() {
      this.notifyNoChanges()

      setTimeout(() => {
        if (this.redirectUrl) {
          window.location.href = this.redirectUrl
        }
      }, 500)
    },

    /**
     * Submit the form with redirect.
     */
    submitWithRedirect() {
      if (this.form.processing) return

      if (this.submitOnlyDirty && !this.isDirty()) return this.notifyNoChangesAndRedirect()

      if (this.shouldConfirmSubmit) return this.confirmSubmit(this.performSubmitWithRedirect)

      this.performSubmitWithRedirect()
    },

    /**
     * Submit the form with redirect.
     */
    performSubmitWithRedirect(reason = null) {
      this.submittingWithRedirect = true
      this.confirmingSubmit = false

      if (this.shouldAskReason) {
        this.form.reason = reason
      }

      this.form[this.method.toLowerCase()](this.action)
          .then(this.onSuccessfulSubmitWithRedirect)
          .catch(this.onFailedSubmit)
    },

    /**
     * Handle successful form submit with redirect.
     */
    onSuccessfulSubmitWithRedirect() {
      this.form.processing = true

      this.$buefy.toast.open({
        message: this.trans('Saved successfully'),
        type: 'is-success'
      })

      // We want to wait a bit before we send the user to redirect route
      // so we can show the message that the action was successful.
      setTimeout(() => {
        this.form.processing = false
        this.submittingWithRedirect = false

        this.hookAfterSubmitWithRedirect()

        if (this.redirectUrl) {
          window.location.href = this.redirectUrl
        }
      }, 500)
    },

    /**
     * Perform after submit with redirect is successful.
     */
    hookAfterSubmitWithRedirect() {},

    /**
     * Submit the form and stay to add more.
     */
    submitWithoutRedirect() {
      if (this.form.processing) return

      if (this.submitOnlyDirty  && !this.isDirty()) return this.notifyNoChanges()

      if (this.shouldConfirmSubmit) return this.confirmSubmit(this.performSubmitWithoutRedirect)

      this.performSubmitWithoutRedirect()
    },

    /**
     * Submit the form and stay to add more.
     */
    performSubmitWithoutRedirect(reason = null) {
      this.submittingWithoutRedirect = true
      this.confirmingSubmit = false

      if (this.shouldAskReason) {
        this.form.reason = reason
      }

      this.form[this.method.toLowerCase()](this.action)
          .then(this.onSuccessfulSubmitWithoutRedirect)
          .catch(this.onFailedSubmit)
    },

    /**
     * Handle successful form submit with no redirect.
     */
    onSuccessfulSubmitWithoutRedirect() {
      this.submittingWithoutRedirect = false

      this.$buefy.toast.open({
        message: this.trans('Saved successfully'),
        type: 'is-success'
      })

      // Reset the form but remember some data.
      const keep = _pick(this.form.data(), this.keepAfterSubmit)
      this.form.reset()
      this.form.populate(keep)

      this.hookAfterSubmitWithoutRedirect()
    },

    /**
     * Perform after submit without redirect is successful.
     */
    hookAfterSubmitWithoutRedirect() {},

    /**
     * Handle failed form submit.
     *
     * @param {Error} error
     */
    onFailedSubmit(error) {
      this.submittingWithRedirect = false
      this.submittingWithoutRedirect = false

      this.$buefy.toast.open({
        duration: 2500,
        message: _get(error, 'response.data.message', error.message),
        type: 'is-danger'
      })
    },

    /**
     * Check if form is changed.
     *
     * @return {Boolean}
     */
    isDirty() {
      return ! _isEqual(this.form.data(), this.form.initial)
    },

    /**
     * Confirmation dialog for canceling.
     */
    confirmCancel() {
      if (this.confirmingCancel) return

      this.confirmingCancel = true

      this.$buefy.dialog.confirm({
        message: this.trans('If you leave this page changes will not be saved.'),
        onConfirm: () => {
          this.confirmingCancel = false
          window.location.href = this.cancelUrl
        },
        onCancel: () => { this.confirmingCancel = false },
        cancelText: this.trans('buttons.stay_on_page'),
        confirmText: this.trans('buttons.leave_page'),
      })
    },

    /**
     * Handle cancel button/link.
     *
     * @param {Event} event
     */
    onCancel(event) {
      if (this.shouldConfirmCancel && this.isDirty()) {
        event.preventDefault()

        this.confirmCancel()
      }
    }
  }
}
