import NzSidebar from '@/components/Sidebar'

export default {
  name: 'nzNavbar',

  components: {
    NzSidebar
  },

  props: {
    hasUnread: Boolean
  },

  data() {
    return {
      active: false,
      showSidebar: false,
      hasUnreadNotifications: this.hasUnread
    }
  },

  methods: {
    toggle() {
      this.active = !this.active;
    },

    toggleSidebar() {
        this.showSidebar = !this.showSidebar;
    },

    updateNotificationStatus(status) {
      this.hasUnreadNotifications = status;
    }
  }
}
