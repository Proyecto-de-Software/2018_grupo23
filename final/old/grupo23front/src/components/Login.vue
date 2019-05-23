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
      <input class="input" id="name" type="text" name="user_name" v-model="email" required>
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
import { mapGetters, mapActions } from 'vuex';

export default {
    props: {
    email: String,
    pass: String,
  },
  computed: {
    ...mapGetters([
      'jwt',
    ])
  },

  methods: {
    ...mapActions([
      `fetchJWT`,
      `setNewJWT`,
    ]),

    // The implementation here doesn't change at all!
    async doSomethingWithJWT() {
      const res = await fetch(`http://localhost/vuejs-jwt-example/do-something`, {
        method: 'POST',
        headers: new Headers({
          Authorization: `Bearer: ${this.jwt}`
        })
      });
      // Do stuff with res here.
    },
    loginViejo(){
          this.fetchJWT({
      // #Security...
      usern: this.email,
      passw: this.pass
    });
    },
    login(){
        var config = {
            username : this.email,
            password : this.pass
        };
        axios
      .post('http://localhost:8000/login_check',config) //mando el post
      .then((response) => {
        if(response.status === 200){
          this.setNewJWT(response.data['token']); //seteo el token en el modulo vuex user (UserModule.js)
          this.$router.push('/'); // con esto me cambio de vista
        }
        })
      .catch((error) => { 
        console.log("Login malio sal") //aca tengo q avisar q mando cualquiera
        })
    }
  },

  mounted() {
  }
}
</script>
