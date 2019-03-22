<template>
    <div class="container">
        <a v-on:click="showAlert" class="waves-effect waves-light btn-small">No me toques</a>
        <a class="button"><i class="material-icons left">cloud</i>{{texto}}</a>
        <a class="waves-effect waves-light btn-small"><i class="material-icons right">cloud</i>{{texto_boton}}</a>

        <a v-on:click="axiosRequestSwal" class="btn-small">Tocame para probar un request</a>
    </div>

</template>

<script>
export default {
  name: 'BotoncitosComponent',

  props: {
    texto: String,
    texto_boton: String
  },

  mounted() {

  },

  methods: {
        showAlert(){
            // this.$swal es para crear modales y ventanas
            //https://sweetalert2.github.io/
            this.$swal('Te dije que no me toques!');
        },
        axiosRequestSwal(){

            this.$swal({
                title: 'are u sure?',
                text: "texto",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ok'
                }).then((result) => {
                    if (result.value) { //si se apretó OK se ejecuta esto

                        axios
                            .get('http://localhost:8000/genero/1') //pido el get
                            .then(response => (console.log( JSON.parse(response.data)))) //y aca viene la respuesta
                    }
                    this.$swal('Mirá la consola')

                });
        }
    }
}
</script>
