<template>
  <div class="modal is-active">
    <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <h3 class="title">Ver Usuario</h3>
        </header>
          <section class="modal-card-body">
              <div class="text-box">
                <h4 class="title is-5">Nombre</h4>
                <p class="subtitle is-6">{{ user.first_name + ' ' + user.last_name }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Email</h4>
                <p class="subtitle is-6">{{ user.email }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Nombre de usuario</h4>
                <p class="subtitle is-6">{{ user.username }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Estado</h4>
                <p class="subtitle is-6">{{ user.activo == 1 ? 'Activo' : 'Inactivo' }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Roles</h4>
                <p class="subtitle is-6">{{ roles() }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Fecha de creación</h4>
                <p class="subtitle is-6">{{ formatDate(user.created_at) }}</p>
              </div>
              <div class="">
                <h4 class="title is-5">Fecha de última actualización</h4>
                <p class="subtitle is-6">{{ formatDate(user.updated_at) }}</p>
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
    user: Object,
  },
  methods: {
    close() {
      this.$destroy();
      this.$el.parentNode.removeChild(this.$el);
    },
    roles() {
      return this.user.roles.length > 0 ? this.user.roles.map(rol => rol.nombre.replace('ROLE_', '')).join(', ') :'Sin roles asignados';
    },
    formatDate(date) {
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
