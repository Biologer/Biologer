<template>
  <span class="is-flex is-align-items-center">
    {{ column.label }}
    <b-icon
      v-if="column.sortable"
      pack="fa"
      :icon="sortIcon"
      size="is-small"
      class="ml-2"
      :class="{'has-text-grey-light': isNotSortedColumn}"
    />
  </span>
</template>

<script>
export default {
  name: 'nzSortableColumnHeader',

  props: {
    column: {
      type: Object,
      required: true,
    },
    sort: Object,
  },

  computed: {
    isNotSortedColumn() {
      return this.sort.field !== this.column.field
    },

    sortIcon() {
      if (this.isNotSortedColumn) {
        return 'sort'
      }

      return this.sort.order === 'asc' ? 'sort-asc' : 'sort-desc'
    },
  },
}
</script>
