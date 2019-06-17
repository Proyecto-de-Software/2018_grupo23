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
                  <label class="label">Apellido*</label>
                  <div class="control">
                    <input id="apellido" name="apellido" type="text" class="input" v-model="patientForm.apellido" v-validate="'required|alpha_spaces'">
                    <span v-show="errors.has('apellido')" class="help is-danger">{{ errors.first('apellido') }}</span>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Nombre*</label>
                  <div class="control">
                    <input id="nombre" name="nombre" type="text" class="input" v-model="patientForm.nombre" v-validate="'required|alpha_spaces'">
                    <span v-show="errors.has('nombre')" class="help is-danger">{{ errors.first('nombre') }}</span>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Fecha de Nacimiento*</label>
                  <div class="control">
                    <input type="date" name="fecha de nacimiento" class="input"
                     :value="patientForm.fechaNac && patientForm.fechaNac.toISOString().split('T')[0]"
                     @input="patientForm.fechaNac = $event.target.valueAsDate">
                  </div>
                  <!-- <span v-show="errors.has('fecha de nacimiento')" class="help is-danger">{{ errors.first('fecha de nacimiento') }}</span> -->
                </div>
                <div class="field">
                  <label class="label">Lugar de nacimiento</label>
                  <div class="control">
                    <input type="text" class="input" v-model="patientForm.lugarNac">
                  </div>
                </div>
                <div class="field">
                  <label class="label">Partido</label>
                  <div class="control">
                    <div class="select">
                      <select v-model="patientForm.partidoId" @change="onPartidoSelect($event)">
                        <option v-for="partido in partidos" :value="partido.id" :selected="patientForm.partidoId == partido.id">
                          {{ partido.nombre }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Region sanitaria</label>
                  <div class="control">
                    <div class="select">
                      <select v-model="patientForm.regionSanitariaId">
                        <option v-for="region in regionesSanitarias" :value="region.id" :selected="patientForm.regionSanitariaId == region.id" :disabled="true">
                          {{ region.nombre }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Localidad</label>
                  <div class="control">
                    <div class="select">
                      <select v-model="patientForm.localidadId">
                        <option v-for="localidad in localidades" :value="localidad.id" :selected="patientForm.localidadId == localidad.id">
                          {{ localidad.nombre }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Domicilio*</label>
                  <div class="control">
                    <input type="text" name="domicilio" class="input" v-model="patientForm.domicilio" v-validate="'required'">
                    <span v-show="errors.has('domicilio')" class="help is-danger">{{ errors.first('domicilio') }}</span>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Género*</label>
                  <div class="control">
                    <div class="select">
                      <select name="genero" v-model="patientForm.genero" v-validate="'required'">
                        <option v-for="genero in generos" :value="genero.id" :selected="patientForm.genero == genero.id">
                          {{ genero.nombre }}
                        </option>
                      </select>
                    </div>
                    <span v-show="errors.has('genero')" class="help is-danger">{{ errors.first('genero') }}</span>
                  </div>
                </div>
                <div class="field">
                  <label class="label">¿Tiene en su poder un documento?*</label>
                  <div class="control">
                    <div class="select">
                      <select name="tiene documento" v-model="patientForm.tieneDocumento" v-validate="'required'">
                        <option id="Sí" :value="true" v-model="patientForm.tieneDocumento" :checked="patientForm.tieneDocumento == true">Sí</option>
                        <option id="No" :value="false" v-model="patientForm.tieneDocumento" :checked="patientForm.tieneDocumento == false">No</option>
                      </select>
                    </div>
                    <span v-show="errors.has('tiene documento')" class="help is-danger">{{ errors.first('tiene documento') }}</span>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Tipo de documento*</label>
                  <div class="control">
                    <div class="select">
                      <select name="tipo de documento" v-model="patientForm.tipoDocId" v-validate="'required'">
                        <option v-for="docType in docTypes" :value="docType.id" :selected="patientForm.tipoDocId == docType.id">
                          {{ docType.nombre }}
                        </option>
                      </select>
                    </div>
                    <span v-show="errors.has('tipo de documento')" class="help is-danger">{{ errors.first('tipo de documento') }}</span>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Número de documento*</label>
                  <div class="control">
                    <input type="text" name="número de documento" class="input" v-model="patientForm.numero" v-validate="'required|numeric'">
                  </div>
                  <span v-show="errors.has('número de documento')" class="help is-danger">{{ errors.first('número de documento') }}</span>
                </div>
                <div class="field">
                  <label class="label">Tel/Cel</label>
                  <div class="control">
                    <input type="number" class="input" v-model="patientForm.tel">
                  </div>
                </div>
                <div class="field">
                  <label class="label">Número de carpeta</label>
                  <div class="control">
                    <input type="number" class="input" v-model="patientForm.nroCarpeta">
                  </div>
                </div>
                <div class="field">
                  <label class="label">Obra social</label>
                  <div class="control">
                    <div class="select">
                      <select v-model="patientForm.obraSocialId">
                        <option v-for="obraSocial in obrasSociales" :value="obraSocial.id" :selected="patientForm.obraSocialId == obraSocial.id">
                          {{ obraSocial.nombre }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Historia Clínica</label>
                  <div class="control">
                    <input type="text" class="input" v-model="patientForm.historiaClinica" readonly>
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
    loadPatients: Function,
    patient: Object,
    title: String,
    generos: Array,
    partidos: Array,
    regionesSanitarias: Array,
    localidades: Array,
    docTypes: Array,
    obrasSociales: Array,
    getPartido: Function,
    getRegionSanitaria: Function,
    getLocalidad: Function,
    getDocType: Function,
    getObraSocial: Function,
  },
  data() {
    return {
      isLoading: false,
      isReadOnly: false,
      localidadesSinFiltrar: Array,
      patientForm: {
        apellido: '',
        nombre: '',
        fechaNac: '',
        lugarNac: '',
        partidoId: '',
        regionSanitariaId: '',
        localidadId: '',
        domicilio: '',
        genero: '',
        tieneDocumento: '',
        tipoDocId: '',
        numero: '',
        tel: '',
        nroCarpeta: '',
        obraSocialId: '',
        historiaClinica: '',
      }
    }
  },
  created() {
    this.localidadesSinFiltrar=this.localidades
    if (this.patient != null) { //estoy en edición
      this.patientForm.apellido = this.patient.apellido
      this.patientForm.nombre = this.patient.nombre
      this.patientForm.fechaNac = new Date(this.patient.fecha_nac)
      this.patientForm.lugarNac = this.patient.lugar_nac
      this.patientForm.partidoId = this.patient.partido_id
      this.patientForm.regionSanitariaId = this.patient.region_sanitaria_id
      this.patientForm.localidadId = this.patient.localidad_id
      this.patientForm.domicilio = this.patient.domicilio
      this.patientForm.genero = this.patient.genero.id
      this.patientForm.tieneDocumento = this.patient.tiene_documento
      this.patientForm.tipoDocId = this.patient.tipo_doc_id
      this.patientForm.numero = this.patient.numero
      this.patientForm.tel = this.patient.tel
      this.patientForm.nroCarpeta = this.patient.nro_carpeta
      this.patientForm.obraSocialId = this.patient.obra_social_id
      this.patientForm.historiaClinica = this.patient.id
    }
  },
  methods: {
    onPartidoSelect(event) {
      var index = this.partidos.findIndex(partido => partido.id==event.target.value)
      this.patientForm.regionSanitariaId = this.partidos[index]['region_sanitaria_id']
      this.localidades=this.localidadesSinFiltrar.filter(loc => loc.partido_id==event.target.value)
    },
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
          this.patientForm.tieneDocumento = this.patientForm.tieneDocumento == true ? 1 : 0
          if (this.patient) { //edit
            axios
            .post(this.burl('/paciente/' + this.patient.id + '/edit'), this.patientForm)
            .then(response => {
                if (response.status == 200) {
                  Vue.swal('El paciente fue actualizado', '', 'success')
                  this.loadPatients()
                }
            })
          } else { //new
            axios
            .post(this.burl('/paciente/new'), this.patientForm)
            .then(response =>  {
                if (response.status == 200) {
                  Vue.swal('El paciente fue creado', '', 'success')
                  this.loadPatients()
                }
            })
          }
          this.close()
        }
      })
    }
  }
}
</script>
