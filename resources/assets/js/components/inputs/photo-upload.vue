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
				<img :src="image.url" alt="Photo">
			</figure>
	  	</div>
		<footer class="card-footer" v-if="image.path">
			<p class="card-footer-item">
				<button type="button" class="delete is-danger is-medium" @click="remove"></button>
			</p>
		</footer>
	</div>
</template>

<script>
export default {
	name: 'nz-photo-upload',

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
				path: this.imagePath
			},
			reader: null,
			uploading: false,
			progress: 0,
			hasExisting: !!this.imageUrl,
			error: null
    	};
    },

	created() {
		this.initFileReader();
	},

	computed: {
		haveImage() {
			return !!this.image.url;
		},

		imageStyles() {
			return {
				backgroundImage: `url('${this.image.url}')`,
				backgroundSize: 'cover',
			    backgroundRepeat: 'no-repeat',
			    backgroundPosition: 'center center'
			}
		}
	},

	methods: {
		initFileReader() {
			this.reader = new FileReader();

			this.reader.addEventListener('load', () => {
				this.image.url = this.reader.result
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
		}
	}
}
</script>
