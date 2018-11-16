$(document).ready(function(){
  $("#reporte_motivo").on('click', function(){
  	$.ajax({
  		url: "./?action=reporte_motivo_show",
  		method: "GET",
      dataType: "json",
      success: function(data){
        var chart = new CanvasJS.Chart("chartContainer", {
            	animationEnabled: true,
            	title: {
            		text: "Atenciones por motivo"
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
        $('#tabla thead tr').empty();
        $('#tabla tbody').empty();
        $("#tabla thead tr").append("<th>" + "Motivo" + "</th>");
        $("#tabla thead tr").append("<th>" + "Cantidad de atenciones" + "</th>");
        $.each(data,function(i, item){
          $("#tabla tbody").append("<tr><td>" + item.label + "</td><td>" + item.cant + "</td></tr>");
        });
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
        var chart = new CanvasJS.Chart("chartContainer", {
            	animationEnabled: true,
            	title: {
            		text: "Atenciones por género"
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
        $('#tabla thead tr').empty();
        $('#tabla tbody').empty();
        $("#tabla thead tr").append("<th>" + "Género" + "</th>");
        $("#tabla thead tr").append("<th>" + "Cantidad de atenciones" + "</th>");
        $.each(data,function(i, item){
          $("#tabla tbody").append("<tr><td>" + item.label + "</td><td>" + item.cant + "</td></tr>");
        });
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
        var chart = new CanvasJS.Chart("chartContainer", {
            	animationEnabled: true,
            	title: {
            		text: "Atenciones por localidad"
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
        $('#tabla thead tr').empty();
        $('#tabla tbody').empty();
        $("#tabla thead tr").append("<th>" + "Localidad(id por ahora)" + "</th>");
        $("#tabla thead tr").append("<th>" + "Cantidad de atenciones" + "</th>");
        $.each(data,function(i, item){
          $("#tabla tbody").append("<tr><td>" + item.label + "</td><td>" + item.cant + "</td></tr>");
        });
        }
      });
    });
});
