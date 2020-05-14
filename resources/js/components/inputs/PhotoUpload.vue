<template>
	<b-field v-if="!haveImage" :message="error || null" :type="error ? 'is-danger' : null" expanded>
		<b-upload @input="onInput" drag-drop type="is-fullwidth" class="is-fullwidth">
			<section class="section is-relative" ref="uploadZone">
				<div class="has-text-centered">
					<div>
						<b-icon
							icon="upload"
							size="is-medium">
						</b-icon>
					</div>

					<progress class="progress is-primary is-small" :value="progress" max="100" v-if="uploading">{{ progress }}%</progress>

          <div v-else-if="loading">{{ trans('Loading...') }}</div>

					<div v-else>{{ text }}</div>
				</div>
			</section>
		</b-upload>
	</b-field>

	<div class="card" v-else>
		<div class="card-image">
			<figure class="has-magnifier has-text-centered" @click="showModal = true">
        <img :src="thumbnailUrl" alt="Uploaded photo" class="max-h-36" />
        <div class="image-magnifier">
          <b-icon icon="expand" size="is-medium" />
        </div>
			</figure>
	  </div>

		<footer class="card-footer" v-if="haveImage">
			<div class="card-footer-item flex-col">
				<div class="mb-4 w-full flex justify-between">
					<button
            type="button"
            class="button is-outlined is-small mr-2"
            v-if="withCrop"
            @click="openCropModal"
            @close="closeCropModal"
          >
            <b-icon icon="crop" />
          </button>

					<button type="button" class="delete is-danger is-medium" @click="remove" v-if="image.path || hasExisting"></button>
				</div>

				<div class="w-full" v-if="withLicense">
					<b-select :value="image.license" @input="handleLicenseChanged" expanded>
						<option :value="null">{{ trans('labels.field_observations.default') }}</option>
						<option v-for="(label, value) in licenses" :value="value" :key="value" v-text="label"></option>
					</b-select>
				</div>
			</div>
		</footer>

		<nz-image-crop-modal :active.sync="showCropModal" :crop.sync="image.crop" :image-url="image.url" v-if="haveImage"/>

		<nz-image-modal :items="[thumbnailUrl]" v-if="showModal" @close="showModal = false"/>
	</div>
</template>

<script>
import loadImage from 'blueimp-load-image'
import NzImageCropModal from '@/components/ImageCropModal'
import NzImageModal from '@/components/ImageModal'

export default {
	name: 'nzPhotoUpload',

  components: {
    NzImageCropModal,
    NzImageModal
  },

  props: {
		imageUrl: {
			type: String,
			default: null
		},
		imagePath: {
			type: String,
			default: null
		},
		imageLicense: {
			type: [Number, String],
			default: null
		},
		licenses: {
			type: Object,
			required: true
		},
		text: String,
		withLicense: {
      type: Boolean,
      default: true
    },
    withCrop: {
      type: Boolean,
      default: true
    }
	},

  data() {
    return {
    	image: {
				url: this.imageUrl,
				path: this.imagePath,
				crop: null,
				license: this.imageLicense
			},
			croppedImageUrl: null,
			reader: null,
      uploading: false,
      loading: false,
			progress: 0,
			hasExisting: !!this.imageUrl,
			error: null,
			showCropModal: false,
			showModal: false
    }
  },

	watch: {
		'image.crop': function (value) {
			this.$emit('cropped', this.image)

			if (!value) {
				this.croppedImageUrl = null
				return
			}

			this.cropThumbnail(value)
    }
	},

	computed: {
		haveImage() {
			return !!this.image.url
		},

		thumbnailUrl() {
			return this.croppedImageUrl || this.image.url
		}
	},

	methods: {
		selectImage() {
			this.$refs.input.click()
		},

		onInput(file) {
			this.error = null

			if (!file) {
				return
			}

			this.upload(file)
		},

		upload(file) {
      this.uploading = true
			return axios.post(route('api.photo-uploads.store'), this.makeForm(file), {
        headers: {
          'Content-Type': 'multipart/form-data',
					Accept: 'application/json'
				},
				onUploadProgress: progressEvent => {
          this.progress = Math.floor((progressEvent.loaded * 100) / progressEvent.total)
				}
			}).then(response => {
        this.image.path = response.data.file
				this.image.exif = response.data.exif
				this.image.license = null
				this.uploading = false
				this.progress = 0

        this.$emit('uploaded', this.image)

        this.loading = true

        loadImage(file, img => {
            this.image.url = this.getImageDataUrl(img)
            this.loading = false
        }, { orientation: true })
			}).catch(this.handleError)
    },

    getImageDataUrl(img) {
      let canvas = img

      if (! (canvas instanceof HTMLCanvasElement)) {
        canvas = document.createElement('canvas')
        canvas.setAttribute('width', img.width)
        canvas.setAttribute('height', img.height)
        canvas.getContext('2d').drawImage(img, 0, 0)
      }

      return canvas.toDataURL()
    },

		makeForm(file) {
			let form = new FormData()

			form.append('file', file)

			return form
		},

		remove() {
			if (this.hasExisting) {
				this.hasExisting = false
				return this.clearPhoto()
			}

			axios.delete(route('api.photo-uploads.destroy', this.image.path)).then(() => {
				this.clearPhoto()
			})
		},

		clearPhoto() {
			this.$emit('removed', this.image)

			this.image.path = null
			this.image.url = null
			this.image.crop = null
			this.image.license = null
			this.croppedImageUrl = null
		},

		handleError(error) {
			this.uploading = false
			this.loading = false
			this.progress = 0
			this.image.url = null
			// Clear the input so we can select the same image if needed.
			this.$el.querySelector('input[type="file"]').value = ''

			if (!error.response) {
				return this.$buefy.toast.open({
					duration: 5000,
					message: error.message,
					position: 'is-top',
					type: 'is-danger'
				})
			}

			this.error = error.response.data.errors.file[0] || this.trans('Error')
		},

		openCropModal() {
			this.showCropModal = true
		},

		closeCropModal() {
			this.showCropModal = false
		},

		cropThumbnail(crop) {
			// Create image
			const image = document.createElement('img')
			image.src = this.image.url

			// Draw canvas
			const canvas = document.createElement('canvas')

  		canvas.setAttribute('width', crop.width)
  		canvas.setAttribute('height', crop.height)

  		const context = canvas.getContext('2d')
  		context.drawImage(image, -crop.x, -crop.y)

			this.croppedImageUrl = canvas.toDataURL()
		},

		/**
		 * Handle license change.
		 *
		 * @param {String|Number} license
		 */
		handleLicenseChanged(license) {
			this.image.license = license
			this.$emit('license-changed', license)
    }
	}
}
</script>
