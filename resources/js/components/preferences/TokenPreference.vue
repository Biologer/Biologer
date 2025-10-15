<template>
  <div class="token-preference">
    {{ trans("User API Tokens allow you to authenticate API requests. You can generate a token to use with your applications or scripts.") }}
    <hr>



    <div v-if="isRevoked" class="block">
      <h2><strong>{{trans("Generate Access Token")}}</strong></h2>
    </div>

    <div v-if="!isRevoked && existingToken">
      <h2><strong>{{trans("Your Token:")}}</strong></h2>

      <b-input type="textarea" v-model="token" readonly></b-input>

      <br>
      <p v-html="trans('You should copy your token and keep it safe. If you lose your token you can revoke it and generate another one. This token will be displayed <strong>only this time</strong>.')"></p>
      <p>{{ trans('You can use it as: "Authorization: Bearer YOUR_ACCESS_TOKEN"') }}</p>
    </div>

    <div v-else-if="!isRevoked && !existingToken" class="block">
        <p>{{ trans("You have already generated access token. If you don't have it saved, you can revoke it and generate a new one.") }}</p>
    </div>

    <hr>

    <button v-if="isRevoked" class="button is-primary" @click="generateWithReason">{{ trans('buttons.generate_token') }}</button>

    <button v-if="!isRevoked && existingToken" class="button is-secondary" @click="copyToken">{{ trans('buttons.copy_token') }}</button>

    <button v-if="!isRevoked" class="button is-primary" @click="revokeConfirm">{{ trans('buttons.revoke_token') }}</button>


  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'nzTokenPreference',

  props: {
    generateRoute: String,
    revokeRoute: String,
    tokens: {
      type: Array,
      default: () => []
    },
  },

  data() {
    return {
      token: '',
      token_id: '',
      reason: '',
    };
  },

  computed: {
    isRevoked() {
      return !this.token_id || this.token_id === '';
    },

    existingToken() {
      return !!this.token;
    },

    lastValidToken() {
      const validTokens = this.tokens.filter(token => !token.revoked);
      return validTokens.length ? validTokens[validTokens.length - 1] : null;
    }
  },

  watch: {
    lastValidToken(newToken) {
      this.token = newToken ? newToken.token : '';
      this.token_id = newToken ? newToken.id : '';
    }
  },

  methods: {
    async generateToken() {
      try {
        const response = await axios.post(route(this.generateRoute), { name: this.reason });
        if (response.data.message) {
          this.$buefy.toast.open(response.data.message);
        } else {
          this.token = response.data.token;
          this.token_id = response.data.id;
          this.$emit('last-valid-token', [...this.tokens, response.data.token]);
          this.$buefy.toast.open(this.trans(`API token generated!`));
        }
      } catch (error) {
        console.error(this.trans("Error generating token:", error));
      }
    },

    async revokeToken() {
      try {
        await axios.post(route(this.revokeRoute), { token_id: this.token_id });
        this.$emit('last-valid-token', this.tokens.filter(t => t.id !== this.token_id));
        this.token = '';
        this.token_id = '';
        this.$buefy.toast.open(this.trans(`API Token revoked!`));
      } catch (error) {
        console.error(this.trans("Error revoking token:"), error);
      }
    },

    generateWithReason() {
      this.$buefy.dialog.prompt({
        message: this.trans('What is your purpose of using API tokens?'),
        inputAttrs: {
          type: "text",
          placeholder: this.trans("Describe your usage.."),
          minlength: 10,
          maxlength: 255
        },
        confirmText: this.trans('buttons.generate_token'),
        cancelText: this.trans('buttons.cancel'),
        trapFocus: true,
        closeOnConfirm: false,
        onConfirm: (value, { close }) => {
          this.reason = value;
          this.generateToken().then(() => close());
        }
      });
    },

    revokeConfirm() {
      this.$buefy.dialog.confirm({
        title: this.trans('Revoking API token'),
        message: this.trans('Are you sure you want to <b>revoke</b> your token? This action cannot be undone.'),
        confirmText: this.trans('Revoke API Token'),
        cancelText: this.trans('buttons.cancel'),
        type: 'is-danger',
        hasIcon: true,
        onConfirm: () => {
          setTimeout(() => {
            this.revokeToken().then(() => {
              close();
            });
          }, 1000);
        }
      })
    },

    copyToken() {
      navigator.clipboard.writeText(this.token)
        .then(() => this.$buefy.toast.open(this.trans(`Token copied to clipboard!`)))
        .catch(err => console.error(this.trans('Failed to copy token'), err));
    },


  }
};
</script>
