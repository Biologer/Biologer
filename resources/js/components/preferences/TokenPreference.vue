<template>
  <div class="token-preference">
    {{ trans("User API Tokens allow you to authenticate API requests. You can generate a token to use with your applications or scripts.") }}

    <hr>
    <div>Token status: <b>{{ statusLabel }}</b></div>
    <hr>

    <component :is="statusComponent" :token="token" />

    <hr>

    <button
      v-if="canGenerate"
      class="button is-primary"
      :disabled="loading"
      @click="generateWithReason"
    >
      {{ trans('buttons.generate_token') }}
    </button>

    <button
      v-if="status === 'active' && token"
      class="button is-secondary"
      :disabled="loading"
      @click="copyToken"
    >
      {{ trans('buttons.copy_token') }}
    </button>

    <button
      v-if="status === 'active'"
      class="button is-primary"
      :disabled="loading"
      @click="revokeConfirm"
    >
      {{ trans('buttons.revoke_token') }}
    </button>
  </div>
</template>

<script>
import axios from 'axios';
import NzTokenNone from './token/NzTokenNone.vue';
import NzTokenRevoked from './token/NzTokenRevoked.vue';
import NzTokenExpired from './token/NzTokenExpired.vue';
import NzTokenActive from './token/NzTokenActive.vue';

const STATUS_COMPONENTS = {
  none: 'nz-token-none',
  revoked: 'nz-token-revoked',
  expired: 'nz-token-expired',
  active: 'nz-token-active',
};

const STATUS_LABELS = {
  none: 'Not generated',
  revoked: 'Revoked',
  expired: 'Expired',
  active: 'Active',
};

export default {
  name: 'nzTokenPreference',

  components: { NzTokenNone, NzTokenRevoked, NzTokenExpired, NzTokenActive },

  props: {
    generateRoute: String,
    revokeRoute: String,
    tokens: { type: Array, default: () => [] },
  },

  data() {
    return {
      token: '',
      loading: false,
      localTokens: [...this.tokens],
      now: Date.now(),
      expirationTimer: null,
    };
  },

  computed: {
    normalizedTokens() {
      return this.localTokens.map(t => ({
        ...t,
        revoked: t.revoked === true || t.revoked === 1 || t.revoked === '1' || t.revoked === 'true',
        createdTs: t.created_at ? new Date(t.created_at).getTime() : 0,
        expiresTs: t.expires_at ? new Date(t.expires_at).getTime() : null,
      }));
    },

    sortedTokens() {
      return [...this.normalizedTokens].sort((a, b) => {
        if (b.createdTs !== a.createdTs) return b.createdTs - a.createdTs;
        return String(b.id).localeCompare(String(a.id));
      });
    },

    latestToken() {
      return this.sortedTokens[0] || null;
    },

    activeToken() {
      return this.sortedTokens.find(t => !t.revoked && !this.hasExpired(t)) || null;
    },

    status() {
      if (this.activeToken) return 'active';
      if (!this.latestToken) return 'none';
      return this.latestToken.revoked ? 'revoked' : 'expired';
    },

    statusComponent() {
      return STATUS_COMPONENTS[this.status];
    },

    statusLabel() {
      return STATUS_LABELS[this.status];
    },

    canGenerate() {
      return this.status !== 'active';
    },
  },

  watch: {
    tokens: {
      deep: true,
      handler(newTokens) {
        this.localTokens = [...newTokens];
      },
    },

    status(newStatus) {
      if (newStatus !== 'active') {
        this.token = '';
      }
    },
  },

  mounted() {
    this.expirationTimer = setInterval(() => {
      this.now = Date.now();
    }, 1000);
  },

  beforeDestroy() {
    clearInterval(this.expirationTimer);
  },

  methods: {
    hasExpired(token) {
      return token.expiresTs !== null && token.expiresTs <= this.now;
    },

    async generateToken(reason) {
      if (this.loading) return;
      this.loading = true;

      try {
        const { data } = await axios.post(route(this.generateRoute), { name: reason });

        this.localTokens = [
          ...this.localTokens,
          {
            id: data.id,
            revoked: false,
            expires_at: data.expires_at,
            created_at: new Date().toISOString(),
          },
        ];

        this.token = data.token;
        this.now = Date.now();

        this.$emit('last-valid-token', [...this.localTokens]);
        this.$buefy.toast.open(this.trans('API token generated!'));
      } catch (error) {
        console.error('Error generating token:', error);
        this.$buefy.toast.open(
          error.response?.data?.message || this.trans('Error generating token.')
        );
      } finally {
        this.loading = false;
      }
    },

    async revokeToken() {
      if (this.loading || !this.activeToken) return;
      this.loading = true;

      const revokedId = this.activeToken.id;

      try {
        await axios.post(route(this.revokeRoute), { token_id: revokedId });

        this.localTokens = this.localTokens.map(t =>
          t.id === revokedId ? { ...t, revoked: true } : t
        );

        this.$emit('last-valid-token', [...this.localTokens]);
        this.$buefy.toast.open(this.trans('API Token revoked!'));
      } catch (error) {
        console.error('Error revoking token:', error);
        this.$buefy.toast.open(
          error.response?.data?.message || this.trans('Error revoking token.')
        );
      } finally {
        this.loading = false;
      }
    },

    generateWithReason() {
      this.$buefy.dialog.prompt({
        message: this.trans('What is your purpose of using API tokens?'),
        inputAttrs: {
          type: 'text',
          placeholder: this.trans('Describe your usage..'),
          minlength: 10,
          maxlength: 255,
        },
        confirmText: this.trans('buttons.generate_token'),
        cancelText: this.trans('buttons.cancel'),
        trapFocus: true,
        closeOnConfirm: false,
        onConfirm: (value, { close }) => {
          this.generateToken(value).then(() => close());
        },
      });
    },

    revokeConfirm() {
      this.$buefy.dialog.confirm({
        title: this.trans('Revoking API token'),
        message: this.trans(
          'Are you sure you want to <b>revoke</b> your token? This action cannot be undone.'
        ),
        confirmText: this.trans('Revoke API Token'),
        cancelText: this.trans('buttons.cancel'),
        type: 'is-danger',
        hasIcon: true,
        onConfirm: () => this.revokeToken(),
      });
    },

    copyToken() {
      if (!this.token) return;

      navigator.clipboard.writeText(this.token)
        .then(() => this.$buefy.toast.open(this.trans('Token copied to clipboard!')))
        .catch(error => console.error('Failed to copy token:', error));
    },
  },
};
</script>
