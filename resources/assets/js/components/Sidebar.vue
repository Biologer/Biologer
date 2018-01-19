<template>
<div class="sidebar" :class="{'is-active': active}">
    <div class="sidebar-header">
        <div class="buttons has-addons">
            <button
                type="button"
                class="button"
                :class="[activeTab === 'notifications' ? 'is-link' : '']"
                @click="activeTab = 'notifications'">
                Notifications
            </button>
            <button
                type="button"
                class="button"
                :class="[activeTab === 'announcements' ? 'is-link' : '']"
                @click="activeTab = 'announcements'">
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
        <button type="button" class="button" @click="hide">Close</button>
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
        }
    }
}
</script>
