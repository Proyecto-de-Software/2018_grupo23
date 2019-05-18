<template>
  <div>
      <div class="box">
    <section class="section">
      <div class="container" ref="container">
          <div v-if="isLoading">
            <h1 class="title">Cargando datos...</h1>
          </div>
          <div v-else>
            <vue-good-table
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
                  <button type="button" class="button is-info" title="Editar" @click="showAddPatientModal(props.row, 'Editar Paciente')">Editar</button>
                  <button type="button" class="button is-info" title="Ver" @click="showViewPatientModal(props.row)">Ver</button>
                  <button class="button_delete button is-danger" title="Eliminar" @click="deletePatient(props.row.id)">Eliminar</button>
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
import axios from 'axios';
import Vue from 'vue';
import AddPatientModal from './AddPatientModal.vue';
import ViewPatientModal from './ViewPatientModal.vue';
import swal from 'sweetalert2';
export default {
  name: 'PatientIndex',
  components: { AddPatientModal, ViewPatientModal },
  data() {
    return {
      patients: null,
      isLoading: true,
      appRoles: [],
      columns: [
        {
          label: 'Nombre completo',
          field: this.patientCompleteName,
          filterable: true
        },
        {
          label: 'Tipo',
          field: this.patientDocType,
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
    this.loadPatients()
  },
  methods: {
    loadPatients: function() {
      axios
        .get('http://localhost:8000/paciente/')
        .then(response => {
          this.patients = JSON.parse(response.data);
          console.log(this.patients)
          this.isLoading = false
        })
        .catch(error => {
          console.log(error)
        })
    },
    deletePatient(patientId) {
      swal.fire({
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
              swal.fire(
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
        propsData: { patient: patientData, loadPatients: this.loadPatients, title: modalTitle}
      })
      instance.$mount()
      this.$refs.container.appendChild(instance.$el)
    },
    showViewPatientModal(row) {
      var ComponentClass = Vue.extend(ViewPatientModal);
      var instance = new ComponentClass({
        propsData: { patient: row }
      })
      instance.$mount()
      this.$refs.container.appendChild(instance.$el)
    },
    patientCompleteName(patient) {
      return patient.apellido + ' ' + patient.nombre
    },
    patientDocType(patient){
      axios
        .get('https://api-referencias.proyecto2018.linti.unlp.edu.ar/tipo-documento/'+patient.tipoDocId,
         {
      method: 'GET',
      mode: 'no-cors',
      headers: {
        'Access-Control-Allow-Origin': '*',
        'Content-Type': 'application/json',
      },
    },)
        .then(response => {
          docType = JSON.parse(response.data);
          console.log(docType)
        })
        .catch(error => {
          console.log(error)
        })
    },
    patientDocNum(patient){
      return patient.numero
    },
    patientClinicHistoryNumber(patient){
      return patient.id
    }
  },
  computed: {
    rowsPerPage() {
      return this.$root.config.paginado
    }
  }
};
</script>
</script>
