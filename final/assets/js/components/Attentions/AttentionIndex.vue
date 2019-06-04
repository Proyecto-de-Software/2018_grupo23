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
              v-if="attentions && attentions.length"
              :columns="columns"
              :rows="attentions"
              :lineNumbers="true"
              :defaultSortBy="{field: 'motivo', type: 'asec'}"
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
                 rowsPerPageLabel: 'Atenciones por tabla',
                 ofLabel: 'de',
               }"
              :search-options="{ enabled: true, placeholder: 'Buscar' }"
               styleClass="vgt-table bordered">
              <div slot="table-actions">
                <button type="button" class="button is-info" @click="showAddAttentionModal(null, 'Agregar Atencion')">Agregar Atención</button>
              </div>
              <template slot="table-row" slot-scope="props">
                <span v-if="props.column.field == 'acciones'">
                  <button v-if="loggedUser.permisos.includes('atencion_update')" type="button" class="button is-info is-small button-is-spaced" title="Editar" @click="showAddAttentionModal(props.row, 'Editar atencion')">Editar</button>
                  <button v-if="loggedUser.permisos.includes('atencion_show')" type="button" class="button is-info is-small button-is-spaced" title="Ver" @click="showViewAttentionModal(props.row)">Ver</button>
                  <button v-if="loggedUser.permisos.includes('atencion_destroy')" class="button_delete button is-danger is-small button-is-spaced" title="Eliminar" @click="deleteAttention(props.row.id)">Eliminar</button>
                  
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
import Vue from 'vue'
import ViewAttentionModal from './ViewAttentionModal.vue';
export default {
    components: { ViewAttentionModal},
    data(){
        return {
        attentions: Array,
        isLoading: true,
        columns: [
        {
          label: 'Fecha',
          field: this.attentionDate,
          filterable: true
        },
        {
          label: 'Motivo',
          field: this.attentionMotive,
        },
        {
          label: 'Internación',
          field: this.attentionInternation
        },
        {
          label: 'Derivación',
          field: this.attentionDerivation
        },
        {
          label: 'Acciones',
          field: 'acciones',
        },
      ],
        };
    },
    
    created(){
        this.loadAttentions();
    },
    methods: {
    loadAttentions: function() {
      axios
        .get('http://localhost:8000/consulta/index/'+this.$route.params.idPaciente)
        .then(response => {
          this.attentions = response.data;
          console.log(this.attentions) //borrar después
          this.isLoading = false
        })
        .catch(error => {
          console.log(error)
        })
    },
    attentionDate(attention){
      return new Date(attention.fecha).toString()
    },
    attentionMotive(attention){
      return attention.motivo['nombre']
    },
    attentionInternation(attention){
      return (attention.internacion? 'Sí' : 'No')
    },
    attentionDerivation(attention){
      return attention.derivacion['nombre']
    },
    showViewAttentionModal(attentionData) {
      var ComponentClass = Vue.extend(ViewAttentionModal);
      var instance = new ComponentClass({
        propsData: {
          attention: attentionData,
          getFormattedDate: this.getFormattedDate,
          }
      })
      instance.$mount()
      this.$refs.container.appendChild(instance.$el)
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
  },
    }

</script>
