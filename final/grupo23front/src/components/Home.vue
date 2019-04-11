<template>
  <div v-if="!cargando">
    <section class="section is-hidden-touch">
    </section>
    <section class="section">
      <div class = "columns">
        <div class="column">
          <article>
            <div class="content box">
              <h2 class="h2" >{{config.titulo_col_uno}}</h2>
              {{ config.columna_uno }}
            </div>
          </article>
        </div>
          <div class="column">
            <article>
              <div class="content box">
                <h2 class="h2">{{ config.titulo_col_dos }}</h2>
                {{ config.columna_dos }}
              </div>
            </article>
          </div>
          <div class="column">
            <article>
              <div class="content box">
                <h2 class="h2">{{ config.titulo_col_tres }}</h2>
                {{ config.columna_tres }}
              </div>
            </article>
          </div>
      </div>
      <section class="section is-hidden-touch">
      </section>
    </section>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'Home',

    computed : {
          cargando: function(){
      return this.$root.bloqueo
          },
          config: function(){
            return this.$root.config
          }},

}


</script>
