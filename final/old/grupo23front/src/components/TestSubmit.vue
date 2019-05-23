<template>
    <div class="container">
        <h5>Llená el form</h5>
        <input v-model="nom" placeholder="Como te llamas?">
        <input v-model="ape" placeholder="Como te apellidas?">
        <input v-model="email" placeholder="Como es tu email?">
        <input v-model="pass" placeholder="Pon tu contrasenia">
        <input v-model="user" placeholder="Ahora si pon tu nombre de usuario.">
        <a v-on:click="ventana" class="btn-small">Tocá acá</a>
    </div>
</template>

<script>
export default {
  name: 'TestSubmit',

  props: {
    nom: String,
    ape: String,
    email: String,
    pass: String,
    user: String
  },

  mounted() {

  },

  methods: {
        ventana(){

            this.$swal({
                title: 'Estas por submitear',
                text: "Estas seguro?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar'
                }).then((result) => {
                    if (result.value) { //si apretó en OK se ejecuta esto

                        axios
                            .post('http://localhost:8000/usuario/new',
                            {
                                email: this.email,
                                username: this.user,
                                firstname: this.nom,
                                lastname: this.ape,
                                //password: this.pass
                            }) //mando el post
                            .then(response => (console.log( JSON.parse(response.data)))) //y aca viene la respuesta
                    }
                    this.$swal('Mira la consola')

                });
        }
    }
}
</script>
