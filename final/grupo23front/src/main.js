import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './App.vue';
import VueSweetalert2 from 'vue-sweetalert2';
import store from './store'

// require ('./assets/materialize/css/materialize.css')
// require ('./assets/materialize/js/materialize.js')
require ('../node_modules/bulma/css/bulma.css')

import axios from 'axios'
import swal from 'sweetalert2'

Vue.use(VueRouter);
Vue.use(VueSweetalert2);

Vue.config.productionTip = false
window.axios = axios

//rutas
// import TestSubmit from './components/TestSubmit.vue'
// import MainComponent from './components/MainComponent.vue'
import Home from './components/Home.vue';
import Login from './components/Login.vue';
import PatientIndex from './components/Patients/PatientIndex.vue';

const routes = [
  { path: '/', component: Home },
  { path: '/login', component: Login},
  { path: '/paciente', component: PatientIndex},
  // { path: '/lugar1', component: MainComponent},
  // { path: '/lugar2', component: TestSubmit},
  { path: '*', redirect: '/' }
]

const router = new VueRouter({
  mode: "history",
  routes
})


const methods = [
  window.axios.interceptors.response.use(function (response) {  //metodo para redirigir a login cuando la sesion expira, siempre y cuando no este en el login
    return response;
  }, function (error) {
    if (window.location.pathname !='/login' && 401 === error.response.status) {
      swal({
            title: "Session Expired",
            text: "Your session has expired. Would you like to be redirected to the login page?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            closeOnConfirm: false
        }, function(){
            window.location = '/login';
        });
    } else {
        return Promise.reject(error);
    }
  }),
  (function() {
    var token = localStorage.getItem('token');
    if (token) {
        axios.defaults.headers.common['Authorization'] = token;
    } else {
        axios.defaults.headers.common['Authorization'] = null;
        /*if setting null does not remove `Authorization` header then try     
          delete axios.defaults.headers.common['Authorization'];
        */
    }
})()
]

new Vue({
  render: h => h(App),
  store,
  router,
  methods
}).$mount('#app')


