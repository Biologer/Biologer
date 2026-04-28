export default {
  name: 'nzNavbar',

  data() {
    return {
      active: false,
      activeDropdown: null
    }
  },

  methods: {
    toggle() {
      this.active = !this.active;
    },

    toggleDropdown(menu) {
      this.activeDropdown = this.activeDropdown === menu ? null : menu;
    }
  }
}
