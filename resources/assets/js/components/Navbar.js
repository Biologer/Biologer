export default {
  name: 'nzNavbar',

  props: {
    hasUnread: Boolean
  },

  data() {
    return {
      active: false,
      showSidebar: false,
      hasUnreadNotificationsOrAnnouncements: this.hasUnread
    }
  },

  created() {
    this.$root.$on('announcement-read', (announcement) => {
      this.hasUnreadNotificationsOrAnnouncements = !announcement
    });
  },

  methods: {
    toggle() {
      this.active = !this.active;
    },

    toggleSidebar() {
        this.showSidebar = !this.showSidebar;
    }
  }
}
