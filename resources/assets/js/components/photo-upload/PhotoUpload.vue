<template>
	<b-field>
        <b-upload
            multiple
            drag-drop
            :loading="true">
        </b-upload>
    </b-field>
</template>

<script>
export default {
	name: 'nz-photo-upload',

    props: {
        action: String,
    },

    data() {
    	return {
    		photo: {
    			url: ''
    		}
    	};
    }
}
</script>
