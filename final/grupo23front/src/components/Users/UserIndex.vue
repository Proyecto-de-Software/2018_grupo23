<template>
  <div class="box">
    <section class="section">
      <div class="container">
          <div v-if="isLoading">
            <h1 class="title">Cargando datos...</h1>
          </div>
          <div v-else class="container">
            <vue-good-table
            :columns="columns"
            :rows="users"
            :lineNumbers="true"
            :defaultSortBy="{field: 'age', type: 'asec'}"
            :globalSearch="true"
            :paginate="true"
            :search-options="{ enabled: true}"
            styleClass="vgt-table bordered">
            <div slot="table-actions">
                <modal v-show="showModal" @close="showModal = false"></modal>
                <button type="button"
                    class="button is-info"
                    @click="showModal = true">Agregar Usuario</button>
            </div>
            <template slot="table-row" slot-scope="props">
              <span v-if="props.column.field == 'acciones'">
                <button class="button_view button is-info" title="Ver">Ver</button>
                <button class="button_edit button is-info" title="Editar">Editar</button>
                <!-- <button class="button_block button is-danger" title="Bloquear">Bloquear</i></button>
                <button class="button_unblock button is-info" title="Desbloquear"></i></button> -->
                <button class="button_delete button is-danger" title="Eliminar">Eliminar</i></button>
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
import Modal from './Modal.vue';
export default {
  name: 'UserIndex',
  data(){
    return {
      users: null,
      isLoading: true,
      showModal: false,
      columns: [
        {
          label: 'Apellido',
          field: 'lastName',
          filterable: true,
        },
        {
          label: 'Nombre',
          field: 'firstName',
          html: false,
          filterable: true,
        },
        {
          label: 'Creado',
          field: 'createdAt',
        },
        {
          label: 'Roles',
          field: this.roles
        },
        {
          label: 'Estado',
          field: this.active
        },
        {
          label: 'Acciones',
          field: 'acciones',
        }
      ],
    };
  },
  created() {
    axios
      .get('http://localhost:8000/usuario')
      .then(response => {
        this.users = JSON.parse(response.data);
        this.isLoading = false
      })
      .catch(error => {
        console.log(error)
      })
  },
  methods: {
    roles(user) {
      return user.roles.map(rol => rol.nombre).join(', ');
    },
    active(user) {
      return user.activo == 1 ? 'Si' : 'No'
    },
  },
  components: { Modal }
};
</script>
