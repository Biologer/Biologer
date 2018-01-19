import './bootstrap';
import Vue from 'vue';
import Buefy from 'buefy';
import * as VueGoogleMaps from 'vue2-google-maps';

import Navbar from './components/Navbar';
import Sidebar from './components/Sidebar';
import Captcha from './components/Captcha';

import DateInput from './components/inputs/DateInput';
import PhotoUpload from './components/inputs/PhotoUpload';
import SpatialInput from './components/inputs/SpatialInput';
import TaxonAutocomplete from './components/inputs/TaxonAutocomplete';

import Table from './components/table/Table';
import TaxaTable from './components/tables/TaxaTable';
import UsersTable from './components/tables/UsersTable';
import FieldObservationsTable from './components/tables/FieldObservationsTable';

import UserForm from './components/forms/UserForm';
import TaxonForm from './components/forms/TaxonForm';
import FieldObservationForm from './components/forms/FieldObservationForm';

window.Vue = Vue;

Vue.use(Buefy, { defaultIconPack: 'fa' });

// Config Google Maps
let gmapsConfig = {};
if (window.App && window.App.gmaps && window.App.gmaps.load) {
    gmapsConfig.load = {
        key: window.App.gmaps.apiKey,
        libraries: 'drawing'
    }
}
Vue.use(VueGoogleMaps, gmapsConfig);

if (window.route) {
    Vue.prototype.$ziggy = window.route;
}

Vue.component(Navbar.name, Navbar);
Vue.component(Sidebar.name, Sidebar);
Vue.component(Captcha.name, Captcha);
Vue.component(DateInput.name, DateInput);
Vue.component(Table.name, Table);
Vue.component(TaxaTable.name, TaxaTable);
Vue.component(UsersTable.name, UsersTable);
Vue.component(PhotoUpload.name, PhotoUpload);
Vue.component(SpatialInput.name, SpatialInput);
Vue.component(TaxonAutocomplete.name, TaxonAutocomplete);
Vue.component(FieldObservationForm.name, FieldObservationForm);
Vue.component(TaxonForm.name, TaxonForm);
Vue.component(UserForm.name, UserForm);
Vue.component(FieldObservationsTable.name, FieldObservationsTable);

const app = new Vue({
    el: '#app'
});
