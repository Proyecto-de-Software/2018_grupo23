window.onload = function() { //funcion que evita el resubmit de un mismo form
  id_paciente= $('#id_paciente').val();
  history.replaceState("", "", "./?action=atencion_index&id_paciente="+id_paciente);
};

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
