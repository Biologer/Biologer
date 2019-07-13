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
        ? `nz-table.${this.cacheKey}`
        : `nz-table.${window.location.host}${window.location.pathname}`
    }
  },

  methods: {
    getPersistantKeys() {
      return [
        'sortField', 'sortOrder', 'perPage'
      ]
    },

    saveState() {
      expiringStorage.set(this.storageKey, _pick(this.$data, this.getPersistantKeys()), this.cacheLifetime)
    },

    restoreState() {
      const previousState = expiringStorage.get(this.storageKey)

      if (previousState === null || previousState === undefined) {
        return
      }

      this.getPersistantKeys().forEach(key => {
        if (previousState[key] !== undefined) {
          this.$set(this, key, previousState[key])
        }
      })

      this.saveState()
    }
  }
}
