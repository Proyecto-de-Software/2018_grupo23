window.onload = function() { //funcion que evita el resubmit de un mismo form
  history.replaceState("", "", "./?action=usuario_index");
}

//para validar addUserForm
$(document).ready(function () {
  $('#addUserForm').validate({ // initialize the plugin
  });
});


//para el modal de ver un usuario
$('#tabla').on("click",".button_view",function(){
  var id_usuario= $(this).closest('tr').find('.user_id').val();
  $.ajax({
    method: "POST",
    url: "./?action=usuario_show",
    data: {id: id_usuario}
  })
    .done(function(user){
      var u= $.parseJSON(user);
      $('header').append("<p class='modal-card-title'>Ver usuario</p>");
      $('#view_apellido').val(u['user'][0].last_name);
      $('#view_nombre').val(u['user'][0].first_name);
      $('#view_email').val(u['user'][0].email);
      $('#view_username').val(u['user'][0].username);
      $('#view_created').val(u['user'][0].created_at);
      $('#view_updated').val(u['user'][0].updated_at);
      ((u['user'][0].activo) = 1) ? $('#view_activo').val('Activo') : $('#view_activo').val('Bloqueado');
      $.each(u['roles'],function(index,item){
          $('<tr>').append($('<td>').text(item.rol)).appendTo("#tbody_roles");
      });
      if ((u['roles']).length == 0) {
          $('<tr>').append($('<td>').text('Sin roles asignados')).appendTo("#tbody_roles");
      }
    });
    $('#viewUser').addClass('is-active');
});

$('.modal-close, #close').on("click",function(){
  $('#viewUser').removeClass('is-active');
  $('#tbody_roles').empty();
  $('header').empty();
});

//funcion boton editar
$('#tabla').on("click",".button_edit",function(){
  var id_usuario= $(this).closest('tr').find('.user_id').val();
  $.ajax({
    method: "POST",
    url: "./?action=usuario_show",
    data: {id: id_usuario}
  })
    .done(function(user){
      var u = $.parseJSON(user);
      $('header').append("<p class='modal-card-title'>Editar usuario</p>");
      $('#addUserForm').attr('action', './?action=usuario_update');
      $('#user_id').val(u['user'][0].id);
      $('#apellido').val(u['user'][0].last_name);
      $('#nombre').val(u['user'][0].first_name);
      $('#email').val(u['user'][0].email);
      $('#username').val(u['user'][0].username);
      $('#roles_div .roles-label').text('¿Qué roles desea asignarle?');
      var roles_box_values= $("input[name='roles[]']").map(function(){
        return $(this).val();
      }).get();
      $.each(u['roles'],function(index,item){
        if (roles_box_values.includes(item.rol.toString())){
          $('#roles_box input[value="' + item.rol.toString() + '"]').prop('checked', true);
        }
        else{
          $('#roles_box input[value="' + item.rol.toString() + '"]').prop('checked', false);
        }
      });
      $('#password').val(u['user'][0].password);
      $('#re_password').val(u['user'][0].password);
    });
    $('#addUser').addClass('is-active');
});

//para el modal de bloquear usuario
$('#tabla').on("click",".button_block",function(){
  $("#blockModal").addClass('is-active');
  $('#blockModal header').append("<p class='modal-card-title'>Bloquear usuario</p>");
  $('#blockModal .modal-card-body').append("<div class='box'><p>¿Desea bloquear al usuario?</p><p>No podrá acceder al sistema hasta que vuelva a activarlo</p></div>");
  var id_user= $(this).closest('tr').find('.user_id').val();
  $('#usuario_block').val(id_user);
});

$('#tabla').on("click",".button_unblock",function(){
  $("#blockModal").addClass('is-active');
  $('#blockModal header').append("<p class='modal-card-title'>Desbloquear usuario</p>");
  $('#blockModal .modal-card-body').append("<div class='box'><p>¿Desea desbloquear al usuario?</p></div>");
  var id_user= $(this).closest('tr').find('.user_id').val();
  $('#usuario_block').val(id_user);
});

$('#blockModal .modal-close, #blockModal #closed').on("click",function(){
  $('#blockModal .modal-card-body').empty();
  $('#blockModal').removeClass('is-active');
  $('header').empty();
  $('#blockUserForm').reset();
});

//modal de agregar usuario
$('#showAddUser').on("click",function(){
  $('header').append("<p class='modal-card-title'>Agregar usuario</p>");
  $('#addUserForm').attr('action', './?action=usuario_new');
  $('#roles_div .roles-label').text('¿Qué roles desea asignarle? Si no asigna ninguno podrá hacerlo en "editar"');
  $('#addUser').addClass('is-active');
});

$('#addUser .modal-close, #addUser #cancel').on("click",function(){
  $('#addUser').removeClass('is-active');
  $('#addUser #roles_box :checkbox').prop('checked',false);
  $("#addUser input[type!='checkbox']").val('');
  $('header').empty();
});

//modal delete usuario
$('#tabla').on("click",".button_delete",function(){
  $('header').append("<p class='modal-card-title'>Eliminar usuario</p>");
  $("#deleteModal").addClass('is-active');
  var id_user= $(this).closest('tr').find('.user_id').val();
  $('#usuario_destroy').val(id_user);
});

$('#deleteModal .modal-close, #deleteModal #closed').on("click",function(){
  $('#deleteModal').removeClass('is-active');
  $('header').empty();
  $('#deleteUserForm').reset();
});
