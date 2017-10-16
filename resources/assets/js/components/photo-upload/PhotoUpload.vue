<template>
	<b-upload @input="onInput" drag-drop v-if="!haveImage" type="is-fullwidth">
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
	<div class="card" v-else>
		<div class="card-image">
			<figure class="image is-4by3">
				<img :src="image.url" alt="Photo">
			</figure>
	  	</div>
		<footer class="card-footer" v-if="image.file">
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
				file: this.imagePath
			},
			reader: null,
			uploading: false,
			progress: 0,
			hasExisting: !!this.imageUrl
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
			let file = files[0];

			if (!file) {
				return;
			}

			this.upload(file).then(() => {
				this.reader.readAsDataURL(file)
			})
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
				this.image.file = response.data.file = response.data.file;
				this.uploading = false;
				this.progress = 0;

				this.$emit('uploaded', this.image.file);
			}).catch((error) => {
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
					file: this.image.file
				},
				url: this.removeUrl
			}).then(() => {
				this.clearPhoto();

				this.$refs.input.value='';
			})
		},

		clearPhoto() {
			this.$emit('removed', this.image.file);

			this.image.file = null;
			this.image.url = null;
		}
	}
}
</script>
