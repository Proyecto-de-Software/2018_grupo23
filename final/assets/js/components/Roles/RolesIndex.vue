<template>
  <div class="container margin-global" ref="container">
      <div v-if="!contentIsReady" class="has-text-centered">
        <a class="button is-loading page-loading-button"></a>
      </div>
      <div v-else>
        <vue-good-table
          :columns="columns"
          :rows="appRoles"
          :lineNumbers="true"
          :defaultSortBy="{field: 'nombre', type: 'asec'}"
           styleClass="vgt-table bordered">
           <div slot="emptystate" class="has-text-centered">
             <h3 class="h3">No hay roles cargados en el sistema</h3>
           </div>
          <template slot="table-row" slot-scope="props">
            <span v-if="props.column.field == 'acciones'">
              <button v-if="loggedUser.permisos.includes('rol_update')" type="button" class="button is-info is-small is-spaced" title="Editar" @click="showEditAndViewRoleModal(props.row, 'Editar')">Editar</button>
              <button v-if="loggedUser.permisos.includes('rol_show')" type="button" class="button is-info is-small is-spaced" title="Ver" @click="showEditAndViewRoleModal(props.row, 'Ver')">Ver</button>
            </span>
          </template>
        </vue-good-table>
      </div>
  </div>
</template>

<script>
import Vue from 'vue'
import EditAndViewRoleModal from './EditAndViewRoleModal.vue'
export default {
  components: { EditAndViewRoleModal },
  data() {
    return {
      appRoles: null,
      appPerms: null,
      columns: [
        {
          label: 'Nombre',
          field: this.roleName,
          filterable: true
        },
        {
          label: 'Acciones',
          field: 'acciones',
        },
      ],
    };
  },
  created() {
    this.loadAppRoles()
    this.loadAppPermissions()
  },
  methods: {
    loadAppRoles: function() {
       axios
      .post(this.burl('/role/index'))
      .then(response => this.appRoles = response.data)
    },
    loadAppPermissions() {
       axios
      .get(this.burl('/role/permissions_all'))
      .then(response => this.appPerms = response.data)
    },
    showEditAndViewRoleModal(row, modalTitle) {
      var ComponentClass = Vue.extend(EditAndViewRoleModal)
      var instance = new ComponentClass({
        propsData: { role: row, loadAppRoles: this.loadAppRoles, appPerms: this.appPerms, title: modalTitle}
      })
      instance.$mount()
      this.$refs.container.appendChild(instance.$el)
    },
    roleName(role) {
      return role.nombre.replace('ROLE_', '')
    }
  },
  computed: {
    contentIsReady() {
      return (this.appRoles) && (this.appPerms) && (this.loggedUser !== undefined)
    }
  }
}
</script>

<style scoped>

.is-spaced {
  margin: 5px;
}

</style>
