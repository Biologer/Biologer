<template>
    <div class="activity-log">
        <component
            v-for="activity in activities"
            :key="activity.id"
            :is="chooseActivityComponent(activity)"
            :activity="activity"
        />
    </div>
</template>

<script>
import ActivityFieldObservationApproved from './FieldObservationApproved';
import ActivityFieldObservationCreated from './FieldObservationCreated';
import ActivityFieldObservationMarkedUnidentifiable from './FieldObservationMarkedUnidentifiable';
import ActivityFieldObservationMovedToPending from './FieldObservationMovedToPending';
import ActivityFieldObservationUpdated from './FieldObservationUpdated';

export default {
    name: 'nzFieldObservationActivityLog',

    components: {
        [ActivityFieldObservationApproved.name]: ActivityFieldObservationApproved,
        [ActivityFieldObservationCreated.name]: ActivityFieldObservationCreated,
        [ActivityFieldObservationMarkedUnidentifiable.name]: ActivityFieldObservationMarkedUnidentifiable,
        [ActivityFieldObservationMovedToPending.name]: ActivityFieldObservationMovedToPending,
        [ActivityFieldObservationUpdated.name]: ActivityFieldObservationUpdated
    },

    props: {
        activities: {
            type: Array,
            default: () => []
        }
    },

    methods: {
        chooseActivityComponent(activity) {
            const component = `nz-activity-field-observation-${activity.description.split('_').join('-')}`;

            return component;
        },
    }
}
</script>

<style lang="css">
</style>
