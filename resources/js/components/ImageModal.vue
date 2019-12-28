<template>
  <b-modal :active.sync="active" :can-cancel="['escape', 'x']" @close="onClose">
    <div class="image-modal">
      <img :src="openImage">

      <div class="image-modal-navigation" v-if="items.length > 1">
        <div class="image-modal-previous" @click="prev">
          <i class="fa fa-chevron-left" aria-hidden="true"></i>
        </div>

        <div class="image-modal-next" @click="next">
          <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </div>
      </div>
    </div>
  </b-modal>
</template>

<script>
export default {
  name: 'nzImageModal',

  props: {
    items: {
      type: Array,
      default: () => []
    },
    value: {
      type: Number,
      default: 0
    }
  },

  data() {
    return {
      newValue: this.value,
      active: false
    }
  },

  computed: {
    openImage() {
      return this.items[this.newValue]
    }
  },

  watch: {
    newValue() {
      this.$emit('input', this.newValue)
    },

    openImage: {
      handler(value) {
        this.active = Boolean(value)
      }
    }
  },

  created() {
    document.addEventListener('keyup', this.registerKeyListener)
  },

  mounted() {
    this.active = Boolean(this.openImage)
  },

  beforeDestroy() {
    document.removeEventListener('keyup', this.registerKeyListener)
  },

  methods: {
    next() {
      let newValue = this.newValue + 1

      if (newValue >= this.items.length) {
        newValue = 0
      }

      this.newValue = newValue
    },

    prev() {
      let newValue = this.newValue - 1

      if (newValue < 0) {
        newValue = this.items.length - 1
      }

      this.newValue = newValue
    },

    registerKeyListener(e) {
      const key = e.which || e.keyCode

      if (key === 37) { // left arrow
        this.prev()
      } else if (key === 39) { // right arrow
        this.next()
      }
    },

    onClose(e) {
      this.active = false

      // b-modal component adds classes that prevent scrolling on body when active
      // so we need to wait for it to remove them before we signal it's closing
      this.$nextTick(() => {
        this.$emit('close', e)
      })
    }
  }
}
</script>
