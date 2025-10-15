import './bootstrap'
import Vue from 'vue'
import Buefy from './buefy'
import dayjs from './dayjs'
import * as VueGoogleMaps from 'vue2-google-maps'
import { VTooltip } from 'v-tooltip'
import { setTooltipOptions } from './tooltip'
import VueLazyload from 'vue-lazyload'
import _eachRight from 'lodash/eachRight'
import _replace from 'lodash/replace'

window.Vue = Vue

Vue.use(VueLazyload, {
  attempt: 1,
  loading: 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHZpZXdCb3g9IjAgMCAzOCAzOCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBzdHJva2U9IiMzNjM2MzYiPgogICAgPGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj4KICAgICAgICA8ZyB0cmFuc2Zvcm09InRyYW5zbGF0ZSgxIDEpIiBzdHJva2Utd2lkdGg9IjIiPgogICAgICAgICAgICA8Y2lyY2xlIHN0cm9rZS1vcGFjaXR5PSIuNSIgY3g9IjE4IiBjeT0iMTgiIHI9IjE4Ii8+CiAgICAgICAgICAgIDxwYXRoIGQ9Ik0zNiAxOGMwLTkuOTQtOC4wNi0xOC0xOC0xOCI+CiAgICAgICAgICAgICAgICA8YW5pbWF0ZVRyYW5zZm9ybQogICAgICAgICAgICAgICAgICAgIGF0dHJpYnV0ZU5hbWU9InRyYW5zZm9ybSIKICAgICAgICAgICAgICAgICAgICB0eXBlPSJyb3RhdGUiCiAgICAgICAgICAgICAgICAgICAgZnJvbT0iMCAxOCAxOCIKICAgICAgICAgICAgICAgICAgICB0bz0iMzYwIDE4IDE4IgogICAgICAgICAgICAgICAgICAgIGR1cj0iMXMiCiAgICAgICAgICAgICAgICAgICAgcmVwZWF0Q291bnQ9ImluZGVmaW5pdGUiLz4KICAgICAgICAgICAgPC9wYXRoPgogICAgICAgIDwvZz4KICAgIDwvZz4KPC9zdmc+'
})
Vue.use(Buefy, { defaultIconPack: 'fa' })

// Config Google Maps
let gmapsConfig = {}
if (window.App && window.App.gmaps && window.App.gmaps.load) {
    gmapsConfig.load = {
        key: window.App.gmaps.apiKey,
        libraries: 'drawing',
        language: document.documentElement.lang
    }
}
Vue.use(VueGoogleMaps, gmapsConfig)

if (window.route) {
    Vue.prototype.$ziggy = window.route
}

Vue.component('NzNavbar', () => import(/* webpackChunkName: "public" */ './components/Navbar'))
Vue.component('NzDashboardNavbar', () => import(/* webpackChunkName: "dashboard" */ './components/DashboardNavbar'))
Vue.component('NzAnnouncement', () => import(/* webpackChunkName: "dashboard" */ './components/Announcement'))
Vue.component('NzSlider', () => import(/* webpackChunkName: "public" */ './components/Slider'))
Vue.component('NzGroupTaxaSearchButton', () => import(/* webpackChunkName: "public" */ './components/GroupTaxaSearchButton'))

Vue.component('NzTokenPreference', () => import('./components/preferences/TokenPreference.vue'))

Vue.component('NzTaxaTable', () => import('./components/tables/TaxaTable'))
Vue.component('NzUsersTable', () => import('./components/tables/UsersTable'))
Vue.component('NzAnnouncementsTable', () => import('./components/tables/AnnouncementsTable'))
Vue.component('NzViewGroupsTable', () => import('./components/tables/ViewGroupsTable'))
Vue.component('NzFieldObservationsTable', () => import('./components/tables/FieldObservationsTable'))
Vue.component('NzLiteratureObservationsTable', () => import('./components/tables/LiteratureObservationsTable'))
Vue.component('NzPublicationsTable', () => import('./components/tables/PublicationsTable'))
Vue.component('NzTaxonomyTable', () => import('./components/tables/TaxonomyTable'))
Vue.component('NzTimedCountObservationsTable', () => import('./components/tables/TimedCountObservationsTable'))
Vue.component('NzTimedCountFieldObservationsTable', () => import('./components/tables/TimedCountFieldObservationsTable'))

Vue.component('NzRegistrationForm', () => import('./components/forms/RegistrationForm'))
Vue.component('NzUserForm', () => import('./components/forms/UserForm'))
Vue.component('NzTaxonForm', () => import('./components/forms/TaxonForm'))
Vue.component('NzFieldObservationForm', () => import('./components/forms/FieldObservationForm'))
Vue.component('NzLiteratureObservationForm', () => import('./components/forms/LiteratureObservationForm'))
Vue.component('NzViewGroupForm', () => import('./components/forms/ViewGroupForm'))
Vue.component('NzAnnouncementForm', () => import('./components/forms/AnnouncementForm'))
Vue.component('NzPublicationForm', () => import('./components/forms/PublicationForm'))

Vue.component('NzFieldObservationActivityLog', () => import('./components/activity/FieldObservationActivityLog'))
Vue.component('NzLiteratureObservationActivityLog', () => import('./components/activity/LiteratureObservationActivityLog'))
Vue.component('NzTaxonActivityLog', () => import('./components/activity/TaxonActivityLog'))
Vue.component('NzTimedCountObservationActivityLog', () => import('./components/activity/TimedCountObservationActivityLog'))

Vue.component('NzFieldObservationsImport', () => import('./components/imports/FieldObservationsImport'))
Vue.component('NzLiteratureObservationsImport', () => import('./components/imports/LiteratureObservationsImport'))

Vue.component('NzFieldObservationApproval', () => import('./components/FieldObservationApproval'))

Vue.component('NzOccurrenceChart', () => import(/* webpackChunkName: "public" */ './components/OccurrenceChart'))

Vue.component('NzCaptcha', () => import(/* webpackChunkName: "public" */ './components/Captcha'))

Vue.component('NzDeleteAccountButton', () => import(/* webpackChunkName: "dashboard" */ './components/DeleteAccountButton'))


Vue.prototype.trans = window.trans = (string, args = {}, defaultString = '') => {
    let value = window.App.i18n[string] || defaultString || string

    _eachRight(args, (paramVal, paramKey) => {
        value = _replace(value, `:${paramKey}`, paramVal)
    })

    return value
}

Vue.filter('formatDateTime', function (value, format = 'DD.MM.YYYY HH:mm') {
  if (!value) return ''

  value = value.toString()

  return dayjs(value).format(format)
})

setTooltipOptions(VTooltip)
Vue.directive('tooltip', VTooltip)

const app = new Vue({
    el: '#app'
})
