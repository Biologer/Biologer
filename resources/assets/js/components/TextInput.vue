<template>
  <div class="field">
    <label class="label">{{ label }}</label>
    <div class="control">
      <input class="input" type="text" :name="name" :placeholder="placeholder" :value="value" @input="onInput">
    </div>
  </div>
</template>

<script>
export default {
  name: 'text-input',

  props: [
    'label',
    'form',
    'name',
    'value',
    'placeholder'
  ],

  methods: {
    onInput(value) {
      this.$emit('input', value)
    }
  }
}
</script>
