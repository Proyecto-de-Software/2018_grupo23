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
$('#tabla').on("click",".button_v",function(){
  getUserDataForModal($(this),'#viewAttention');
});


$('.modal-close, #close').on("click", function() {
  $('#viewAttention').removeClass('is-active');
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

$('#tabla').on("click", ".button_d", function() {
  $("#deleteAttention").addClass('is-active');
  var id_at = $(this).closest('tr').find('.a_id').val();
  $('#a_destroy').val(id_at);

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
