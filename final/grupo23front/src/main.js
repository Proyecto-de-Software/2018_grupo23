import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './App.vue';
import VueSweetalert2 from 'vue-sweetalert2';
import VueGoodTable from 'vue-good-table';
import VeeValidate from 'vee-validate';
import store from './store'

// require ('./assets/materialize/css/materialize.css')
// require ('./assets/materialize/js/materialize.js')
require ('../node_modules/bulma/css/bulma.css')

import axios from 'axios'
import swal from 'sweetalert2'
import 'vue-good-table/dist/vue-good-table.css'

Vue.use(VueRouter);
Vue.use(VueSweetalert2);
Vue.use(VueGoodTable);
Vue.use(VeeValidate);

Vue.config.productionTip = false
window.axios = axios

//rutas
// import TestSubmit from './components/TestSubmit.vue'
// import MainComponent from './components/MainComponent.vue'
import Home from './components/Home.vue';
import Login from './components/Login.vue';
import Config from './components/Config.vue';
import PatientIndex from './components/Patients/PatientIndex.vue';
import UserIndex from './components/Users/UserIndex.vue';
import ReportsIndex from './components/Reports/ReportsIndex.vue';

const routes = [
  { path: '/', component: Home },
  { path: '/login', component: Login},
  { path: '/paciente', component: PatientIndex},
  { path: '/config', component: Config},
  { path: '/usuario', component: UserIndex},
  { path: '/reportes', component: ReportsIndex },
  // { path: '/lugar1', component: MainComponent},
  // { path: '/lugar2', component: TestSubmit},
  { path: '*', redirect: '/' }
]

const router = new VueRouter({
  mode: "history",
  routes
})

const data = {
  info: '',
  bloqueo: true
}

const computed = {
  config : {
    get: function () {
      var datos = JSON.parse(this.info);
      var resp = {};
      for (var i = 0; i < datos.length; i++) { //queda algo del tipo { titulo: "Hospital...", email: email@gmail.com...}
         resp[datos[i].variable] = datos[i].valor;
      }
      return resp;
    },
    set: function(dato){
        data.info = dato;
    }
  }
}

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
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
    } else {
        axios.defaults.headers.common['Authorization'] = null;

    }
})()

]

new Vue({
  render: h => h(App),
  store,
  router,
  methods,
  data,
  computed,
  mounted(){
    this.bloqueo = true;
    axios.get('http://localhost:8000/configuracion/').then((response) => {
      this.info = response.data;
      this.bloqueo = false;
      })
  }

}).$mount('#app')
