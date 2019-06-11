<template>
  <transition :name="animation">
    <div v-if="isActive" class="modal is-active">
      <div class="modal-background"/>

      <div
        class="animation-content modal-content"
        :style="{ maxWidth: newWidth }"
      >
        <img
          :src="imageUrl"
          class="image"
          alt="Cropped image"
          ref="cropModalImage"
        >
      </div>

      <button
        type="button"
        class="modal-close is-large"
        @click="cancel('x')"
      />

      <button
        type="button"
        class="modal-action"
        @click="cropImage"
      >
        <b-icon icon="check"/>
      </button>
    </div>
  </transition>
</template>

<script>
import Croppr from '@/Croppr'
import bModal from 'buefy/src/components/modal/Modal'

export default {
  name: 'nzImageCropModal',

  mixins: [bModal],

  props: {
    canCancel: {
      type: [Array, Boolean],
      default: () => ['escape', 'x']
    },
    imageUrl: String,
    crop: Object
  },

  data() {
    return {
			croppr: null,
      newCrop: this.crop,
      preserveRatio: false
    }
  },

  mounted() {
    document.addEventListener('keydown', this.ctrlIsPressed)
    document.addEventListener('keyup', this.ctrlIsNotPressed)
  },

  beforeDestroy() {
    document.removeEventListener('keydown', this.ctrlIsPressed)
    document.removeEventListener('keyup', this.ctrlIsNotPressed)
  },

  watch: {
    isActive(value) {
      this.handleScroll()

      this.croppr =  null

      if (value) {
        this.$nextTick(() => {
          this.croppr = new Croppr(this.$refs.cropModalImage, {
            onCropEnd: (value) => {
              this.newCrop = value
            },

            onInitialize: (croppr) => {
              if (this.newCrop) {
                croppr.setBoxToRealPosition(this.newCrop)
              }
            }
          })
        })
      }
    }
  },

  methods: {
    handleScroll() {
      if (typeof window === 'undefined') return

      this.savedScrollTop = !this.savedScrollTop
        ? document.documentElement.scrollTop
        : this.savedScrollTop

      document.body.classList.toggle('is-noscroll', this.isActive)

      if (this.isActive) {
        document.body.style.top = `-${this.savedScrollTop}px`
        return
      }

      document.documentElement.scrollTop = this.savedScrollTop
      document.body.style.top = null
      this.savedScrollTop = null
    },

    cropImage() {
      this.$emit('update:crop', this.newCrop)

      this.close()
    },

    ctrlIsPressed(e) {
      if (e.which == 17 && this.croppr) {
        this.croppr.options.aspectRatio = this.croppr.box.height() / this.croppr.box.width()
      }
    },

    ctrlIsNotPressed(e) {
      if (e.which == 17  && this.croppr) {
        this.croppr.options.aspectRatio = null
      }
    }
  }
}
</script>
