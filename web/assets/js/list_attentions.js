window.onload = function() { //funcion que evita el resubmit de un mismo form
  history.replaceState("", "", "./?action=atencion_index");
};

function isJsonString(str) {
  try {
    JSON.parse(str);
  } catch (e) {
    return false;
  }
  return true;
}



$('#showAddAttention').on("click", function() {
  $('#addAttention').addClass('is-active');
});

$('#showMap').on("click", function() {
  $('#modalMap').addClass('is-active');
});

//cerrar form add
$('.modal-close, #cancel').on("click", function() {
  $('#addAttention').removeClass('is-active');
  $('#formAddAttention').attr('action', './?action=atencion_new');
  token = $('#tokenAdd').val();
  $('#formAddAttention')[0].reset();
  $('#tokenAdd').val(token);
});

function fillModal(a,modaltag){
  $(modaltag+', #motivo').val(a[0].motivo_id);
  $(modaltag+', #derivacion').val(a[0].derivacion_id);
  $(modaltag+', #articulacion').val(a[0].articulacion_con_instituciones);
  $(modaltag+', #internacion').val(a[0].internacion);
  $(modaltag+', #diag').val(a[0].diagnostico);
  $(modaltag+', #obs').val(a[0].observaciones);
  $(modaltag+', #trat').val(a[0].tratamiento_farmacologico_id);
  $(modaltag+', #acomp').val(a[0].acompanamiento_id);
  $(modaltag+', #id_at').val(a[0].id);
  $(modaltag).addClass('is-active');
}
function getUserDataForModal(esto,modaltag){
  showLoading();
  var id_atencion = $(esto).closest('tr').find('.a_id').val();
  console.log(id_atencion);
  $.ajax({
      method: "POST",
      url: "./?action=atencion_show",
      data: {
        id: id_atencion
      }
    })
    .done(function(atencion) {
      if (isJsonString(atencion)) {
        var a = JSON.parse(atencion);
        console.log(a[0].id);
        fillModal(a,modaltag);
      } else {
        showNotAvailable();
      }
      hideLoading();
    });
}
$(document).ready(function() {
  var map = L.map('map',{ zoomControl:false }).setView([-34.93621,-57.97242],15);
  L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>', maxZoom: 18}).addTo(map);
  L.marker([-34.93621,-57.97242],{draggable: false}).addTo(map);
$('#tabla').on("click",".button_v",function(){
  getUserDataForModal($(this),'#viewAttention');
});


$('.modal-close, #close').on("click", function() {
  $('#viewAttention').removeClass('is-active');
  $('#viewAttention, #motivo').val("1");
  $('#viewAttention, #derivacion').val("");
  $('#viewAttention, #articulacion').val("");
  $('#viewAttention, #internacion').val("1");
  $('#viewAttention, #diag').val("");
  $('#viewAttention, #obs').val("");
  $('#viewAttention, #trat').val("1");
  $('#viewAttention, #acomp').val("1");
  $('#viewAttention, #id_at').val("");
});


$('.modal-close, #cancel').on("click", function() {
  $('#editAttention').removeClass('is-active');
  $('#formEditAttention').attr('action', './?action=atencion_update');
  token = $('#tokenEdit').val();
  $('#formEditAttention')[0].reset();
  $('#tokenEdit').val(token);
});

$('#tabla').on("click",".button_e",function(){
  getUserDataForModal($(this),'#editAttention');
});


$('.modal-close, #cancel').on("click", function() {
  $('#deleteAttention').removeClass('is-active');
});

$('#tabla').on("click",".button_d",function(){
    var id_at = $(this).closest('tr').find('.a_id').val();
    $('#deleteAttention, #a_destroy').val(id_at);
    $('#deleteAttention').addClass('is-active');
});

});
