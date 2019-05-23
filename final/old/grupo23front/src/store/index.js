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

const store = new Vuex.Store(
  {
      modules: {
        UserModule
      },
      store: {
        plugins: [VuexPer.plugin]
      }
  }
);

export default store;
