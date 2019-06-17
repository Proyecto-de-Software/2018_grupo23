<template>
    <div class = "container margin-global">
    <section class = "section">
      <div class="columns is-centered">
        <div class="column is-two-fifths box">
          <div class="field">
            <label class="label" for="name">Usuario:</label>
            <div class="control">
              <input class="input" id="name" type="text" name="nombre de usuario" v-model="name" @keyup.enter="login" v-validate="'required'">
              <span v-show="errors.has('nombre de usuario')" class="help is-danger">{{ errors.first('nombre de usuario') }}</span>
            </div>
          </div>
          <div class="field">
            <label class="label" for="password">Contraseña:</label>
            <div class="control">
              <input class="input" id="password" type="password" name="password" v-model="pass" @keyup.enter="login" v-validate="'required'">
              <span v-show="errors.has('password')" class="help is-danger">{{ errors.first('password') }}</span>
            </div>
          </div>
          <div class="field is-grouped is-grouped-centered">
            <div class="control">
              <button class="button is-info" type="submit" @click="login">Ingresar</button>
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
      .post(this.burl('/api/login_check'),credentials) //mando el post
      .then((response) => {
        if (response.status === 200) {
          this.jwtToken = response.data['token']; //seteo el token
          this.$router.push('/'); // con esto me cambio de vista
        }
      })
      .catch((error) => {
         events.$emit('alert:error','Usuario o contraseña incorrecta');//emito el error
      })
    }
  },
}
</script>
