function showNotAvailable() {
  $('#not_available').addClass('is-active');
}

function hideNotAvailable() {
  $('#not_available').removeClass('is-active');
}

$(document).ready(function() {
  $("#close_not_available").on("click", function() {
    $("#not_available").removeClass('is-active');
  });
});
