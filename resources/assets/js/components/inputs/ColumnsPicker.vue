<template>
  <sortable-list v-model="allColumns" class="columns-picker">
    <div class="panel" slot-scope="{ items }">
      <div class="panel-heading">
        <b-checkbox :value="allChecked" @change.native="checkAll" />{{ title }}
      </div>
      <sortable-item v-for="(item,index) in items" :key="item.value">
        <sortable-handle>
          <div class="panel-block">
            <label
              class="b-checkbox checkbox"
              :tabindex="item.required ? false : 0"
              @keydown.prevent.space="$event.target.click()">
              <input
                v-model="checkedColumns"
                type="checkbox"
                :disabled="item.required"
                :required="item.required"
                :value="item.value">
              <span class="check" />
              <span class="control-label">{{ item.label }}</span>
            </label>
<!--
            <b-checkbox
              v-model="checkedColumns"
              :native-value="item.value"
              :disabled="item.required"
            >
              {{ item.label }}
            </b-checkbox> -->
          </div>
        </sortable-handle>
      </sortable-item>
    </div>
  </sortable-list>
</template>

<script>
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
      checkedColumns: _.uniq(this.value.concat(this.columns.filter((column) => column.required).map((column) => column.value))),
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
