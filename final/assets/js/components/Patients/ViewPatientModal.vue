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
                <p class="subtitle is-6">{{ patient.fechaNac }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Lugar de nacimiento</h4>
                <p class="subtitle is-6">{{ patient.lugarNac }}</p>
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
                <p class="subtitle is-6">{{ patient.tieneDocumento ? 'si' : 'no' }}</p>
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
                <p class="subtitle is-6">{{ patient.nroCarpeta }}</p>
              </div>
               <div class="text-box">
                <h4 class="title is-5">Obra Social</h4>
                <p class="subtitle is-6">{{ patient.obraSocialId }}</p>
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
    partidos: Array,
    partidosLoading: true,
    regionesSanitarias: Array,
    regionesLoading: true,
    localidades: Array,
    localidadesLoading: true,
    docTypes: Array,
    docTypesLoading: true,
  },
  created(){
    this.loadPartidos();
    this.loadRegionesSanitarias();
    this.loadLocalidades();
    this.loadDocTypes()
  },
  methods: {
    close() {
      this.$destroy();
      this.$el.parentNode.removeChild(this.$el);
    },
    loadPartidos(){
      axios
        .get('https://api-referencias.proyecto2018.linti.unlp.edu.ar/partido')
        .then(response => {
          this.partidos=response.data;
          this.partidosLoading=false;
        })
        .catch(error => {
          console.log(error)
        })
    },
    getPartido(patient){
      if(this.partidosLoading==false){
      var index = this.partidos.findIndex(obj => obj.id==patient.partidoId)
      }
    if(index==undefined){
      return 'partido no asignado'
    }
    else{
      return this.partidos[index].nombre
    }
    },
    loadRegionesSanitarias(){
      axios
        .get('https://api-referencias.proyecto2018.linti.unlp.edu.ar/region-sanitaria')
        .then(response => {
          this.regionesSanitarias=response.data;
          this.regionesLoading=false;
        })
        .catch(error => {
          console.log(error)
        })
    },
    getRegionSanitaria(patient){
      if(this.regionesLoading==false){
      var index = this.regionesSanitarias.findIndex(obj => obj.id==patient.regionSanitariaId)
      }
    if(index==undefined){
      return 'región no asignada'
    }
    else{
      return this.regionesSanitarias[index].nombre
    }
    },
    loadLocalidades(){
      axios
        .get('https://api-referencias.proyecto2018.linti.unlp.edu.ar/localidad')
        .then(response => {
          this.localidades=response.data;
          this.localidadesLoading=false;
        })
        .catch(error => {
          console.log(error)
        })
    },
    getLocalidad(patient){
      if(this.localidadesLoading==false){
      var index = this.localidades.findIndex(obj => obj.id==patient.localidadId)
      }
    if(index==undefined){
      return 'localidad no asignada'
    }
    else{
      return this.localidades[index].nombre
    }
    },
    loadDocTypes: function(){
      axios
        .get('https://api-referencias.proyecto2018.linti.unlp.edu.ar/tipo-documento')
        .then(response => {
          this.docTypes= response.data;
          this.docTypesLoading=false;
        })
        .catch(error => {
          console.log(error)
        })
    },
    getDocType(patient){
      if(this.docTypesLoading==false){
      var index = this.docTypes.findIndex(obj => obj.id==patient.tipoDocId)
      }
    if(index==undefined){
      return 'no asignado'
    }
    else{
      return this.docTypes[index].nombre
    }
    },
    
  }
}
</script>

<style scoped>
  .text-box {
    margin-bottom: 15px;
  }
</style>
