<template>
  <div class="box">
    <section class="section">
      <div class="container">
         <a class="button is-info">Agregar Usuario</a>
      </div>
    </section>
    <section class="section">
      <div class="container is-fluid">
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
            </vue-good-table>
          </div>
      </div>
    </section>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'UserIndex',
  data(){
    return {
      users: null,
      isLoading: true,
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
          field: 'activo'
        },
        {
          label: 'Acciones'
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
    }
  }
};
</script>
