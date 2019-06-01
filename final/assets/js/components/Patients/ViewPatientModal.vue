<template>
  <div class="modal is-active">
    <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <h3 class="title">Ver Paciente</h3>
        </header>
          <section class="modal-card-body">
              <div class="text-box">
                <h4 class="title is-5">Nombre</h4>
                <p class="subtitle is-6">{{ patient.nombre + ' ' + patient.apellido }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Fecha de nacimiento</h4>
                <p class="subtitle is-6">{{ this.getFormattedDate(new Date(patient.fecha_nac)) }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Lugar de nacimiento</h4>
                <p class="subtitle is-6">{{ patient.lugar_nac }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Partido</h4>
                <p class="subtitle is-6">{{ this.getPartido(patient) }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Región sanitaria</h4>
                <p class="subtitle is-6">{{ this.getRegionSanitaria(patient) }}</p>
              </div>
               <div class="text-box">
                <h4 class="title is-5">Localidad</h4>
                <p class="subtitle is-6">{{ this.getLocalidad(patient) }}</p>
              </div>
               <div class="text-box">
                <h4 class="title is-5">Domicilio</h4>
                <p class="subtitle is-6">{{ patient.domicilio }}</p>
              </div>
               <div class="text-box">
                <h4 class="title is-5">Género</h4>
                <p class="subtitle is-6">{{ patient.genero.nombre }}</p>
              </div>
               <div class="text-box">
                <h4 class="title is-5">Tiene en su poder un documento</h4>
                <p class="subtitle is-6">{{ patient.tiene_documento ? 'si' : 'no' }}</p>
              </div>
               <div class="text-box">
                <h4 class="title is-5">Tipo de documento</h4>
                <p class="subtitle is-6">{{ this.getDocType(patient) }}</p>
              </div>
               <div class="text-box">
                <h4 class="title is-5">Numero de documento</h4>
                <p class="subtitle is-6">{{ patient.numero ? patient.numero : 'sin numero' }}</p>
              </div>
               <div class="text-box">
                <h4 class="title is-5">Tel/Cel</h4>
                <p class="subtitle is-6">{{ patient.tel ? patient.tel : 'sin numero' }}</p>
              </div>
               <div class="text-box">
                <h4 class="title is-5">Número de carpeta</h4>
                <p class="subtitle is-6">{{ patient.nro_carpeta }}</p>
              </div>
               <div class="text-box">
                <h4 class="title is-5">Obra Social</h4>
                <p class="subtitle is-6">{{this.getObraSocial(patient)}}</p>
              </div>
              
          </section>
        <footer class="modal-card-foot">
          <button type="button" class="button is-success" @click="close">Cerrar</button>
        </footer>
        <button class="modal-close" @click="close"></button>
      </div>
    </div>
</template>


<script>
export default {
  props: {
    patient: Object,
    loadPartidos: Function,
    loadRegionesSanitarias: Function,
    loadLocalidades: Function,
    loadDocTypes: Function,
    loadObrasSociales: Function,
    getFormattedDate: Function,
    getPartido: Function,
    getRegionSanitaria: Function,
    getLocalidad: Function,
    getDocType: Function,
    getObraSocial: Function,
    
  },
  created(){
    this.loadPartidos();
    this.loadRegionesSanitarias();
    this.loadLocalidades();
    this.loadDocTypes();
    this.loadObrasSociales();
  },
  methods: {
    close() {
      this.$destroy();
      this.$el.parentNode.removeChild(this.$el);
    },
  
    
  }
}
</script>

<style scoped>
  .text-box {
    margin-bottom: 15px;
  }
</style>
