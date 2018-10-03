
          function getPartidos(){
            var url="https://api-referencias.proyecto2018.linti.unlp.edu.ar/partido";
            $.getJSON(url, function(data) {
              $("#partido option").remove();
              $("#partido").append('<option value="">Selecciona un partido</option>');
              $.each(data,function(index, value){
                $("#partido").append(
                  '<option value="' + value.id + '">' + value.nombre + "</option>"
                );
              });
            });
          }
          $(document).ready(function() {
            getPartidos();
            $("#partido").on("change",function(){
              var partido=$("#partido").val();
              if(!$.isEmptyObject(partido)){ //esto es para evitar que pida el valor nulo al seleccionar el "selecciona un partido"
              var url="https://api-referencias.proyecto2018.linti.unlp.edu.ar/region-sanitaria/"+partido;
              $.getJSON(url, function(data){
                $("#region_s").val(data.nombre);
                $("#region_id").val(data.id);
              });
            }
            else{
              $("#region_s").val("");
            }
            });

            $("#partido").on("change",function(){
              var partido_id=$("#partido").val();
              if(!$.isEmptyObject(partido_id)){ //esto es para evitar que pida el valor nulo al seleccionar el "selecciona un partido"
              var url="https://api-referencias.proyecto2018.linti.unlp.edu.ar/localidad/partido/"+partido_id;
              $.getJSON(url, function(data) {
                $("#localidad option").remove();
                $("#localidad").append('<option value="">Selecciona una localidad</option>');
                if(!$.isArray(data)){
                  $("#localidad").append(
                  '<option value="' + data.id + '">' + data.nombre + "</option>"
                  );
                }
                else{
                $.each(data,function(index, value){
                  $("#localidad").append(
                    '<option value="' + value.id + '">' + value.nombre + "</option>"
                  );
                });
              } //cierre del else
              });
            }
            else{
              $("#localidad option").remove();
              $("#localidad").append('<option value="">Selecciona una localidad</option>');
            }
            });

            $('#showAddPatient').on("click",function(){
              $('#addPatient').fadeIn();
            });

            $('.modal-close').on("click",function(){
              $('#addPatient').fadeOut();
            });

            $('#cancel').on("click",function(){
              $('#addPatient').fadeOut();
            });



            $('#tabla').on("click",".button_v",function(){
              var id_paciente= $(this).closest('tr').find('.p_id').val();
              $.ajax({
                method: "POST",
                url: "./?action=view_patient",
                data: { id: id_paciente}
              })
                .done(function(paciente){
                  var p=JSON.parse(paciente);
                  console.log(p);
                  $('#v_nombre').val(p[0].nombre);
                  $('#v_apellido').val(p[0].apellido);
                  $('#v_dob').val(p[0].fecha_nac);
                  $('#v_localidad').val(p[0].localidad_id.nombre);
                  if(p[0].genero_id=="1"){
                    $('#v_genero').val("Masculino");
                  }else if (p[0].genero_id=="2") {
                    $('#v_genero').val("Femenino");
                  }else {
                    $('#v_genero').val("Otro");
                  }
                  //tengo que ir a buscar el nombre del genero a la tabla de nuestra db.
                  $('#v_tipodoc').val(p[0].tipo_doc_id.nombre);
                  $('#v_tel').val(p[0].tel);
                  $('#v_dobplace').val(p[0].lugar_nac);
                  $('#v_region').val(p[0].region_sanitaria_id.nombre);
                  $('#v_domicilio').val(p[0].domicilio);
                  if(p[0].tiene_documento=="1"){
                    $('#v_tienedoc').val("Sí");
                  }else {
                    $('#v_tienedoc').val("No");
                  }
                  $('#v_numdoc').val(p[0].numero);
                  $('#v_numcarpeta').val(p[0].nro_carpeta);
                  $('#v_obrasocial').val(p[0].obra_social_id.nombre);
                  $('#v_historia').val(p[0].id);
                  $('#v_partido').val(p[0].partido_id.nombre);
                  $('#viewPatient').fadeIn();
                });
            });

            $('.modal-close').on("click",function(){
              $('#viewPatient').fadeOut();
            });

            $('#close').on("click",function(){
              $('#viewPatient').fadeOut();
            });

          /*
            //funcion boton editar
              $('#t_pacientes').on("click",".button_e",function(){
                var id_paciente= $(this).closest('tr').find('.p_id').val();
                $.ajax({
                  method: "POST",
                  url: "./?action=view_patient",
                  data: { id: id_paciente}
                })
                  .done(function(paciente){
                  });
              });

            //funcion boton borrar
            $('#t_pacientes').on("click",".button_d",function(){
              var id_paciente= $(this).closest('tr').find('.p_id').val();
              console.log(id_paciente);
              $.ajax({
                method: "POST",
                url: "./?action=view_patient",
                data: { id: id_paciente}
              })
                .done(function(paciente){
                });
            }); */

          });
