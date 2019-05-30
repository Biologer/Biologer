<template>
  <div class="is-flex is-flex-center">
    <img class="image" style="height: 60px" :src="imageUrl" alt="CAPTCHA Image">

    <button type="button" class="button is-small ml-2" @click="refresh">
      <b-icon icon="refresh" size="is-small"/>
    </button>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'nzCaptcha',

  props: {
    url: String,
  },

  data() {
    return {
      imageUrl: this.url,
      refreshing: false
    }
  },

  methods: {
    refresh() {
      if (this.refreshing) return

      this.refreshing = true

      axios.get(this.imageUrl + '&refresh').then(({ data }) => {
        this.imageUrl = data.url
        this.refreshing = false
      })
    }
  }
}
</script>
