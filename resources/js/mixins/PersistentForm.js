import _pick from 'lodash/pick'
import expiringStorage from '@/expiring-storage'

export default {
  props: {
    cacheKey: { default: null },
    cacheLifetime: { default: 60 * 24 }
  },

  computed: {
    storageKey() {
      return this.cacheKey
        ? `nz-form.${this.cacheKey}`
        : `nz-form.${window.location.host}${window.location.pathname}`
    }
  },

  methods: {
    getFormKey() {
      return 'form'
    },

    otherPersistentKeys() {
      return []
    },

    saveState() {
      const data = {
        ..._pick(this.$data, this.otherPersistentKeys()),
        form: this[this.getFormKey()].data()
      }

      expiringStorage.set(this.storageKey, data, this.cacheLifetime)
    },

    restoreState() {
      const previousState = expiringStorage.get(this.storageKey);

      if (previousState === null) {
        return
      }

      this[this.getFormKey()].populate(previousState.form)

      this.otherPersistentKeys().forEach(key => {
        if (previousState[key] !== undefined) {
          this.$set(this, key, previousState[key])
        }
      })

      this.clearPersistedState()
    },

    clearPersistedState() {
      expiringStorage.delete(this.storageKey)
    }
  }
}
