<template>
  <div>
      <div class="box">
      <section class="section">
      <div class="container" ref="container">
          <div v-if="isLoading" class="has-text-centered">
            <a class="button is-loading page-loading-button "></a>
          </div>
          <div v-else>
            <vue-good-table
              v-if="patients && patients.length"
              :columns="columns"
              :rows="patients"
              :lineNumbers="true"
              :defaultSortBy="{field: 'apellido', type: 'asec'}"
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
                 rowsPerPageLabel: 'Pacientes por tabla',
                 ofLabel: 'de',
               }"
              :search-options="{ enabled: true, placeholder: 'Buscar' }"
               styleClass="vgt-table bordered">
              <div slot="table-actions">
                <button type="button" class="button is-info" @click="showAddPatientModal(null, 'Agregar Paciente')">Agregar Paciente</button>
              </div>
              <template slot="table-row" slot-scope="props">
                <span v-if="props.column.field == 'acciones'">
                  <button v-if="loggedUser.permisos.includes('paciente_update')" type="button" class="button is-info is-small button-is-spaced" title="Editar" @click="showAddPatientModal(props.row, 'Editar Paciente')">Editar</button>
                  <button v-if="loggedUser.permisos.includes('paciente_show')" type="button" class="button is-info is-small button-is-spaced" title="Ver" @click="showViewPatientModal(props.row)">Ver</button>
                  <button v-if="loggedUser.permisos.includes('paciente_destroy')" class="button_delete button is-danger is-small button-is-spaced" title="Eliminar" @click="deletePatient(props.row.id)">Eliminar</button>
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
import AddPatientModal from './AddPatientModal.vue';
import ViewPatientModal from './ViewPatientModal.vue';

export default {
  components: { AddPatientModal, ViewPatientModal },
  data() {
    return {
      patients: null,
      docTypes: null,
      docTypesLoading: true,
      isLoading: true,
      partidos: Array,
      partidosLoading: true,
      regionesSanitarias: Array,
      regionesLoading: true,
      localidades: Array,
      localidadesLoading: true,
      docTypes: Array,
      docTypesLoading: true,
      obrasSociales: Array,
      obrasSocialesLoading: true,
      appRoles: [],
      generos: Array,
      generosLoading: true,
      columns: [
        {
          label: 'Nombre completo',
          field: this.patientCompleteName,
          filterable: true
        },
        {
          label: 'Tipo',
          field: this.getDocType,
        },
        {
          label: 'Numero de documento',
          field: this.patientDocNum
        },
        {
          label: 'Historia clínica',
          field: this.patientClinicHistoryNumber
        },
        {
          label: 'Acciones',
          field: 'acciones',
        },
      ],
    };
  },
  created() {
    this.loadPatients();
    this.loadGeneros();
    this.loadPartidos();
    this.loadRegionesSanitarias();
    this.loadLocalidades();
    this.loadDocTypes();
    this.loadObrasSociales();

  },
  methods: {
    loadPatients: function() {
      axios
        .get('http://localhost:8000/paciente/index')
        .then(response => {
          this.patients = response.data;
          console.log(this.patients)
          this.isLoading = false
        })
        .catch(error => {
          console.log(error)
        })
    },
    loadGeneros: function(){
      axios
        .get('http://localhost:8000/paciente/generos')
        .then(response =>{
          this.generos= response.data
          this.generosLoading = false
        })
    },
    deletePatient(patientId) {
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
            .delete('http://localhost:8000/paciente/' + patientId)
            .then(response => {
              Vue.swal(
                'El paciente fue eliminado',
                '',
                'success'
              )
              this.loadPatients()
            })
            .catch(error => {
              console.log(error)
          });
        }
      })
    },
    showAddPatientModal(patientData, modalTitle) {
      var ComponentClass = Vue.extend(AddPatientModal);
      var instance = new ComponentClass({
        propsData: {
          patient: patientData,
          loadPatients: this.loadPatients,
          title: modalTitle,
          generos: this.generos,
          partidos: this.partidos,
          regionesSanitarias: this.regionesSanitarias,
          localidades: this.localidades,
          docTypes: this.docTypes,
          obrasSociales: this.obrasSociales,
          getFormattedDate: this.getFormattedDate,
          getPartido: this.getPartido,
          getRegionSanitaria: this.getRegionSanitaria,
          getLocalidad: this.getLocalidad,
          getDocType: this.getDocType,
          getObraSocial: this.getObraSocial,
          }
      })
      instance.$mount()
      this.$refs.container.appendChild(instance.$el)
    },
    showViewPatientModal(patientData) {
      var ComponentClass = Vue.extend(ViewPatientModal);
      var instance = new ComponentClass({
        propsData: {
          patient: patientData,
          getFormattedDate: this.getFormattedDate,
          getPartido: this.getPartido,
          getRegionSanitaria: this.getRegionSanitaria,
          getLocalidad: this.getLocalidad,
          getDocType: this.getDocType,
          getObraSocial: this.getObraSocial,
          }
      })
      instance.$mount()
      this.$refs.container.appendChild(instance.$el)
    },
    patientCompleteName(patient) {
      return patient.apellido + ' ' + patient.nombre
    },
    patientDocNum(patient){
      return patient.numero
    },
    patientClinicHistoryNumber(patient){
      return patient.id
    },
    loadPartidos(){
      this
        .makeCorsRequest('https://api-referencias.proyecto2018.linti.unlp.edu.ar/partido')
        .then(response => {
          this.partidos=response;
          this.partidosLoading=false;
        })
        .catch(error => {
          console.log(error)
        })
    },
    getPartido(patient){
      if(this.partidosLoading==false){
      var index = this.partidos.findIndex(obj => obj.id==patient.partido_id)
      }
    if(index==undefined){
      return 'partido no asignado'
    }
    else{
      return this.partidos[index].nombre
    }
    },
