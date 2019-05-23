<template>
  <div class="box">
    <section class="section">
      <div class="container" ref="container">
          <div v-if="isLoading">
            <h1 class="title">Cargando datos...</h1>
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
              <div slot="table-actions">
                <button type="button" class="button is-info" @click="showAddUserModal('Agregar Usuario')">Agregar Usuario</button>
              </div>
              <template slot="table-row" slot-scope="props">
                <span v-if="props.column.field == 'acciones'">
                  <button type="button" class="button is-info is-small is-spaced" title="Editar" @click="showAddUserModal('Editar Usuario', props.row)">Editar</button>
                  <button type="button" class="button is-info is-small is-spaced" title="Ver" @click="showViewUserModal(props.row)">Ver</button>
                  <span v-if="props.row.activo == 1">
                    <button class="button_block button is-danger is-small is-spaced" @click="toggleUserState(props.row.id)" title="Bloquear">Bloquear</button>
                  </span>
                  <span v-else>
                    <button class="button_unblock button is-info is-small is-spaced" @click="toggleUserState(props.row.id)" title="Desbloquear">Desbloquear</button>
                  </span>
                  <button class="button_delete button is-danger is-small is-spaced" title="Eliminar" @click="deleteUser(props.row.id)">Eliminar</button>
                </span>
              </template>
            </vue-good-table>
          </div>
      </div>
    </section>
  </div>
</template>

<script>
import Vue from 'vue';
import AddUserModal from './AddUserModal.vue';
import ViewUserModal from './ViewUserModal.vue';
export default {
  components: { AddUserModal, ViewUserModal },
  data() {
    return {
      users: null,
      isLoading: true,
      appRoles: [],
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
    this.loadUsers();
    this.loadRoles()
  },
  methods: {
    loadUsers: function() {
      axios
        .get('http://localhost:8000/usuario/')
        .then(response => {
          this.users = JSON.parse(response.data);
          this.isLoading = false
        })
        .catch(error => {
          this.$swal.fire('Se produjo un error', error.message, 'error')
        })
    },
    loadRoles() {
      axios
        .get('http://localhost:8000/role/')
        .then(response => {
          this.appRoles = JSON.parse(response.data);
          this.isLoading = false
        })
        .catch(error => {
          console.log(error)
        })
    },
    toggleUserState(id) {
      axios
        .post('http://localhost:8000/usuario/' + id + '/edit_state')
        .then(response => { this.loadUsers();
                            this.$swal.fire('El usuario fue bloqueado', '', 'success')
                          })
        .catch(error => console.log(error.message))
    },
    deleteUser(userId) {
      this.$swal.fire({
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
            .delete('http://localhost:8000/usuario/' + userId)
            .then(response => {
              this.loadUsers()
              this.$swal.fire('El usuario fue eliminado', '', 'success')
              })
            .catch(error => {
              this.$swal.fire('Se produjo un error', error.message, 'error')
          });
        }
      })
    },
    showAddUserModal(modalTitle, userData = false) {
      var ComponentClass = Vue.extend(AddUserModal);
      var instance = new ComponentClass({
        propsData: { user: userData, roles: this.appRoles, loadUsers: this.loadUsers, title: modalTitle}
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
      return user.lastName + ' ' + user.firstName
    },
    userRoles(user) {
      return user.roles.length > 0 ? user.roles.map(rol => rol.nombre).join(', ') :'Sin roles asignados';
    },
    userIsActive(user) {
      return user.activo == 1 ? 'Activo' : 'Inactivo'
    },
    userIsCreatedAt(user) {
      var created= new Date(user.createdAt);
      var options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric'};
      return (created.toLocaleDateString("es-ES", options) + "hs.")
    },
  },
  computed: {
    rowsPerPage() {
      return this.$root.config.paginado
    }
  }
};
</script>

<style scoped>

.is-spaced {
  margin: 5px;
}

</style>
