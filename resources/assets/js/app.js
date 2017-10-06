import './bootstrap';
import Vue from 'vue';
import Buefy from 'buefy';
import Navbar from './components/navbar';
import DateInput from './components/date-input';
import PhotoUpload from './components/photo-upload';
import DynamicField from './components/dynamic-input';
import TaxonAutocomplete from './components/taxon-autocomplete';
import FieldObservationForm from './components/field-observation-form';

window.Vue = Vue;

Vue.use(Buefy, {
    defaultIconPack: 'fa'
})

Vue.component(Navbar.name, Navbar);
Vue.component(DateInput.name, DateInput);
Vue.component(DynamicField.name, DynamicField);
Vue.component(DynamicField.name, DynamicField);
Vue.component(TaxonAutocomplete.name, TaxonAutocomplete);
Vue.component(FieldObservationForm.name, FieldObservationForm);
Vue.component(FieldObservationForm.name, FieldObservationForm);
Vue.component(PhotoUpload.name, PhotoUpload);

const app = new Vue({
    el: '#app'
});
