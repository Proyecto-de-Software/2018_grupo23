<template>
  <div class="modal is-active">
    <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <h3 class="title">Ver Institución</h3>
        </header>
          <section class="modal-card-body">
              <div class="text-box">
                <h4 class="title is-5">Nombre</h4>
                <p class="subtitle is-6">{{ inst.nombre }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Director</h4>
                <p class="subtitle is-6">{{ inst.director }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Teléfono</h4>
                <p class="subtitle is-6">{{ inst.telefono }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Tipo de institución</h4>
                <p class="subtitle is-6">{{ inst.tipo_institucion.nombre }}</p>
              </div>
              <div class="text-box">
                <h4 class="title is-5">Región sanitaria</h4>
                <p class="subtitle is-6">{{ this.getRegionSanitaria(inst.region_sanitaria_id) }}</p>
              </div>
               <div class="text-box">
                <h4 class="title is-5">Ubicación geográfica</h4>
              </div>
              <div class="container" id="mapcontainer" ref="container">
                <l-map :zoom="zoom" :center="center" :options="{ zoomControl: false, minZoom: 10 }" ref="minimap"> <!-- el mapa -->
                  <l-tile-layer :url="url" :attribution="attribution"></l-tile-layer> <!-- estos son las imagenes del mapa -->
                  <l-marker v-for="item in markers" :key="item.id" :lat-lng="item.latlng" :content="item.content"></l-marker> <!-- este es un marcador en el mapa -->
                </l-map>
                </div>
              
          </section>
        <footer class="modal-card-foot">
          <button type="button" class="button is-success" @click="close">Cerrar</button>
        </footer>
        <button class="modal-close" @click="close"></button>
      </div>
    </div>
</template>


<script>
import Vue from 'vue'
import {LMap, LTileLayer, LMarker} from 'vue2-leaflet';
export default {
  components: {LMap, LTileLayer, LMarker},
  props: {
    inst: Object,
    getRegionSanitaria: Function,
    
  },
  data() {
    return {
      zoom:11,
        center: L.latLng(-34.9213561,-57.9545116),
        url:'http://{s}.tile.osm.org/{z}/{x}/{y}.png',
        attribution:'&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
        /* EN ESTE ARRAY SE METEN TODOS LOS MARCADORES */
        // habria que hacer un metodo que a medida que carga los marcadores le haga push al array con los datos
        markers: [],
    }
  },
  created(){
    setTimeout(() => {
        this.$refs.minimap.mapObject.invalidateSize();
      }, 100);
    this.markers.push({
            latlng: JSON.parse(this.inst.coordenadas),
          })
  },
  methods: {
    close() {
      this.$destroy();
      this.$el.parentNode.removeChild(this.$el);
    },
  
    
  }
}
</script>

<style scoped>
  .text-box {
    margin-bottom: 15px;
  }

#mapcontainer {
  height: 250px;
  width: 100%;
  display: block;
}

</style>
