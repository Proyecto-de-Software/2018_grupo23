
          $(document).ready(function() {
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
          });
