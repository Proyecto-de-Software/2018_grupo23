//para buscar los datos de un usuario
$('#tabla').on("click",".button_view",function(){
  var id_usuario= $(this).closest('tr').find('.user_id').val();
  $.ajax({
    method: "POST",
    url: "./?action=view_user",
    data: {id: id_usuario}
  })
    .done(function(user){
      var u=JSON.parse(user);
      $('#view_apellido').val(u['user'][0].last_name);
      $('#view_nombre').val(u['user'][0].first_name);
      $('#view_email').val(u['user'][0].email);
      $('#view_username').val(u['user'][0].username);
      $.each(u['roles'],function(index,item){
                       $("<tr>").append(
                       $("<td>").text(item.rol)
                     ).appendTo("#tabla_roles tbody");
      });
      // jQuery.each(u['roles'], function(i, val) {
      //       $("#view_roles").append(val + "<br/>");
      //     });
      // $('#view_roles').val(JSON.stringify(u['roles']));
      $('#viewUser').fadeIn();
    });
});

//para el modal de agregar usuario
$('#showAddUser').on("click",function(){
  $('#addUser').fadeIn();
  $('#addUser input').val('');
});

$('.modal-close').on("click",function(){
  $('#addUser').fadeOut();
});

$('#cancel').on("click",function(){
  $('#addUser').fadeOut();
});

//para el modal de ver un usuario
$('.modal-close').on("click",function(){
  $('#viewUser').fadeOut();
});

$('#close').on("click",function(){
  $('#viewUser').fadeOut();
});
