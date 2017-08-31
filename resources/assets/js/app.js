import './bootstrap';
import Vue from 'vue';
import Buefy from 'buefy';
import DateInput from './components/date-input';
import TaxonAutocomplete from './components/taxon-autocomplete';

window.Vue = Vue;

Vue.use(Buefy, {
    defaultIconPack: 'fa'
})

Vue.component('nz-date-input', DateInput);
Vue.component('nz-taxon-autocomplete', TaxonAutocomplete);

const app = new Vue({
    el: '#app'
});
