<template>
  <div>
    <footer class="footer">
      <div class="content has-text-centered">
        <p class="level-item"><strong>Hospital Dr. Alejandro Korn</strong></p>
        <p class="level-item">{{ config }}</p>
        <p class="level-item">v 2.0</p>
        <p class="level-item">Contacto: alejandrito@msn.com</p>
      </div>
    </footer>
  </div>
</template>

<script>
  import { mapState } from 'vuex'
  export default {
    name: 'Footer',
    mounted() { //esto se ejecuta cuando se carga este componente, y lo que hace es ejecutar la 'action' loadConfig del vueXStore
      this.$store.dispatch('loadConfig')
    },
    computed: mapState([ //acá se hace esto para que este componente conozca a la variable config que está en el VueX
      'config'
    ])
  }
</script>


<!--Si ponés scoped el alcance del estilo es acotado a este componente nomás-->
<style scoped>
  .footer {
    background-color: #E0F0EE
  }
</style>
