<template>
  <div>
    <footer class="footer">
      <div class="content has-text-centered">
          <p class="level-item"><strong>{{ title }}</strong></p>
          <p class="level-item">{{ description }}</p>
          <p class="level-item">Contacto: {{ email }}</p>
          <p class="level-item">v 2.0</p>
      </div>
    </footer>
  </div>
</template>

<script>
  import {  mapGetters } from 'vuex'
  export default {
    name: 'Footer',
    computed: mapGetters([
                'config'
              ]),
              data: function() {
                return {
                  title: this.$store.getters.config.titulo,
                  description: this.$store.getters.config.descripcion,
                  email: this.$store.getters.config.email
                }
              }
  }
</script>


<!--Si ponés scoped el alcance del estilo es acotado a este componente nomás-->
<style scoped>
  .footer {
    background-color: #E0F0EE
  }
</style>
