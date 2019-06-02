import 'babel-polyfill';
import './font-awesome.js';
import 'vue-good-table/dist/vue-good-table.css';
import { messages as esOriginalMessages } from 'vee-validate/dist/locale/es.js';

require('../css/bulma.css');

import Vue from 'vue';
import axios from 'axios';
import VueRouter from 'vue-router';
import VueSweetalert2 from 'vue-sweetalert2';
import VueGoodTable from 'vue-good-table';
import VeeValidate from 'vee-validate';

window.axios = axios;
window.events = new Vue();
Vue.use(VueSweetalert2);
Vue.use(VueGoodTable);
Vue.use(VeeValidate, {
  locale: 'es',
  dictionary: {
    es: {
      messages: { ...esOriginalMessages }
    }
  }
});


/*importo los componentes*/
//import Example from './components/Example.vue'
import Alertas from './components/Alerts/BarraAlerts.vue'
import Closepage from './components/Alerts/ClosePage.vue'
import Home from './components/Home.vue'
import Futer from './components/Futer.vue' /*esto de NINGUNA manera puede contener la palabra Footer*/
import Barra from './components/Barra.vue' /*lo mismo aca srry*/
import Login from './components/Login.vue'
import Config from './components/Config.vue'
import UserIndex from './components/Users/UserIndex.vue'
import ReportsIndex from './components/Reports/ReportsIndex.vue'
import RolesIndex from './components/Roles/RolesIndex.vue'
import PatientIndex from './components/Patients/PatientIndex.vue'

/****************Ruteo *************************************** */
/** no olvidar registrar el componente abajo del todo en VUE */
Vue.use(VueRouter);

const routes = [
  { path: '/', component: Home },
  { path: '/app', component: Home },
  { path: '/app/login', component: Login},
  //{ path: '/app/paciente', component: PatientIndex},
  { path: '/app/config', component: Config},
  { path: '/app/usuario', component: UserIndex},
  { path: '/app/reportes', component: ReportsIndex },
  { path: '/app/roles', component: RolesIndex },
  { path: '/app/paciente', component: PatientIndex},
  { path: '*', redirect: '/' }
]

const router = new VueRouter({
  mode: "history",
  routes
})

/**************************************************************** */

/*todo lo que se ponga aca va a estar/hacerse en todos los componentes del sistema*/
Vue.mixin({

    computed: {
        config : {
            set: function (info) {
              var datos = info;
              var resp = {};
              for (var i = 0; i < datos.length; i++) { //queda algo del tipo { titulo: "Hospital...", email: email@gmail.com...}
                 resp[datos[i].variable] = datos[i].valor;
              }
              this.$root.$data.store_config = resp;
              events.$emit('loading_config:finish');
            },
            get: function () {
                return this.$root.$data.store_config;
            }
          },

          jwtToken : {
            set: function(token) {
              this.$root.$data.store_token = token;
              localStorage.setItem('token',token);
              axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
            },
            get: function (){
              return this.$root.$data.store_token;
            },
            clear: function(){
              this.$root.$data.store_token = '';
            }
          },

          loggedUser : {
            set: function(data){
              this.$root.$data.store_user['id'] = data.id;
              this.$root.$data.store_user['email'] = data.email;
              this.$root.$data.store_user['username'] = data.username;
              this.$root.$data.store_user['roles'] = [];
              this.$root.$data.store_user['permisos'] = [];
              data.roles.forEach(r => {
                this.$root.$data.store_user['roles'].push(r.nombre);
                r.permisos.forEach(p => {
                  this.$root.$data.store_user['permisos'].push(p.nombre); //despues ve de eliminar los repetidos
                });
              });
              this.$root.$data.store_user['created_at'] = data.created_at;
              this.$root.$data.store_user['updated_at'] = data.updated_at;
              this.$root.$data.store_user['first_name'] = data.first_name;
              this.$root.$data.store_user['last_name'] = data.last_name;
              this.$root.$data.store_user['activo'] = data.activo;
              events.$emit('loading_user:finish');
            },
            get: function(){
              return this.$root.$data.store_user;
            },
            clear: function(){
              this.$root.$data.store_user = {};
            }
          },
    },

    methods: {

      //para utilizar este metodo en su componente se pone this.makeCorsRequest('https://api-referencias.proyecto2018.linti.unlp.edu.ar/tipo-documento').then((respuesta) => { console.log(respuesta)})
      async makeCorsRequest(url){
        var info = '';
        delete axios.defaults.headers.common["Authorization"];
        await axios.get(url).then( (response) => {
          axios.defaults.headers.common["Authorization"] = 'Bearer ' + this.jwtToken;
          info = response.data;
        }).catch((error) => {
          axios.defaults.headers.common["Authorization"] = 'Bearer ' + this.jwtToken;
          info = error;
         });
         return info;
      },


    }
})

new Vue({
  el: '#app',

  data: {store_config: '',
         store_token: '',
         store_user: {},
  },
  router,

  created(){
    events.$emit('loading:start');

    axios.interceptors.response.use((response) => {  //metodo para redirigir a login cuando la sesion expira, siempre y cuando no este en el login
      return response;
    }, (error) => {this.expireJWTcheck(error)} );

    this.fetchPageConfig();
    this.jwtToken = localStorage.getItem('token') ? localStorage.getItem('token') : '';
    if(!(this.store_token === '')){
      this.fetchLoggedUser();
    }

  },

  methods: {
    async fetchPageConfig(){
      await axios.get('http://localhost:8000/configuracion/').then((response) => {
        this.config = response.data;
        if(this.config.estado === "deshabilitado"){
          events.$emit('mantenimiento:active')
        }
      })
    },

    async fetchLoggedUser(){
      await axios.get('http://localhost:8000/api/session').then((response) => {
        this.loggedUser = response.data
      }).catch((error) => { });
    },

    expireJWTcheck(error) {
      if (window.location.pathname !='/app/login' && 401 === error.response.status) {
        Vue.swal({
              title: "La sesión expiró",
              text:  "Su sesión ha expirado. Será redirigido a la página de login",
              type:  "warning",
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "Ok",
          }).then( () => {
              localStorage.removeItem('token');
              this.store_token = '';
              axios.defaults.headers.common['Authorization'] = null;
              window.location = 'app/login';
          });
      } else {
          if( 400 === error.response.status ||
              403 === error.response.status ||
              404 === error.response.status ||
              500 === error.response.status){
                if(error.response.data.length > 300){
                  events.$emit('alert:error', "Se produjo una violacion en los tipos de parametros");
                }else{
                  events.$emit('alert:error', error.response.data);
                }
          }
          return Promise.reject(error);
      }
    },
  },

watch: {
  $route(to,from){
    events.$emit('loading:start');
    this.fetchPageConfig();
    if(!(this.store_token === '')){
      this.fetchLoggedUser();
    }
  }
},
  components: { Home, Futer, Barra, Alertas, Config, Closepage, UserIndex, ReportsIndex, RolesIndex, PatientIndex }
});
