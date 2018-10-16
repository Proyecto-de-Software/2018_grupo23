function showLoading(){
  $('#loading').addClass('is-active');
}

function hideLoading(){
  $('#loading').removeClass('is-active');
}

$(document).ready(function() {
  $("#close_loading").on("click",function(){
    $("#loading").removeClass('is-active');
  });
});
