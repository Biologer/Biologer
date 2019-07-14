<template>
  <div v-if="type" :class="{'has-background-light': isNotRead}">
    <div class="flex-1" :class="{'is-clickable': link}" @click="visitLinkAndMarkAsRead">
      <div v-html="message" />

      <span class="has-text-grey is-size-7">{{ createdAt }}</span>
    </div>

    <a
      href="#"
      v-if="isNotRead"
      @click.prevent="markAsRead"
      :title="trans('notifications.mark_as_read')"
    >
      <b-icon icon="remove" size="is-small" />
    </a>
  </div>
</template>

<script>
  import FieldObservationApproved from './FieldObservationApproved'
  import FieldObservationEdited from './FieldObservationEdited'
  import FieldObservationForApproval from './FieldObservationForApproval'
  import FieldObservationMovedToPending from './FieldObservationMovedToPending'
  import FieldObservationMarkedUnidentifiable from './FieldObservationMarkedUnidentifiable'

  const NOTIFICATIONS_MAP = {
    'App\\Notifications\\FieldObservationApproved': FieldObservationApproved,
    'App\\Notifications\\FieldObservationEdited': FieldObservationEdited,
    'App\\Notifications\\FieldObservationForApproval': FieldObservationForApproval,
    'App\\Notifications\\FieldObservationMovedToPending': FieldObservationMovedToPending,
    'App\\Notifications\\FieldObservationMarkedUnidentifiable': FieldObservationMarkedUnidentifiable
  }

  export default {
    name: 'nzNotification',

    props: {
      notification: {
        type: Object,
        required: true
      }
    },

    computed: {
      type() {
        return NOTIFICATIONS_MAP[this.notification.type]
      },

      isNotRead() {
        return !this.notification.readAt;
      },

      data() {
        return this.notification.data
      },

      createdAt() {
        return moment(this.notification.created_at).format('D.M.YYYY HH:mm')
      },

      message() {
        return this.type.message(this.notification)
      },

      link() {
        return this.type.link(this.notification)
      }
    },

    methods: {
      /**
       * Mark notification as read.
       */
      markAsRead() {
        if (this.isNotRead) {
          this.$emit('mark-read', this.notification.id)
        }
      },

      /**
       * Visit the notification link
       * If the notification has not been read before, mark if as read.
       */
      visitLinkAndMarkAsRead() {
        if (this.isNotRead) {
          this.$emit('mark-read', this.notification.id, () => {
            this.visitLink()
          });
        } else {
          this.visitLink()
        }
      },

      /**
       * Visit the notification link.
       */
      visitLink() {
        if (this.link) {
          window.location.href = this.link
        }
      }
    }
  }
</script>
