import expiringStorage from '../expiring-storage';

export default {
    props: {
        cacheKey: { default: null },
        cacheLifetime: { default: 60 * 24 },
    },

    computed: {
        storageKey() {
            return this.cacheKey
                ? `nz-table.${this.cacheKey}`
                : `nz-table.${window.location.host}${window.location.pathname}${this.cacheKey}`;
        },
    },

    methods: {
        saveState() {
            expiringStorage.set(this.storageKey, _.pick(this.$data, [
              'sortField', 'sortOrder', 'perPage'
            ]), this.cacheLifetime);
        },

        restoreState() {
            const previousState = expiringStorage.get(this.storageKey);

            if (previousState === null) {
                return;
            }

            this.sortField = previousState.sortField;
            this.sortOrder = previousState.sortOrder;
            this.perPage = previousState.perPage;

            this.saveState();
        },
    }
}
