<template>
  <div class="container margin-global">
    <div v-if="isLoading" class="has-text-centered">
      <a class="button is-loading page-loading-button"></a>
    </div>
    <div v-else>
      <div class="box">
        <h4 class="title is-4">Configuración general del sistema</h4>
        <div class="columns">
            <div class="column is-6">
              <div class="field">
                  <label class="label" for="titulo">* Título:</label>
                  <div class="control">
                    <input class="input" id="titulo" type="text" name="titulo" v-model="config.titulo" required>
                  </div>
              </div>
              <div class="field">
                <label class="label" for="email">* Mail de contacto:</label>
                <div class="control">
                  <input class="input" id="email" type="email" name="email" value="" v-model="config.email" placeholder="e-mail" required>
                </div>
              </div>
              <div class="field">
                <label class="label" for="descripcion">* Descripción:</label>
                <div class="control">
                  <textarea class="textarea" id="descripcion" name="descripcion" value="" v-model="config.descripcion" rows="4" required></textarea>
                </div>
              </div>
              <p class="help">(*) Se mostrarán en el pie de página</p>
            </div>
            <div class="column is-6">
              <div class="field">
                <label class="label">Cantidad de elementos por página en los listados</label>
                  <div class="control">
                    <div class="select">
                      <select name="paginado" v-model="config.paginado">
                        <option>5</option>
                        <option>10</option>
                        <option>15</option>
                        <option>20</option>
                        <option>30</option>
                      </select>
                    </div>
                      <p class="help">Define cuantos usuarios, pacientes y atenciones se mostrarán en sus respectivos listados</p>
                  </div>
              </div>
              <div class="field">
                <label class="label">Estado del sitio</label>
                <div class="control">
                  <div class="select">
                    <select name="estado" v-model="config.estado">
                      <option>habilitado</option>
                      <option>deshabilitado</option>
                    </select>
                  </div>
                  <p class="help">Si se encuentra deshabilitado lo podrán ver solamente el/los administradores del sistema</p>
                </div>
              </div>
             </div>
        </div>
      </div>
      <div class="box">
        <h4 class="title is-4">Contenido de la página principal</h4>
        <div class="columns">
          <div class="column">
              <div class="field">
                <label class="label" for="email">Título</label>
                <div class="control">
                  <input class="input" name="col1_title" v-model="config.titulo_col_uno">
                </div>
              </div>
              <div class="field">
                <label class="label" for="descripcion">Cuerpo</label>
                <div class="control">
                  <textarea class="textarea" name="col1_text" v-model="config.columna_uno" rows="12"></textarea>
                </div>
              </div>
          </div>
          <div class="column">
            <div class="field">
              <label class="label" for="email">Título</label>
              <div class="control">
                <input class="input" name="col2_title" v-model="config.titulo_col_dos" value="">
              </div>
            </div>
            <div class="field">
              <label class="label" for="descripcion">Cuerpo</label>
              <div class="control">
                <textarea class="textarea" name="col2_text" v-model="config.columna_dos" rows="12"></textarea>
              </div>
            </div>
          </div>
          <div class="column">
            <div class="field">
              <label class="label" for="email">Título</label>
              <div class="control">
                <input class="input" name="col3_title" v-model="config.titulo_col_tres">
              </div>
            </div>
            <div class="field">
              <label class="label" for="descripcion">Cuerpo</label>
              <div class="control">
                <textarea class="textarea" name="col3_text" v-model="config.columna_tres" rows="12"></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="field is-grouped is-grouped-centered">
        <button v-if="loggedUser.permisos.includes('configuracion_update')" type="submit" class="button is-success button-is-spaced" @click="submit">Guardar cambios</button>
        <button type="button" class="button is-light button-is-spaced" @click="cancel">Cancelar</button>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        isLoading: true
      }
    },
    methods: {
      submit() {
        var configJSON = JSON.stringify(this.config);
        axios
         .post(this.burl('/configuracion/new'), configJSON)
         .then((response) => {
                  if(this.config.estado === 'habilitado'){
                    events.$emit('mantenimiento:inactive');
                  }
                  this.$router.replace('/');
         })
      },
      cancel() {
        this.$router.replace('/');
      }
    },
    mounted() {
      events.$on('loading_user:finish', () => this.isLoading = false)
    }
  }
</script>
