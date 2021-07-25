<template>
  <div class="sidebar" :class="{'is-active': active}">
    <div class="sidebar-header">
      <h3 class="title is-size-5">{{ trans('notifications.title') }}</h3>
    </div>

    <div class="sidebar-body">
      <template v-if="hasUnreadNotifications">
        <transition-group name="notification-list" v-if="notifications.length">
          <nz-notification
            class="sidebar-block"
            v-for="notification in notifications"
            :notification="notification"
            @mark-read="markNotificationAsRead"
            :key="`notification-${notification.id}`"
          />
        </transition-group>

        <div class="sidebar-block justify-center py-8">
          <button
            type="button"
            class="button is-primary"
            :class="{'is-loading': loading}"
            :disabled="!hasMoreToLoad"
            @click="loadMore"
          >
            {{ trans('notifications.load_more') }}
          </button>
        </div>
      </template>

      <div class="sidebar-block justify-center py-8" v-else>
        {{ trans('notifications.no_new_notifications') }}
      </div>
    </div>

    <footer class="sidebar-footer">
      <button type="button" class="button" @click="hide">{{ trans('buttons.close') }}</button>

      <button type="button" class="button is-text" @click="markAllNotificationsAsRead" v-if="notifications.length">
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
    hasUnreadNotifications: Boolean,
  },

  data() {
    return {
      activeTab: 'notifications',
      notifications: [],
      currentPage: 1,
      hasMoreToLoad: this.hasUnreadNotifications,
      loading: false,
    }
  },

  created () {
    if (typeof window !== 'undefined') {
      document.addEventListener('keyup', this.keyPress)
    }

    this.hasUnreadNotifications && this.load()
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

    load() {
      if (this.loading) return

      this.loading = true
      const page = this.currentPage

      axios.get(route('api.my.unread-notifications.index', {
        page,
      })).then((response) => {
        this.notifications = this.notifications.concat(response.data.data)

        this.hasMoreToLoad = this.page < response.data.meta.last_page
      }).finally(() => {
        this.loading = false
      })
    },

    loadMore() {
      if (this.loading || !this.hasMoreToLoad) return

      this.currentPage++
      this.load()
    },

    /**
     * Mark single notification as read.
     *
     * @param  {String}  notificationId
     */
    markNotificationAsRead(notificationId, callback) {
      axios.post(route('api.my.read-notifications-batch.store'), {
        notifications_ids: [notificationId]
      }).then((response) => {
        this.notifications = this.notifications.filter(notification => {
          return notification.id !== notificationId
        })

        this.$emit('update:has-unread-notifications', response.data.meta.has_unread)

        callback && callback()
      })
    },

    /**
     * Mark all unread notifications as read.
     */
    markAllNotificationsAsRead() {
      if (!this.hasUnreadNotifications) return

      axios.post(route('api.my.read-notifications-batch.store'), {
        all: true,
      }).then(() => {
        this.notifications = []

        this.$emit('update:has-unread-notifications', false)
      })
    }
  }
}
</script>
