export default {
  data() {
    return {
      showFilter: false,
      filter: this.filterDefaults(),
      newFilter: this.filterDefaults(),
      filterIsActive: false,
    }
  },

  methods: {
    filterDefaults() {
      return {}
    },

    clearFilter() {
      this.newFilter = this.filterDefaults()

      this.applyFilter(false)
    },

    applyFilter(filterIsActive = true) {
      let reload = false

      Object.keys(this.newFilter).forEach((field) => {
        if (this.filter[field] !== this.newFilter[field]) {
          reload = true
        }

        this.filter[field] = this.newFilter[field]
      })

      this.filterIsActive = filterIsActive

      if (reload) {
        this.$emit('filter')
      }
    }
  }
}
