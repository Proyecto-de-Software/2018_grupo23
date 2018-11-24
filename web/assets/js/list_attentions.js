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
  token = $('[name=token]').val();
  $('#formAddAttention')[0].reset();
  $('[name=token]').val(token);
});
$(document).ready(function() {
$('#tabla').on("click",".button_v",function(){
  showLoading();
  var id_atencion = $(this).closest('tr').find('.a_id').val();
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
        console.log(a);
        $('#viewAttention, #motivo').val(a[0].motivo_id);
        $('#viewAttention, #derivacion').val(a[0].derivacion_id);
        $('#viewAttention, #articulacion').val(a[0].articulacion_con_instituciones);
        $('#viewAttention, #diag').val(a[0].diagnostico);
        $('#viewAttention, #obs').val(a[0].observaciones);
        $('#viewAttention, #trat').val(a[0].tratamiento_farmacologico_id);
        $('#viewAttention, #acomp').val(a[0].acompanamiento_id)
        $('#viewAttention').addClass('is-active');
      } else {
        showNotAvailable();
      }
      hideLoading();
    });
});
$('.modal-close, #close').on("click", function() {
  $('#viewAttention').removeClass('is-active');
});
});
