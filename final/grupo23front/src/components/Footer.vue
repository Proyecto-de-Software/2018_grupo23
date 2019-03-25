<template>
  <div>
    <footer class="footer">
      <div class="content has-text-centered">
          <p class="level-item"><strong></strong></p>
          <p class="level-item">Contacto: </p>
      </div>
    </footer>
  </div>
</template>

<script>
  import {  mapGetters } from 'vuex'
  export default {
    name: 'Footer',
    // computed: mapGetters([
    //             'title'
    //           ])
  }
</script>


<!--Si ponés scoped el alcance del estilo es acotado a este componente nomás-->
<style scoped>
  .footer {
    background-color: #E0F0EE
  }
</style>
