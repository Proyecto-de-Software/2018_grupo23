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
              :rows="appRoles"
              :lineNumbers="true"
              :defaultSortBy="{field: 'nombre', type: 'asec'}"
               styleClass="vgt-table bordered">
              <template slot="table-row" slot-scope="props">
                <span v-if="props.column.field == 'acciones'">
                  <button type="button" class="button is-info is-small is-spaced" title="Editar" @click="showEditAndViewRoleModal(props.row, 'Editar')">Editar</button>
                  <button type="button" class="button is-info is-small is-spaced" title="Ver" @click="showEditAndViewRoleModal(props.row, 'Ver')">Ver</button>
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
import EditAndViewRoleModal from './EditAndViewRoleModal.vue'
export default {
  components: { EditAndViewRoleModal },
  data() {
    return {
      appRoles: null,
      appPerms: [],
      isLoading: true,
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
        .get('http://localhost:8000/role/index/')
        .then(response => {
          this.appRoles = response.data
          this.isLoading = false
        })
        .catch(error => {
          console.log(error)
        })
    },
    loadAppPermissions() {
      axios
      .get('http://localhost:8000/role/permissions_all')
      .then(response =>  this.appPerms = response.data)
      .catch(error => console.log(error))
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
  }
}
</script>

<style scoped>

.is-spaced {
  margin: 5px;
}

</style>
