import './bootstrap';
import Vue from 'vue';
import Buefy from 'buefy';
import Navbar from './components/navbar';
import Sidebar from './components/sidebar';
import * as VueGoogleMaps from 'vue2-google-maps';
import DateInput from './components/inputs/date-input';
import TaxaTable from './components/tables/taxa-table';
import PhotoUpload from './components/inputs/photo-upload';
import DynamicField from './components/inputs/dynamic-input';
import SpatialInput from './components/inputs/spatial-input';
import TaxonAutocomplete from './components/inputs/taxon-autocomplete';
import FieldObservationForm from './components/forms/field-observation-form';
import FieldObservationsTable from './components/tables/field-observations-table';

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
Vue.component(DateInput.name, DateInput);
Vue.component(TaxaTable.name, TaxaTable);
Vue.component(PhotoUpload.name, PhotoUpload);
Vue.component(DynamicField.name, DynamicField);
Vue.component(DynamicField.name, DynamicField);
Vue.component(SpatialInput.name, SpatialInput);
Vue.component(TaxonAutocomplete.name, TaxonAutocomplete);
Vue.component(FieldObservationForm.name, FieldObservationForm);
Vue.component(FieldObservationsTable.name, FieldObservationsTable);

const app = new Vue({
    el: '#app'
});
