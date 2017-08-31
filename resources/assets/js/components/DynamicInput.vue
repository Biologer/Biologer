<template>
  <div class="">
    <nz-select-input v-if="'select' === field.type"
      :label="field.label"
      :options="field.options"
      :value="field.value"
      @input="onInput"
    ></nz-select-input>
    <nz-text-input v-else
      :label="field.label"
      :value="field.value"
      @input="onInput"
    ></nz-text-input>
  </div>
</template>

<script>
export default {
  props: ['field'],

  methods: {
    onInput(event) {
      this.$emit('input', event.target.value)
    }
  }
}
</script>
