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
                  <button type="button" class="button is-info" @click="showAddInstitutionModal(null, 'Agregar Institución')">Agregar Institución</button>
                </div>
                <template slot="table-row" slot-scope="props">
                  <span v-if="props.column.field == 'acciones'">
                    <button v-if="loggedUser.permisos.includes('atencion_update')" type="button" class="button is-info is-small button-is-spaced" title="Editar" @click="showAddInstitutionModal(props.row, 'Editar Institución')">Editar</button>
                    <button v-if="loggedUser.permisos.includes('atencion_show')" type="button" class="button is-info is-small button-is-spaced" title="Ver" @click="showViewInstitutionModal(props.row)">Ver</button>
                    <button v-if="loggedUser.permisos.includes('atencion_destroy')" class="button_delete button is-danger is-small button-is-spaced" title="Eliminar" @click="deleteInstitution(props.row.id)">Eliminar</button>
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
import ViewInstitutionModal from './ViewInstitutionModal.vue'
import AddInstitutionModal from './AddInstitutionModal.vue'

export default {
  components: { },
  data() {
    return {
      institutions: null,
      regionesSanitarias: null,
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
    this.loadRegionesSanitarias()
    this.loadTiposInstitucion()
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
    },
    loadRegionesSanitarias(){
      this
        .makeCorsRequest('https://api-referencias.proyecto2018.linti.unlp.edu.ar/region-sanitaria')
        .then(response => {
          this.regionesSanitarias = response;
        })
        .catch(error => {
          console.log(error)
        })
    },
    loadTiposInstitucion(){
      axios
            .post(this.burl('/institucion/tipos'))
            .then( response => {
              this.tiposInstitucion= response.data
            }

            )
    },
    getRegionSanitaria(id){
      var index = this.regionesSanitarias.findIndex(obj => obj.id==id)
      if ( index == undefined || index == -1 ) {
        return 'Región no asignada'
      }
      else {
        return this.regionesSanitarias[index].nombre
      }
    },
    showViewInstitutionModal(inst){
      var ComponentClass = Vue.extend(ViewInstitutionModal);
        var instance = new ComponentClass({
          propsData: {
            inst: inst,
            getRegionSanitaria: this.getRegionSanitaria,
            }
        })
        instance.$mount()
        this.$refs.container.appendChild(instance.$el)
    },
    showAddInstitutionModal(inst, title){
      var ComponentClass = Vue.extend(AddInstitutionModal);
        var instance = new ComponentClass({
          propsData: {
            loadInstitutions: this.loadInstitutions,
            institution: inst,
            title: title,
            regionesSanitarias: this.regionesSanitarias,
            tiposInstitucion: this.tiposInstitucion,
            }
        })
        instance.$mount()
        this.$refs.container.appendChild(instance.$el)
    },
    deleteInstitution(id) {
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
            .delete(this.burl('/institucion/' + id))
            .then(response => {
              Vue.swal(
                'La institucion fue eliminada',
                '',
                'success'
              )
              this.loadInstitutions()
            })
            .catch(error => {
              console.log(error)
          });
        }
      })
    },
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