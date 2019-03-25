export default {
  state: {
    config: []
  },
  getters: {
    config:state => {
      return state.config
    }
  },
  mutations: {
    setConfig: (state, config) => {
      state.config = config;
    },
  },
  actions: {
    loadConfig({ commit }) {
      axios.get('http://localhost:8000/configuracion').then((response) => {
        commit('setConfig', JSON.parse(response.data))//haciendo el parse me queda un array de objetos y se lo paso a la mutation setConfig
      })
    },
    saveConfig({ commit }, config) {
      console.log(config)// acá con axios hay que hacer el POSTRequest a la api
    },
  }
}
