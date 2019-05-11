<template>
  <div class="modal is-active">
    <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <h3 class="title">{{ title }}</h3>
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
                    <input id="apellido" type="text" class="input" v-model="userForm.lastName">
                  </div>
                </div>
                <div class="field">
                  <label class="label">Nombre*</label>
                  <div class="control">
                    <input id="nombre" type="text" class="input" v-model="userForm.firstName">
                  </div>
                </div>
                <div class="field">
                  <label class="label">Email*</label>
                  <div class="control">
                    <input type="text" class="input" name="email" v-model="userForm.email" placeholder="ejemplo@gmail.com">
                    <!-- <ul>
                      <li v-for="error in errors.collect('email')">{{ error }}</li>
                    </ul> -->
                  </div>
                </div>
                <div class="field">
                  <label class="label">Nombre de usuario*</label>
                  <div class="control">
                    <input type="text" class="input" v-model="userForm.username">
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

                <div v-if="!user">
                  <div class="field">
                    <label class="label">Contraseña*</label>
                    <div class="control">
                      <input type="password" class="input" v-model="userForm.newPass">
                    </div>
                    <p class="help">Debe tener entre 8 y 20 caracteres</p>
                  </div>
                  <div class="field">
                    <label class="label">Confirmar contraseña*</label>
                    <div class="control">
                      <input type="password" class="input" v-model="userForm.repeatNewPass">
                    </div>
                  </div>
                </div>

                <!-- editMode -->
                <div v-else>
                  <button type="button" class="button is-info" @click="passInputModal = !passInputModal" v-if="!passInputModal">Modificar contraseña</button>
                  <div v-if="passInputModal" class="modal is-active">
                    <div class="modal-background"></div>
                      <div class="modal-card">
                        <header class="modal-card-head">
                          <h3 class="title">Modificar contraseña</h3>
                        </header>
                        <section class="modal-card-body">
                          <form class="form">
                            <div class="field">
                              <label class="label">Contraseña actual*</label>
                              <div class="control">
                                <input type="password" class="input" v-model="userForm.oldPass">
                              </div>
                              <p class="help">Debe tener entre 8 y 20 caracteres</p>
                            </div>
                            <div class="field">
                              <label class="label">Nueva contraseña*</label>
                              <div class="control">
                                <input type="password" class="input" v-model="userForm.newPass">
                              </div>
                            </div>
                            <div class="field">
                              <label class="label">Confirmar nueva contraseña*</label>
                              <div class="control">
                                <input type="password" class="input" v-model="userForm.repeatNewPass">
                              </div>
                            </div>
                          </form>
                        </section>
                        <footer class="modal-card-foot">
                          <button type="button" class="button is-success" @click="{ userForm.passHasBeenModified = true; passInputModal = false }">Aceptar</button>
                          <button type="button" class="button is-text" @click="{ userForm.passHasBeenModified = false; passInputModal = false }">Cancelar</button>
                        </footer>
                      </div>
                  </div>
                </div>
                <!--edit-->

                <p>* campos obligatorios</p>
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
import swal from 'sweetalert2';
export default {
  name: 'AddUserModal',
  props: {
    loadUsers: Function,
    roles: Array,
    user: {
       type: [Object, Boolean],
       default: false
    },
    title: String
  },
  data() {
    return {
      isLoading: false,
      passInputModal: false,
      userForm: {
        lastName: '',
        firstName: '',
        email: '',
        username: '',
        oldPass: '',
        newPass: '',
        repeatNewPass: '',
        passHasBeenModified: false,
        roles: []
      }
    }
  },
  created() {
    if (this.user) { //edit
      this.userForm.lastName = this.user.lastName;
      this.userForm.firstName = this.user.firstName;
      this.userForm.email = this.user.email;
      this.userForm.username = this.user.username;
      this.user.roles.forEach((role) => this.userForm.roles.push(role.nombre))
    }
  },
  methods: {
    close() {
      this.$destroy();
      this.$el.parentNode.removeChild(this.$el);
    },
    submit() {
      if (this.user) { //edit
        axios
        .post('http://localhost:8000/usuario/' + this.user.id + '/edit', this.userForm)
        .then(response => { swal.fire('El usuario fue actualizado', '', 'success');
                            this.loadUsers(),
                            this.close() })
        .catch(error => swal.fire('Se produjo un error', 'error'))
      } else { //new
        axios
        .post('http://localhost:8000/usuario/new', this.userForm)
        .then(response => { swal.fire('El usuario fue agregado', '', 'success');
                            this.loadUsers();
                            this.close() })
        .catch(error => console.log(error))
      }
    },
  }
}
</script>
