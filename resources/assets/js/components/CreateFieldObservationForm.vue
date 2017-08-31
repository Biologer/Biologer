<template>
  <div class="">
    <form :action="action" method="POST" @submit.prevent="submit">
      <div v-for="field in dynamicFields" :key="field.name">
        <nz-dynamic-input :field="field" v-model="form.dynamic[field.name]"></nz-dynamic-input>
        <button type="button" class="button is-primary" @click="removeField(field)">Remove</button>
      </div>

      <div v-if="availableDynamicFields.length">
        <select v-model="chosenField" :disabled="!availableDynamicFields.length">
          <option v-for="field in availableDynamicFields" :value="field">{{ field.label }}</option>
        </select>

        <button type="button" @click="addField()" :disabled="!chosenField">Add</button>
      </div>
    </form>
  </div>
</template>

<script>
import collect from 'collect.js';
import Form from 'form-backend-validation';

export default {
  props: {
    action: {
      type: String,
      required: true
    },
    dataDynamicFields: {
      type: Object,
      default: {}
    },
    dataAvailableDynamicFields: {
      type: Array,
      default: []
    }
  },

  data() {
    return {
      form: new Form({
        dynamic: Object.assign({}, this.dataDynamicFields)
      }),
      dynamicFields: [],
      chosenField: null
    };
  },

  created() {
    this.updateFields()
  },

  methods: {
    /**
     * Add field to the form.
     */
    addField() {
      this.form.dynamic[this.chosenField.name] = this.chosenField.value || this.chosenField.default;
      this.chosenField = null;
      this.updateFields()
    },
    /**
     * Don't use the field any more.
     * @param  {Object} field
     */
    removeField(field) {
      delete this.form.dynamic[field.name];
      this.updateFields()
    },

    submit() {
      this.form.post(this.action)
    },

    updateFields() {
      this.dynamicFields = this.dataAvailableDynamicFields.filter((field) => {
        return collect(Object.keys(this.form.dynamic)).contains(field.name);
      }).map((field) => {
        field.value = this.form.dynamic[field.name] || field.value || field.default;
        return field;
      });
    }
  },

  computed: {
    /**
     * Fields that have not been used yet.
     * @return {Array} of field data
     */
    availableDynamicFields() {
      return this.dataAvailableDynamicFields.filter((availableField) => {
        return ! collect(this.dynamicFields).contains((field) => {
          return availableField.name === field.name;
        });
      }).map((field) => {
        field.value = field.value || field.default;
        return field;
      });
    }
  }
}
</script>
