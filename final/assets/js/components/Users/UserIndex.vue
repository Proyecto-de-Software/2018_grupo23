<template>
  <div class="box">
    <section class="section">
      <div class="container" ref="container">
          <div v-if="!contentIsReady" class="has-text-centered">
            <a class="button is-loading page-loading-button"></a>
          </div>
          <div v-else>
            <vue-good-table
              :columns="columns"
              :rows="users"
              :lineNumbers="true"
              :defaultSortBy="{field: 'lastName', type: 'asec'}"
              :globalSearch="false"
              :pagination-options="{
                 enabled: true,
                 mode: 'records',
                 perPage: rowsPerPage,
                 perPageDropdown: [ rowsPerPage ],
                 position: 'bottom',
                 dropdownAllowAll: false,
                 setCurrentPage: 1,
                 nextLabel: 'siguiente',
                 prevLabel: 'anterior',
                 rowsPerPageLabel: 'Usuarios por tabla',
                 ofLabel: 'de',
               }"
              :search-options="{ enabled: true, placeholder: 'Buscar' }"
               styleClass="vgt-table bordered">
              <div slot="emptystate" class="has-text-centered">
                <h3 class="h3">No hay usuarios cargados en el sistema</h3>
              </div>
              <div slot="table-actions">
                <button v-if="loggedUser.permisos.includes('usuario_new')" type="button" class="button is-info" @click="showAddUserModal('Agregar Usuario')">Agregar Usuario</button>
              </div>
              <template slot="table-row" slot-scope="props">
                <span v-if="props.column.field == 'acciones'">
                  <button  v-if="loggedUser.permisos.includes('usuario_update')" type="button" class="button is-info is-small button-is-spaced" title="Editar" @click="showAddUserModal('Editar Usuario', props.row)">Editar</button>
                  <button v-if="loggedUser.permisos.includes('usuario_show')" type="button" class="button is-info is-small button-is-spaced" title="Ver" @click="showViewUserModal(props.row)">Ver</button>
                  <span v-if="props.row.activo == 1">
                    <button v-if="loggedUser.permisos.includes('usuario_update')" class="button_block button is-danger is-small button-is-spaced" @click="toggleUserState(props.row.id, props.row.activo)" title="Bloquear">Bloquear</button>
                  </span>
                  <span v-else>
                    <button v-if="loggedUser.permisos.includes('usuario_update')" class="button_unblock button is-info is-small button-is-spaced" @click="toggleUserState(props.row.id)" title="Desbloquear">Desbloquear</button>
                  </span>
                  <button v-if="loggedUser.permisos.includes('usuario_destroy')" class="button_delete button is-danger is-small button-is-spaced" title="Eliminar" @click="deleteUser(props.row.id)">Eliminar</button>
                </span>
              </template>
            </vue-good-table>
          </div>
      </div>
    </section>
  </div>
</template>

<script>
import Vue from 'vue'
import AddUserModal from './AddUserModal.vue'
import ViewUserModal from './ViewUserModal.vue'
export default {
  components: { AddUserModal, ViewUserModal },
  data() {
    return {
      users: null,
      appRoles: null,
      columns: [
        {
          label: 'Nombre completo',
          field: this.userCompleteName,
          filterable: true
        },
        {
          label: 'Fecha de creación',
          field: this.userIsCreatedAt
        },
        {
          label: 'Roles',
          field: this.userRoles
        },
        {
          label: 'Estado',
          field: this.userIsActive
        },
        {
          label: 'Acciones',
          field: 'acciones',
        },
      ],
    };
  },
  created() {
    this.loadUsers()
    this.loadAppRoles()
  },
  methods: {
    loadUsers: function() {
       axios
        .get('http://localhost:8000/user/index/')
        .then(response => {
          if (response.status === 200) {
            this.users = response.data
          }
        })
        .catch(error => {
          Vue.swal('Error: no fue posible cargar a los usuarios del sistema', '', 'error')
        })
    },
    loadAppRoles() {
       axios
        .post('http://localhost:8000/role/index')
        .then(response => {
          if (response.status === 200) {
            this.appRoles = response.data
          }
        })
        .catch(error => {
          Vue.swal('Error: no fue posible cargar los roles de usuario', '', 'error')
        })
    },
    toggleUserState(id, state) {
      axios
        .post('http://localhost:8000/user/' + id + '/edit_state')
        .then(response => {
              if (response.status == 200) {
                if (state == 1)
                  Vue.swal('El usuario fue bloqueado', '', 'success')
                else
                  Vue.swal('El usuario fue desbloqueado', '', 'success')
              }
              this.loadUsers()
        })
    },
    deleteUser(userId) {
      Vue.swal({
        title: 'Está seguro?',
        text: "No podrá revertirlo!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Si, eliminar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.value) {
          axios
            .delete('http://localhost:8000/user/' + userId)
            .then(response => {
              if (response.status === 200) {
                Vue.swal('El usuario fue eliminado', '', 'success')
              }
              this.loadUsers()
            })
        }
      })
    },
    showAddUserModal(modalTitle, userData = false) {
      var ComponentClass = Vue.extend(AddUserModal);
      var instance = new ComponentClass({
        propsData: { user: userData, roles: this.appRoles, loadUsers: this.loadUsers, title: modalTitle }
      })
      instance.$mount()
      this.$refs.container.appendChild(instance.$el)
    },
    showViewUserModal(row) {
      var ComponentClass = Vue.extend(ViewUserModal);
      var instance = new ComponentClass({
        propsData: { user: row }
      })
      instance.$mount()
      this.$refs.container.appendChild(instance.$el)
    },
    userCompleteName(user) {
      return user.last_name + ' ' + user.first_name
    },
    userRoles(user) {
      return user.roles.length > 0 ? user.roles.map(rol => rol.nombre.replace('ROLE_', '')).join(', ') :'Sin roles asignados';
    },
    userIsActive(user) {
      return user.activo == 1 ? 'Activo' : 'Inactivo'
    },
    userIsCreatedAt(user) {
      var created= new Date(user.created_at);
      var options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric'};
      return (created.toLocaleDateString("es-ES", options) + "hs.")
    },
  },
  computed: {
    rowsPerPage() {
      return this.$root.config.paginado
    },
    contentIsReady() {
      return (this.users && this.appRoles)
    }
  }
};
</script>
