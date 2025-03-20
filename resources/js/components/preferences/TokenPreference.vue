<template>
  <div class="token-preference">
    <hr>

    <div v-if="isRevoked" class="block">
      <h2><strong>Generate Access Token</strong></h2>
    </div>

    <div v-if="!isRevoked && token">
      <h2><strong>Your Token:</strong></h2>

      <b-input type="textarea" v-model="token" readonly></b-input>

      <br>

      <p>You should copy your token and keep it safe. If you lose your token you can revoke it and generate another one. This token will be displayed <strong>only this time</strong>.</p>
      <p>You can use it as: "Authorization: Bearer YOUR_ACCESS_TOKEN"</p>
    </div>

    <div v-else-if="!isRevoked && !token" class="block">
        <p>You already have generated your access token. If you dont have it saved you can revoke your token and generate a new one.</p>
    </div>

    <hr>

    <button v-if="isRevoked" class="button is-primary" @click="generateToken">{{ trans('buttons.generate_token') }}</button>

    <button v-if="!isRevoked && token" class="button is-secondary" @click="copyToken">{{ trans('buttons.copy_token') }}</button>

    <button v-if="!isRevoked" class="button is-primary" @click="revokeToken">{{ trans('buttons.revoke_token') }}</button>


  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    generateRoute: String,
    revokeRoute: String,
    tokens: {
      type: Array,
      default: () => []
    }
  },

  data() {
    return {
      token: '',
      revoked: this.tokens.length === 0
    };
  },

  computed: {
    isRevoked() {
      return this.revoked;
    }
  },

  watch: {
    tokens(newTokens) {
      this.token = newTokens.length ? newTokens[0] : '';
      this.revoked = newTokens.length === 0;
    }
  },

  methods: {
    async generateToken() {
      try {
        const response = await axios.get(route(this.generateRoute));
        this.token = response.data.token;
        this.revoked = false;
        this.$emit('update-tokens', [this.token]);
      } catch (error) {
        console.error("Error generating token:", error);
      }
    },

    async revokeToken() {
      try {
        await axios.get(route(this.revokeRoute));
        this.token = '';
        this.revoked = true;
        this.$emit('update-tokens', []);
      } catch (error) {
        console.error("Error revoking token:", error);
      }
    },

    copyToken() {
      navigator.clipboard.writeText(this.token)
        .then(() => alert('Token copied to clipboard!'))
        .catch(err => console.error('Failed to copy token', err));
    },
  }
};
</script>
