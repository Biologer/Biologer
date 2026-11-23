<template>
  <div>
    <!-- Secret is still in memory (just generated) -->
    <template v-if="token">
      <h2><strong>{{ trans("Your Token:") }}</strong></h2>

      <b-input type="textarea" :value="token" readonly />

      <br>

      <p v-html="trans('You should copy your token and keep it safe. If you lose your token you can revoke it and generate another one. This token will be displayed <strong>only this time</strong>.')"></p>

      <p>{{ trans('You can use it as: "Authorization: Bearer YOUR_ACCESS_TOKEN"') }}</p>
    </template>

    <!-- Active, but the secret is no longer available -->
    <div v-else class="block">
      <p>
        {{ trans("You have already generated access token. If you don't have it saved, you can revoke it and generate a new one.") }}
      </p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'nzTokenActive',
  props: { token: { type: String, default: '' } },
};
</script>
