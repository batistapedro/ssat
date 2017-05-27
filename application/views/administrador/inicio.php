<!doctype html>
<html lang='es'>
  <head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="icon" href='./favicon.ico'>
    <title>Inicio</title>
    <link rel="stylesheet" href='./public/bootstrap/css/bootstrap.min.css'>
    <link rel="stylesheet" href="./public/bootstrap/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="./public/jqueryui/jquery-ui.min.css">
    <link rel="stylesheet" href="./public/jqueryui/jquery-ui.theme.min.css">

    <style>
      .banner
      {
        background-color: white;
      }
      #section
      {
        width:100%;
        padding: 0px;
        margin: 0px;
      }
      .ui-datepicker-month, .ui-datepicker-year
      {
        background-color: silver;
      }
      #form_buscar_solicitud,#form_encuesta,#ver_encuestas_pendientes
      {
        width: 50%;
      }
    </style>
    <script src="./public/jquery/jquery.js"></script>
    <script src="./public/bootstrap/js/bootstrap.min.js"></script>
    <script src="./public/jqueryui/jquery-ui.min.js"></script>
    <script>
      $(document).ready(function(){

        //datapickers

      $('.fecha_respuesta').datepicker({
      dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
      changeYear: true,
      changeMonth:true,
      monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
      minDate: new Date(2016, 1 - 1, 1),
      dateFormat:"yy/mm/dd"
    });

        //recarga de la pagina de inicio
        $('#inicio').on('click',function(e){
          e.preventDefault();
          window.location.reload();
        });

        //function que llama al formulario para cambiar clave
        $('#form_cambiar_clave').on('click',function(e){
          e.preventDefault();
          $.ajax({
            type:'post',
            url : $(this).attr('href'),
            beforeSend : function()
            {
              $('#section').html('<img class="img img-responsive center-block" src="./public/img/cargando.gif">');
            },
            success : function(respuesta)
            {
              $('#section').html(respuesta);
            }
          });
        });

        //funcion que llama al modal para salir de session
        $("#salirSesion").on('click',function(e){
        e.preventDefault();
        url = $(this).attr('href');
        $("#salirdesesion").modal('show');
        //funcion para comprobar si el usuario desea salir de session
        $("#nosalirsesion, #sisalirsesion").on('click',function(e){
            e.preventDefault();
            valor = $(this).val();
            if(valor=="si")
            {
              $("#salirdesesion").modal('hide');
              window.location=""+url;

            }
            else
            {
              $("#salirdesesion").modal('hide');
            }
        });
      });

      $(document).on('click','#ver_descripcion_solicitud',function(e){
        e.preventDefault();
        descripcion = $(this).closest('tr').find('.descripcion').text();
        $('#modal_descripcion_solicitud_body').html(descripcion);
        $('#descripcion_solicitud').modal('show');
      });

      $(document).on('click','#procesar_solicitud',function(e){
        e.preventDefault();
        id = $(this).closest('tr').find('.idsolicitud').text();
        fecha = $(this).closest('tr').find('td .fecha_respuesta').val();
        idusuario = $(this).closest('tr').find('.idusuario').text();
        td = $(this).closest('tr').find('td');
        cantidad = $('#cantidad').text();
        if(fecha.length=='')
        {
          $('#distintosErrores_title').html('<h3 class="text-center text-primary">Mensaje de Error</h3>');
          $('#distintosErrores_body').html('<h4 class="text-center text-danger">Error el Campo Fecha Respuesta no Puede ser Vacio</h4>');
          $('#distintosErrores').modal('show');
        }
        else
        {
          $.ajax({
            type:'post',
            url : $(this).attr('href'),
            data :{id:id,idusuario:idusuario,fecha:fecha,cantidad:cantidad},
            beforeSend : function(){
              $('#distintosErrores_title').html('<h3 class="text-center text-primary">Procesando Solicitud</h3>');
              $('#distintosErrores_body').html('<img class="center-block img img-responsive" src="./public/img/cargando.gif">');
              $('#distintosErrores').modal('show');
            },
            success : function(respuesta)
            {
              json = JSON.parse(respuesta);
              if(json.respuesta=='error')
              {
                $('#distintosErrores_title').html('<h3 class="text-center text-primary">Mensaje de Error</h3>');
                $('#distintosErrores_body').html('<h4 class="text-danger">'+json.error+'</h4>');
                $('#distintosErrores').modal('show');
              }
              else
              {
                $('#distintosErrores_title').html('<h3 class="text-center text-primary">Mensaje de Exito</h3>');
                $('#distintosErrores_body').html('<h4 class="text-success">'+json.exito+'</h4>');
                $('#distintosErrores').modal('show');
                td.css('display','none').html('');
                $('#cantidad').html(''+json.cantidad+'');
              }

            },
            error : function(xhr, status)
            {
              alert('Disculpe, existió un problema '+xhr+' <=> '+status);
            },
          });
        }
      });
      //funcion que llama al formulario para registrar usuarios
      $('#registrar_usuarios').on('click',function(e){
        e.preventDefault();
        $.ajax({
          type:'post',
          url:$(this).attr('href'),
          beforeSend : function()
          {
              $('#section').html('<img class="img img-responsive center-block" src="./public/img/cargando.gif">');
          },
          success : function(respuesta)
          {
            $('#section').html(respuesta);
          }

        });
      });

      //funcion que envia los datos del formulario registrar usuarios
      $(document).on('submit','#form_registrar_usuarios',function(e){
        e.preventDefault();
        $.ajax({
          type :$(this).attr('method'),
          url :$(this).attr('action'),
          data : $(this).serialize(),
          success : function(respuesta)
          {
            $('#error_nombre,#error_apellido,#error_usuario,#error_cedula,#error_gerencia_departamento,#error_coordinacion,#error_cargo,#error_correo,#error_tipo_usuario,#error_estado_usuario').css('display','none').html('');
            json = JSON.parse(respuesta);

            if(json.respuesta=='error')
            {

              if(json.error_nombre)
              {
                $("#error_nombre").css('display','block').append(json.error_nombre);
              }

              if(json.error_apellido)
              {
                $("#error_apellido").css('display','block').append(json.error_apellido);
              }

              if(json.error_usuario)
              {
                $("#error_usuario").css('display','block').append(json.error_usuario);
              }

              if(json.error_cedula)
              {
                $("#error_cedula").css('display','block').append(json.error_cedula);
              }

              if(json.error_gerencia_departamento)
              {
                $("#error_gerencia").css('display','block').append(json.error_gerencia_departamento);
              }

              if(json.error_coordinacion)
              {
                $("#error_coordinacion").css('display','block').append(json.error_coordinacion);
              }

              if(json.error_cargo)
              {
                $("#error_cargo").css('display','block').append(json.error_cargo);
              }

              if(json.error_correo)
              {
                $("#error_correo").css('display','block').append(json.error_correo);
              }

              if(json.error_tipo_usuario)
              {
                $("#error_tipo_usuario").css('display','block').append(json.error_tipo_usuario);
              }

              if(json.error_estado_usuario)
              {
                $("#error_estado_usuario").css('display','block').append(json.error_estado_usuario);
              }

              if(json.error_db)
              {
                $('#distintosErrores_title').html('<h3 class="text-center text-primary">Mensaje de Error</h3>');
                $('#distintosErrores_body').html('<h4 class="text-danger">'+json.error_db+'</h4>');
                $('#distintosErrores').modal('show');
              }

            }
            else
            {
              $('#distintosErrores_title').html('<h3 class="text-center text-primary">Mensaje de Exito</h3>');
              $('#distintosErrores_body').html('<h4 class="text-success">'+json.exito+'</h4>');
              $('#distintosErrores').modal('show');
              $('#error_nombre,#error_apellido,#error_usuario,#error_clave,#error_gerencia,#error_coordinacion,#error_cargo,#error_correo,#error_tipo_usuario,#error_estado_usuario').css('display','none').html('');
              $('#nombre,#apellido,#cedula,#usuario,#clave,#gerencias_departamentos,#coordinacion,#cargo,#correo,#tipo_usuario').val('');
            }
          }
        });
      });




      // funcion que muestar el reportes de usuarios
      $('#reportes_usuarios').on('click',function(e){
        e.preventDefault();
        $.ajax({
          type:'post',
          url:$(this).attr('href'),
          beforeSend : function()
          {
              $('#section').html('<img class="img img-responsive center-block" src="./public/img/cargando.gif">');
          },
          success : function(respuesta)
          {
            $('#section').html(respuesta);
          }
        });
      });
      //variable globales necesario valor y td para editar campo usuario
      var valor=null;
      var td=null;
      var idgencia=null;
      var idcoordinacion=null;
      var usuario=null;
      var id=null;
      var gerencia=null;
      var coordinacion=null;
      //funcion editar usuario
        $(document).on("click","td.editableUsuario span",function(e)
        {
          e.preventDefault();
             campo=$(this).closest("td").data("campo");
              id=$(this).closest('tr').find('.id').text();
             if(campo=='gerencia' || campo=='coordinacion')
             {
               $('#modal_gerencia,#modal_coordinacion').html('');
               idgerencia = $(this).closest('tr').find('.idgerencia').text();
               idcoordinacion = $(this).closest('tr').find('.idcoordinacion').text();
               gerencia = $(this).closest('tr').find('.gerencia');
               coordinacion = $(this).closest('tr').find('.coordinacion');
               usuario = $(this).closest('tr').find('.usuario').text();

               $.post('administrador/Administrador/extraer_gerencias',function(respuesta){
                 json = JSON.parse(respuesta);
                 console.log(json);
                 $('#modal_usuario').html(usuario);
                  $('#modal_gerencia').html('<option value="">Seleccione Gerencia o Departemento</option>');
                 for(i=0;i<json.length;i++)
                 {
                   $('#modal_gerencia').append('<option value='+json[i].idgerencia+'>'+json[i].gerencia+'</option>');
                 }
               });
               //muestra el modal
               $('#modal_editar_gerencias_coordinacion').modal('show');

             }
             else
             {
               id=$(this).closest("tr").find(".id").text();
               $("td:not(.id)").removeClass("editableUsuario");
                td=$(this).closest("td");
                valor =$(this).text();
               td.text("").html("<input type='text' name='"+campo+"' value='"+valor+"'><a class='enlace guardarEditableUsuario' href='#' title='Guardar'>Guardar</a> <a class='enlace cancelarEditableUsuario' href='#' title='cancelar'>Cancelar</a>");
             }

          });
          //funcion que cargar las coordinaciones del modal_gerencia
          $('#modal_gerencia').on('change',function(){
            $('#modal_coordinacion').html('');
              id =$(this).val();
              $.post('administrador/Administrador/extraer_coordinacion',{valor:id},function(respuesta){
                json = JSON.parse(respuesta);
                for(i=0;i<json.length;i++)
                {
                    $('#modal_coordinacion').append('<option value='+json[i].idcoordinacion+'>'+json[i].coordinacion+'</option>');
                }

              });
          });

      //funcion cancelar editar usuario
     $(document).on("click",".cancelarEditableUsuario",function(e)
     {
           e.preventDefault();
           td.text("").html("<span>"+valor+"</span>");
           $("td:not(.id)").addClass("editableUsuario");
      });

     //funcion guardar datos editados de usuario
    $(document).on("click",".guardarEditableUsuario",function(e)
    {
       e.preventDefault();
        nuevovalor= $(this).closest("td").find("input").val();
        id = $(this).closest("tr").find(".id").text();
        urls = $(this).closest('tr').find('.url').text();
        campo= $(this).closest("td").data("campo");
        td= $(this).closest("td");

         if(nuevovalor.trim()!="")
         {
           $.ajax({
                 type: "post",
                 url: urls,
                 data: { campo: campo, nuevovalor: nuevovalor, id : id },
                 success: function(respuesta)
                 {
                    json  = JSON.parse(respuesta);

                   if(json.respuesta=="error")
                   {
                     $('#distintosErrores_title').html('<h3 class="text-center text-primary">Mensaje de Error</h3>');
                     $('#distintosErrores_body').html('<h4 class="text-danger">'+json.error+'</h4>');
                     $('#distintosErrores').modal('show');
                     td.text("").html("<span>"+valor+"</span>");
                     $("td:not(.id)").addClass("editableUsuario");
                   }
                   else
                   {
                     $('#distintosErrores_title').html('<h3 class="text-center text-primary">Mensaje de Exito</h3>');
                     $('#distintosErrores_body').html('<h4 class="text-success">'+json.exito+'</h4>');
                     $('#distintosErrores').modal('show');
                     td.text("").html("<span>"+nuevovalor+"</span>");
                     $("td:not(.id)").addClass("editableUsuario");
                   }


                 }

               });

         }



     });
      //funcion para activa o desactivar usuarios
      $(document).on('click','#activador_usuarios',function(e){
        e.preventDefault();
        id = $(this).closest('tr').find('.id').text();
        url= $(this).closest('tr').find('.url').text();
        campo = 'estado_usuario';
        texto = $(this).text();
        btn = $(this);
        if(texto=='Activo')
        {
          estado=0;
        }
        else
        {
          estado=1;
        }
        $.ajax({
          type:'post',
          url:url,
          data:{id:id,nuevovalor:estado,campo:campo},
          success : function(respuesta)
          {
            console.log(respuesta);
            json  = JSON.parse(respuesta);

           if(json.respuesta=="error")
           {
             $('#distintosErrores_title').html('<h3 class="text-center text-primary">Mensaje de Error</h3>');
             $('#distintosErrores_body').html('<h4 class="text-danger">'+json.error+'</h4>');
             $('#distintosErrores').modal('show');
           }
           else
           {
             $('#distintosErrores_title').html('<h3 class="text-center text-primary">Mensaje de Exito</h3>');
             $('#distintosErrores_body').html('<h4 class="text-success">'+json.exito+'</h4>');
             $('#distintosErrores').modal('show');
             if(texto=='Activo')
             {
               btn.removeClass('btn btn-success');
               btn.addClass('btn btn-default');
               btn.attr('title','Inactivo');
               btn.text('Inactivo');
             }
             else
             {
               btn.removeClass('btn btn-default');
               btn.addClass('btn btn-success');
               btn.attr('title','Activo');
               btn.text('Activo');
             }
           }

          }
        });
      });
      //funcion que captura la gerencia y llama a la coordinacions
      $(document).on('change','#gerencias_departamentos',function(e){
        e.preventDefault();
        valor = $(this).val();
        url = $(this).data('url');

        if(valor=='10')
        {
          $('#tipo_usuario').html('<option value="0">Administrador</option>');
        }
        else
        {
          $('#tipo_usuario').html('<option value="1">Usuario</option>');
        }
        $.ajax({
          type:'post',
          url :url,
          data:{valor:valor},
          beforeSend : function()
          {
            $('#coordinacion').html('<option>Cargando...</option>');
          },
          success : function(respuesta)
          {
              json = JSON.parse(respuesta);
              $('#coordinacion').html('<option value="">Elija Coordinacion</option>');
              for(i=0;i<json.length;i++)
              {
                $('#coordinacion').append('<option value='+json[i].idcoordinacion+'>'+json[i].coordinacion+'</option>');
              }
          },
          error: function(){
            alert('hubo un error al cargar coordinaciones');
          }
        });
      });

      //funcion que llama al formualrio de reportes de solicitudes de los usuarios
      $('#reportes_solicitudes_usuarios').on('click',function(e){
        e.preventDefault();
        $.ajax({
          type:'post',
          url:$(this).attr('href'),
          beforeSend : function()
          {
            $('#section').html('<img class="img img-responsive center-block" src="./public/img/cargando.gif">');
          },
          success: function(respuesta)
          {
            $('#section').html(respuesta);
          }
        });
      });

      //fuincion que lla al formulario de busqueda de encuentas pendientes
      $('#form_reportes_encuesta_usuarios_pendientes').on('click',function(e)
      {
        e.preventDefault();
        $.ajax({
          type:'post',
          url:$(this).attr('href'),
          beforeSend : function()
          {
            $('#section').html('<img class="img img-responsive center-block" src="./public/img/cargando.gif">');
          },
          success : function(respuesta)
          {
            $('#section').html(respuesta);
          }
        });
      });

      ///function extraer las encuestas pendientes
      $(document).on('submit','#ver_encuestas_pendientes',function(e){
        e.preventDefault();
        $.ajax({
          type:$(this).attr('method'),
          url :$(this).attr('action'),
          data : $(this).serialize(),
          beforeSend : function(){
              $('#section_encuesta_pendiente_usuario').html('<img class="img img-responsive center-block" src="./public/img/cargando.gif">');
          },
          success: function(respuesta)
          {
              $('#section_encuesta_pendiente_usuario').html(respuesta);
          }
        });
      });

      //funcion que validar el select de tipo busqueda el formulario form_solicitud_usuario
      $(document).on('change','#select_reportes_opcion',function(e){
        if($(this).val()=='gerencias')
        {
          $('.select_gerencias').removeClass('hide');
        }
        else
        {
            $('.select_gerencias').addClass('hide');
        }

      });

      //funcion que enviar informacion del formulario form_buscar_solicitud
      $(document).on('submit','#form_buscar_solicitud',function(e){
        e.preventDefault();
        $.ajax({
          type:$(this).attr('method'),
          url :$(this).attr('action'),
          data : $(this).serialize(),
          beforeSend :  function()
          {
            $('#section_solictiud_usuario').html('<img class="center-block img img-responsive" src="./public/img/cargando.gif">');

          },
          success : function(respuesta)
          {
            $('.section_solictiud_usuario').html(respuesta);

          }
        });
      });

      //funcion que gernera el pdf como ventana emergente
      $(document).on('click','.btn_generar_pdf_todos',function(e){
        e.preventDefault();
        window.open($(this).attr('href'),'Reportes de solicitudes','width=1200,height=1100,resizable=yes,scrollbars=yes,status=yes,location=no');
      });

      //funcion que gernera el pdf como ventana emergente
      $(document).on('click','.btn_generar_pdf_departamento',function(e){
        e.preventDefault();
        window.open($(this).attr('href'),'Reportes de solicitudes','width=1200,height=1100,resizable=yes,scrollbars=yes,status=yes,location=no');
      });
      //funcion que llama al formulario de busqueda de encuesta
      $('#reportes_encuesta_usuarios').on('click',function(e){
        e.preventDefault();
        $.ajax({
          type:'post',
          url:$(this).attr('href'),
          beforeSend : function()
          {
          $('#section').html('<img class="center-block img img-responsive" src="./public/img/cargando.gif">');
          },
          success : function(respuesta)
          {
            $('#section').html(respuesta);
          }
        });
      });

      //funcio que envia los datos del formulario encuesta
      $(document).on('submit','#form_encuesta',function(e){
        e.preventDefault();
        $.ajax({
          type:$(this).attr('method'),
          url:$(this).attr('action'),
          data : $(this).serialize(),
          beforeSend : function()
          {
            $('#section_encuesta_usuario').html('<img class="center-block img img-responsive" src="./public/img/cargando.gif">');
          },
          success : function(respuesta)
          {
            $('#section_encuesta_usuario').html(respuesta);
          }
        });
      });
      //funcion que llama al modal ver observacion de encuesta
      $(document).on('click','#btn_observacion_encuesta',function(e){
        e.preventDefault();
        $('#modal_descripcion_encuesta_body').html($(this).data('observacion'));
        $('#modal_descripcion_encuesta').modal('show');
      });

      //funcion que envia el id,idgerencia y idcoordinacion a modificar en la base de datos
      $(document).on('submit','#form_guardar_gerencias_coordinacion',function(e){
        e.preventDefault();
        idgerencia = $('#modal_gerencia').val();
        idcoordinacion = $('#modal_coordinacion').val();
        textgerencia = $('#modal_gerencia option:selected').text();
        textcoordinacion = $('#modal_coordinacion option:selected').text();

          $.ajax({
            type:$(this).attr('method'),
            url : $(this).attr('action'),
            data :{usuario:usuario,idgerencia:idgerencia,idcoordinacion:idcoordinacion},
            beforeSend : function()
            {

            },
            success: function(respuesta)
            {
              $('#modal_editar_gerencias_coordinacion').modal('hide');
              json = JSON.parse(respuesta);
              if(json.respuesta=='error')
              {
                $('#distintosErrores_title').html('<h3 class="text-center text-primary">Mensaje de Error</h3>');
                $('#distintosErrores_body').html('<h4 class="text-danger">'+json.error+'</h4>');
                $('#distintosErrores').modal('show');
              }
              else
              {
                $('#distintosErrores_title').html('<h3 class="text-center text-primary">Mensaje de Exito</h3>');
                $('#distintosErrores_body').html('<h4 class="text-success">'+json.exito+'</h4>');
                $('#distintosErrores').modal('show');
                gerencia.html("<td class='editableUsuario' data-campo='gerencia'><span>"+textgerencia+"</span></td>");
                coordinacion.html("<td class='editableUsuario' data-campo='gerencia'><span>"+textcoordinacion+"</span></td>");

              }
            }
          });

      });

      });
    </script>
  </head>
  <body style='padding-top: 175px;'>
    <div class="container-fluid master">
   <div class="row" style='padding:0px; margin:0px;'>
     <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
       <header class="navbar navbar-default navbar-fixed-top" role="navigation">
         <img class="img img-responsive center-block banner" src="./public/img/logo3.png">
         <nav>
           <div class="navbar-header">
             <div class="navbar-brand"><p title="Solicitud de Soporte y Asistencia Tecnica" style="color:#337ab7; font-weght:bold; font-size:1em;">S.S.A.T</p></div>
             <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
               <span class="sr-only"></span>
               <span class="icon-bar"></span>
               <span class="icon-bar"></span>
               <span class="icon-bar"></span>
             </button>

           </div>
           <div class="collapse navbar-collapse" id="menu">
             <ul class="nav nav-pills nav-justified">
               <li>
                 <a href="" class="btn btn-md" title="Inicio" id="inicio" style="font-weight: bold;">Inicio </a>
               </li>
               <li class="dropdown">
                   <a href="" title="Registrar" class="dropdown-toggle btn btn-md" data-toggle="dropdown" style="font-weight: bold;">Registrar <span class="caret"></span> </a>
                   <ul class="dropdown-menu" role="menu" style="box-shadow:0px 0px 12px rgba(0,0,0,0.5);">
                     <li role="presentation" class="dropdown-header">Registrar</li>
                     <li class="divider"></li>
                     <li> <a href="<?php echo base_url('administrador/Administrador/form_registrar_usuarios');?>" id="registrar_usuarios" role="menuitem" tabindex="-1"> Usuarios</a></li>
                     <!--<li> <a href="<?php echo base_url('administrador/Administrador/form_registrar_solicitud');?>" id="registrar_solicitud" role="menuitem" tabindex="-1">Solicitudes Programadas </a></li>-->
                   </ul>
                 </li>
                 <li class="dropdown">
                   <a href="" title="Reportes" class="dropdown-toggle btn btn-md" data-toggle="dropdown" style="font-weight: bold;">Reportes <span class="caret"></span></a>
                   <ul class="dropdown-menu" role="menu" style="box-shadow:0px 0px 12px rgba(0,0,0,0.5);">
                     <li role="presentation" class="dropdown-header">Reportes </li>
                     <li class="divider"></li>
                     <li> <a href="<?php echo base_url('administrador/Administrador/extraer_usuarios');?>" id="reportes_usuarios" role="menuitem" tabindex="-1"> Usuarios</a></li>
                     <li> <a href="<?php echo base_url('administrador/Administrador/reportes_solicitudes_usuarios');?>" id="reportes_solicitudes_usuarios" role="menuitem" tabindex="-1"> Solicitudes de Usuarios </a></li>
                     <li> <a href="<?php echo base_url('administrador/Administrador/repostes_encuesta_usuario');?>" id="reportes_encuesta_usuarios" role="menuitem" tabindex="-1"> Encuestas Realizadas</a></li>
                     <li> <a href="<?php echo base_url('administrador/Administrador/form_reportes_encuesta_usuarios_pendientes');?>" id="form_reportes_encuesta_usuarios_pendientes" role="menuitem" tabindex="-1"> Encuestas Pendientes</a></li>
                   </ul>
                 </li>
                 <li class="dropdown">
                   <a href="" class="dropdown-toggle btn btn-md" data-toggle="dropdown" title="Configurar" style="font-weight: bold;">Configurar <span class="caret"></span></a>
                   <ul class="dropdown-menu" role="menu" style="box-shadow:0px 0px 12px rgba(0,0,0,0.5);">
                     <li role="presentation" class="dropdown-header">Configurar </li>
                     <li class="divider"></li>
                     <li><a href="<?php echo base_url('administrador/Administrador/form_cambiar_clave');?>" id="form_cambiar_clave" role="menuitem" tabindex="-1"> Clave de Usuario</a> </li>

                   </ul>
                 </li>
                 <li>
                   <a href="<?php echo base_url('administrador/Administrador/respaldar_bd');?>" title="Respaldar Base de Datos" style="font-weight: bold;">Respaldar BD</a>
                 </li>
                 <li>
                   <a href="<?php echo base_url('SalirSesion');?>" class="btn btn-md" id="salirSesion" style="font-weight: bold;">Salir (<?php echo $this->session->userdata('usuario');?>)</a>
                 </li>
             </ul>
           </div>
         </nav>
       </header>

       <!-- form modal de editar datos gerencias deparatemtos y coordinaciones -->
       <div class="modal fade" tabindex="-1" role="dialog" id="modal_editar_gerencias_coordinacion" aria-labelledby="myModalLabel" aria-hidden="true">
         <div class="modal-dialog">
             <div class="modal-content">
                 <div class="modal-header">
                       <h3 class="modal-title text-center text-info">Editar Datos Usuarios</h3>
                 </div>
                 <div class="modal-body text-center">
                    <h3 class='text-center text-primary'> Usuario : <span id='modal_usuario'></span></h3>
                    <?php echo form_open(base_url('administrador/Administrador/modificar_gerencia_coordinacion'),array('class'=>'form','role'=>'form','id'=>'form_guardar_gerencias_coordinacion'))?>
                    <div class='form-group'>
                      <label class='text-left text-primary' for='modal_gerencia'>Selecciones Gerencia o Departamento</label><br>
                      <select name='modal_gerencia' id='modal_gerencia' class='form-control' required>
                      </select>
                     </div>
                     <div class='form-group'>
                       <label class='text-left text-primary' for='modal_coordinacion'>Seleciones Coordinacion</label><br>
                       <select id='modal_coordinacion' class='form-control' required></select>
                     </div>
                     <div class='form-group text-left'>
                       <button type='submit' class='btn btn-primary'><span class='glyphicon glyphicon-ok-sing'></span>Registrar</button>
                     </div>
                     <?php echo form_close();?>
                   </div>
                 </div>
           </div>
         </div>
       </div>
       <!--fin del modal editar datos gerencias y coordinaciones -->

       <!--modal de distintos errores -->
       <div class="modal fade" tabindex="-1" role="dialog" id="distintosErrores" aria-labelledby="myModalLabel" aria-hidden="true">
         <div class="modal-dialog">
             <div class="modal-content">
                 <div class="modal-header">
                       <h3 class="modal-title text-center text-info" id='distintosErrores_title'>Error del Sistema</h3>
                 </div>
                 <div class="modal-body text-center" id="distintosErrores_body"></div>
           </div>
         </div>
       </div>
       <!--fin del modal de distintos errores -->
       <!---modal para salir de session-->
       <div class="modal fade" tabindex="-1" role="dialog" id="salirdesesion" aria-labelledby="myModalLabel" aria-hidden='true'>
           <div class="modal-dialog">
             <div class="modal-content">
               <div class="modal-header">
                 <h4 class="modal-title">
                   Salir de Sesion
                 </h4>
               </div>
               <div class="modal-body text-center text-info">
                 Deseas Salir de Sesion <?php echo $this->session->userdata('usuario');?> ?

               </div>
               <div class="modal-footer text-center">
                 <button id="sisalirsesion" type="button" value='si' class="btn btn-primary">
                   <span class="glyphicon glyphicon-ok-sign"> Aceptar</span>
                 </button>
                 <button id="nosalirsesion" type="button" value='no' class="btn btn-danger">
                   <span class="glyphicon glyphicon-remove-sign"> Cancelar</span>
                 </button>
               </div>
             </div>
           </div>
         </div>
         <!---fin modal para salir de session-->

         <!---modal para ver descicion de la solicitud-->
         <div class="modal fade" tabindex="-1" role="dialog" id="descripcion_solicitud" aria-labelledby="myModalLabel" aria-hidden='true'>
             <div class="modal-dialog">
               <div class="modal-content">
                 <div class="modal-header">
                   <h3 class="modal-title text-center text-info">
                     Descripcion de la Solicitud
                   </h3>
                 </div>
                 <div class="modal-body text-center" id='modal_descripcion_solicitud_body'>

                 </div>

               </div>
             </div>
           </div>
           <!---fin modal para ver descripcion de la solicitud-->

           <!---modal para ver descicion de la encuesta-->
           <div class="modal fade" tabindex="-1" role="dialog" id="modal_descripcion_encuesta" aria-labelledby="myModalLabel" aria-hidden='true'>
               <div class="modal-dialog">
                 <div class="modal-content">
                   <div class="modal-header">
                     <h3 class="modal-title text-center text-info">
                       Observacion de la encuesta
                     </h3>
                   </div>
                   <div class="modal-body text-center" id='modal_descripcion_encuesta_body'>

                   </div>

                 </div>
               </div>
             </div>
             <!---fin modal para ver descripcion de la encuesta-->
       <section id="section">
         <?php if(intval($cantidad)>0):?>
         <div class='table table-responsive'>
           <table class='table table-hover'>
             <caption><h3 class='text-info text-center'>Solicitudes Pendientes</h3></caption>
             <thead>
               <tr>
                 <th class='hide id'>id</th>
                 <th class='text-primary'>Nombres y Apellidos</th>
                 <th class='text-primary'>Unidad Solicitante</th>
                 <th class='text-primary'>Cargo</th>
                 <th class='text-primary'>Motivo Solicitud</th>
                 <th class='text-primary'>Fecha Solicitud</th>
                 <th class='text-primary'>Fecha respuesta</th>
                 <th class='text-primary'>Descripcion</th>
                 <th class='text-primary'>Procesar</th>

               </tr>
             </thead>
             <tbody>

                 <?php foreach($solicitudes as $solicitud):?>
                <tr>
                  <td class='idsolicitud hide'><?php echo $solicitud['idsolicitud'];?></td>
                  <td class='hide idusuario'><?php echo $solicitud['idusuario'];?></td>
                 <td><?php echo $solicitud['nombre'].' '.$solicitud['apellido'];?></td>
                 <?php if(trim($solicitud['coordinacion'])=='n/a'):?>
                   <td><?php echo $solicitud['gerencia'];?></td>
                <?php else :?>
                  <td><?php echo $solicitud['gerencia'].'/ '.$solicitud['coordinacion'];?></td>
                <?php endif;?>
                 <td><?php echo $solicitud['cargo'];?></td>
                 <td><?php echo $solicitud['motivo'];?></td>
                 <td><?php echo $solicitud['fecha_solicitud'];?></td>
                 <td><input type='text' name='fecha_respuesta' class='fecha_respuesta' placeholder='yyyy/mm/dd'></td>
                 <td class='hide descripcion'><?php echo $solicitud['descripcion'];?></td>
                 <td><button class='btn btn-default' id='ver_descripcion_solicitud' title='Ver descripcion de la solicitud'>ver <span class='glyphicon glyphicon-eye-open'></span></button></td>
                 <td class='text-center'>
                   <a href="<?php echo base_url('administrador/Administrador/procesar_solicitud_pendiente');?>" class='btn btn-primary'  id='procesar_solicitud'> Procesar</a>
                 </td>
                 </tr>
               <?php endforeach;?>

             </tbody>
           </table>
         </div>
         <div class='text-center lead text-info'>Numero de Solicitudes : <span class='badge' id='cantidad'><?php echo $cantidad;?></span></div>
       <?php else :?>
         <h3 class='text-center text-info'>Hoy No hay Solicitudes Registradas</h3>
      <?php endif;?>
       </section>
     </div>

   </div>
 </div>
  </body>
</html>
