<template>
  <div class="modal is-active">
    <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <h3 class="title">{{ title }} Rol</h3>
        </header>
          <section class="modal-card-body">

              <form class="form">

                <div class="field">
                  <div class="text-box">
                    <h4 class="title is-5">Nombre</h4>
                    <p class="subtitle is-6">{{ role.nombre.replace('ROLE_', '') }}</p>
                  </div>
                </div>

                <div class="field">
                  <div class="text-box">
                    <h4 class="title is-5">Permisos</h4>
                  </div>
                </div>

                <h5 class="h5 has-text-weight-semibold">Usuarios</h5>
                <div class="field">
                    <div class="control">
                      <div v-for="permiso in appPerms">
                        <div v-if="sectionName(permiso.nombre) == 'usuario'">
                          <label>{{ actionName(permiso.nombre) }}</label>
                          <input class="styled" type="checkbox" :value="permiso.nombre" v-model="roleForm.perms" :disabled="title === 'Ver'">
                        </div>
                      </div>
                    </div>
                 </div>

                 <h5 class="h5 has-text-weight-semibold">Pacientes</h5>
                 <div class="field">
                   <div class="control">
                     <div v-for="permiso in appPerms">
                       <div v-if="sectionName(permiso.nombre) == 'paciente'">
                         <label>{{ actionName(permiso.nombre) }}</label>
                         <input class="styled" type="checkbox" :value="permiso.nombre" v-model="roleForm.perms" :disabled="title === 'Ver'">
                       </div>
                     </div>
                   </div>
                </div>

                <h5 class="h5 has-text-weight-semibold">Atenciones de pacientes</h5>
                <div class="field">
                  <div class="control">
                    <div v-for="permiso in appPerms">
                      <div v-if="sectionName(permiso.nombre) == 'atencion'">
                        <label>{{ actionName(permiso.nombre) }}</label>
                        <input class="styled" type="checkbox" :value="permiso.nombre" v-model="roleForm.perms" :disabled="title === 'Ver'">
                      </div>
                    </div>
                  </div>
               </div>

                <h5 class="h5 has-text-weight-semibold">Configuración</h5>
                <div class="field">
                  <div class="control">
                    <div v-for="permiso in appPerms">
                      <div v-if="sectionName(permiso.nombre) == 'configuracion'">
                        <label>{{ actionName(permiso.nombre) }}</label>
                        <input class="styled" type="checkbox" :value="permiso.nombre" v-model="roleForm.perms" :disabled="title === 'Ver'">
                      </div>
                    </div>
                  </div>
               </div>

              <h5 class="h5 has-text-weight-semibold">Roles</h5>
              <div class="field">
                <div class="control">
                  <div v-for="permiso in appPerms">
                    <div v-if="sectionName(permiso.nombre) == 'rol'">
                      <label>{{ actionName(permiso.nombre) }}</label>
                      <input class="styled" type="checkbox" :value="permiso.nombre" v-model="roleForm.perms" :disabled="title === 'Ver'">
                    </div>
                  </div>
                </div>
             </div>

            </form>

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
    loadAppRoles: Function,
    appPerms: Array,
    title: String
  },
  data() {
    return {
      roleForm: {
        perms: []
      }
    }
  },
  created() {
    this.role.permisos.forEach((perm) => this.roleForm.perms.push(perm.nombre))
  },
  methods: {
    sectionName(permitName) {
      return permitName.split('_')[0]
    },
    actionName(permitName) {
      var $actionName
      switch (permitName.split('_')[1]) {
        case 'index':
          $actionName = 'Listar'
          break
        case 'show':
          $actionName = 'Ver'
          break
        case 'new':
          $actionName = 'Crear'
          break
        case 'update':
          $actionName = 'Editar'
          break
        case 'destroy':
          $actionName = 'Eliminar'
          break
      }
      return $actionName
    },
    close() {
      this.$destroy();
      this.$el.parentNode.removeChild(this.$el);
    },
    submit() {
      if ( this.title === 'Editar' ) {
        axios
        .post('http://localhost:8000/role/' + this.role.id + '/edit', this.roleForm.perms)
        .then(response => {
          if (response.status === 200) {
            events.$emit('alert:success', 'El rol fue actualizado');
            this.loadAppRoles()
          } else {
            events.$emit('alert:error', 'No fue posible realizar esa acción')
          }
          this.close()
        })
        .catch(error => this.close())
      } else {
        this.close()
      }
    },
  }
}
</script>

<style scoped>

.is-spaced {
  margin: 5px;
}

</style>
