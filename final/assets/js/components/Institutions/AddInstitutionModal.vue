<template>
  <div class="modal is-active">
    <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <h3 class="title">{{ title }}</h3>
        </header>
          <section class="modal-card-body">
            <div v-if="isLoading">
              <h1 class="title">Cargando...</h1>
            </div>
            <div v-else>
              <form class="form">
                <div class="field">
                  <label class="label">Nombre*</label>
                  <div class="control">
                    <input id="apellido" name="nombre" type="text" class="input" v-model="institutionsForm.nombre" v-validate="'required|alpha_spaces'">
                    <span v-show="errors.has('nombre')" class="help is-danger">{{ errors.first('nombre') }}</span>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Director*</label>
                  <div class="control">
                    <input id="nombre" name="director" type="text" class="input" v-model="institutionsForm.director" v-validate="'required|alpha_spaces'">
                    <span v-show="errors.has('director')" class="help is-danger">{{ errors.first('director') }}</span>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Telefono*</label>
                  <div class="control">
                    <input id="nombre" name="telefono" type="number" class="input" v-model="institutionsForm.telefono" >
                    <span v-show="errors.has('telefono')" class="help is-danger">{{ errors.first('telefono') }}</span>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Region sanitaria*</label>
                  <div class="control">
                    <div class="select">
                      <select v-model="institutionsForm.regionSanitariaId">
                        <option v-for="region in regionesSanitarias" :value="region.id" :selected="institutionsForm.regionSanitariaId == region.id">
                          {{ region.nombre }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Tipo de Institución*</label>
                  <div class="control">
                    <div class="select">
                      <select v-model="institutionsForm.tipoInstitucionId">
                        <option v-for="tipo in tiposInstitucion" :value="tipo.id" :selected="institutionsForm.tipoInstitucionId == tipo.id">
                          {{ tipo.nombre }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Coordenadas*</label>
                  <div class="control">
                    <input type="text" name="coordenadas" class="input" v-model="institutionsForm.coordenadas" v-validate="'required'">
                    <span v-show="errors.has('coordenadas')" class="help is-danger">{{ errors.first('coordenadas') }}</span>
                  </div>
                </div>

                <p>* campos obligatorios</p>
              </form>
            </div>
          </section>
        <footer class="modal-card-foot">
          <button type="button" class="button is-success" @click="submit">Aceptar</button>
          <button type="button" class="button is-text" @click="close">Cancelar</button>
        </footer>
        <button class="modal-close" @click="close"></button>
      </div>
    </div>
</template>

<script>
import Vue from 'vue'
export default {
  props: {
    loadInstitutions: Function,
    institution: Object,
    title: String,
    regionesSanitarias: Array,
    tiposInstitucion: Array,
  },
  data() {
    return {
      isLoading: false,
      isReadOnly: false,
      institutionsForm: {
        nombre: '',
        director: '',
        telefono: '',
        regionSanitariaId: '',
        tipoInstitucionId: '',
        coordenadas: '',

      }
    }
  },
  created() {
    if (this.institution != null) { //estoy en edición
      this.institutionsForm.nombre = this.institution.nombre
      this.institutionsForm.director = this.institution.director
      this.institutionsForm.telefono = this.institution.telefono
      this.institutionsForm.regionSanitariaId = this.institution.region_sanitaria_id
      this.institutionsForm.tipoInstitucionId = this.institution.tipo_institucion.id
    }
  },
  methods: {
    close() {
      this.$destroy();
      this.$el.parentNode.removeChild(this.$el);
    },
    submit() {
      this.$validator.validate()
      .then(valid => {
        if (!valid) {
          Vue.swal('El formulario no cumple con lo solicitado', '', 'error')
        } else {
          if (this.institution) { //edit
            axios
            .post(this.burl('/institucion/' + this.institution.id + '/edit'), this.institutionsForm)
            .then(response => { if (response.status == 200) Vue.swal('La institución fue actualizada', '', 'success') })
          } else { //new
            axios
            .post(this.burl('/institucion/new'), this.institutionsForm)
            .then(response =>  { if (response.status == 200) Vue.swal('La institución fue creada', '', 'success') })
          }
          this.loadInstitutions()
          this.close()
        }
      })
    },
  }
}
</script>
