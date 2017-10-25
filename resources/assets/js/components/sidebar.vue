<template>
    <div class="quickview" :class="{'is-active': active}" style="max-width: 350px; width: 90%">
        <header class="quickview-header">
            <p class="title"></p>
            <span class="delete"></span>
        </header>

        <div class="quickview-body">
            <div class="quickview-block">
                marksheet
            </div>
            <div class="quickview-block">
                marksheet
            </div>
            <div class="quickview-block">
                marksheet
            </div>
            <div class="quickview-block">
                marksheet
            </div>
            <b-tabs position="is-centered" class="block">
                <b-tab-item label="Notifications">
                </b-tab-item>
                <b-tab-item label="Announcements">
                </b-tab-item>
            </b-tabs>
        </div>

        <footer class="quickview-footer">

        </footer>
    </div>
</template>

<script>
export default {
    props: ['active']
}
</script>
