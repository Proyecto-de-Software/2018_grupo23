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
                  <div class="control">
                    <input type="text" class="input" v-model="patientForm.partidoId">
                  </div>
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
import swal from 'sweetalert2';
export default {
  name: 'AddPatientModal',
  props: {
    loadPatients: Function,
    patient: Object,
    title: String
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
      this.patientForm.fechaNac = this.patient.fechaNac;
      this.patientForm.lugarNac = this.patient.lugarNac;
      this.patientForm.partidoId = this.patient.partidoId;
      this.patientForm.regionSanitariaId = this.patient.regionSanitariaId;
      this.patientForm.localidadId = this.patient.localidadId;
      this.patientForm.domicilio = this.patient.domicilio;
      this.patientForm.genero = this.patient.genero;
      this.patientForm.tieneDocumento = this.patient.tieneDocumento;
      this.patientForm.tipoDocId = this.patient.tipoDocId;
      this.patientForm.numero = this.patient.numero;
      this.patientForm.tel = this.patient.tel;
      this.patientForm.nroCarpeta = this.patient.nroCarpeta;
      this.patientForm.obraSocialId = this.patient.obraSocialId;
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
      .then(response => { swal.fire(
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
