<template>
  <div class="modal is-active">
    <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <h3 class="title">Ver Atención</h3>
        </header>
          <section class="modal-card-body">
              <div class="text-box">
                <h4 class="title is-5">Nombre y apellido del paciente</h4>
                <p class="subtitle is-6">{{attention.paciente.nombre +' '+ attention.paciente.apellido }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Fecha de Atención</h4>
                <p class="subtitle is-6">{{ this.getFormattedDate(new Date(attention.fecha)) }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Acompañamiento</h4>
                <p class="subtitle is-6">{{attention.acompanamiento ? attention.acompanamiento.nombre : 'Sin acompañamiento' }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Derivación</h4>
                <p class="subtitle is-6">{{attention.derivacion ? attention.derivacion.nombre : 'Sin derivación'}}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Articulación con otras instituciones</h4>
                <p class="subtitle is-6">{{attention.articulacion_con_insituciones ? attention.articulacion_con_insituciones : 'No tiene' }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Internación</h4>
                <p class="subtitle is-6">{{attention.internacion? 'Sí' : 'No' }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Diagnóstico</h4>
                <p class="subtitle is-6">{{attention.diagnostico ? attention.diagnostico : 'Sin diagnóstico' }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Observaciones</h4>
                <p class="subtitle is-6">{{attention.observaciones ? attention.observaciones : 'Sin observaciones' }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Tratamiento Farmacológico</h4>
                <p class="subtitle is-6">{{attention.tratamiento_farmacologico ? attention.tratamiento_farmacologico.nombre : 'Sin tratamiento' }}</p>
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
    attention: Object,
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
