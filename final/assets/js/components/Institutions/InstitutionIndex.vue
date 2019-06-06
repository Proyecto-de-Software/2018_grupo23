<template>
  <div>
      <div class="box">
      <section class="section">
      <div class="container" ref="container">
          <div v-if="!contentIsReady" class="has-text-centered">
            <a class="button is-loading page-loading-button"></a>
          </div>
          <div v-else>
            <vue-good-table
              :columns="columns"
              :rows="institutions"
              :lineNumbers="true"
              :defaultSortBy="{field: 'nombre', type: 'asec'}"
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
                 rowsPerPageLabel: 'Instituciones por tabla',
                 ofLabel: 'de',
               }"
              :search-options="{ enabled: true, placeholder: 'Buscar' }"
               styleClass="vgt-table bordered">
               <div slot="emptystate" class="has-text-centered">
                 <h3 class="h3">No hay pacientes cargados en el sistema</h3>
               </div>
                <div slot="table-actions">
                  <button type="button" class="button is-info">Agregar Institución</button>
                </div>
                <template slot="table-row" slot-scope="props">
                  <span v-if="props.column.field == 'acciones'">
                    <button v-if="loggedUser.permisos.includes('paciente_update')" type="button" class="button is-info is-small button-is-spaced" title="Editar">Editar</button>
                    <button v-if="loggedUser.permisos.includes('paciente_show')" type="button" class="button is-info is-small button-is-spaced" title="Ver" >Ver</button>
                    <button v-if="loggedUser.permisos.includes('paciente_destroy')" class="button_delete button is-danger is-small button-is-spaced" title="Eliminar" >Eliminar</button>
                    <router-link
                    v-if="loggedUser.permisos.includes('atencion_index')"
                    class="button is-info is-small button-is-spaced"
                    :to="{ name: 'consulta', params: { idPaciente: props.row.id}}"
                    title="Atenciones"
                    replace>
                      Atenciones
                    </router-link>
                  </span>
                </template>
            </vue-good-table>
          </div>
      </div>
    </section>
  </div>
  </div>
</template>

<script>
import Vue from 'vue';

export default {
  components: { },
  data() {
    return {
      institutions: null,
      columns: [
        {
          label: 'Nombre',
          field: this.nombre,
          filterable: true
        },
        {
          label: 'Director',
          field: this.director,
        },
        {
          label: 'Telefono',
          field: this.telefono
        },
        {
          label: 'Acciones',
          field: 'acciones',
        },
      ],
    };
  },
  created() {
    this.loadInstitutions()
  },
  methods: {
    loadInstitutions: function() {
      axios
        .get('http://localhost:8000/institucion/')
        .then(response => {
          this.institutions = response.data;
        })
        .catch(error => {
          console.log(error)
        })
    },
    nombre(institution){
        return institution.nombre
    },
    director(institution){
        return institution.director
    },
    telefono(institution){
        return institution.telefono 
    }
  },
  computed: {
    rowsPerPage() {
      return this.$root.config.paginado
    },
    contentIsReady() {
      return (this.institutions)
    }
  }
};
</script>