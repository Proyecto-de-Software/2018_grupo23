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
                  <button type="button" class="button is-info is-small is-spaced" title="Editar" @click="showEditRoleModal(props.row)">Editar</button>
                  <button type="button" class="button is-info is-small is-spaced" title="Ver" @click="showViewRoleModal(props.row)">Ver</button>
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
import EditRoleModal from './EditRoleModal.vue'
import ViewRoleModal from './ViewRoleModal.vue'
export default {
  components: { EditRoleModal, ViewRoleModal },
  data() {
    return {
      appRoles: null,
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
    showEditRoleModal(row) {
      var ComponentClass = Vue.extend(EditRoleModal)
      var instance = new ComponentClass({
        propsData: { role: row, loadAppRoles: this.loadAppRoles }
      })
      instance.$mount()
      this.$refs.container.appendChild(instance.$el)
    },
    showViewRoleModal(row) {
      var ComponentClass = Vue.extend(ViewRoleModal)
      var instance = new ComponentClass({
        propsData: { role: row }
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
