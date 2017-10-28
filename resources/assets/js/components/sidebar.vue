<template>
<div class="sidebar" :class="{'is-active': active}">
    <div class="sidebar-header">
        <div class="tabs is-toggle is-fullwidth">
          <ul>
            <li :class="{'is-active': activeTab === 'notifications'}">
                <a @click="tabClick('notifications')">Notifications</a>
            </li>
            <li :class="{'is-active': activeTab === 'announcements'}">
                <a @click="tabClick('announcements')">Announcements</a>
            </li>
          </ul>
        </div>
    </div>
    <div class="sidebar-body">
        <template v-if="activeTab === 'announcements'">
            <div class="sidebar-block"
                v-for="announcement in announcements"
                v-if="announcements.length">
                Dummy announcement
            </div>
            <div class="sidebar-block is-fullheight" v-else>
                You don't have new notifications at the moment.
            </div>
        </template>
        <template v-else>
            <div class="sidebar-block"
                v-for="notification in notifications"
                v-if="notifications.length">
                Dummy notification
            </div>
            <div class="sidebar-block is-fullheight" v-else>
                You don't have new notifications at the moment.
            </div>
        </template>
    </div>

    <footer class="sidebar-footer">
        <button type="button" class="button" @click="$emit('close')">Close</button>
    </footer>
</div>
</template>

<script>
export default {
    name: 'nzSidebar',

    props: ['active'],

    data() {
        return {
            notifications: [],
            announcements: [],
            activeTab: 'notifications'
        };
    },

    methods: {
        hide() {
            this.$emit('close');
        },

        tabClick(tab) {
            this.activeTab = tab;
        }
    }
}
</script>
