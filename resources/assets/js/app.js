import './bootstrap';
import Vue from 'vue';
import Buefy from 'buefy';
import * as VueGoogleMaps from 'vue2-google-maps';

import Navbar from './components/navbar';
import Sidebar from './components/sidebar';
import Captcha from './components/captcha';

import DateInput from './components/inputs/date-input';
import PhotoUpload from './components/inputs/photo-upload';
import SpatialInput from './components/inputs/spatial-input';
import TaxonAutocomplete from './components/inputs/taxon-autocomplete';

import Table from './components/table';
import TaxaTable from './components/tables/taxa-table';
import UsersTable from './components/tables/users-table';
import FieldObservationsTable from './components/tables/field-observations-table';

import UserForm from './components/forms/user-form';
import TaxonForm from './components/forms/taxon-form';
import FieldObservationForm from './components/forms/field-observation-form';

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
