<template>
  <div class="modal is-active">
    <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <h3 class="title">Ver Institución</h3>
        </header>
          <section class="modal-card-body">
              <div class="text-box">
                <h4 class="title is-5">Nombre</h4>
                <p class="subtitle is-6">{{ inst.nombre }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Director</h4>
                <p class="subtitle is-6">{{ inst.director }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Teléfono</h4>
                <p class="subtitle is-6">{{ inst.telefono }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Tipo de institución</h4>
                <p class="subtitle is-6">{{ inst.tipo_institucion.nombre }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Región sanitaria</h4>
                <p class="subtitle is-6">{{ this.getRegionSanitaria(inst.region_sanitaria_id) }}</p>
              </div>
               <div class="text-box">
                <h4 class="title is-5">Coordenadas | update mapa</h4>
                <p class="subtitle is-6">{{ inst.coordenadas }}</p>
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
    inst: Object,
    getRegionSanitaria: Function,
    
  },
  created(){
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
