<template>
  <b-field>
    <b-select :value="value" @input="$emit('input', $event)" placeholder="Per page">
      <option
        v-for="(option, index) in options"
        :value="option"
        :key="index"
        v-text="option"
      />
    </b-select>
  </b-field>
</template>
<script>
export default {
  name: 'nzPerPageSelect',

  props: {
    value: Number,
    options: Array,
  }
}
</script>
