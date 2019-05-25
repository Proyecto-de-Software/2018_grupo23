<template>
  <div class="modal is-active">
    <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <h3 class="title">Editar rol</h3>
        </header>
          <section class="modal-card-body">
            <div v-if="isLoading">
              <h1 class="title">Cargando...</h1>
            </div>
            <div v-else>
              <form class="form">

                <div class="text-box">
                  <h4 class="title is-5">Nombre</h4>
                  <p class="subtitle is-6">{{ role.nombre.replace('ROLE_', '') }}</p>
                </div>

                <div class="field">
                  <label class="label">Qué permisos desea asignarle al rol?</label>
                    <div class="control">
                        <div v-for="permiso in role.permisos">
                            <label>{{ permiso.nombre }}</label>
                            <input class="styled" type="checkbox" :value="permiso.nombre" v-model="roleForm.perms">
                        </div>
                    </div>
                </div>

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
  </div>
</template>

<script>
import Vue from 'vue'
export default {
  props: {
    role: Object,
    loadAppRoles: Function
  },
  data() {
    return {
      isLoading: false,
      roleForm: {
        perms: []
      }
    }
  },
  methods: {
    close() {
      this.$destroy();
      this.$el.parentNode.removeChild(this.$el);
    },
    submit() {
      console.log(this.roleForm)
    },
  }
}
</script>

<style scoped>

.is-spaced {
  margin: 5px;
}

</style>
