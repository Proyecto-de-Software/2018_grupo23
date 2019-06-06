<template>

  <div>
      <div class="box">
      <section class="section">
      <div class="container" ref="container">
          <div id="app">
            <l-map :zoom="zoom" :center="center">
             <l-tile-layer :url="url" :attribution="attribution"></l-tile-layer>
              <l-marker :lat-lng="marker"></l-marker>
          </l-map>
        </div>
          <div v-if="!contentIsReady" class="has-text-centered">
            <a class="button is-loading page-loading-button "></a>
          </div>
          <div v-else>
            <vue-good-table
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
              <div slot="emptystate" class="has-text-centered">
                 <h3 class="h3">No hay atenciones cargadas en el sistema</h3>
               </div>
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

<style scoped>
  #app {
  height: 100%;
  margin: 0;
  }
</style>

<script>

import Vue from 'vue'
import ViewAttentionModal from './ViewAttentionModal.vue';
import AddAttentionModal from './AddAttentionModal.vue';

export default {
    components: { ViewAttentionModal, AddAttentionModal},
    data(){
        return {
          attentions: null,
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
        zoom:13,
        center: L.latLng(47.413220, -1.219482),
        url:'http://{s}.tile.osm.org/{z}/{x}/{y}.png',
        attribution:'&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
        marker: L.latLng(47.413220, -1.219482),
        acompanamientos: null,
        instituciones: null,
        motivos: null,
        tratamientos: null,
        map: null,
      };
    },
    created(){
      this.loadAttentions();
      this.loadAcompanamientos();
      this.loadInstituciones();
      this.loadMotivos();
      this.loadTratamientos();
      
    },
    mounted(){
       //this.loadMap();

    },
    methods: {
      loadAttentions: function() {
        axios
          .get('http://localhost:8000/consulta/index/'+this.$route.params.idPaciente)
          .then(response => {
            this.attentions = response.data;
            //this.loadMapPoints();
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
        if(attention.derivacion != undefined){
          return attention.derivacion['nombre']
        }
        else
          return 'no derivado'
      },
      deleteAttention(attentionId) {
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
              .delete('http://localhost:8000/consulta/' + attentionId)
              .then(response => {
                Vue.swal(
                  'La atención fue eliminada',
                  '',
                  'success'
                )
                this.loadAttentions()
              })
              .catch(error => {
                console.log(error)
            });
          }
        })
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
      showAddAttentionModal(attentionData) {
        var ComponentClass = Vue.extend(AddAttentionModal);
        var instance = new ComponentClass({
          propsData: {
            attention: attentionData,
            loadAttentions: this.loadAttentions,
            getFormattedDate: this.getFormattedDate,
            acompanamientos: this.acompanamientos,
            instituciones: this.instituciones,
            motivos: this.motivos,
            tratamientos: this.tratamientos,
            idPaciente: this.$route.params.idPaciente,
            }
        })
        instance.$mount()
        this.$refs.container.appendChild(instance.$el)
      },

      getFormattedDate(date) {
      return [date.getDate(), date.getMonth()+1, date.getFullYear()]
        .map(n => n < 10 ? `0${n}` : `${n}`).join('/');
      },

      loadAcompanamientos(){
          axios
          .get('http://localhost:8000/consulta/acompanamientos')
          .then(response => {
            this.acompanamientos=response.data;
          })
          .catch(error => {
            console.log(error)
          })
      },
      loadInstituciones(){
          axios
          .get('http://localhost:8000/consulta/instituciones')
          .then(response => {
            this.instituciones=response.data;
          })
          .catch(error => {
            console.log(error)
          })
      },
      loadMotivos(){
          axios
          .get('http://localhost:8000/consulta/motivos')
          .then(response => {
            this.motivos=response.data;
          })
          .catch(error => {
            console.log(error)
          })
      },
      loadTratamientos(){
          axios
          .get('http://localhost:8000/consulta/tratamientos')
          .then(response => {
            this.tratamientos=response.data;
          })
          .catch(error => {
            console.log(error)
          })
      },
      loadMap(){
        this.map = LMap.map('mapid',{ zoomControl:false }).setView([-34.9213561,-57.9545116],11);
        LMap.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 18}).addTo(this.map);
        //L.marker([-34.93621,-57.97242],{draggable: false}).addTo(map);
        L.Icon.Default.imagePath = 'assets/img/images'
      },
      loadMapPoints(){
        this.attentions.forEach(atencion => {
          var aux=LMarker.marker(JSON.parse(atencion.derivacion.coordenadas),
          {draggable: false, title: atencion.derivacion.nombre, title: atencion.derivacion.nombre})
          .addTo(this.map);
          console.log(aux)
        });
        //$.each(a,function(i,v){
            //L.marker(JSON.parse(v.coordenadas),{draggable: false, title: v.nombre, title: v.nombre}).addTo(map);
          //});
      },

    },
    computed: {
      rowsPerPage() {
        return this.$root.config.paginado
      },
      contentIsReady() {
        return (this.attentions && this.acompanamientos && this.instituciones && this.motivos && this.tratamientos)
      }
    },
  }

</script>
