<template>
  <div class="wysiwyg">

    <input
      :id="inputId"
      type="hidden"
      :name="name"
      :value="value"
      ref="input"
    >

    <trix-toolbar :id="toolbarId">
      <div class="trix-button-row">

        <span class="trix-button-group trix-button-group--text-tools" data-trix-button-group="text-tools">
          <button type="button" class="trix-button trix-button--icon trix-button--icon-bold"
                  data-trix-attribute="bold" data-trix-key="b"
                  :title="lang.bold">{{ lang.bold }}</button>

          <button type="button" class="trix-button trix-button--icon trix-button--icon-italic"
                  data-trix-attribute="italic" data-trix-key="i"
                  :title="lang.italic">{{ lang.italic }}</button>

          <button type="button" class="trix-button trix-button--icon trix-button--icon-strike"
                  data-trix-attribute="strike"
                  :title="lang.strike">{{ lang.strike }}</button>

          <button type="button" class="trix-button trix-button--icon trix-button--icon-link"
                  data-trix-attribute="href" data-trix-action="link" data-trix-key="k"
                  :title="lang.link">{{ lang.link }}</button>
        </span>

        <span class="trix-button-group trix-button-group--history-tools" data-trix-button-group="history-tools">
          <button type="button" class="trix-button trix-button--icon trix-button--icon-undo"
                  data-trix-action="undo" data-trix-key="z"
                  :title="lang.undo">{{ lang.undo }}</button>

          <button type="button" class="trix-button trix-button--icon trix-button--icon-redo"
                  data-trix-action="redo" data-trix-key="shift+z"
                  :title="lang.redo">{{ lang.redo }}</button>
        </span>

      </div>

      <div class="trix-dialogs" data-trix-dialogs>
        <div class="trix-dialog trix-dialog--link" data-trix-dialog="href" data-trix-dialog-attribute="href">
          <div class="trix-dialog__link-fields">
            <input type="url" name="href" class="trix-input trix-input--dialog"
                   :placeholder="lang.urlPlaceholder" required data-trix-input>

            <div class="trix-button-group">
              <input type="button" class="trix-button trix-button--dialog"
                     :value="lang.link" data-trix-method="setAttribute">

              <input type="button" class="trix-button trix-button--dialog"
                     :value="lang.unlink" data-trix-method="removeAttribute">
            </div>
          </div>
        </div>
      </div>
    </trix-toolbar>

    <trix-editor
      ref="trix"
      :input="inputId"
      :toolbar="toolbarId"
    ></trix-editor>
  </div>
</template>


<script>
import Vue from "vue"
import Trix from "trix"

Vue.config.ignoredElements = ["trix-editor", "trix-toolbar"]

function makeid() {
  const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"
  let text = ""
  for (let i = 0; i < 8; i++) {
    text += chars.charAt(Math.floor(Math.random() * chars.length))
  }
  return text
}

export default {
  name: "nzWysiwyg",

  props: {
    name: String,
    value: String
  },

  data() {
    return {
      inputId: makeid(),
      toolbarId: "toolbar-" + makeid(),
      lang: Trix.config.lang
    }
  },

  mounted() {
    this.$refs.trix.addEventListener("trix-change", this.onInput)
  },

  beforeDestroy() {
    this.$refs.trix.removeEventListener("trix-change", this.onInput)
  },

  methods: {
    onInput(e) {
      this.$emit("input", e.target.innerHTML)
    },

    focus() {
      this.$refs.trix.focus()
    }
  }
}
</script>
