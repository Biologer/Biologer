import User from '@/models/user'

export default {
  data() {
    return {
      currentUser: new User(window.App.User)
    }
  },

  computed: {
    isCuratorOrAdmin() {
        return this.currentUser.hasRole(['admin', 'curator'])
    }
  }
}
