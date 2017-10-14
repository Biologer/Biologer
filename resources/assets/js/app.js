import './bootstrap';
import Vue from 'vue';
import Buefy from 'buefy';
import Navbar from './components/navbar';
import DateInput from './components/date-input';
import * as VueGoogleMaps from 'vue2-google-maps';
import PhotoUpload from './components/photo-upload';
import DynamicField from './components/dynamic-input';
import SpatialInput from './components/spatial-input';
import TaxonAutocomplete from './components/taxon-autocomplete';
import FieldObservationForm from './components/field-observation-form';

window.Vue = Vue;

Vue.use(Buefy, {
    defaultIconPack: 'fa'
})

// Config Google Maps
let gmapsConfig = {};
if (window.App && window.App.gmaps && window.App.gmaps.load) {
    gmapsConfig = {
        load: {
            key: window.App.gmaps.apiKey,
            libraries: 'drawing'
        }
    }
}
Vue.use(VueGoogleMaps, gmapsConfig)

Vue.component(Navbar.name, Navbar);
Vue.component(DateInput.name, DateInput);
Vue.component(PhotoUpload.name, PhotoUpload);
Vue.component(DynamicField.name, DynamicField);
Vue.component(DynamicField.name, DynamicField);
Vue.component(SpatialInput.name, SpatialInput);
Vue.component(TaxonAutocomplete.name, TaxonAutocomplete);
Vue.component(FieldObservationForm.name, FieldObservationForm);
Vue.component(FieldObservationForm.name, FieldObservationForm);

const app = new Vue({
    el: '#app'
});
