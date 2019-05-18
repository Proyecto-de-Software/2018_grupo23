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
                <p class="subtitle is-6">{{ patient.partidoId }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Región sanitaria</h4>
                <p class="subtitle is-6">{{ patient.regionSanitariaId }}</p>
              </div>
               <div class="text-box">
                <h4 class="title is-5">Localidad</h4>
                <p class="subtitle is-6">{{ patient.localidadId }}</p>
              </div>
               <div class="text-box">
                <h4 class="title is-5">Domicilio</h4>
                <p class="subtitle is-6">{{ patient.domicilio }}</p>
              </div>
               <div class="text-box">
                <h4 class="title is-5">Género</h4>
                <p class="subtitle is-6">{{ patient.genero }}</p>
              </div>
               <div class="text-box">
                <h4 class="title is-5">TIene en su poder un documento</h4>
                <p class="subtitle is-6">{{ patient.tieneDocumento }}</p>
              </div>
               <div class="text-box">
                <h4 class="title is-5">TipoDocumento</h4>
                <p class="subtitle is-6">{{ patient.numero }}</p>
              </div>
               <div class="text-box">
                <h4 class="title is-5">Tel/Cel</h4>
                <p class="subtitle is-6">{{ patient.tel }}</p>
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
  name: 'ViewUserModal',
  props: {
    user: Object,
  },
  methods: {
    close() {
      this.$destroy();
      this.$el.parentNode.removeChild(this.$el);
    },
    roles() {
      if (this.user.roles.length > 0) {
        return this.user.roles.map(rol => rol.nombre).join(', ')
      } else {
        return 'Sin roles asignados'
      }
    },
    createdOrUpdatedAt(date) {
      var localDate= new Date(date);
      var options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric'};
      return (localDate.toLocaleDateString("es-ES", options) + "hs.")
    }
  }
}
</script>

<style scoped>
  .text-box {
    margin-bottom: 15px;
  }
</style>
