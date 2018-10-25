<template>
  <div class="modal-card">
    <div class="modal-card-head">
      <p class="modal-card-title">{{ trans('labels.exports.title') }}</p>
    </div>

    <div class="modal-card-body">
      {{ trans('labels.exports.finished') }}

      <a :href="url" :download="filename">{{ trans('buttons.download') }}</a>
    </div>

    <div class="modal-card-foot">
      <button type="button" class="button" @click="$emit('close')">{{ trans('buttons.close') }}</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'nzExportDownloadModal',

  props: {
    url: {
      type: String,
      required: true
    }
  },

  computed: {
    filename() {
      const filename = this.url.substring(this.url.lastIndexOf('/') + 1)

      return `export-${filename}`
    }
  }
}
</script>
