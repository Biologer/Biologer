<template>
    <button
        type="button"
        class="button has-text-hidden-tablet-only"
        @click="showModal"
        :title="trans('navigation.find_in_group')"
    >
        <span class="icon">
            <i class="fa fa-search" />
        </span>

        <span class="is-hidden-tablet-only">
          {{ trans('navigation.find_in_group') }}
        </span>
    </button>
</template>

<script>
import nzGroupTaxaSearch from './GroupTaxaSearch';

export default {
    name: 'nzGroupTaxaSearchButton',

    props: {
        group: {
            type: Number,
            required: true
        }
    },

    methods: {
        showModal() {
            const modal = this.$modal.open({
                parent: this,
                component: nzGroupTaxaSearch,
                hasModalCard: true,
                props: {
                    group: this.group
                }
            });

            this.$nextTick(() => {
                modal.$el.querySelector('input').focus();
            });
        }
    }
}
</script>

<style lang="css">
</style>
