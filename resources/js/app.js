import './bootstrap'
import Vue from 'vue'
import Buefy from 'buefy'
import * as VueGoogleMaps from 'vue2-google-maps'
import { VTooltip } from 'v-tooltip'
import { setTooltipOptions } from './tooltip'
import VueLazyload from 'vue-lazyload'
import _eachRight from 'lodash/eachRight'
import _replace from 'lodash/replace'

import Navbar from './components/Navbar'
import Sidebar from './components/Sidebar'
import Announcement from './components/Announcement'
import Captcha from './components/Captcha'
import ImageModal from './components/ImageModal'
import ImageCropModal from './components/ImageCropModal'
import Slider from './components/Slider'
import GroupTaxaSearchButton from './components/GroupTaxaSearchButton'

import DateInput from './components/inputs/DateInput'
import PhotoUpload from './components/inputs/PhotoUpload'
import SpatialInput from './components/inputs/SpatialInput'
import PublicationAutocomplete from './components/inputs/PublicationAutocomplete'
import TaxonAutocomplete from './components/inputs/TaxonAutocomplete'
import UserAutocomplete from './components/inputs/UserAutocomplete'
import Wysiwyg from './components/inputs/Wysiwyg'
import ColumnsPicker from './components/inputs/ColumnsPicker'
import DatetimePicker from './components/inputs/DatetimePicker'

import Table from './components/table/Table'
import TaxaTable from './components/tables/TaxaTable'
import UsersTable from './components/tables/UsersTable'
import ViewGroupsTable from './components/tables/ViewGroupsTable'
import AnnouncementsTable from './components/tables/AnnouncementsTable'
import FieldObservationsTable from './components/tables/FieldObservationsTable'
import LiteratureObservationsTable from './components/tables/LiteratureObservationsTable'

import RegistrationForm from './components/forms/RegistrationForm'
import UserForm from './components/forms/UserForm'
import TaxonForm from './components/forms/TaxonForm'
import FieldObservationForm from './components/forms/FieldObservationForm'
import LiteratureObservationForm from './components/forms/LiteratureObservationForm'
import ViewGroupForm from './components/forms/ViewGroupForm'
import AnnouncementForm from './components/forms/AnnouncementForm'
import PublicationForm from './components/forms/PublicationForm'

import FieldObservationActivityLog from './components/activity/FieldObservationActivityLog'
import LiteratureObservationActivityLog from './components/activity/LiteratureObservationActivityLog'
import TaxonActivityLog from './components/activity/TaxonActivityLog'

import CustomExport from './components/exports/CustomExport'
import ExportModal from './components/exports/ExportModal'

import FieldObservationsImport from './components/imports/FieldObservationsImport'

import FieldObservationApproval from './components/FieldObservationApproval'

import OccurrenceChart from './components/OccurrenceChart'

import DeleteAccountButton from './components/DeleteAccountButton';

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

Vue.component(Navbar.name, Navbar)
Vue.component(Sidebar.name, Sidebar)
Vue.component(Announcement.name, Announcement)
Vue.component(Captcha.name, Captcha)
Vue.component(ImageModal.name, ImageModal)
Vue.component(ImageCropModal.name, ImageCropModal)
Vue.component(Slider.name, Slider)
Vue.component(GroupTaxaSearchButton.name, GroupTaxaSearchButton)

Vue.component(Table.name, Table)
Vue.component(TaxaTable.name, TaxaTable)
Vue.component(UsersTable.name, UsersTable)
Vue.component(ViewGroupsTable.name, ViewGroupsTable)
Vue.component(AnnouncementsTable.name, AnnouncementsTable)
Vue.component(FieldObservationsTable.name, FieldObservationsTable)
Vue.component(LiteratureObservationsTable.name, LiteratureObservationsTable)

Vue.component(DateInput.name, DateInput)
Vue.component(PhotoUpload.name, PhotoUpload)
Vue.component(SpatialInput.name, SpatialInput)
Vue.component(PublicationAutocomplete.name, PublicationAutocomplete)
Vue.component(TaxonAutocomplete.name, TaxonAutocomplete)
Vue.component(UserAutocomplete.name, UserAutocomplete)
Vue.component(Wysiwyg.name, Wysiwyg)
Vue.component(ColumnsPicker.name, ColumnsPicker)
Vue.component(DatetimePicker.name, DatetimePicker)

Vue.component(RegistrationForm.name, RegistrationForm)
Vue.component(FieldObservationForm.name, FieldObservationForm)
Vue.component(LiteratureObservationForm.name, LiteratureObservationForm)
Vue.component(TaxonForm.name, TaxonForm)
Vue.component(UserForm.name, UserForm)
Vue.component(ViewGroupForm.name, ViewGroupForm)
Vue.component(AnnouncementForm.name, AnnouncementForm)
Vue.component(PublicationForm.name, PublicationForm)

Vue.component(FieldObservationActivityLog.name, FieldObservationActivityLog)
Vue.component(LiteratureObservationActivityLog.name, LiteratureObservationActivityLog)
Vue.component(TaxonActivityLog.name, TaxonActivityLog)

Vue.component(CustomExport.name, CustomExport)
Vue.component(ExportModal.name, ExportModal)

Vue.component(FieldObservationsImport.name, FieldObservationsImport)

Vue.component(FieldObservationApproval.name, FieldObservationApproval)

Vue.component(OccurrenceChart.name, OccurrenceChart)

Vue.component(DeleteAccountButton.name, DeleteAccountButton)

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

  return window.moment(value).format(format)
})

setTooltipOptions(VTooltip)
Vue.directive('tooltip', VTooltip)

const app = new Vue({
    el: '#app'
})
