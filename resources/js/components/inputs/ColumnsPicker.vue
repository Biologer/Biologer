<template>
  <sortable-list v-model="allColumns" class="columns-picker">
    <div class="panel" slot-scope="{ items }">
      <div class="panel-heading">
        <b-checkbox :value="allChecked" @change.native="checkAll" />{{ title }}
      </div>
      <sortable-item v-for="(item) in items" :key="item.value">
        <div class="panel-block">
          <b-checkbox
            v-model="checkedColumns"
            :disabled="item.required"
            :required="item.required"
            :native-value="item.value"
          >
            {{ item.label }}
          </b-checkbox>

          <sortable-handle>
            <b-icon icon="arrows-v" size="is-small"/>
          </sortable-handle>
        </div>
      </sortable-item>
    </div>
  </sortable-list>
</template>

<script>
import _uniq from 'lodash/uniq'
import { SortableHandle, SortableItem, SortableList } from '../sortable'

export default {
  name: 'nzColumnsPicker',

  components: {
    SortableHandle, SortableItem, SortableList
  },

  props: {
    value: {
      type: Array,
      default: () =>[]
    },

    columns: {
      type: Array,
      required: true
    },

    title: {
      type: String,
      default: () => 'Columns'
    }
  },

  data() {
    return {
      allColumns: this.columns,
      checkedColumns: _uniq(this.value.concat(this.columns.filter((column) => column.required).map((column) => column.value))),
    }
  },

  computed: {
    allChecked() {
      return this.checkedColumns.length && this.checkedColumns.length === this.columns.length
    },

    checked() {
      return this.allColumns.map((column) => column.value)
        .filter((column) => this.checkedColumns.includes(column))
    },

    required() {
      return this.allColumns.filter((column) => column.required)
    }
  },

  watch: {
    checked: {
      immediate: true,
      handler() {
        this.$emit('input', this.checked)
      }
    }
  },

  methods: {
    checkAll(event) {
      if (event.target.checked) {
        this.checkedColumns = this.allColumns.map((column) => column.value)
      } else {
        this.checkedColumns = this.required.map((column) => column.value)
      }
    }
  }
}
</script>

<style lang="scss">
  .columns-picker {
    :focus {
      outline: none;
    }

    .panel-block {
      &:hover {
        cursor: default;
      }

      justify-content: space-between;
    }

    .panel-heading {
      padding-left: 0.6em;
    }

    .sortable-handle {
      &:hover {
        cursor: move;
      }
    }

    .b-checkbox {
      input[type="checkbox"][disabled] + .check {
          opacity: 0.5;
      }
    }
  }
</style>
