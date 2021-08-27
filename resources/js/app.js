import Vue from 'vue/dist/vue.common';
import VueRouter from 'vue-router';
import App from './App.vue';
import routes from './routes';
import store from './store';
import Toasted from 'vue-toasted';
import JsonExcel from 'vue-json-excel'
import Pagination from 'laravel-vue-pagination'
import Cookies from 'js-cookie'
import 'vue-select/dist/vue-select.css';
import Select2 from 'vue-select';
import swal from 'sweetalert2'
import tinymce from 'vue-tinymce-editor';
import { Compact } from 'vue-color';
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import DateRangePicker from 'vue2-daterange-picker'
import 'vue2-daterange-picker/dist/vue2-daterange-picker.css'
import VueTagsInput from '@johmun/vue-tags-input';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import VueApexCharts from 'vue-apexcharts'
import excel from 'vue-excel-export'
import VueI18n from 'vue-i18n';
import langs from './../lang/Modules/Help/main';

require('./bootstrap');

Vue.use(VueRouter);
Vue.use(Toasted);
Vue.use(Cookies);
Vue.use(Toast);
Vue.use(Loading);
Vue.use(VueApexCharts)
Vue.use(excel)
Vue.use(VueI18n);

Vue.component('downloadExcel', JsonExcel)
Vue.component('pagination', Pagination)
Vue.component('v-select', Select2)
Vue.component('tinymce', tinymce)
Vue.component('compact-picker', Compact)
Vue.component('date-range-picker', DateRangePicker);
Vue.component('vue-tags-input', VueTagsInput);
Vue.component('apexchart', VueApexCharts)

const i18n = new VueI18n({
    locale: 'en',
    messages : langs
})

const router = new VueRouter({
    mode: 'history',
    routes,
});
// // Auto logout
// var idleMax = 120; // Logout after 1hr of IDLE
var idleMax = 480; // Logout after 4 hrs of IDLE
var idleTime = 0;

var idleInterval = setInterval(timerIncrement, 5000);  // 1 minute interval
$( "body" ).mousemove(function( event ) {
    idleTime = 0; // reset to zero
});

// count minutes
function timerIncrement() {
  idleTime = idleTime + 1;
  if (idleTime > idleMax) {
    store.dispatch('logout')
    localStorage.clear();
    Cookies.remove('vuex');
    router.push({ name: 'login' });
  }
}
// //End auto logout
router.beforeEach((to, from, next) => {

    if (to.matched.some(record => record.meta.requiresAuth)) {
        const isLoggedIn = store.getters['isLoggedIn'];
        if (!isLoggedIn) {
            return next({ path: '/login' });
        }
        return next();
    } else {
        next() // make sure to always call next()!
    };

    if (store.state.storeLoading.errorPage.status) {
        store.dispatch('setErrorPage', {
            vue: this,
            error: {
                status: false,
                code: null,
            },
        });
    }
  })

//sweetalert
window.swal = swal;

//Work around for TinyMCE integration with bootstrap
// Prevent bootstrap dialog from blocking focusin
$(document).on('focusin', function(e) {
    if ($(e.target).closest(".mce-window").length) {
        e.stopImmediatePropagation();
    }
});

//toast
const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });

new Vue({
    el: '#app',
    render: h => h(App),
    i18n,
    router,
    store
});
