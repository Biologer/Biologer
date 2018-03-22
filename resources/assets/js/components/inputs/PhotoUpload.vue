<template>
	<b-field v-if="!haveImage" :message="error || null" :type="error ? 'is-danger' : null" expanded>
		<b-upload @input="onInput" drag-drop type="is-fullwidth">
			<section class="section">
				<div class="has-text-centered">
					<div>
						<b-icon
							:icon="icon"
							size="is-medium">
						</b-icon>
					</div>

					<progress class="progress is-primary is-small" :value="progress" max="100" v-if="uploading">{{ progress }}%</progress>

					<div v-else>{{ text }}</div>
				</div>
			</section>
		</b-upload>
	</b-field>

	<div class="card" v-else>
		<div class="card-image">
			<figure class="image is-4by3">
				<img :src="thumbnailUrl" alt="Uploaded photo">
			</figure>
	  </div>

		<footer class="card-footer" v-if="haveImage">
			<p class="card-footer-item">
				<button type="button" class="button is-outlined is-small mr-2" @click="openCropModal" @close="closeCropModal"><b-icon icon="crop" /></button>
				<button type="button" class="delete is-danger is-medium" @click="remove" v-if="image.path"></button>
			</p>
		</footer>

		<nz-image-crop-modal :active.sync="showCropModal" :crop.sync="image.crop" :image-url="image.url" v-if="haveImage"/>
	</div>
</template>

<script>
export default {
	name: 'nzPhotoUpload',

  props: [
    'uploadUrl',
    'removeUrl',
		'imageUrl',
		'imagePath',
		'text',
		'icon'
  ],

  data() {
    return {
    	image: {
				url: this.imageUrl,
				path: this.imagePath,
				crop: null
			},
			croppedImageUrl: null,
			reader: null,
			uploading: false,
			progress: 0,
			hasExisting: !!this.imageUrl,
			error: null,
			showCropModal: false
    };
  },

	watch: {
		'image.crop': function (value) {
			this.$emit('cropped', this.image);

			if (!value) {
				this.croppedImageUrl = null;
				return;
			}

			this.cropThumbnail(value);
		}
	},

	computed: {
		haveImage() {
			return !!this.image.url;
		},

		thumbnailUrl() {
			return this.croppedImageUrl || this.image.url;
		}
	},

	created() {
		this.initFileReader();
	},

	methods: {
		initFileReader() {
			this.reader = new FileReader();

			this.reader.addEventListener('load', () => {
				this.image.url = this.reader.result;
			});
		},

		selectImage() {
			this.$refs.input.click();
		},

		onInput(files) {
			this.error = null;

			let file = files[0];

			if (!file) {
				return;
			}

			this.upload(file)
		},

		upload(file) {
			this.uploading = true
			return axios.post(this.uploadUrl, this.makeForm(file), {
				headers: {
					'Content-Type': 'multipart/form-data',
					Accept: 'application/json'
				},
				onUploadProgress: progressEvent => {
				   this.progress = Math.floor((progressEvent.loaded * 100) / progressEvent.total);
				}
			}).then(response => {
				this.image.path = response.data.file;
				this.uploading = false;
				this.progress = 0;

				this.$emit('uploaded', this.image);

				this.reader.readAsDataURL(file)
			}).catch((error) => {
				this.handleError(error)
				this.uploading = false;
				this.progress = 0;
			})
		},

		makeForm(file) {
			let form = new FormData();

			form.append('file', file);

			return form;
		},

		remove() {
			if (this.hasExisting) {
				this.hasExisting = false;
				return this.clearPhoto();
			}

			axios({
				method: 'DELETE',
				data: {
					file: this.image.path
				},
				url: this.removeUrl
			}).then(() => {
				this.clearPhoto();
			})
		},

		clearPhoto() {
			this.$emit('removed', this.image);

			this.image.path = null;
			this.image.url = null;
			this.image.crop = null;
			this.croppedImageUrl = null;
		},

		handleError(error) {
			if (!error.response) {
				return this.$toast.open({
					duration: 5000,
					message: error.message,
					position: 'is-top',
					type: 'is-danger'
				})
			}

			this.error = error.response.data.errors.file[0];
		},

		openCropModal() {
			this.showCropModal = true;
		},

		closeCropModal() {
			this.showCropModal = false;
		},

		cropThumbnail(crop) {
			// Create image
			const image = document.createElement('img');
			image.src = this.image.url;

			// Draw canvas
			const canvas = document.createElement('canvas');

  		canvas.setAttribute('width', crop.width);
  		canvas.setAttribute('height', crop.height);

  		const context = canvas.getContext('2d');
  		context.drawImage(image, -crop.x, -crop.y);

			this.croppedImageUrl = canvas.toDataURL();
		}
	}
}
</script>
