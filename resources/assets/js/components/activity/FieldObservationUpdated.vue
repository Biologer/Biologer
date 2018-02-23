<template>
    <div class="activity-log-item">
        {{ activity.created_at | formatDateTime }} {{ activity.causer.full_name }} {{ trans('changed') }} {{ formatedChanges }}: {{ activity.properties.reason }}
    </div>
</template>

<script>
export default {
    name: 'nzActivityFieldObservationUpdated',

    props: {
        activity: {
            type: Object,
            required: true
        }
    },

    computed: {
        formatedChanges() {
            const old = this.activity.properties.old;

            return Object.keys(old).map(key => {
                const val = this.oldValue(old, key)
                return `${this.trans('labels.field_observations.'+key)}` + (val ? ` (${val})` : '');
            }).join(', ');
        }
    },

    methods: {
        oldValue(old, key) {
            const value = old[key];

            if (value === null || value === undefined) {
                return null;
            }

            if (typeof value === 'object') {
                return value.label ? this.trans(value.label) : null;
            }

            return value;
        }
    }
}
</script>
