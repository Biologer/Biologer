<template>
  <div class="slider">
    <!-- Slideshow container -->
    <div class="slide-container">
      <!-- Full-width images with number and caption text -->
      <div class="slide" v-for="(image, index) in items" v-show="isCurrent(index)">
        <div class="slide-image-container">
          <img v-lazy="image.url">

            <div class="slide-caption" v-if="image.caption" v-html="image.caption"></div>
        </div>
      </div>

      <!-- Next and previous buttons -->
      <a class="prev flex is-flex-center" @click="goToPrevSlide" v-if="moreThanOne" :title="trans('pagination.previous')">
        <b-icon icon="chevron-left" size="is-small" aria-hidden="true" />
      </a>
      <a class="next flex is-flex-center" @click="goToNextSlide" v-if="moreThanOne" :title="trans('pagination.next')">
          <b-icon icon="chevron-right" size="is-small" aria-hidden="true" />
      </a>
    </div>

    <tn-slider class='thumbnails' :index='item' :count='thumbnailNumber' v-if="withThumbnails && moreThanOne">
      <tn-item
        v-for='(image,i) in items'
        :key="i"
        @on-item-click='setCurrentSlide(i)'
        :class="{'active': isCurrent(i)}"
      >
        <img v-lazy="image.url">

        <div class="inner-shadow"></div>
      </tn-item>
    </tn-slider>

    <!-- The dots/circles -->
    <div class="dots" v-else-if="moreThanOne">
      <div
        v-for="(image, index) in items"
        class="dot"
        :class="{'active': isCurrent(index)}"
        @click="setCurrentSlide(index)"
      />
    </div>
  </div>
</template>

<script>
import 'thumbnail-slider/dist/thumbnailSlider.css'
import { TnSlider, TnItem } from 'thumbnail-slider'

export default {
  name: 'nzSlider',

  components: {
    'tn-item': TnItem,
    'tn-slider': TnSlider,
  },

  props: {
    items: {
      type: Array,
      default: () => []
    },

    withThumbnails: {
      type: Boolean,
      default: true
    },

    thumbnailNumber: {
      type: Number,
      default: 5
    }
  },

  data() {
    return {
      item: 0,
    }
  },

  computed: {
    moreThanOne() {
      return this.items.length > 1
    }
  },

  methods: {
    goToPrevSlide() {
      if (this.item > 0) {
        this.item--
      } else {
        this.item = this.items.length - 1
      }
    },

    goToNextSlide() {
      if (this.item < this.items.length - 1) {
        this.item++
      } else {
        this.item = 0
      }
    },

    setCurrentSlide(index) {
      this.item = index
    },

    isCurrent(index) {
      return this.item === index
    }
  }
}
</script>
