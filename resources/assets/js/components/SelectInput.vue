<template>
  <div class="field">
    <label class="label">{{ label }}</label>
    <div class="control">
      <div class="select is-fullwidth">
        <select :value="value" @input="onInput">
          <option v-for="option in options" :value="option.value">{{ option.text }}</option>
        </select>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['label', 'options', 'value'],
  methods: {
    onInput(value) {
      this.$emit('input', value);
    }
  }

}
</script>
