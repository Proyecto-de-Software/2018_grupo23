<template>
    <div class="section"  v-if="!(error === '') || !(success === '') || js_alert">

        <div id="error_noti" class=" notification is-danger" v-if="!(error === '')">
            <button id="close_error" class="delete" title="Cerrar alerta" v-on:click="close_error()"></button>
            <p>{{ error }}</p>
        </div>
    
    
        <div id="success_noti" class="container notification is-success" v-if="!(success === '')">
            <button id="close_success" class="delete" title="Cerrar alerta" v-on:click="close_success()"></button>
            <p>{{ success }}</p>
        </div>
        

        <div id="js_alert" class="container notification is-danger" v-if="js_alert">
            <button id="close_alert" class="delete" v-on:click="close_js()">Cerrar</button>
            <p>El sitio está teniendo problemas para acceder a cierto contenido. Algunas funciones podrían no estar disponibles.</p>
        </div>

    </div>

</template>

<script>
  export default {
    data() {
        return {
          error: '',
          success: '',
          js_alert: false,
        }
    },
    created(){
        events.$on('alert:error',(msj) => this.error = msj);
        events.$on('alert:success',(msj) => this.success = msj);
        events.$on('alert:js',() => this.js_alert = true);
    },

    methods: {
        close_error(){
            this.error = '';
        },

        close_success(){
            this.success = '';
        },

        close_js(){
            this.js_alert = false;
        }
    }

  }
</script>
