import Vue from 'vue';
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

require('./bootstrap');

Vue.use(VueRouter);
Vue.use(Toasted);
Vue.use(Cookies);

Vue.component('downloadExcel', JsonExcel)
Vue.component('pagination', Pagination)
Vue.component('v-select', Select2)
Vue.component('tinymce', tinymce)


const router = new VueRouter({
    mode: 'history',
    routes,
});
// Auto logout
var idleMax = 60; // Logout after 30 minutes of IDLE
var idleTime = 0;

var idleInterval = setInterval(timerIncrement, 60000);  // 1 minute interval
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
//End auto logout
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
    router,
    store
});
