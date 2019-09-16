<template>
  <form @submit.prevent="submit" class="announcement-form">
    <b-field
      class="is-required"
      :label="trans('labels.announcements.title')"
      :type="form.errors.has('title') ? 'is-danger' : ''"
      :message="form.errors.has('title') ? form.errors.first('title') : ''"
    >
      <b-tabs size="is-small" class="block" @change="(index) => focusOnTranslation(index, 'title')">
        <b-tab-item :label="trans('languages.' + data.name)" v-for="(data, locale) in supportedLocales" :key="locale">
          <b-input v-model="form.title[locale]" autofocus  :ref="`title-${locale}`" />
        </b-tab-item>
      </b-tabs>
    </b-field>

    <b-field
      class="is-required"
      :label="trans('labels.announcements.message')"
      :type="form.errors.has('message') ? 'is-danger' : ''"
      :message="form.errors.has('message') ? form.errors.first('message') : ''"
    >
      <b-tabs size="is-small" class="block" @change="(index) => focusOnTranslation(index, 'message')">
        <b-tab-item :label="trans('languages.' + data.name)" v-for="(data, locale) in supportedLocales" :key="locale">
          <nz-wysiwyg v-model="form.message[locale]" :ref="`message-${locale}`" />
        </b-tab-item>
      </b-tabs>
    </b-field>

    <div class="columns">
      <div class="column">
        <b-field :label="trans('labels.announcements.private')">
          <b-switch v-model="form.private">
              {{ form.private ? trans('Yes') : trans('No') }}
          </b-switch>
        </b-field>
      </div>
    </div>

    <hr>

    <button type="submit" class="button is-primary">{{ isEdit ? trans('buttons.save') : trans('labels.announcements.publish') }}</button>

    <a :href="cancelUrl" class="button">{{ trans('buttons.cancel') }}</a>
  </form>
</template>

<script>
import Form from 'form-backend-validation'
import _keys from 'lodash/keys'
import _get from 'lodash/get'
import _first from 'lodash/first'
import NzWysiwyg from '@/components/inputs/Wysiwyg'

function defaultTranslations() {
    const value = {};

    _keys(window.App.supportedLocales).forEach(locale => {
        value[locale] = null;
    });

    return value;
}

export default {
    name: 'nzAnnouncementForm',

    components: {
      NzWysiwyg
    },

    props: {
        action: String,
        method: {
            type: String,
            default: 'post'
        },
        types: {
            type: Array,
            default: () => []
        },
        announcement: {
            type: Object,
            default() {
                return {
                    private: false,
                };
            }
        },
        title: {
            type: Object,
            default: () => defaultTranslations()
        },
        message: {
            type: Object,
            default: () => defaultTranslations()
        },
        redirectUrl: String,
        cancelUrl: String
    },

    data() {
        return {
            form: this.newForm()
        };
    },

    computed: {
        supportedLocales() {
            return window.App.supportedLocales;
        },

        isEdit() {
            return this.method.toLowerCase() === 'put'
        }
    },

    methods: {
        submit() {
            if (this.form.processing) return;

            this.form[this.method.toLowerCase()](this.action)
                .then(this.onSuccessfulSubmit)
                .catch(this.onFailedSubmit);
        },

        /**
         * Handle successful form submit.
         */
        onSuccessfulSubmit() {
            this.form.processing = true

            this.$buefy.toast.open({
                message: this.trans('Saved successfully'),
                type: 'is-success'
            });

            // We want to wait a bit before we send the user to redirect route
            // so we can show the message that the action was successful.
            setTimeout(() => {
                this.form.processing = false;

                window.location.href = this.redirectUrl;
            }, 500);
        },

        /**
         * Handle failed form submit.
         *
         * @param {Error} error
         */
        onFailedSubmit(error) {
            this.$buefy.toast.open({
                duration: 2500,
                message: _get(error, 'response.data.message', error.message),
                type: 'is-danger'
            });
        },

        newForm() {
            return new Form({
                ...this.announcement,
                title: this.title,
                message: this.message
            }, {
                resetOnSuccess: false
            });
        },

        focusOnTranslation(index, attribute) {
            const locales = _keys(this.supportedLocales);
            const selector = `${attribute}-${locales[index]}`;

            setTimeout(() => {
                _first(this.$refs[selector]).focus();
            }, 500);
        }
    }
}
</script>
