function getPartidos() {
  var url = "https://api-referencias.proyecto2018.linti.unlp.edu.ar/partido";
  $.getJSON(url, function(data) {
    $("#partido option").remove();
    $("#partido").append('<option value="">Selecciona un partido</option>');
    $.each(data, function(index, value) {
      $("#partido").append(
        '<option value="' + value.id + '">' + value.nombre + "</option>"
      );
    });
  })
  .fail(function(){
      viewAlert();
  });
}

function getLocalidades(id){
  var partido_id = $("#partido").val();
  if (!$.isEmptyObject(partido_id)) { //esto es para evitar que pida el valor nulo al seleccionar el "selecciona un partido"
    var url = "https://api-referencias.proyecto2018.linti.unlp.edu.ar/localidad/partido/" + partido_id;
    $.getJSON(url, function(data) {
      $("#localidad option").remove();
      $("#localidad").append('<option value="">Selecciona una localidad</option>');
        $.each(data, function(index, value) {
          $("#localidad").append(
            '<option value="' + value.id + '">' + value.nombre + "</option>"
          );
          if(id){
            $('#localidad option[value='+id+']').prop('selected',true);
          }
        });

    }).fail(function(){
        viewAlert();
      });
  } else {
    $("#localidad option").remove();
    $("#localidad").append('<option value="">Selecciona una localidad</option>');
  }
}
function isJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}
window.onload = function() { //funcion que evita el resubmit de un mismo form
  history.replaceState("", "", "./?action=paciente_index");
}
$(document).ready(function() {
  getPartidos();
  $("#partido").on("change", function() {
    var partido = $("#partido").val();
    if (!$.isEmptyObject(partido)) { //esto es para evitar que pida el valor nulo al seleccionar el "selecciona un partido"
      var url = "https://api-referencias.proyecto2018.linti.unlp.edu.ar/region-sanitaria/" + partido;
      $.getJSON(url, function(data) {
        $("#region_s").val(data.nombre);
        $("#region_id").val(data.id);
      })
      .fail(function(){
          viewAlert();
        });
    } else {
      $("#region_s").val("");
    }
  });

  $("#partido").on("change", function() {
    getLocalidades();
  });

  $('#showAddPatient').on("click", function() {
    $('#addPatient').addClass('is-active');
  });
//cerrar form add/edit paciente
    $('.modal-close, #cancel').on("click",function(){
      $('#addPatient').removeClass('is-active');
      $('#formAddPatient').attr('action', './?action=paciente_new');
      $('#p_title').text('Agregar paciente');
      $('#p_save').text('Agregar');
      $('#formAddPatient')[0].reset();

    });



//ver paciente
  $('#tabla').on("click", ".button_v", function() {
    showLoading();
    var id_paciente = $(this).closest('tr').find('.p_id').val();
    $.ajax({
        method: "POST",
        url: "./?action=paciente_show",
        data: {
          id: id_paciente
        }
      })
      .done(function(paciente) {
        if(isJsonString(paciente)){
        var p = JSON.parse(paciente);
        $('#v_nombre').val(p[0].nombre);
        $('#v_apellido').val(p[0].apellido);
        $('#v_dob').val(p[0].fecha_nac);
        $('#v_localidad').val(p[0].localidad_id.nombre);
        if (p[0].genero_id == "1") {
          $('#v_genero').val("Masculino");
        } else if (p[0].genero_id == "2") {
          $('#v_genero').val("Femenino");
        } else {
          $('#v_genero').val("Otro");
        }
        //tengo que ir a buscar el nombre del genero a la tabla de nuestra db.
        $('#v_tipodoc').val(p[0].tipo_doc_id.nombre);
        $('#v_tel').val(p[0].tel);
        $('#v_dobplace').val(p[0].lugar_nac);
        $('#v_region').val(p[0].region_sanitaria_id.nombre);
        $('#v_domicilio').val(p[0].domicilio);
        if (p[0].tiene_documento == "1") {
          $('#v_tienedoc').val("Sí");
        } else {
          $('#v_tienedoc').val("No");
        }
        $('#v_numdoc').val(p[0].numero);
        $('#v_numcarpeta').val(p[0].nro_carpeta);
        $('#v_obrasocial').val(p[0].obra_social_id.nombre);
        $('#v_historia').val(p[0].id);
        $('#v_partido').val(p[0].partido_id.nombre);
        $('#viewPatient').addClass('is-active');
        }
        else {
          showNotAvailable();
        }
        hideLoading();
      });
  });
//cerrar ver paciente
  $('.modal-close, #close').on("click", function() {
    $('#viewPatient').removeClass('is-active');
  });

//funcion boton editar
  $('#tabla').on("click",".button_e",function(){
    showLoading();
    var id_paciente= $(this).closest('tr').find('.p_id').val();
    $.ajax({
      method: "POST",
      url: "./?action=paciente_show",
      data: { id: id_paciente}
    })
      .done(function(paciente){
        if(isJsonString(paciente)){
        var p = JSON.parse(paciente);
        console.log(p);
        $('#formAddPatient').attr('action', './?action=paciente_update'); //en los cierres de esto cambiar el action al addpatient
        $('#p_title').text('Editar paciente');
        $('#p_save').text('Guardar');
        $('#edit_id').val(p[0].id);
        $('#e_nombre').val(p[0].nombre);
        $('#e_apellido').val(p[0].apellido);
        $('#e_dob').val(p[0].fecha_nac);
        $('#e_dobplace').val(p[0].lugar_nac);
        $('#e_tipodoc option[value='+p[0].tipo_doc_id.id+']').attr('selected','selected');
        $('#e_numdoc').val(p[0].numero);
        $('#e_numcarpeta').val(p[0].nro_carpeta);
        $('#e_tel').val(p[0].tel);
        $('#e_domicilio').val(p[0].domicilio);
        $('#e_genero option[value='+p[0].genero_id+']').attr('selected','selected');
        $('#e_obrasocial option[value='+p[0].obra_social_id.id+']').attr('selected','selected');
        $('#partido option[value='+p[0].partido_id.id+']').prop('selected',true);
        $('#region_s').val(p[0].region_sanitaria_id.nombre);
        $('#region_id').val(p[0].region_sanitaria_id.id);
        getLocalidades(p[0].localidad_id.id);
        if (p[0].tiene_documento == "1"){
          $('#check_no').removeAttr('checked','checked');
          $('#check_yes').attr('checked','checked');
        }
        else{
          $('#check_yes').removeAttr('checked','checked');
          $('#check_no').attr('checked','checked');
        }
        $('#addPatient').addClass('is-active');
        }
        else {
          showNotAvailable();
        }
        hideLoading();
      });
  });
//funcion boton borrar
$('#tabla').on("click",".button_d",function(){
  $("#deletePatient").addClass('is-active');
  var id_paciente= $(this).closest('tr').find('.p_id').val();
  $('#p_destroy').val(id_paciente);

});
//cierre borrar
$('.modal-close, #closed').on("click",function(){
  $('#deletePatient').removeClass('is-active');
  $('#formDeletePatient')[0].reset();
});


});
/*
          $.ajax({
            method: "POST",
            url: "./?action=view_patient",
            data: { id: id_paciente}
          })
            .done(function(paciente){
            }); */
