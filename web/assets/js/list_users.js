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
