<template>
    <b-field :label="field.label" expanded>
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

    props: ['field'],

    methods: {
        onInput(value) {
            this.$emit('input', value)
        }
    }
}
</script>
