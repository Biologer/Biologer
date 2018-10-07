<template>
<div class="sidebar" :class="{'is-active': active}">
    <div class="sidebar-header">
        <h3 class="sidebar-title">Notifications</h3>
    </div>
    <div class="sidebar-body">
      <template v-if="currentNotifications.length">
          <div class="sidebar-block"
              v-for="notification in currentNotifications">
              Dummy notification
          </div>
      </template>

      <div class="sidebar-block is-fullheight" v-else>
          You don't have new notifications at the moment.
      </div>
    </div>

    <footer class="sidebar-footer">
        <button type="button" class="button is-text" @click="markAllAsRead">Mark all as read</button>

        <button type="button" class="button" @click="hide">{{ trans('buttons.close') }}</button>
    </footer>
</div>
</template>

<script>
export default {
  name: 'nzSidebar',

  props: {
    active: Boolean,

    notifications: {
      type: Array,
      default: () => []
    }
  },

  data() {
    return {
      activeTab: 'notifications',
      currentNotifications: this.notifications,
    };
  },

  computed: {
  },

  created () {
    if (typeof window !== 'undefined') {
      document.addEventListener('keyup', this.keyPress)
    }
  },

  beforeDestroy() {
    if (typeof window !== 'undefined') {
      document.removeEventListener('keyup', this.keyPress)
    }
  },

  methods: {
    hide() {
      this.$emit('close');
    },

    keyPress(event) {
      // Esc key
      if (event.keyCode === 27) this.hide();
    },

    markAllAsRead() {
      //
    }
  }
}
</script>
