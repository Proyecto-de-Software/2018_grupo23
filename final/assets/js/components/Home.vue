<template>
  <div>
    <section class="section is-hidden-touch">
    </section>
    <section class="section">
      <div v-if="isLoading" class="has-text-centered">
        <a class="button is-loading page-loading-button "></a>
      </div>
      <div v-else>
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
      </div>
      <section class="section is-hidden-touch">
      </section>
    </section>
  </div>
</template>

<script>

export default {
  data() {
    return {
      isLoading: true
    }
  },
  mounted() {
    events.$on('loading_config:finish', () => this.isLoading = false)
  },
}

</script>
