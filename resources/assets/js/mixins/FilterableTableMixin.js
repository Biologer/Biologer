export default {
    data() {
      return {
          showFilter: false,
          filter: this.filterDefaults(),
          newFilter: this.filterDefaults(),
      };
    },

    methods: {
        filterDefaults() {
            return {};
        },

        clearFilter() {
            this.newFilter = this.filterDefaults();

            this.applyFilter();
        },

        applyFilter() {
            let reload = false;

            for (let field in this.newFilter) {
                if (this.filter[field] !== this.newFilter[field]) {
                    reload = true;
                }

                this.filter[field] = this.newFilter[field];
            }

            if (reload) {
                this.$emit('filter');
            }
        }
    }
};
