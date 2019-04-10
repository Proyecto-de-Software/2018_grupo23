<template>
  <div>
    <section class="section is-hidden-touch">
    </section>
    <section class="section">
      <div class = "columns">
        <div class="column">
          <article>
            <div class="content box">
              <h2 class="h2" >{{ colOneTitle }}</h2>
              {{ colOneBody }}
            </div>
          </article>
        </div>
          <div class="column">
            <article>
              <div class="content box">
                <h2 class="h2">{{ colTwoTitle }}</h2>
                {{ colTwoBody }}
              </div>
            </article>
          </div>
          <div class="column">
            <article>
              <div class="content box">
                <h2 class="h2">{{ colThreeTitle }}</h2>
                {{ colThreeBody }}
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
import {  mapGetters } from 'vuex'
export default {
  name: 'Home',
  computed: mapGetters([
              'config'
            ]),
            data: function () { //de esta forma cada uso de la variable es independiente, si no se pone funcion es compartido entre todoos
              return {
                colOneTitle: this.$store.getters.config['titulo_col_uno'],
                colTwoTitle: this.$store.getters.config['titulo_col_dos'],
                colThreeTitle: this.$store.getters.config['titulo_col_tres'],
                colOneBody: this.$store.getters.config['columna_uno'],
                colTwoBody: this.$store.getters.config['columna_dos'],
                colThreeBody: this.$store.getters.config['columna_tres'],
              }
            }
    }
</script>
