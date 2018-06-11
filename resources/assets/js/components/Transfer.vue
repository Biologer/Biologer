<template>
  <div class="transfer columns">
    <div class="column">
      <div class="panel">
        <p class="panel-heading">
          <input type="checkbox" :checked="allAvailableChecked" @change="onAllAvailabelChecked">
          {{ trans('labels.imports.available') }}
        </p>

        <a class="panel-block" v-for="item in available">
          <input type="checkbox" :value="item.value" v-model="checkedAvailable">
          {{ item.label }}
        </a>
      </div>
    </div>

    <div class="column is-narrow">
      <div class="buttons has-addons is-centered">
        <button
          class="button"
          @click="moveToAvailable"
          :disabled="!checkedChosen.length"
        >
          &#10094;
        </button>

        <button
          class="button"
          @click="moveToChosen"
          :disabled="!checkedAvailable.length"
        >
          &#10095;
        </button>
      </div>
    </div>

    <div class="column">
      <sortable-list :value="chosen" @input="sortChosen">
        <div class="panel" slot-scope="{ items }">
          <p class="panel-heading">
            <input type="checkbox" :checked="allChosenChecked" @change="onAllChosenChecked">
            {{ trans('labels.imports.chosen') }}
          </p>
          <sortable-item v-for="(item,index) in items" :key="item.value">
            <sortable-handle>
              <div class="panel-block">
                <input type="checkbox" :value="item.value" v-model="checkedChosen" v-if="!item.required">
                {{ item.label }}
              </div>
            </sortable-handle>
          </sortable-item>
        </div>
      </sortable-list>
    </div>
  </div>
</template>

<script>
import { SortableHandle, SortableItem, SortableList } from './sortable'

export default {
  name: 'nzTransfer',

  components: {
    SortableHandle, SortableItem, SortableList
  },

  props: {
    items: {
      type: Array,
      default: () => []
    },

    value: {
      type: Array,
      default: () => []
    }
  },

  computed: {
    required() {
      return this.items.filter(item => item.required)
    },

    chosen() {
      return this.value.map(value => _.find(this.items, { value })).filter(item => item)
    },

    available() {
      return this.items.filter(item => !this.value.includes(item.value))
    },

    allAvailableChecked() {
      return this.available.length === this.checkedAvailable.length && this.available.length !== 0
    },

    allChosenChecked() {
      const chosen = this.chosen.filter(item => !item.required)

      return chosen.length === this.checkedChosen.length && chosen.length !== 0
    }
  },

  data() {
    return {
      checkedAvailable: [],
      checkedChosen: [],
    }
  },

  created() {
    this.updateWithRequired()
  },

  methods: {
    /**
     * Update the initial value to include required items
     */
    updateWithRequired() {
      const requiredValues = this.required.map(item => item.value)

      this.$emit('input', _.uniq(this.value.concat(requiredValues)))
    },

    moveToChosen() {
      const checkedAvailable = this.checkedAvailable
      this.checkedAvailable = []

      this.$emit('input', this.value.concat(checkedAvailable))
    },

    moveToAvailable() {
      const checkedChosen = this.checkedChosen
      this.checkedChosen = []

      this.$emit('input', this.value.filter(item => !checkedChosen.includes(item)))
    },

    sortChosen(items) {
      this.$emit('input', items.map(item => item.value))
    },

    onAllAvailabelChecked(e) {
      if (e.target.checked) {
        this.checkedAvailable = this.available.map(item => item.value)
      } else {
        this.checkedAvailable = []
      }
    },

    onAllChosenChecked(e) {
      if (e.target.checked) {
        this.checkedChosen = this.chosen.filter(item => !item.required).map(item => item.value)
      } else {
        this.checkedChosen = []
      }
    }
  }
}
</script>

<style lang="scss">
  .transfer {
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

      input[type="checkbox"] {
        margin-right: 0.75em;
      }
    }

    .sortable-handle {
      &:hover {
        cursor: move;
      }
    }
  }
</style>
