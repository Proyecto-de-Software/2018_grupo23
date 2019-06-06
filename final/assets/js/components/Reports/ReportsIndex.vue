<template>
  <div class="box">

    <section class="section">
      <div class="container is-fluid">
        <div class="has-text-centered">
          <h4 class="title is-4">Gráfico y listado de atenciones</h4>
          <h6 class="title is-6">(seleccione una opción)</h6>
          <button class="button is-info is-spaced" type="button" @click="getAttentionsBy('motivo')">Motivo</button>
          <button class="button is-info is-spaced" type="button" @click="getAttentionsBy('genero')">Género</button>
          <button class="button is-info is-spaced" type="button" @click="getAttentionsBy('localidad')">Localidad</button>
        </div>
      </div>
    </section>

    <div class="has-text-centered" v-if="isEmptyData">
      <h5 class="title is-5">No hay consultas en el sistema</h5>
    </div>

    <div v-if="!loaded" class="has-text-centered">
      <a class="button is-loading page-loading-button"></a>
    </div>
    <div v-else>
      <div v-if="loaded" class="container" ref="content">
        <div ref="chart-container">
          <pie-chart
            :chartdata="chartdata"
            :options="options"/>
          </pie-chart>
        </div>
        <section class="section">
          <div v-if="loaded" class="columns is-centered">
            <div class="column is-narrow">
              <table class="table is-bordered" ref="table">
                <thead>
                  <tr>
                    <th>{{ tabledata.heading }}</th>
                    <th>Cantidad</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="row in tabledata.rows">
                      <td>{{ row.label }}</td>
                      <td>{{ row.total }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>
      <div v-if="loaded" class="has-text-centered">
        <button class="button is-info" type="button" @click="downloadPdf">Descargar</button>
      </div>
    </div>


  </div>
</template>

<script>
import PieChart from './PieChart.vue'
import jsPDF from 'jspdf'
import html2canvas from "html2canvas"
export default {
  components: { PieChart },
  data() {
    return {
          chartdata: {
              labels: [],
              datasets: [
                {
                  label: 'Data',
                  backgroundColor: [],
                  data: []
                }
              ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false
          },
          tabledata: {
            heading: '',
            rows: []
          },
          loaded: false,
          isEmptyData: false,
          locations: []
    }
  },
  created() {
    this.loadLocationsFromApi()
  },
  methods: {
    loadLocationsFromApi() {
      this.makeCorsRequest('https://api-referencias.proyecto2018.linti.unlp.edu.ar/localidad')
      .then(response => this.locations = response)
    },
    getAttentionsBy(criteriaStr) {
      this.loaded = false;
      axios.
       get(this.url('/consulta/reportes/' + criteriaStr))
       .then(response => {
                           var result = response.data;
                           if (result.length > 0) {
                             this.tabledata.heading = criteriaStr.charAt(0).toUpperCase() + criteriaStr.slice(1)
                             this.removeChartAndTableData();
                             this.addChartAndTableData(result, criteriaStr);
                             this.loaded = true
                           } else {
                             this.isEmptyData = true
                           }
                         }
        )
    },
    getLocationName(locationId) {
      var index = this.locations.findIndex(loc => loc.id == locationId)
      return this.locations[index].nombre
    },
    addChartAndTableData(data, criteria) {
      for (var i = 0; i < data.length; i++) {
        if (criteria == 'localidad') {
          data[i].nombre = this.getLocationName(data[i].id)
        }
        this.chartdata.labels.push(data[i].nombre);
        this.tabledata.rows.push({ label: data[i].nombre, total: data[i].cant })
        this.chartdata.datasets.forEach((dataset) => {
          dataset.backgroundColor.push(this.getRandomColor());
          dataset.data.push(data[i].cant)
        });
      }
    },
    removeChartAndTableData() {
      this.chartdata.labels = [];
      this.tabledata.rows = [];
      this.chartdata.datasets.forEach((dataset) => {
          dataset.data = [];
          dataset.backgroundColor = []
      });
    },
    getRandomColor() {
      var letters = '0123456789ABCDEF';
      var color = '#';
      for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
      }
      return color;
    },
    downloadPdf() {
      const doc = new jsPDF();
      /** WITH CSS */
      var canvasElement = document.createElement('canvas');
      html2canvas(this.$refs.content, { canvas: canvasElement
          }).then(function (canvas) {
                                      const img = canvas.toDataURL("image/png");
                                      doc.addImage(img, 'JPEG', -50, 20);
                                      doc.save("reporte.pdf");
                                    });
     }
  }
}
</script>

<style scoped>

.is-spaced {
  margin: 10px;
}

</style>
