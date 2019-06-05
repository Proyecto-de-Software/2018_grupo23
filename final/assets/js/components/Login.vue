<template>
    <div class = "container">
    <section class = "section is-hidden-touch">
    </section>

    <section class = "section">
    <div class="columns is-centered">
    <div class="column is-two-fifths box">
    
    <div class="field">
      <label class="label" for="name">Usuario:</label>
      <div class="control">
      <input class="input" id="name" type="text" name="user_name" v-model="name" required>
      </div>
    </div>
    <div class="field">
      <label class="label" for="password">Contraseña:</label>
      <div class="control">
      <input class="input" id="password" type="password" name="password" v-model="pass" required>
      </div>
    </div>
    <div class="field is-grouped is-grouped-centered">
        <div class="control">
      <a class="button is-info" type="submit" v-on:click="login">Ingresar</a>
      </div>
    </div>
    
    </div>
    </div>
    </section>
      <section class = "section is-hidden-touch">
    </section>

    </div>
</template>

<script>

export default {
  data() {
    return {
      name: '',
      pass: '',
    }
  },

  methods: {

    login(){
        var credentials = {
            _username : this.name,
            _password : this.pass
        };
        axios
      .post(this.url('/api/login_check'),credentials) //mando el post
      .then((response) => {
        if(response.status === 200){
          this.jwtToken = response.data['token']; //seteo el token
          this.$router.push('/'); // con esto me cambio de vista
        }
        })
      .catch((error) => { 
         events.$emit('alert:error','Usuario o cotraseña incorrecta');//emito el error
        })
    }
  },
}
</script>
