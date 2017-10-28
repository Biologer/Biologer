export default {
  name: 'nz-navbar',

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
