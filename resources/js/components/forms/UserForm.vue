<template>
  <div class="user-form">
    <form @submit.prevent="submit">
      <div class="columns">
        <div class="column">
          <b-field :label="trans('labels.users.first_name')" class="is-required">
            <b-input v-model="form.first_name" />
          </b-field>
        </div>

        <div class="column">
          <b-field :label="trans('labels.users.last_name')" class="is-required">
            <b-input v-model="form.last_name" />
          </b-field>
        </div>
      </div>

      <b-field :label="trans('labels.users.institution')">
        <b-input v-model="form.institution" />
      </b-field>

      <b-field :label="trans('labels.users.roles')">
        <div class="block">
          <b-checkbox
            v-for="role in roles"
            :key="role.id"
            v-model="form.roles_ids"
            :native-value="role.id"
          >
            {{ trans('roles.' + role.name) }}
          </b-checkbox>
        </div>
      </b-field>

      <b-field :label="trans('labels.users.curated_taxa')" v-if="isCurator">
        <b-taginput
          v-model="curatedTaxa"
          :data="filteredTaxa"
          autocomplete
          field="name"
          placeholder="Type taxon name"
          @typing="onTaxonNameInput"
        />
      </b-field>

      <hr>

      <button type="submit" class="button is-primary">{{ trans('buttons.save') }}</button>
    </form>
  </div>
</template>

<script>
import Form from 'form-backend-validation'

export default {
  name: 'nzUserForm',

  props: {
    action: String,
    method: {
      type: String,
      default: 'post'
    },
    roles: Array,
    user: {
      type: Object,
      default() {
        return {
          first_name: '',
          last_name: '',
          institution: '',
          roles: [],
          curated_taxa: [],
        }
      }
    },
    redirect: String
  },

  data() {
    return {
      filteredTaxa: [],
      curatedTaxa: this.user.curated_taxa,
      form: new Form({
        first_name: this.user.first_name,
        last_name: this.user.last_name,
        institution: this.user.institution,
        roles_ids: this.user.roles.map(role => role.id),
        curated_taxa_ids: this.user.curated_taxa.map(taxon => taxon.id)
      }, {
        resetOnSuccess: false
      })
    }
  },

  computed: {
    isCurator() {
      return _.includes(this.form.roles_ids, _.find(this.roles, { name: 'curator' }).id)
    }
  },

  watch: {
    curatedTaxa(value) {
      this.form.curated_taxa_ids = value.map(taxon => taxon.id)
    }
  },

  methods: {
    onTaxonNameInput: _.debounce(function (name) {
      return this.fetchTaxa(name)
    }, 500),

    fetchTaxa(name) {
      axios.get(route('api.taxa.index'), {
        params: {
          name,
          page: 1,
          per_page: 10,
          except: this.curatedTaxa.map(taxon => taxon.id)
        }
      }).then(({ data }) => {
        this.filteredTaxa = data.data
      })
    },

    submit() {
      if (this.form.processing) return

      this.form[this.method.toLowerCase()](this.action)
          .then(this.onSuccessfulSubmit)
          .catch(this.onFailedSubmit)
    },

    /**
     * Handle successful form submit.
     */
    onSuccessfulSubmit() {
      this.form.processing = true

      this.$toast.open({
          message: this.trans('Saved successfully'),
          type: 'is-success'
      })

      // We want to wait a bit before we send the user to redirect route
      // so we can show the message that the action was successful.
      setTimeout(() => {
          this.form.processing = false

          window.location.href = this.redirect
      }, 500)
    },

    /**
     * Handle failed form submit.
     *
     * @param {Error} error
     */
    onFailedSubmit(error) {
      this.$toast.open({
        duration: 2500,
        message: _.get(error, 'response.data.message', error.message),
        type: 'is-danger'
      })
    }
  }
}
</script>
