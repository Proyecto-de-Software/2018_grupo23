$(document).ready(function(){
  $("#reporte_motivo").on('click', function(){
  	$.ajax({
  		url: "./?action=reporte_motivo_show",
  		method: "GET",
      dataType: "json",
      success:  function(data){
        if ($.isEmptyObject(data)) {
          alert("No hay consultas cargadas en el sistema");
        }
        else {
          showPieChart(data, "Atenciones por motivo" );
          refreshTableData(data, "Motivo");
        }
        }
    });
  });
});

$(document).ready(function(){
  $("#reporte_genero").on('click', function(){
  	$.ajax({
  		url: "./?action=reporte_genero_show",
  		method: "GET",
      dataType: "json",
      success: function(data){
          if ($.isEmptyObject(data)) {
            alert("No hay consultas cargadas en el sistema");
          }
          else {
            showPieChart(data, "Atenciones por género" );
            refreshTableData(data, "Género");
          }
        }
      });
    });
});

$(document).ready(function(){
  $("#reporte_localidad").on('click', function(){
  	$.ajax({
  		url: "./?action=reporte_localidad_show",
  		method: "GET",
      dataType: "json",
      success: function(data){
          if ($.isEmptyObject(data)) {
            alert("No hay consultas cargadas en el sistema");
          }
          else {
            showPieChart(data, "Atenciones por localidad del paciente" );
            refreshTableData(data, "Localidad");
          }
        }
      });
    });
});

function refreshTableData(data, tableTitle){
  $('#tabla thead tr').empty();
  $('#tabla tbody').empty();
  $("#button-box").empty();
  $("#tabla thead tr").append("<th>" + tableTitle + "</th>");
  $("#tabla thead tr").append("<th>" + "Cantidad de atenciones" + "</th>");
  $.each(data,function(i, item){
    $("#tabla tbody").append("<tr><td>" + item.label + "</td><td>" + item.cant + "</td></tr>");
  });
  $("#button-box").append('<button id="pdf" class="button is-info" type="button">Descargar</button>');
}

function showPieChart(data, titleText){
   var chart = new CanvasJS.Chart("chartContainer", {
         animationEnabled: true,
         title: {
           text: titleText
         },
         subtitles: [{
           text: "Contabilizadas al día de la fecha"
         }],
         data: [{
           type: "pie",
           yValueFormatString: "#,##0.00\"%\"",
           indexLabel: "{label} ({y})",
           dataPoints: data
         }]
       });
   chart.render();
};

$("#button-box").on('click', '#pdf', function(){
    var canvas = $("#chartContainer .canvasjs-chart-canvas").get(0);
    var dataURL = canvas.toDataURL();
    var pdf = new jsPDF();
    var specialElementHandlers = {
        '#editor': function (element, renderer) {
            return true;
        }
    };
    source = $('#content')[0];
    margins = {
            top: 120,
            left: 30,
            width: 100
        };
    pdf.fromHTML(
    source, // HTML string or DOM elem ref.
    margins.left, // x coord
    margins.top, { // y coord
        'width': margins.width, // max width of content on PDF
        'elementHandlers': specialElementHandlers
      });
    pdf.addImage(dataURL, 'JPEG', -10, 30, 230, 90); //addImage(image, format, x-coordinate, y-coordinate, width, height)
    pdf.save("reporte.pdf");
});
