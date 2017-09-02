import './bootstrap';
import Vue from 'vue';
import Buefy from 'buefy';
import Navbar from './components/navbar';
import DateInput from './components/date-input';
import TaxonAutocomplete from './components/taxon-autocomplete';

window.Vue = Vue;

Vue.use(Buefy, {
    defaultIconPack: 'fa'
})

Vue.component(Navbar.name, Navbar);
Vue.component(DateInput.name, DateInput);
Vue.component(TaxonAutocomplete.name, TaxonAutocomplete);

const app = new Vue({
    el: '#app'
});
