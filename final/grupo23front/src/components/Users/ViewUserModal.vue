<template>
  <div class="modal is-active">
    <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <h3 class="title">Ver Usuario</h3>
        </header>
          <section class="modal-card-body">

              <h4 class="title is-5">Nombre</h4>
              <p class="subtitle is-6">{{ user.firstName + ' ' + user.lastName }}</p>

              <h4 class="title is-5">Email</h4>
              <p class="subtitle is-6">{{ user.email }}</p>

              <h4 class="title is-5">Nombre de usuario</h4>
              <p class="subtitle is-6">{{ user.username }}</p>

              <h4 class="title is-5">Roles</h4>
              <p class="subtitle is-6">{{ roles() }}</p>

          </section>
        <footer class="modal-card-foot">
          <button type="button" class="button is-success" @click="close">Cerrar</button>
        </footer>
        <button class="modal-close" @click="close"></button>
      </div>
    </div>
  </div>
</template>


<script>
export default {
  name: 'ViewUserModal',
  props: [ 'user' ],
  methods: {
    close() {
      this.$destroy();
      this.$el.parentNode.removeChild(this.$el);
    },
    roles() {
      return this.user.roles.map(rol => rol.nombre).join(', ');
    },
  }
}
</script>

<style>
  div {

  }
</style>
