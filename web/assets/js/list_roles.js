window.onload = function() { //funcion que evita el resubmit de un mismo form
  history.replaceState("", "", "./?action=rol_index");
};

//modal de agregar
$('#showAddRole').on("click",function(){
  showLoading();
  $('header').append("<p class='modal-card-title'>Agregar rol</p>");
  $('#addRoleForm').attr('action', './?action=rol_new');
  $('#perms_title').text('¿Qué permisos desea asignarle? Si no asigna ninguno podrá hacerlo en "editar"');
  hideLoading();
  $('#addRole').addClass('is-active');
});

$('#addRole .modal-close, #addRole #cancel').on("click",function(){
  $('#addRole').removeClass('is-active');
  $('#addRoleForm :checkbox').prop('checked',false);
  var token= $("#addRoleForm #token").val();
  $("#addRoleForm input[type!='checkbox']").val('');
  $("#addRoleForm input[id='token']").val(token);
  $('#addRoleForm input').removeClass('is-warning is-success');
  $('label.error').remove();
  $('header').empty();
});

//para el modal de ver
$('#tabla').on("click",".button_view",function(){
  showLoading();
  var id_rol= $(this).closest('tr').find('.rol_id').val();
  $.ajax({
    method: "POST",
    url: "./?action=rol_show",
    data: { id: id_rol }
  })
    .done(function(response){
      var rol= $.parseJSON(response);
      $('header').append("<p class='modal-card-title'>Ver rol</p>");
      $('#view_nombre').val(rol['rol'][0].nombre);
      $.each(rol['perms'],function(index,item){
          $('<tr>').append($('<td>').text(item.permiso)).appendTo("#tbody_perms");
      });
      if ((rol['perms']).length == 0) {
          $('<tr>').append($('<td>').text('Sin permisos asignados')).appendTo("#tbody_perms");
      }
      hideLoading();
    });
    $('#viewRoleModal').addClass('is-active');
});

$('#viewRoleModal .modal-close, #viewRoleModal #close').on("click",function(){
  $('#viewRoleModal').removeClass('is-active');
  $('#tbody_perms').empty();
  $('header').empty();
});

//función boton editar
$('#tabla').on("click",".button_edit",function(){
  showLoading();
  var id_rol= $(this).closest('tr').find('.rol_id').val();
  $.ajax({
    method: "POST",
    url: "./?action=rol_show",
    data: { id: id_rol }
  })
    .done(function(response){
      var rol= $.parseJSON(response);
      $('header').append("<p class='modal-card-title'>Editar rol</p>");
      $('#nombre').val(rol['rol'][0].nombre);
      $('#rol_id').val(rol['rol'][0].id);
      $('#addRoleForm').attr('action', './?action=rol_update');
      $('#perms_title').text('¿Qué permisos desea asignarle?');
      var perms_box_values= $("input[name='rol_perms[]']").map(function(){
        return $(this).val();
      }).get();
      $.each(rol['perms'],function(index,item){
        if (perms_box_values.includes(item.permiso.toString())){
          $('#pacient_perms_box input[value="' + item.permiso.toString() + '"]').prop('checked', true);
          $('#user_perms_box input[value="' + item.permiso.toString() + '"]').prop('checked', true);
          $('#rol_perms_box input[value="' + item.permiso.toString() + '"]').prop('checked', true);
          $('#config_perms_box input[value="' + item.permiso.toString() + '"]').prop('checked', true);
        }
        else{
          $('#pacient_perms_box input[value="' + item.permiso.toString() + '"]').prop('checked', false);
          $('#user_perms_box input[value="' + item.permiso.toString() + '"]').prop('checked', false);
          $('#rol_perms_box input[value="' + item.permiso.toString() + '"]').prop('checked', false);
          $('#config_perms_box input[value="' + item.permiso.toString() + '"]').prop('checked', false);
        }
      });
      hideLoading();
    });
    $('#addRole').addClass('is-active');
});


//modal delete
$('#tabla').on("click",".button_delete",function(){
  $('header').append("<p class='modal-card-title'>Eliminar rol</p>");
  $("#deleteRoleModal").addClass('is-active');
  var id_rol= $(this).closest('tr').find('.rol_id').val();
  $('#rol_destroy').val(id_rol);
});

$('#deleteRoleModal .modal-close, #deleteRoleModal #closed').on("click",function(){
  $('#deleteRoleModal').removeClass('is-active');
  $('header').empty();
  $('#deleteRoleForm').reset();
});
