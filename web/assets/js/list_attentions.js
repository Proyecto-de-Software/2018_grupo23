$('#showAddAttention').on("click", function() {
  $('#addAttention').addClass('is-active');
});

//cerrar form add
$('.modal-close, #cancel').on("click", function() {
  $('#addAttention').removeClass('is-active');
  $('#formAddAttention').attr('action', './?action=attention_new');
  token = $('[name=token]').val();
  $('#formAddAttention')[0].reset();
  $('[name=token]').val(token);
});
