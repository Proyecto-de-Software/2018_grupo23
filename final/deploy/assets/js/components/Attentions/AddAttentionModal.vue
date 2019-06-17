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
                  <label class="label">Fecha de Atención*</label>
                  <div class="control">
                    <input id="fecha" type="date" class="input"
                    :value="attentionForm.fecha && attentionForm.fecha.toISOString().split('T')[0]"
                     @input="attentionForm.fecha = $event.target.valueAsDate">
                  </div>
                </div>
                <div class="field">
                  <label class="label">Motivo*</label>
                  <div class="control">
                    <div class="select">
                      <select name="motivo" v-model="attentionForm.motivo" v-validate="'required'">
                        <option v-for="motivo in motivos" :value="motivo.id" :selected="attentionForm.motivo == motivo.id">
                          {{ motivo.nombre }}
                        </option>
                      </select>
                    </div>
                    <span v-show="errors.has('motivo')" class="help is-danger">{{ errors.first('motivo') }}</span>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Acompañamiento</label>
                  <div class="control">
                    <div class="select">
                      <select v-model="attentionForm.acompanamiento">
                        <option v-for="acompanamiento in acompanamientos" :value="acompanamiento.id" :selected="attentionForm.acompanamiento == acompanamiento.id">
                          {{ acompanamiento.nombre }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Derivación</label>
                  <div class="control">
                    <div class="select">
                      <select v-model="attentionForm.derivacion">
                        <option v-for="institucion in instituciones" :value="institucion.id" :selected="attentionForm.derivacion == institucion.id">
                          {{ institucion.nombre }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Internación*</label>
                  <div class="control">
                    <div class="select">
                      <select name="internación" v-model="attentionForm.internacion" v-validate="'required'">
                        <option id="Sí" :value="true" v-model="attentionForm.internacion" :checked="attentionForm.internacion == true">Sí</option>
                        <option id="No" :value="false" v-model="attentionForm.internacion" :checked="attentionForm.internacion == false">No</option>
                      </select>
                    </div>
                    <span v-show="errors.has('internación')" class="help is-danger">{{ errors.first('internación') }}</span>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Diagnóstico*</label>
                  <div class="control">
                    <input name="diagnóstico" type="text" class="input" v-model="attentionForm.diagnostico" v-validate="'required'" >
                  </div>
                  <span v-show="errors.has('diagnóstico')" class="help is-danger">{{ errors.first('diagnóstico') }}</span>
                </div>
                <div class="field">
                  <label class="label">Observaciones generales</label>
                  <div class="control">
                    <input type="text" class="input" v-model="attentionForm.observaciones">
                  </div>
                </div>
                <div class="field">
                  <label class="label">Articulación con otras Instituciones</label>
                  <div class="control">
                    <input type="text" class="input" v-model="attentionForm.articulacion">
                  </div>
                </div>
                <div class="field">
                  <label class="label">Tratamiento Farmacológico</label>
                  <div class="control">
                    <div class="select">
                      <select v-model="attentionForm.tratamiento">
                        <option v-for="tratamiento in tratamientos" :value="tratamiento.id" :selected="attentionForm.tratamiento == tratamiento.id">
                          {{ tratamiento.nombre }}
                        </option>
                      </select>
                    </div>
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
    modalTitle: String,
    loadAttentions: Function,
    attention: Object,
    title: String,
    acompanamientos: Array,
    instituciones: Array,
    motivos: Array,
    tratamientos: Array,
    idPaciente: Number,

  },
  data() {
    return {
      isLoading: false,
      isReadOnly: false,
      attentionForm: {
        acompanamiento: '',
        articulacion: '',
        derivacion: '',
        diagnostico: '',
        fecha: '',
        internacion: '',
        motivo: '',
        observaciones: '',
        tratamiento: '',
      }
    }
  },
  created() {
    if (this.attention != null) { //estoy en edición
      this.attentionForm.fecha = new Date(this.attention.fecha)
      this.attentionForm.motivo= this.attention.motivo.id
      this.attentionForm.acompanamiento = this.attention.acompanamiento.id
      this.attentionForm.articulacion= this.attention.articulacion_con_insituciones
      this.attentionForm.derivacion= this.attention.derivacion.id
      this.attentionForm.internacion= this.attention.internacion
      this.attentionForm.diagnostico= this.attention.diagnostico
      this.attentionForm.observaciones= this.attention.observaciones
      this.attentionForm.tratamiento=this.attention.tratamiento_farmacologico.id
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
          this.attentionForm.internacion = this.attentionForm.internacion == true ? 1 : 0
          if (this.attention) { //edit
            axios
            .post(this.burl('/consulta/' + this.attention.id + '/edit'), this.attentionForm)
            .then(response => {
                if (response.status == 200) Vue.swal('La atención fue editada', '', 'success')
            })
          } else { //new
            axios
            .post(this.burl('/consulta/new/' + this.idPaciente), this.attentionForm)
            .then(response => {
                if (response.status == 200) Vue.swal('La atención fue agregada', '', 'success')
            })
          }
          this.loadAttentions()
          this.close()
        }
      })
    }
  }
}
</script>
