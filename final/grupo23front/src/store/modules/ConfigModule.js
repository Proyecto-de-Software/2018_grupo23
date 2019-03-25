export default {
  state: {
    config: {}
  },
  getters: {
    config:state => {
      return state.config
    }
  },
  mutations: {
    setConfig: (state, config) => {
      for (var i = 0; i < config.length; i++) {
         state.config[config[i].variable] = config[i].valor;
      }
    }
  },
  actions: {
    loadConfig({ commit }) {
      axios.get('http://localhost:8000/configuracion').then((response) => {
        commit('setConfig', JSON.parse(response.data))//haciendo el parse me queda un array de objetos y se lo paso a la mutation setConfig
      })
    },
    saveConfig({ state }, config) {
      axios.post('http://localhost:8000/configuracion/new', state.config);
    }
  }
}
