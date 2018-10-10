$('#tabla').on("click",".button_view",function(){
  var id_usuario= $(this).closest('tr').find('.user_id').val();
  $.ajax({
    method: "POST",
    url: "./?action=usuario_show",
    data: {id: id_usuario}
  })
    .done(function(user){
      var u= $.parseJSON(user);
      $('#view_apellido').val(u['user'][0].last_name);
      $('#view_nombre').val(u['user'][0].first_name);
      $('#view_email').val(u['user'][0].email);
      $('#view_username').val(u['user'][0].username);
      $('#view_created').val(u['user'][0].created_at);
      $('#view_updated').val(u['user'][0].updated_at);
      $.each(u['roles'],function(index,item){
          $('<tr>').append($('<td>').text(item.rol)).appendTo("#tbody_roles");
      });
    });
    $('#viewUser').addClass('is-active');
});

window.onload = function() { //funcion que evita el resubmit de un mismo form
  history.replaceState("", "", "./?action=usuario_index");
}

//para el modal de agregar usuario
$('#showAddUser').on("click",function(){
  $('#addUser').addClass('is-active');
});

$('.modal-close').on("click",function(){
  $('#addUser').removeClass('is-active');
  $('#addUser input').val('');
});

$('#cancel').on("click",function(){
  $('#addUser').removeClass('is-active');
  $('#addUser input').val('');
});

//para el modal de ver un usuario
$('.modal-close').on("click",function(){
  $('#viewUser').removeClass('is-active');
  $('#tbody_roles').empty();
});

$('#close').on("click",function(){
  $('#viewUser').removeClass('is-active');
  $('#tbody_roles').empty();
});

//función boton delete
$('#tabla').on("click",".button_delete",function(){
  $("#deleteModal").addClass('is-active');
  var id_user= $(this).closest('tr').find('.user_id').val();
  $('#usuario_destroy').val(id_user);
});

$('.modal-close').on("click",function(){
  $('#deleteModal').removeClass('is-active');
  $('#deleteUserForm').reset();
});

$('#closed').on("click",function(){
  $('#deleteModal').removeClass('is-active');
  $('#deleteUserForm').reset();
});
