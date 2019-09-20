export default {
  name: 'nzNavbar',

  data() {
    return {
      active: false
    }
  },

  methods: {
    toggle() {
      this.active = !this.active;
    }
  }
}
