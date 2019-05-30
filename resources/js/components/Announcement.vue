<template>
  <article class="message is-info shadow" v-if="newAnnouncement">
    <div class="message-body flex justify-between">
      <div class="">
        {{ newAnnouncement.title }}
      </div>

      <div class="message-actions">
        <a :href="link">Read more</a>

        <b-icon icon="check" @click.native="markAsRead" title="Mark as read" class="is-clickable" />
      </div>
    </div>
  </article>
</template>

<script>
export default {
  name: 'nzAnnouncement',

  props: {
    announcement: Object
  },

  data() {
    return {
      newAnnouncement: this.announcement
    }
  },

  computed: {
    link() {
      return route('announcements.show', { announcement: this.newAnnouncement.id })
    }
  },

  methods: {
    sendRequestToMarkAsRead() {
      return axios.post('/api/read-announcements', {
        announcement_id: this.newAnnouncement.id,
      })
    },

    markAsRead() {
      this.sendRequestToMarkAsRead().then(() => {
        this.newAnnouncement = null
      })
    }
  }
}
</script>
