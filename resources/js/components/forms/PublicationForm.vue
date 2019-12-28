<template>
  <form :action="action" :method="method" :lang="locale" class="publication-form" @submit.prevent="submitWithRedirect">
    <div class="columns">
      <div class="column">
        <b-field
          :label="trans('labels.publications.type')"
          label-for="type"
          :type="form.errors.has('type') ? 'is-danger' : null"
          :message="form.errors.has('type') ? form.errors.first('type') : null"
          class="is-required"
        >
          <b-select id="type" name="type" v-model="form.type" expanded>
            <option
              v-for="publicationType in publicationTypes"
              :value="publicationType.value"
              :key="publicationType.value"
            >
              {{ publicationType.label }}
            </option>
          </b-select>
        </b-field>

        <b-field
          :label="trans('labels.publications.year')"
          label-for="year"
          :type="form.errors.has('year') ? 'is-danger' : null"
          :message="form.errors.has('year') ? form.errors.first('year') : null"
          class="is-required"
        >
          <b-input id="year" name="year" v-model="form.year" type="number" expanded />
        </b-field>

        <b-field
          v-if="typeHasName"
          :label="nameLabel"
          label-for="name"
          :type="form.errors.has('name') ? 'is-danger' : null"
          :message="form.errors.has('name') ? form.errors.first('name') : null"
          class="is-required"
        >
          <b-input id="name" name="name" v-model="form.name" expanded />
        </b-field>

        <b-field
          :label="titleLabel"
          label-for="title"
          :type="form.errors.has('title') ? 'is-danger' : null"
          :message="form.errors.has('title') ? form.errors.first('title') : null"
          class="is-required"
        >
          <b-input id="title" name="title" v-model="form.title" expanded />
        </b-field>

        <b-field
          v-if="typeHasPublisher"
          :label="trans('labels.publications.publisher')"
          label-for="publisher"
          :class="{'is-required': typeRequiresPublisher}"
          :type="form.errors.has('publisher') ? 'is-danger' : null"
          :message="form.errors.has('publisher') ? form.errors.first('publisher') : null"
        >
          <b-input id="publisher" name="publisher" v-model="form.publisher" expanded />
        </b-field>

        <b-field
          v-if="typeHasIssue"
          :label="trans('labels.publications.issue')"
          label-for="issue"
          :type="form.errors.has('issue') ? 'is-danger' : null"
          :message="form.errors.has('issue') ? form.errors.first('issue') : null"
          :class="{'is-required': typeRequiresIssue}"
        >
          <b-input id="issue" name="issue" v-model="form.issue" expanded />
        </b-field>

        <b-field
          v-if="typeHasPlace"
          :label="trans('labels.publications.place')"
          label-for="place"
          :class="{'is-required': typeRequiresPlace}"
          :type="form.errors.has('place') ? 'is-danger' : null"
          :message="form.errors.has('place') ? form.errors.first('place') : null"
        >
          <b-input id="place" name="place" v-model="form.place" expanded />
        </b-field>

        <b-field
          :label="trans('labels.publications.page_count')"
          label-for="page_count"
          :type="form.errors.has('page_count') ? 'is-danger' : null"
          :message="form.errors.has('page_count') ? form.errors.first('page_count') : null"
        >
          <b-input id="page_count" name="page_count" v-model="form.page_count" expanded />
        </b-field>

        <b-field
          v-if="typeHasPageRange"
          :label="trans('labels.publications.page_range')"
          label-for="page_range"
          :type="form.errors.has('page_range') ? 'is-danger' : null"
          :message="form.errors.has('page_range') ? form.errors.first('page_range') : null"
        >
          <b-input id="page_range" name="page_range" v-model="form.page_range" expanded />
        </b-field>

        <b-field
          :label="trans('labels.publications.doi')"
          label-for="doi"
          :type="form.errors.has('doi') ? 'is-danger' : null"
          :message="form.errors.has('doi') ? form.errors.first('doi') : null"
        >
          <b-input id="doi" name="doi" v-model="form.doi" expanded />
        </b-field>

        <b-field
          label-for="citation"
          :type="form.errors.has('citation') ? 'is-danger' : null"
          :message="form.errors.has('citation') ? form.errors.first('citation') : null"
        >
          <label for="citation" class="label" slot="label">
            <span
              class="is-dashed"
              v-tooltip="{content: trans('labels.publications.citation_tooltip')}"
            >
              {{ trans('labels.publications.citation') }}
            </span>
          </label>

          <b-input id="citation" type="textarea" name="citation" v-model="form.citation" expanded />
        </b-field>
      </div>

      <div class="column">
        <b-field
          :label="trans('labels.publications.authors')"
          :type="form.errors.has('authors') ? 'is-danger' : null"
          :message="form.errors.has('authors') ? form.errors.first('authors') : null"
          class="is-required"
          :addons="false"
        >
          <b-field
            v-for="(_,i) in form.authors"
            :key="i"
            :type="authorHasError(i) ? 'is-danger' : null"
            :message="authorHasError(i) ? getAuthorError(i) : null"
            expanded
            :addons="false"
          >
            <b-field
              :type="authorHasError(i) ? 'is-danger' : null"
              expanded
            >
              <b-input
                :name="`authors[${i}][first_name]`"
                v-model="form.authors[i].first_name"
                :placeholder="trans('labels.publications.first_name')"
                expanded
              />

              <b-input
                :name="`authors[${i}][last_name]`"
                v-model="form.authors[i].last_name"
                :placeholder="trans('labels.publications.last_name')"
                expanded
              />

              <p class="control">
                <button type="button" class="button is-danger is-outlined" @click="removeAuthor(i)">
                  <b-icon icon="times" size="is-small"/>
                </button>
              </p>

            </b-field>
          </b-field>

          <button
            type="button"
            class="button is-secondary is-outlined"
            @click="addAuthor"
          >
            {{ trans('labels.publications.add_author') }}
          </button>
        </b-field>

        <b-field
          v-if="typeHasEditors"
          :class="{'is-required': typeRequiresEditors}"
          :label="trans('labels.publications.editors')"
          :type="form.errors.has('editors') ? 'is-danger' : null"
          :message="form.errors.has('editors') ? form.errors.first('editors') : null"
          :addons="false"
        >
          <b-field
            v-for="(_,i) in form.editors"
            :key="i"
            :type="editorHasError(i) ? 'is-danger' : null"
            :message="editorHasError(i) ? getEditorError(i) : null"
            expanded
            :addons="false"
          >
            <b-field
              :type="editorHasError(i) ? 'is-danger' : null"
              expanded
            >
              <b-input
                :name="`editors[${i}][first_name]`"
                v-model="form.editors[i].first_name"
                :placeholder="trans('labels.publications.first_name')"
                expanded
              />

              <b-input
                :name="`editors[${i}][last_name]`"
                v-model="form.editors[i].last_name"
                :placeholder="trans('labels.publications.last_name')"
                expanded
              />

              <p class="control">
                <button type="button" class="button is-danger is-outlined" @click="removeEditor(i)">
                  <b-icon icon="times" size="is-small"/>
                </button>
              </p>

            </b-field>
          </b-field>

          <button
            type="button"
            class="button is-secondary is-outlined"
            @click="addEditor"
          >
            {{ trans('labels.publications.add_editor') }}
          </button>
        </b-field>

        <b-field
          :label="trans('labels.publications.link')"
          :type="form.errors.has('link') ? 'is-danger' : null"
          :message="form.errors.has(`link`) ? form.errors.first(`link`) : null"
        >
          <b-input name="link" v-model="form.link" expanded/>
        </b-field>

        <b-field :label="trans('labels.publications.attachment')">
          <nz-publication-attachment-upload
            @uploaded="handleAttachmentUploaded"
            @removed="handleRemovedAttachment"
            :attachment="form.attachment"
          />
        </b-field>
      </div>
    </div>

    <hr>

    <button type="submit" class="button is-primary">{{ trans('buttons.save') }}</button>

    <a :href="cancelUrl" class="button">{{ trans('buttons.cancel') }}</a>
  </form>
