<template>
  <div class="modal is-active">
    <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <h3 class="title">{{ title }}</h3>
        </header>
          <section class="modal-card-body">
            <div v-if="isLoading" class="has-text-centered">
              <a class="button is-loading page-loading-button"></a>
            </div>
            <div v-else>
              <form class="form">
                <div class="field">
                  <label class="label">Apellido*</label>
                  <div class="control">
                    <input type="text" class="input" :class="{'is-danger': errors.has('apellido')}" name="apellido" v-model="userForm.lastName" v-validate="'required|alpha_spaces'">
                    <span v-show="errors.has('apellido')" class="help is-danger">{{ errors.first('apellido') }}</span>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Nombre*</label>
                  <div class="control">
                    <input id="nombre" type="text" class="input" :class="{'is-danger': errors.has('nombre')}" name="nombre" v-model="userForm.firstName" v-validate="'required|alpha_spaces'">
                    <span v-show="errors.has('nombre')" class="help is-danger">{{ errors.first('nombre') }}</span>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Email*</label>
                  <div class="control">
                    <input type="text" class="input" :class="{'is-danger': errors.has('email')}" name="email" v-model="userForm.email" placeholder="ejemplo@gmail.com" v-validate="'required|email'">
                    <span v-show="errors.has('email')" class="help is-danger">{{ errors.first('email') }}</span>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Nombre de usuario*</label>
                  <div class="control">
                    <input type="text" class="input" :class="{'is-danger': errors.has('nombre de usuario')}" name="nombre de usuario" v-model="userForm.username" v-validate="'required|alpha_num|min:6|max:20'">
                    <span v-show="errors.has('nombre de usuario')" class="help is-danger">{{ errors.first('nombre de usuario') }}</span>
                  </div>
                  <p class="help">Debe tener entre 6 y 20 caracteres</p>
                </div>
                <div class="field">
                  <label class="label roles-label">Qué roles desea asignarle?</label>
                    <div class="control">
                        <div v-for="rol in roles">
                            <label>{{ rol.nombre.replace('ROLE_', '') }}</label>
                            <input class="styled" type="checkbox" :value="rol.nombre" v-model="userForm.roles">
                        </div>
                    </div>
                </div>

                <div v-if="!user">
                  <div class="field">
                    <label class="label">Contraseña*</label>
                    <div class="control">
                      <input type="password" name="contraseña" class="input" :class="{'is-danger': errors.has('contraseña')}" v-model="userForm.newPass" ref="contraseña" v-validate="'required|min:8|max:20'">
                      <span v-show="errors.has('contraseña')" class="help is-danger">{{ errors.first('contraseña') }}</span>
                    </div>
                    <p class="help">Debe tener entre 8 y 20 caracteres</p>
                  </div>
                  <div class="field">
                    <label class="label">Confirmar contraseña*</label>
                    <div class="control">
                      <input type="password" name="confirmar contraseña" class="input" :class="{'is-danger': errors.has('confirmar_contraseña')}" v-model="userForm.repeatNewPass" v-validate="'required|min:8|max:20|confirmed:contraseña'">
                      <span v-show="errors.has('confirmar contraseña')" class="help is-danger">{{ errors.first('confirmar contraseña') }}</span>
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
                                <input type="password" name="contraseña actual" class="input" :class="{'is-danger': errors.has('contraseña actual')}" v-model="userForm.oldPass" ref="contraseña actual" v-validate="'required|min:8|max:20'">
                                <span v-show="errors.has('contraseña actual')" class="help is-danger">{{ errors.first('contraseña actual') }}</span>
                              </div>
                              <p class="help">Debe tener entre 8 y 20 caracteres</p>
                            </div>
                            <div class="field">
                              <label class="label">Nueva contraseña*</label>
                              <div class="control">
                                <input type="password" name="contraseña nueva" class="input" :class="{'is-danger': errors.has('contraseña nueva')}" v-model="userForm.newPass" ref="contraseña nueva" v-validate="'required|min:8|max:20'">
                                <span v-show="errors.has('contraseña nueva')" class="help is-danger">{{ errors.first('contraseña nueva') }}</span>
                              </div>
                            </div>
                            <div class="field">
                              <label class="label">Confirmar nueva contraseña*</label>
                              <div class="control">
                                <input type="password" name="confirmar contraseña nueva" class="input" :class="{'is-danger': errors.has('confirmar contraseña nueva')}" v-model="userForm.repeatNewPass" v-validate="'required|min:8|max:20|confirmed:contraseña nueva'">
                                <span v-show="errors.has('confirmar contraseña nueva')" class="help is-danger">{{ errors.first('confirmar contraseña nueva') }}</span>
                              </div>
                            </div>
                          </form>
                        </section>
                        <footer class="modal-card-foot">
                          <!-- <button type="button" class="button is-success" @click="{ userForm.passHasBeenModified = true; passInputModal = false }">Aceptar</button> -->
                          <button type="button" class="button is-success" @click="passInputValidate">Aceptar</button>

                          <button type="button" class="button is-text"
                                  @click="{ userForm.passHasBeenModified = false; userForm.oldPass = ''; userForm.newPass = ''; userForm.repeatNewPass = ''; passInputModal = false }">Cancelar</button>
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
import Vue from 'vue'
export default {
  props: {
    loadUsers: Function,
    roles: Array,
    user: {
       type: [Object, Boolean],
       default: false
    },
    title: String,
  },
  data() {
    return {
      isLoading: true,
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
      this.userForm.lastName = this.user.last_name
      this.userForm.firstName = this.user.first_name
      this.userForm.email = this.user.email
      this.userForm.username = this.user.username
      this.user.roles.forEach((role) => this.userForm.roles.push(role.nombre))
    }
    this.isLoading = false
  },
  methods: {
    close() {
      this.$destroy();
      this.$el.parentNode.removeChild(this.$el)
    },
    passInputValidate() {
      this.$validator.validate()
      .then(valid => {
        if (!valid) {
          Vue.swal('El formulario no cumple con lo solicitado', '', 'error')
        } else {
          this.userForm.passHasBeenModified = true
          this.passInputModal = false
        }
      })
    },
    submit() {
      this.$validator.validate()
      .then(valid => {
        if (!valid) {
          Vue.swal('El formulario no cumple con lo solicitado', '', 'error')
        } else {
          if (this.user) { //edit
            axios
            .post(this.burl('/user/' + this.user.id + '/edit'), this.userForm)
            .then(response => {
              if (response.status == 200) {
                Vue.swal('El usuario fue actualizado', '', 'success')
              }
            })
          } else { //new
            axios
            .post(this.burl('/user/new'), this.userForm)
            .then(response => {
              if (response.status == 200) {
                Vue.swal('El usuario fue creado', '', 'success')
              }
            })
          }
          this.loadUsers()
          this.close()
        }
      })
    }
  }
}
</script>
