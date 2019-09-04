<template>
  <b-field :message="error || null" :type="error ? 'is-danger' : null" expanded>
    <b-field class="file">
      <progress class="progress is-primary is-small" :value="progress" max="100" v-if="uploading">{{ progress }}%</progress>

      <b-upload @input="onInput" accept=".pdf,.odt,.doc,.docx" v-show="!uploading" v-if="!hasAttachment" ref="upload">
        <button type="button" @click="openUpload" class="button is-secondary">
          <b-icon icon="upload"></b-icon>
          <span>Upload Document</span>
        </button>
      </b-upload>
      <div class="flex items-center justify-between w-full" v-else>
        <div class="mr-4">
          {{ attachmentFileName }}
        </div>
        <button type="button" class="button is-danger is-outlined" @click="remove">
          <b-icon icon="times" />
        </button>
      </div>
    </b-field>
  </b-field>
</template>

<script>
export default {
  name: 'nzPublicationAttachmentUpload',

  props: {
    attachmentName: String
  },

  data() {
    return {
      attachmentFileName: this.attachmentName,
      reader: null,
      uploading: false,
      progress: 0,
      error: null,
    }
  },

  computed: {
    hasAttachment() {
      return !!this.attachmentFileName
    }
  },

  methods: {
    onInput(file) {
      this.error = null

      if (!file) {
        this.remove()
      } else {
        this.upload(file)
      }
    },

    openUpload() {
      this.$refs.upload.$refs.input.click()
    },

    upload(file) {
      this.uploading = true
      return axios.post(route('api.publication-attachments.store'), this.makeForm(file), {
        headers: {
          'Content-Type': 'multipart/form-data',
          Accept: 'application/json'
        },
        onUploadProgress: progressEvent => {
           this.progress = Math.floor((progressEvent.loaded * 100) / progressEvent.total)
        }
      }).then(response => {
        const attachment = response.data.data

        this.attachmentFileName = attachment.original_name

        this.uploading = false
        this.progress = 0

        this.$emit('uploaded', attachment)
      }).catch(this.handleError)
    },

    makeForm(file) {
      let form = new FormData()

      form.append('attachment', file)

      return form
    },

    remove() {
      this.$emit('removed', this.attachment)

      this.attachmentFileName = null
    },

    handleError(error) {
      this.uploading = false
      this.progress = 0

      // Clear the input so we can select the same file if needed.
      this.$el.querySelector('input[type="file"]').value = ''

      if (!error.response) {
        return this.$buefy.toast.open({
          duration: 5000,
          message: error.message,
          position: 'is-top',
          type: 'is-danger'
        })
      }

      this.error = error.response.data.errors.attachment[0] || this.trans('Error')
    }
  }
}
</script>
