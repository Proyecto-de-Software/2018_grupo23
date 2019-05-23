<template>
  <div>
    <footer class="footer">
      <div class="content has-text-centered" v-if="!cargando">
          <p class="level-item"><strong>{{ config.titulo }}</strong></p>
          <p class="level-item">{{ config.descripcion }}</p>
          <p class="level-item">Contacto: {{ config.email }}</p>
          <p class="level-item">v 2.0</p>
      </div>
    </footer>
  </div>
</template>

<script>
  export default {
    name: 'Footer',
        computed : {
          cargando: function(){
      return this.$root.bloqueo
          },
          config: function(){
            return this.$root.config
          }},
  }
</script>


<!--Si ponés scoped el alcance del estilo es acotado a este componente nomás-->
<style scoped>
  .footer {
    background-color: #E0F0EE
  }
</style>
