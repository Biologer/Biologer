export default {
  name: 'nz-navbar',

  data() {
    return {
      active: false
    }
  },

  methods: {
    toggle() {
      this.active = !this.active
    }
  }
}
