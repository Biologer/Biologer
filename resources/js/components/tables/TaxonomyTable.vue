<template>
  <div class="taxonomy-table">

    <div>
      Simple tools for testing. More will be added/modified later ...
    </div>

    <hr>
    <button type="button" class="button is-info" @click="checkConnection">Check</button>
    Check connection to Taxonomy base.
    <br>
    <b>Response:</b> {{ check }}
    <hr>
    <button type="button" class="button is-success" @click="connectTaxonomy">Connect</button>
    If connected, this Biologer database will receive updates from Taxonomy base as soon they are available.
    Connecting will also send info about legislation's and red lists to Taxonomy base, to be in sync.<br>
    <b>Response:</b> {{ connect }}
    <hr>
    <button type="button" class="button is-danger" @click="disconnectTaxonomy">Disconnect</button>
    If disconnected, this Biologer database will NOT receive any updates from taxonomy base. All ID's connected to
    Taxonomy base will be erased!<br>
    <b>Response:</b> {{ disconnect }}
    <hr>
    <button type="button" class="button is-primary" @click="syncTaxonomy">Sync all taxa</button>
    Search for all taxa that are not already updated with Taxonomy base.<br>
    <b>Response:</b> {{ sync }}
    <hr>
    Synced: {{ synced }}
    Not synced: {{ not_synced }}
  </div>
</template>

<script>
export default {
  name: "TaxonomyTable",

  data() {
    return {
      check: null,
      connect: null,
      disconnect: null,
      sync: null,
    }
  },

  props: {
    checkRoute: String,
    connectRoute: String,
    disconnectRoute: String,
    syncRoute: String,

    synced: Number,
    not_synced: Number,
  },

  methods: {
    checkConnection () {
      return axios
        .get(route(this.checkRoute))
        .then(response => (this.check = response.data))
        .catch(error => console.log(error));
    },

    connectTaxonomy () {
      return axios
        .get(route(this.connectRoute))
        .then(response => (this.connect = response.data))
        .catch(error => console.log(error));
    },

    disconnectTaxonomy () {
      if(confirm("Do you really want to disconnect?")) {
        return axios
          .get(route(this.disconnectRoute))
          .then(response => (this.disconnect = response.data))
          .catch(error => console.log(error));
      }
    },

    syncTaxonomy () {
      this.sync = "Syncing... This will take a while... Hang on for a ride.. We dont have progress just yet. Wait for this text to change.."
      return axios
        .get(route(this.syncRoute))
        .then(response => (this.sync = response.data))
        .catch(error => console.log(error));
    },
  },
}
</script>

<style scoped>

</style>
