<template>
  <div class="modal is-active">
    <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <h3 class="title">Ver Rol</h3>
        </header>
          <section class="modal-card-body">

              <div class="text-box">
                <h4 class="title is-5">Nombre</h4>
                <p class="subtitle is-6">{{ role.nombre.replace('ROLE_', '') }}</p>
              </div>

              <div class="text-box">
                <h4 class="title is-5">Permisos asignados</h4>
                <div class="control">
                    <div v-for="permiso in role.permisos">
                        <label>{{ permiso.nombre }}</label>
                    </div>
                </div>
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
    role: Object
  },
  methods: {
    close() {
      this.$destroy();
      this.$el.parentNode.removeChild(this.$el);
    }
  }
}
</script>

<style scoped>
  .text-box {
    margin-bottom: 15px;
  }
</style>
