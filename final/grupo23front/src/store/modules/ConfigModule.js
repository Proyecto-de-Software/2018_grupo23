export default {
  state: {
    config: {},
    success: ''
  },
  getters: {
    config:state => {
      return state.config
    }
  },
  mutations: {
    setConfig: (state, config) => {
      for (var i = 0; i < config.length; i++) { //queda algo del tipo { titulo: "Hospital...", email: email@gmail.com...}
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
      var configJSON = JSON.stringify(state.config);
      axios.post('http://localhost:8000/configuracion/new', configJSON).then((response) => {
        alert(response.data)
      })
    }
  }
}
