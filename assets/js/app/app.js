import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'


window.axios = require('axios');
window.Vue = Vue;

Vue.use(BootstrapVue);

/*Vue.component('modals', modals);
Vue.component('product_archive', product_archive);
Vue.component('check_location_modal', check_location_modal);
Vue.component('Autocomplete', Autocomplete);
Vue.component('ItemTemplate', ItemTemplate);*/

const app = new Vue({
    el: '#app',
});