import config, { setOptions } from 'buefy/src/utils/config'
import Dialog from 'buefy/src/components/dialog'
import Modal from 'buefy/src/components/modal'
import Toast from 'buefy/src/components/toast'

const Buefy = {
    install(Vue, options = {}) {
        // Options
        setOptions(Object.assign(config, options))

        Vue.component('b-autocomplete', () => import(/* webpackChunkName: "buefy" */ 'buefy/src/components/autocomplete/Autocomplete'))
        Vue.component('b-checkbox', () => import(/* webpackChunkName: "buefy" */ 'buefy/src/components/checkbox/Checkbox'))
        Vue.component('b-collapse', () => import(/* webpackChunkName: "buefy" */ 'buefy/src/components/collapse/Collapse'))
        Vue.component('b-datepicker', () => import(/* webpackChunkName: "buefy" */ 'buefy/src/components/datepicker/Datepicker'))
        Vue.component('b-dropdown', () => import(/* webpackChunkName: "buefy" */ 'buefy/src/components/dropdown/Dropdown'))
        Vue.component('b-dropdown-item', () => import(/* webpackChunkName: "buefy" */ 'buefy/src/components/dropdown/DropdownItem'))
        Vue.component('b-field', () => import(/* webpackChunkName: "buefy" */ 'buefy/src/components/field/Field'))
        Vue.component('b-icon', () => import(/* webpackChunkName: "buefy" */ 'buefy/src/components/icon/Icon'))
        Vue.component('b-input', () => import(/* webpackChunkName: "buefy" */ 'buefy/src/components/input/Input'))
        Vue.component('b-notification', () => import(/* webpackChunkName: "buefy" */ 'buefy/src/components/notification/Notification'))
        Vue.component('b-radio', () => import(/* webpackChunkName: "buefy" */ 'buefy/src/components/radio/Radio'))
        Vue.component('b-select', () => import(/* webpackChunkName: "buefy" */ 'buefy/src/components/select/Select'))
        Vue.component('b-switch', () => import(/* webpackChunkName: "buefy" */ 'buefy/src/components/switch/Switch'))
        Vue.component('b-table', () => import(/* webpackChunkName: "buefy" */ 'buefy/src/components/table/Table'))
        Vue.component('b-table-column', () => import(/* webpackChunkName: "buefy" */ 'buefy/src/components/table/TableColumn'))
        Vue.component('b-tabs', () => import(/* webpackChunkName: "buefy" */ 'buefy/src/components/tabs/Tabs'))
        Vue.component('b-tab-item', () => import(/* webpackChunkName: "buefy" */ 'buefy/src/components/tabs/TabItem'))
        Vue.component('b-taginput', () => import(/* webpackChunkName: "buefy" */ 'buefy/src/components/taginput/Taginput'))
        Vue.component('b-timepicker', () => import(/* webpackChunkName: "buefy" */ 'buefy/src/components/timepicker/Timepicker'))
        Vue.component('b-tooltip', () => import(/* webpackChunkName: "buefy" */ 'buefy/src/components/tooltip/Tooltip'))
        Vue.component('b-upload', () => import(/* webpackChunkName: "buefy" */ 'buefy/src/components/upload/Upload'))
        Vue.component('b-pagination', () => import(/* webpackChunkName: "buefy" */ 'buefy/src/components/pagination/Pagination'))

        Vue.use(Dialog)
        Vue.use(Modal)
        Vue.use(Toast)
    }
}

export default Buefy

// export programmatic component
export { DialogProgrammatic } from 'buefy/src/components/dialog'
export { ModalProgrammatic } from 'buefy/src/components/modal'
export { ToastProgrammatic } from 'buefy/src/components/toast'
