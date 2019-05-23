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
                <p class="subtitle is-6">{{ user.firstName + ' ' + user.lastName }}</p>
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
                <h4 class="title is-5">Roles</h4>
                <p class="subtitle is-6">{{ roles() }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Fecha de creación</h4>
                <p class="subtitle is-6">{{ createdOrUpdatedAt(user.createdAt) }}</p>
              </div>
              <div class="">
                <h4 class="title is-5">Fecha de última actualización</h4>
                <p class="subtitle is-6">{{ createdOrUpdatedAt(user.updatedAt) }}</p>
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
