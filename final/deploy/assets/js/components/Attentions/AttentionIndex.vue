<template>
  <div class="container margin-global">
    <div v-if="!contentIsReady" class="has-text-centered">
      <a class="button is-loading page-loading-button"></a>
    </div>
    <div v-else>

      <div class="container" id="mapcontainer" ref="container">
          <l-map id="map" :zoom="zoom" :center="center" :options="{ zoomControl: false, minZoom: 10 }"> <!-- el mapa -->
            <l-tile-layer :url="url" :attribution="attribution"></l-tile-layer> <!-- estos son las imagenes del mapa -->
            <l-marker v-for="item in markers" :key="item.id" :lat-lng="item.latlng" :content="item.content">
              <l-popup :content="item.content"></l-popup>
            </l-marker> <!-- este es un marcador en el mapa -->
          </l-map>
      </div>

      <div>
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
  </div>
</template>

<script>

import Vue from 'vue'
import ViewAttentionModal from './ViewAttentionModal.vue';
import AddAttentionModal from './AddAttentionModal.vue';
import {LMap, LTileLayer, LMarker, LPopup} from 'vue2-leaflet';

export default {
    components: { ViewAttentionModal, AddAttentionModal, LMap, LTileLayer, LMarker, LPopup },
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
        zoom:11,
        center: L.latLng(-34.9213561,-57.9545116),
        url:'http://{s}.tile.osm.org/{z}/{x}/{y}.png',
        attribution:'Lugares a los que fue derivado el paciente',
        marker: L.latLng(-34.93621,-57.97242), //esto es viejo
        /* EN ESTE ARRAY SE METEN TODOS LOS MARCADORES */
        // habria que hacer un metodo que a medida que carga los marcadores le haga push al array con los datos
        markers: [],
        /********************************** */
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
       setTimeout(function() { window.dispatchEvent(new Event('resize')) }, 250);
    },
    methods: {
      loadAttentions: function() {
        axios
          .get('http://localhost:8000/consulta/index/'+this.$route.params.idPaciente)
          .then(response => {
            this.attentions = response.data;
            this.loadMapPoints();
          })
          .catch(error => {
            console.log(error)
          })
      },
      attentionDate(attention){
        return this.getFormattedDate(new Date(attention.fecha))
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
          }
        })
        instance.$mount()
        this.$refs.container.appendChild(instance.$el)
      },
      showAddAttentionModal(attentionData, modalTitle) {
        var ComponentClass = Vue.extend(AddAttentionModal);
        var instance = new ComponentClass({
          propsData: {
            title: modalTitle,
            attention: attentionData,
            loadAttentions: this.loadAttentions,
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
      loadMapPoints(){
        this.attentions.forEach(atencion => {
          var coords=JSON.parse(atencion.derivacion.coordenadas)
          this.markers.push({
            latlng: L.latLng(coords[0],coords[1]),
            content: atencion.derivacion.nombre
          })
        });
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

<style scoped>

#map {
  z-index: 1;
}

#mapcontainer {
  height: 250px;
  width: 100%;
  display: block;
}

</style>
