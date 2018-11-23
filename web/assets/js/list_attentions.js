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
        // agregar todo lo que tiene el modal
      } else {
        showNotAvailable();
      }
      hideLoading();
    });
});
