$(document).ready(function() {

  $(".navbar-burger").on("click", function() {
    $(".navbar-burger").toggleClass("is-active");
    $(".navbar-menu").toggleClass("is-active");
  });

  $("#close_error").on("click",function(){
    $("#error_noti").remove();
  });

  $("#close_success").on("click",function(){
    $("#success_noti").remove();
  });

  $("#close_alert").on("click",function(){
    $("#js_alert").prop('hidden',true);
  });

});

function viewAlert(){
  $('#js_alert').removeAttr('hidden','hidden');
}
