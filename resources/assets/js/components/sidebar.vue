<template>
<div class="sidebar" :class="{'is-active': active}">
    <div class="sidebar-header">
        <div class="buttons has-addons">
            <button
                type="button"
                class="button"
                :class="{'is-link': activeTab === 'notifications'}"
                @click="tabClick('notifications')">
                Notifications
            </button>
            <button
                type="button"
                class="button"
                :class="{'is-link': activeTab === 'announcements'}"
                @click="tabClick('announcements')">
                Announcements
            </button>
        </div>
    </div>
    <div class="sidebar-body">
        <template v-if="activeTab === 'announcements'">
            <template v-if="announcements.length">
                <div class="sidebar-block"
                    v-for="announcement in announcements">
                    Dummy announcement
                </div>
            </template>
            <div class="sidebar-block is-fullheight" v-else>
                There are no announcements at the moment.
            </div>
        </template>
        <template v-else>
            <template v-if="notifications.length">
                <div class="sidebar-block"
                    v-for="notification in notifications">
                    Dummy notification
                </div>
            </template>
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
