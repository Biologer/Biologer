<template>
  <div class="sidebar" :class="{'is-active': active}">
    <div class="sidebar-header">
      <h3 class="title is-size-5">{{ trans('notifications.title') }}</h3>
    </div>

    <div class="sidebar-body">
      <transition-group name="notification-list" v-if="currentNotifications.length">
        <nz-notification
          class="sidebar-block"
          v-for="notification in currentNotifications"
          :notification="notification"
          @mark-read="markNotificationAsRead"
          :key="notification.id"
        />
      </transition-group>

      <div class="sidebar-block justify-center py-8" v-else>
        {{ trans('notifications.no_new_notifications') }}
      </div>
    </div>

    <footer class="sidebar-footer">
      <button type="button" class="button" @click="hide">{{ trans('buttons.close') }}</button>

      <button type="button" class="button is-text" @click="markAllNotificationsAsRead" v-if="currentNotifications.length">
        {{ trans('notifications.mark_all_as_read') }}
      </button>
    </footer>
  </div>
</template>

<script>
import nzNotification from './notifications/Notification'

export default {
  name: 'nzSidebar',

  components: {
    nzNotification
  },

  props: {
    active: Boolean,

    notifications: {
      type: Array,
      default: () => []
    }
  },

  data() {
    return {
      activeTab: 'notifications',
      currentNotifications: this.notifications,
    }
  },

  created () {
    if (typeof window !== 'undefined') {
      document.addEventListener('keyup', this.keyPress)
    }
  },

  beforeDestroy() {
    if (typeof window !== 'undefined') {
      document.removeEventListener('keyup', this.keyPress)
    }
  },

  methods: {
    /**
     * Emit the event to close.
     */
    hide() {
      this.$emit('close')
    },

    /**
     * Handle ESC key.
     *
     * @param  {Event}  event
     */
    keyPress(event) {
      // Esc key
      if (event.keyCode === 27) this.hide()
    },

    /**
     * Mark single notification as read.
     *
     * @param  {String}  notificationId
     */
    markNotificationAsRead(notificationId, callback) {
      axios.post(route('api.my.read-notifications-batch.store'), {
        notifications_ids: [notificationId]
      }).then(() => {
        this.currentNotifications = this.currentNotifications.filter(notification => {
          return notification.id !== notificationId
        })

        this.$emit('update:has-unread-notifications', !!this.currentNotifications.length)

        callback()
      })
    },

    /**
     * Mark all unread notifications as read.
     */
    markAllNotificationsAsRead() {
      if (!this.currentNotifications.length) return

      axios.post(route('api.my.read-notifications-batch.store'), {
        notifications_ids: this.currentNotifications.map(notification => notification.id)
      }).then(() => {
        this.currentNotifications = []

        this.$emit('update:has-unread-notifications', false)
      })
    }
  }
}
</script>
