<template>
  <div>
    <footer class="footer">
      <div class="content has-text-centered">
          <p class="level-item"><strong>{{ config.titulo }}</strong></p>
          <p class="level-item">{{ config.descripcion }}</p>
          <p class="level-item">Contacto: {{ config.email }}</p>
          <p class="level-item">v 3.0</p>
      </div>
    </footer>
  </div>
</template>

<script>
  export default {

  }
</script>


<!--Si ponés scoped el alcance del estilo es acotado a este componente nomás-->
<style scoped>
  .footer {
    background-color: #E0F0EE
  }
</style>
