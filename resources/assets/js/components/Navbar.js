export default {
  name: 'nzNavbar',

  data() {
    return {
      active: false,
      showSidebar: false
    }
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
