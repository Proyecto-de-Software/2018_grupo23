<template>
  <div class="modal is-active">
    <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <h3 class="title">Agregar Usuario</h3>
        </header>
          <section class="modal-card-body">
            <div v-if="isLoading">
              <h1 class="title">Cargando...</h1>
            </div>
            <div v-else>
              <form class="form">
                <div class="field">
                  <label class="label">Apellido*</label>
                  <div class="control">
                    <input id="apellido" type="text" class="input" v-model="userForm.lastName" required>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Nombre*</label>
                  <div class="control">
                    <input id="nombre" type="text" class="input" v-model="userForm.firstName" required>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Email*</label>
                  <div class="control">
                    <input type="email" class="input" placeholder="ejemplo@ejemplo.com"  v-model="userForm.email" required>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Nombre de usuario*</label>
                  <div class="control">
                    <input type="text" class="input" v-model="userForm.username" required>
                  </div>
                  <p class="help">Debe tener entre 6 y 20 caracteres</p>
                </div>
                <div class="field">
                  <label class="label roles-label">Qué roles desea asignarle? Si no asigna ninguno puede hacerlo en "editar"</label>
                    <div class="control">
                        <div v-for="rol in roles">
                            <label>{{ rol.nombre }}</label>
                            <input class="styled" type="checkbox" :value="rol.nombre" v-model="userForm.roles">
                        </div>
                    </div>
                </div>
                <div class="field">
                  <label class="label">Contraseña*</label>
                  <div class="control">
                    <input id="password" type="password" class="input" v-model="userForm.password" required>
                  </div>
                  <p class="help">Debe tener entre 8 y 20 caracteres</p>
                </div>
                <div class="field">
                  <label class="label">Confirmar contraseña*</label>
                  <div class="control">
                    <input id="re_password" type="password" class="input" v-model="userForm.re_password" required>
                  </div>
                </div>
                <p>* campos obligatorios</p>
              </form>
            </div>
          </section>
        <footer class="modal-card-foot">
          <button type="button" class="button is-success" @click="submit">Aceptar</button>
          <button type="button" class="button is-text" @click="$emit('close')">Cancelar</button>
        </footer>
        <button class="modal-close" @click="$emit('close')"></button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Modal',
  data() {
    return {
      roles: null,
      isLoading: true,
      error: '',
      userForm: {
        lastName: '',
        firstName: '',
        email: '',
        username: '',
        password: '',
        re_password: '',
        roles: []
      }
    }
  },
  methods: {
    loadRoles() {
      axios
        .get('http://localhost:8000/role/')
        .then(response => {
          this.roles = JSON.parse(response.data);
          this.isLoading = false
        })
        .catch(error => {
          this.error = error
        })
    },
    submit() {
      axios
      .post('http://localhost:8000/usuario/new', this.userForm)
      .then(response => this.$emit('userAdded')) //acá debería hacer un forceUpdate o de alguna manera que UserIndex vuelva a cargar la tabla
      .catch(error => console.log(error))
    }
  },
  created() { //carga los roles para el form desde la Api
    this.loadRoles();
  }
}
</script>
