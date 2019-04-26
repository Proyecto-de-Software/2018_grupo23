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
            :globalSearch="true"
            :paginate="true"
            :search-options="{ enabled: true}"
            styleClass="vgt-table bordered">
            <div slot="table-actions">
              <button type="button" class="button is-info" @click="showAddUserModal(null, 'Agregar Usuario')">Agregar Usuario</button>
            </div>
            <template slot="table-row" slot-scope="props">
              <span v-if="props.column.field == 'acciones'">

                <button type="button" class="button is-info" title="Editar" @click="showAddUserModal(props.row, 'Editar Usuario')">Editar</button>
                <button type="button" class="button is-info" title="Ver" @click="showViewUserModal(props.row)">Ver</button>

                <span v-if="props.row.activo == 1">
                  <button class="button_block button is-danger" title="Bloquear">Bloquear</button>
                </span>
                <span v-else>
                  <button class="button_unblock button is-info" title="Desbloquear">Desbloquear</button>
                </span>
                <button class="button_delete button is-danger" title="Eliminar" @click="deleteUser(props.row.id)">Eliminar</button>
              </span>
            </template>
            </vue-good-table>
          </div>
      </div>
    </section>
  </div>
</template>

<script>
import axios from 'axios';
import Vue from 'vue';
import AddUserModal from './AddUserModal.vue';
import ViewUserModal from './ViewUserModal.vue';
import swal from 'sweetalert2';
export default {
  name: 'UserIndex',
  components: { AddUserModal, ViewUserModal },
  data() {
    return {
      users: null,
      isLoading: true,
      appRoles: [],
      columns: [
        {
          label: 'Apellido',
          field: 'lastName',
          filterable: true,
        },
        {
          label: 'Nombre',
          field: 'firstName',
          filterable: true,
        },
        {
          label: 'Creado',
          field: 'createdAt',
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
          console.log(error)
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
          this.error = error
        })
    },
    deleteUser(userId) {
      swal.fire({
        title: 'Estás seguro?',
        text: "No podrá revertirlo!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminar'
      }).then((result) => {
        if (result.value) {
          axios
            .delete('http://localhost:8000/usuario/' + userId)
            .then(response => {
              swal.fire(
                response.data['msg'],
              )
              this.loadUsers()
            })
            .catch(error => {
              console.log(error)
          });
        }
      })
    },
    showAddUserModal(userData, modalTitle) {
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
    userRoles(user) {
      return user.roles.map(rol => rol.nombre).join(', ');
    },
    userIsActive(user) {
      return user.activo == 1 ? 'Activo' : 'Inactivo'
    },
  }
};
</script>
