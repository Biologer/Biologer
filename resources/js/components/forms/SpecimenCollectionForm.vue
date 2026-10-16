<template>
  <form :action="action" :method="method" :lang="locale" class="specimen-collection-form" @submit.prevent="submitWithRedirect">
    <div class="columns">
      <b-field
        :label="trans('labels.specimen_collections.name')"
        label-for="name"
        :type="form.errors.has('name') ? 'is-danger' : null"
        :message="form.errors.has('name') ? form.errors.first('name') : null"
        class="is-required column"
      >
        <b-input id="name" name="name" v-model="form.name" expanded />
      </b-field>

      <b-field
        :label="trans('labels.specimen_collections.code')"
        label-for="code"
        :type="form.errors.has('code') ? 'is-danger' : null"
        :message="form.errors.has('code') ? form.errors.first('code') : null"
        class="is-required column"
      >
        <b-input id="code" name="code" v-model="form.code" expanded />
      </b-field>
    </div>

    <div class="columns">
      <b-field
        :label="trans('labels.specimen_collections.institution_name')"
        label-for="institution_name"
        :type="form.errors.has('institution_name') ? 'is-danger' : null"
        :message="form.errors.has('institution_name') ? form.errors.first('institution_name') : null"
        class="column"
      >
        <b-input id="institution_name" name="institution_name" v-model="form.institution_name" expanded />
      </b-field>

      <b-field
        :label="trans('labels.specimen_collections.institution_code')"
        label-for="institution_code"
        :type="form.errors.has('institution_code') ? 'is-danger' : null"
        :message="form.errors.has('institution_code') ? form.errors.first('institution_code') : null"
        class="column"
      >
        <b-input id="institution_code" name="institution_code" v-model="form.institution_code" expanded />
      </b-field>
    </div>

    <hr>

    <button type="submit" class="button is-primary">{{ trans('buttons.save') }}</button>

    <a :href="cancelUrl" class="button">{{ trans('buttons.cancel') }}</a>
  </form>
</template>

<script>
import Form from 'form-backend-validation'
import FormMixin from '@/mixins/FormMixin'

export default {
  name: 'nzSpecimenCollectionForm',

  mixins: [FormMixin],

  props: {
    specimenCollection: {
      type: Object,
      default: () => ({
        name: null,
        code: null,
        institution_name: null,
        institution_code: null,
      })
    },
  },

  data() {
    return {
      form: new Form({
        ...this.specimenCollection
      }, {
        resetOnSuccess: false
      })
    }
  }
}
</script>
