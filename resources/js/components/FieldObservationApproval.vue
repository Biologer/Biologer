<template>
  <div class="level-right">
    <div class="level-item" v-if="approveUrl">
      <button
        type="button"
        class="button"
        :class="{'is-loading': approving}"
        @click="confirmApprove"
        :disabled="busy"
      >
        <b-icon icon="check" class="has-text-success" />

        <span>{{ trans('buttons.approve') }}</span>
      </button>
    </div>

    <div class="level-item" v-if="markAsUnidentifiableUrl">
      <button
        type="button"
        class="button"
        :class="{'is-loading': markingAsUnidentifiable}"
        @click="confirmMarkingAsUnidentifiable"
        :disabled="busy"
      >
        <b-icon icon="times" class="has-text-danger" />

        <span>{{ trans('buttons.unidentifiable') }}</span>
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'nzFieldObservationApproval',

  props: {
    approveUrl: String,
    markAsUnidentifiableUrl: String,

    redirectUrl: {
      type: String,
      required: true
    },

    fieldObservation: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      approving: false,
      markingAsUnidentifiable: false
    }
  },

  computed: {
    busy() {
      return this.approving || this.markingAsUnidentifiable
    }
  },

  methods: {
    confirmApprove() {
      this.$buefy.dialog.confirm({
        message: this.trans('You are about to approve this field observation'),
        confirmText: this.trans('buttons.approve'),
        cancelText: this.trans('buttons.cancel'),
        type: 'is-primary',
        onConfirm: this.approve.bind(this)
      })
    },

    approve() {
      this.approving = true;

      axios.post(this.approveUrl, {
        field_observation_ids: [this.fieldObservation.id]
      }).then(this.successfullyApproved).catch(this.failedToApprove)
    },

    successfullyApproved() {
      this.$buefy.toast.open({
        message: this.trans('Observation has been approved'),
        type: 'is-success'
      });

      setTimeout(() => {
        this.approving = false;

        window.location.href = this.redirectUrl
      }, 1000)
    },

    failedToApprove(error) {
      this.approving = false

      this.$buefy.toast.open({
        message: this.trans('Observation cannot be approved'),
        type: 'is-danger',
        duration: 5000
      })
    },

    confirmMarkingAsUnidentifiable() {
      const dialog = this.$buefy.dialog.prompt({
        message: this.trans('You are about to mark observation as unidentifiable. What\'s the reason?'),
        confirmText: this.trans('buttons.mark_unidentifiable'),
        cancelText: this.trans('buttons.cancel'),
        type: 'is-warning',
        inputAttrs: {
            placeholder: this.trans('Reason'),
            required: true,
            maxlength: 255
        },
        onConfirm: this.markAsUnidentifiable.bind(this)
      })

      dialog.$nextTick(() => {
        this.validateReason(dialog);
      })
    },

    markAsUnidentifiable(reason) {
      this.markingAsUnidentifiable = true

      axios.post(this.markAsUnidentifiableUrl, {
        field_observation_ids: [this.fieldObservation.id],
        reason
      }).then(this.successfullyMarkedAsUnidentifiable)
      .catch(this.failedToMarkAsUnidentifiable)
    },

    successfullyMarkedAsUnidentifiable() {
      this.$buefy.toast.open({
        message: this.trans('Observation has been marked as unidentifiable'),
        type: 'is-success'
      })

      setTimeout(() => {
        this.markingAsUnidentifiable = false

        window.location.href = this.redirectUrl
      }, 1000)
    },

    failedToMarkAsUnidentifiable(error) {
      this.markingAsUnidentifiable = false
      this.$buefy.toast.open({
        message: this.trans('This observation cannot be marked as unidentifiable'),
        type: 'is-danger',
        duration: 5000
      })
    },

    validateReason(dialog) {
      dialog.$refs.input.addEventListener('invalid', (e) => {
        e.target.setCustomValidity('')

        if (!e.target.validity.valid) {
          e.target.setCustomValidity(this.trans('This field is required and can contain max 255 chars.'))
        }
      });

      dialog.$refs.input.addEventListener('input', (e) => {
        dialog.validationMessage = null
      })
    }
  }
}
</script>