loadRegionesSanitarias(){
      this
        .makeCorsRequest('https://api-referencias.proyecto2018.linti.unlp.edu.ar/region-sanitaria')
        .then(response => {
          this.regionesSanitarias=response;
          this.regionesLoading=false;
        })
        .catch(error => {
          console.log(error)
        })
    },
  getRegionSanitaria(patient){
      if(this.regionesLoading==false){
      var index = this.regionesSanitarias.findIndex(obj => obj.id==patient.region_sanitaria_id)
      }
    if(index==undefined){
      return 'región no asignada'
    }
    else{
      return this.regionesSanitarias[index].nombre
    }
    },
 loadLocalidades(){
      this
        .makeCorsRequest('https://api-referencias.proyecto2018.linti.unlp.edu.ar/localidad')
        .then(response => {
          this.localidades=response;
          this.localidadesLoading=false;
        })
        .catch(error => {
          console.log(error)
        })
    },
    getLocalidad(patient){
      if(this.localidadesLoading==false){
      var index = this.localidades.findIndex(obj => obj.id==patient.localidad_id)
      }
    if(index==undefined){
      return 'localidad no asignada'
    }
    else{
      return this.localidades[index].nombre
    }
    },
   loadDocTypes(){
      this
        .makeCorsRequest('https://api-referencias.proyecto2018.linti.unlp.edu.ar/tipo-documento')
        .then(response => {
          this.docTypes= response;
          this.docTypesLoading=false;
        })
        .catch(error => {
          console.log(error)
        })
    },
     getDocType(patient){
      if(this.docTypesLoading==false){
      var index = this.docTypes.findIndex(obj => obj.id==patient.tipo_doc_id)
      }
    if(index==undefined){
      return 'no asignado'
    }
    else{
      return this.docTypes[index].nombre
    }
    },
  loadObrasSociales(){
      this
        .makeCorsRequest('https://api-referencias.proyecto2018.linti.unlp.edu.ar/obra-social')
        .then(response => {
          this.obrasSociales=response;
          this.obrasSocialesLoading=false;
        })
        .catch(error => {
          console.log(error)
        })
    },
    getObraSocial(patient){
      if(this.obrasSocialesLoading==false){
      var index = this.obrasSociales.findIndex(obj => obj.id==patient.obra_social_id)
      }
    if(index==undefined){
      return 'obra social no asignada'
    }
    else{
      console.log(this.obrasSociales[index])
      return this.obrasSociales[index].nombre
    }
    },
  getFormattedDate(date) {
    return [date.getDate(), date.getMonth()+1, date.getFullYear()]
      .map(n => n < 10 ? `0${n}` : `${n}`).join('/');
},
  },
  computed: {
    rowsPerPage() {
      return this.$root.config.paginado
    }
  }
};
</script>
</script>
