export default {
    state: {
      currentJWT: localStorage.getItem('token') ? localStorage.getItem('token') : ''
    },
  
    getters: {
      jwt: state => state.currentJWT,
      jwtData: (state, getters) => state.currentJWT ? JSON.parse(atob(getters.jwt.split('.')[1])) : null,
      jwtSubject: (state, getters) => getters.jwtData ? getters.jwtData.sub : null,
      jwtIssuer: (state, getters) => getters.jwtData ? getters.jwtData.iss : null
    },
  
    mutations: {
      setJWT(state, jwt) {
        // When this updates, the getters and anything bound to them updates as well.
        state.currentJWT = jwt;
      }
    },
  
    actions: {
      async fetchJWT ({ commit }, { usern, passw }) { //hago el pedido de logeo a la api y actualizo el token
        // Perform the HTTP request.
        const config = {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
              "username" : usern,
              "password" : passw
            })
        }
        const res = await fetch(`http://localhost:8000/login_check`,config);
        //console.log(config);
        // Calls the mutation defined to update the state's JWT.
        commit('setJWT', JSON.parse(await res.text())['token']);
      },
      setNewJWT: function({commit}, payload){
        commit('setJWT', payload);
        localStorage.setItem('token',payload); //lo guardo en la pc
      }
    }
  }