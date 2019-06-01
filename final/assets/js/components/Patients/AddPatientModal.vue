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
                    <input id="apellido" type="text" class="input" v-model="patientForm.apellido">
                  </div>
                </div>
                <div class="field">
                  <label class="label">Nombre*</label>
                  <div class="control">
                    <input id="nombre" type="text" class="input" v-model="patientForm.nombre">
                  </div>
                </div>
                <div class="field">
                  <label class="label">Fecha de Nacimiento*</label>
                  <div class="control">
                    <input type="date" class="input" v-model="patientForm.fechaNac" >
                  </div>
                </div>
                <div class="field">
                  <label class="label">Lugar de nacimiento</label>
                  <div class="control">
                    <input type="text" class="input" v-model="patientForm.lugarNac">
                  </div>
                </div>
                <div class="field">
                  <label class="label">Partido</label>
                  select v-model="partidoSelected">
                    <option v-for="partido in partidos" :value="partido.nombre">
                      {{ partido.nombre }}
                    </option>
                  </select>
                </div>
                <div class="field">
                  <label class="label">Region sanitaria</label>
                  <div class="control"> 
                    <input type="text" class="input" v-model="patientForm.regionSanitariaId">
                  </div>
                </div>
                <div class="field">
                  <label class="label">Localidad</label>
                  <div class="control">
                    <input type="text" class="input" v-model="patientForm.localidadId">
                  </div>
                </div>
                <div class="field">
                  <label class="label">Domicilio*</label>
                  <div class="control">
                    <input type="text" class="input" v-model="patientForm.domicilio">
                  </div>
                </div>
                <div class="field">
                  <label class="label">Género*</label>
                  <div class="control">
                    <input type="text" class="input" v-model="patientForm.genero">
                  </div>
                </div>
                <div class="field">
                  <label class="label">Tiene en su poder un documento</label>
                  <div class="control">
                    <input type="text" class="input" v-model="patientForm.tieneDocumento">
                  </div>
                </div>
                <div class="field">
                  <label class="label">Tipo de documento*</label>
                  <div class="control">
                    <input type="text" class="input" v-model="patientForm.tipoDocId">
                  </div>
                </div>
                <div class="field">
                  <label class="label">Número de documento*</label>
                  <div class="control">
                    <input type="text" class="input" v-model="patientForm.numero">
                  </div>
                </div>
                <div class="field">
                  <label class="label">Tel/Cel</label>
                  <div class="control">
                    <input type="text" class="input" v-model="patientForm.tel">
                  </div>
                </div>
                <div class="field">
                  <label class="label">Número de carpeta</label>
                  <div class="control">
                    <input type="text" class="input" v-model="patientForm.nroCarpeta">
                  </div>
                </div>
                <div class="field">
                  <label class="label">Obra social</label>
                  <div class="control">
                    <input type="text" class="input" v-model="patientForm.obraSocialId">
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
export default {
  props: {
    loadPatients: Function,
    patient: Object,
    title: String,
    partidos: Array,
    regionesSanitarias: Array,
    localidades: Array,
    docTypes: Array,
    obrasSociales: Array,
    getFormattedDate: Function,
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
      }
    }
  },
  created() {
    if (this.patient != null) { //estoy en edición
      this.patientForm.apellido = this.patient.apellido;
      this.patientForm.nombre = this.patient.nombre;
      this.patientForm.fechaNac = new Date(this.patient.fecha_nac);
      this.patientForm.lugarNac = this.patient.lugar_nac;
      this.patientForm.partidoId = this.getPartido(this.patient);
      this.patientForm.regionSanitariaId = this.getRegionSanitaria(this.patient);
      this.patientForm.localidadId = this.getLocalidad(this.patient);
      this.patientForm.domicilio = this.patient.domicilio;
      this.patientForm.genero = this.patient.genero.nombre;
      this.patientForm.tieneDocumento = this.patient.tiene_documento ? 'si' : 'no' ;
      this.patientForm.tipoDocId = this.getDocType(this.patient);
      this.patientForm.numero = this.patient.numero;
      this.patientForm.tel = this.patient.tel;
      this.patientForm.nroCarpeta = this.patient.nro_carpeta;
      this.patientForm.obraSocialId = this.getObraSocial(this.patient);
    }
  },
  methods: {
    close() {
      this.$destroy();
      this.$el.parentNode.removeChild(this.$el);
    },
    submit() {
      axios
      .post('http://localhost:8000/paciente/new', this.patientForm)
      .then(response => { this.$swal.fire(
                            'El paciente fue agregado',
                            '',
                            'success'
                          );
                          this.loadPatients();
                          this.close() })
      .catch(error => console.log(error))
    }
  }
}
</script>