</template>

<script>
import Form from 'form-backend-validation'
import _find from 'lodash/find'
import FormMixin from '@/mixins/FormMixin'
import PublicationAttachmentUpload from '@/components/inputs/PublicationAttachmentUpload'

export default {
  name: 'nzPublicationForm',

  components: {
    [PublicationAttachmentUpload.name]: PublicationAttachmentUpload
  },

  mixins: [FormMixin],

  props: {
    publication: {
      type: Object,
      default: () => ({
        type: null,
        year: null,
        name: null,
        title: null,
        publisher: null,
        issue: null,
        place: null,
        page_count: null,
        page_range: null,
        doi: null,
        citation: null,
        authors: [],
        editors: [],
        link: null,
        attachment: null,
        attachment_id: null
      })
    },

    publicationTypes: {
      type: Array,
      default: () => []
    }
  },

  data() {
    return {
      form: new Form({
        ...this.publication
      }, {
        resetOnSuccess: false
      })
    }
  },

  computed: {
    chosenType() {
      return _find(this.publicationTypes, { value: this.form.type })
    },

    typeHasName() {
      return this.chosenType ? this.chosenType.has_name : false
    },

    typeHasEditors() {
      return this.chosenType ? this.chosenType.has_editors : false
    },

    typeRequiresEditors() {
      return this.chosenType ? this.chosenType.requires_editors : false
    },

    typeHasPublisher() {
      return this.chosenType ? this.chosenType.has_publisher : false
    },

    typeRequiresPublisher() {
      return this.chosenType ? this.chosenType.requires_publisher : false
    },

    typeHasIssue() {
      return this.chosenType ? this.chosenType.has_issue : false
    },

    typeRequiresIssue() {
      return this.chosenType ? this.chosenType.requires_issue : false
    },

    typeHasPlace() {
      return this.chosenType ? this.chosenType.has_place : false
    },

    typeRequiresPlace() {
      return this.chosenType ? this.chosenType.requires_place : false
    },

    typeHasPageRange() {
      return this.chosenType ? this.chosenType.has_page_range : false
    },

    nameLabel() {
      const type = this.form.type

      return this.trans(`labels.publications.${type}_name`, {}, this.trans('labels.publications.name'))
    },

    titleLabel() {
      const type = this.form.type

      return this.trans(`labels.publications.${type}_title`, {}, this.trans('labels.publications.title'))
    }
  },

  methods: {
    addAuthor() {
      this.form.authors.push({ first_name: '', last_name: '' })
    },

    addEditor() {
      this.form.editors.push({ first_name: '', last_name: '' })
    },

    removeAuthor(index) {
      this.removeItemFromField('authors', index)
    },

    removeEditor(index) {
      this.removeItemFromField('editors', index)
    },

    removeItemFromField(field, index) {
      this.form[field] = [
        ...this.form[field].slice(0, index),
        ...this.form[field].slice(index + 1)
      ]

      // We don't want to show that error after removing the erroneous field.
      this.removeErrorForItem(field, index)
    },

    removeErrorForItem(field, index) {
      // One problem may occur if we delete the previous field and are left
      // with error showing for noncorresponding field. We need to shift
      // the key of all later errors so they match the correct field.
      const errors = this.form.errors.all()
      const newErrors = {}

      Object.keys(errors).forEach(errorField => {
        const match = errorField.match(new RegExp(`^${field}.\(\\d+\)$`))

        if (match && match[1] > index) {
          newErrors[`${field}.${match[1] - 1}`] = errors[errorField]
        } else if (!match) {
          newErrors[errorField] = errors[errorField]
        }
      })

      this.form.errors.record(newErrors)
    },

    handleAttachmentUploaded(attachment) {
      this.form.attachment = attachment
      this.form.attachment_id = attachment.id
    },

    handleRemovedAttachment() {
      this.form.attachment = null
      this.form.attachment_id = null
    },

    authorHasError(index) {
      return this.form.errors.has(`authors.${index}`) ||
        this.form.errors.has(`authors.${index}.first_name`) ||
        this.form.errors.has(`authors.${index}.last_name`)
    },

    getAuthorError(index) {
      return this.form.errors.first(`authors.${index}`) ||
        this.form.errors.first(`authors.${index}.first_name`) ||
        this.form.errors.first(`authors.${index}.last_name`)
    },

    editorHasError(index) {
      return this.form.errors.has(`editors.${index}`) ||
        this.form.errors.has(`editors.${index}.first_name`) ||
        this.form.errors.has(`editors.${index}.last_name`)
    },

    getEditorError(index) {
      return this.form.errors.first(`editors.${index}`) ||
        this.form.errors.first(`editors.${index}.first_name`) ||
        this.form.errors.first(`editors.${index}.last_name`)
    }
  }
}
</script>
