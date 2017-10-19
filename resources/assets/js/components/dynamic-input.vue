<template>
    <b-field :label="field.label" expanded
        :type="errors.has('dynamic_fields.'+index) ? 'is-danger' : null"
        :message="errors.has('dynamic_fields.'+index) ? errors.first('dynamic_fields.'+index) : null">
        <b-field>
            <b-select :value="field.value" @input="onInput" v-if="'select' === field.type" expanded>
                <option v-for="option in field.options" :value="option.value" :key="option.value">
                    {{ option.text }}
                </option>
            </b-select>
            <b-input :value="field.value" @input="onInput" expanded v-else></b-input>

            <div class="control">
                <button type="button" class="button has-text-danger" @click="$emit('remove')">&times;</button>
            </div>
        </b-field>
    </b-field>
</template>

<script>
export default {
    name: 'nz-dynamic-input',

    props: ['field', 'errors', 'index'],

    methods: {
        onInput(value) {
            this.$emit('input', value)
        }
    }
}
</script>
