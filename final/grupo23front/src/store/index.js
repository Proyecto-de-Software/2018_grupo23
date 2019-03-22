import Vue from 'vue'
import Vuex from 'vuex'
import VuexPersist from 'vuex-persist'
import axios from 'axios'
import VueAxios from 'vue-axios'
import UserModule from './modules/UserModule'

Vue.use(Vuex)
Vue.use(VueAxios, axios)

const VuexPer = new VuexPersist({
  key: 'grupo23',
  storage: localStorage
})

//VueX  te permite crear datos que puedan ser conocidos por cualquier componente, algo así.
//Calculo que es para que no tengas que estar pasando paràmetros entre componentes todo el tiempo.
//Se me ocurrió usarlo para la config de la app. Se usa Vuex en combinación con axios.

export default new Vuex.Store(
    {
      modules: {
        UserModule
      },
        state: {
            config: null
        },
        actions: {
          loadConfig({ commit }) {
            axios
              .get('http://localhost:8000/configuracion')
              .then(r => r.data)
              .then(config => {commit('SET_CONFIG', config)})
          },
        },
        mutations: {
          SET_CONFIG (state, config) {
            state.config = config
          }
        },

        store: {
          plugins: [VuexPer.plugin]
        }
    }
);


