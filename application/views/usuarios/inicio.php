<!doctype html>
<html lang='es'>
  <head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="icon" href='./favicon.ico'>
    <title>Inicio</title>
    <link rel="stylesheet" href="./public/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./public/bootstrap/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="./public/jqueryui/jquery-ui.min.css">
    <style>
      .banner
      {
        background-color: white;
      }

      #error_porque_regular, #error_1,#error_2,#error_3,#error_4
      {
        display: none;
      }

    </style>
    <script src="./public/jquery/jquery.js"></script>
    <script src="./public/bootstrap/js/bootstrap.min.js"></script>
    <script src="./public/jqueryui/jquery-ui.min.js"></script>
    <script>
      $(document).ready(function(){

        //recarga de la pagina de inicio
        $('#inicio').on('click',function(e){
          e.preventDefault();
          window.location.reload();
        });

        //formulario se solicitud
        $('#form_solicitud').on('click',function(e){
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

        //formulario que envia la solicitud del Soporte

        $(document).on('submit','#form_enviar_solicitud',function(e){
          e.preventDefault();
          $.ajax({
            type :$(this).attr('method'),
            url:$(this).attr('action'),
            data: $(this).serialize(),
            beforeSend : function()
            {
              $('#distintosErrores_title').html('<h3 class="text-info">Procesando Solicitud</h3>');
              $("#distintosErrores_body").html('<img src="./public/img/cargando.gif" class="img img-responsive center-block">');
              $("#distintosErrores").modal('show');
            },
            success: function(respuesta)
            {
              json = JSON.parse(respuesta);
              if(json.respuesta=='error')
              {
                if(json.error_solicitud)
                {
                  $('#distintosErrores_title').html('<h3 class="text-info">Mensaje de Error</h3>');
                  $("#distintosErrores_body").html('<h3 class="text-danger">'+json.error_solicitud+'</h3>');
                  $("#distintosErrores").modal('show');
                }

                if(json.sin_procesar)
                {
                  $('#distintosErrores_title').html('<h3 class="text-info">Mensaje de Informacion</h3>');
                  $("#distintosErrores_body").html('<h3 class="text-danger">'+json.sin_procesar+'</h3>');
                  $("#distintosErrores").modal('show');
                }

                if(json.error_encuesta)
                {
                  $('#distintosErrores_title').html('<h3 class="text-info">Mensaje de Error</h3>');
                  $("#distintosErrores_body").html('<h3 class="text-danger">'+json.error_encuesta+'</h3>');
                  $("#distintosErrores").modal('show');
                }

              }
              else
              {
                $('#distintosErrores_title').html('<h3 class="text-info">Mensaje de Exito</h3>');
                $("#distintosErrores_body").html('<h3 class="text-success">'+json.exito+'</h3>');
                $("#distintosErrores").modal('show');
                $('#solicitud').val('');
                $('#descripcion').val('');

              }
            }
          });
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
      //funcion que llama al modal encuesta
      $('#modal_mensaje_encuesta').modal('show');

      $('#link_form_encuesta').on('click',function(e){
        e.preventDefault();
        $('#modal_mensaje_encuesta').modal('hide');
        $('#modal_form_encuesta').modal('show');
      });
      var valordefinitivo=null;
      //funcion q calcula las notas
      function calcular_nota(nota1,nota2,nota3,nota4){
        nota1 = (nota1>0)?nota1:0;
        nota2 = (nota2>0)?nota2:0;
        nota3 = (nota3>0)?nota3:0;
        nota4 = (nota4>0)?nota4:0;
        sumaTotal = nota1+nota2+nota3+nota4;
        promedio = parseFloat(sumaTotal/4);
        valordefinitivo = Math.round(promedio);
        if(valordefinitivo>1)
        {
          valorPonderacion='aprobado';
          $('#opcion_regular').css('display','none');
        }
        else
        {
          valorPonderacion='reprobado';
          $('#opcion_regular').css('display','block');

        }
        console.log(sumaTotal+' '+promedio+' '+valorPonderacion+' '+valordefinitivo);
      }

      //validacion los select
      var validar2 =new Array(4);
      $(document).on('change','#form_encuesta select',function(){

        $('#form_encuesta select option:selected').each(function(indice,elemento){
         validar2[indice]=$(elemento).data('valor');
        });
        calcular_nota(parseInt(validar2[0]),parseInt(validar2[1]),parseInt(validar2[2]),parseInt(validar2[3]));
        console.log(validar2);
      });

//function para enviar formulario de encuesta
      $('#form_encuesta').on('submit',function(e){
        e.preventDefault();
        metodo = $(this).attr('method');
        url=$(this).attr('action');
        datos = $(this).serialize();

        if(valordefinitivo<=1 && $('#porque_regular').val()=='')
        {
          alert('Ya que la ponderacion es regular explique el ¿ porque ? para mejorar nustros servicios '+valordefinitivo);
          $("#error_porque_regular").html('Debe llenar este campo').css('display','block');
        }
        else
        {

          $.ajax({
                type: metodo,
                url:url,
                data :datos,
                success : function(respuesta)
                {
                  json = JSON.parse(respuesta);
                  $('#error_1,#error_2,#error_3,#error_4,#error_porque_regular').css('display','none').val('');
                  if(json.respuesta=='error')
                  {
                    if(json.error_1)
                    {
                      $('#error_1').css('display','block').append(json.error_1);
                    }

                    if(json.error_2)
                    {
                      $('#error_2').css('display','block').append(json.error_2);
                    }

                    if(json.error_3)
                    {
                      $('#error_3').css('display','block').append(json.error_3);
                    }

                    if(json.error_4)
                    {
                      $('#error_4').css('display','block').append(json.error_4);
                    }
                    if(json.error_porque_regular)
                    {
                      $('#error_porque_regular').css('display','block').append(json.error_porque_regular);
                    }

                  }
                  else
                  {
                    $('#modal_form_encuesta').modal('hide');
                    $('#distintosErrores_title').html('<h3 class="text-info">Mensaje del Sistema</h3>');
                    $("#distintosErrores_body").html('<h3 class="text-success">'+json.exito+'</h3>');
                    $("#distintosErrores").modal('show');

                  }
                }
            });

        }



      });
      $('#manual').on('click',function(e){
        e.preventDefault();
          window.open('./public/manual.pdf','Manual de Usuario','width=1200,height=1100,resizable=yes,scrollbars=yes,status=yes,location=no');
        //alert('manual');
      });
      });
    </script>
  </head>
  <body style='padding-top: 175px;'>
    <div class="container">
   <div class="row">
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
               <li>
                 <a href="<?php echo base_url('usuarios/Usuarios/form_solicitud');?>" id='form_solicitud' title="Soporte y Asistencia" class="btn btn-md" style="font-weight: bold;">Soporte y Asistencia </a>
               </li>
               <li>
                 <a href="<?php echo base_url('usuarios/Usuarios/form_cambiar_clave');?>" id='form_cambiar_clave' class="btn btn-md" title="Cambiar Clave" style="font-weight: bold;">Cambiar Clave</a>
               </li>
               <li>
                 <a href="<?php echo base_url('usuarios/Usuarios/form_cambiar_clave');?>" id='manual' class="btn btn-md" title="Manual de Usuario" style="font-weight: bold;">Manual</a>
               </li>
               <li>
                 <a href="<?php echo base_url('SalirSesion');?>" class="btn btn-md" id="salirSesion" style="font-weight: bold;">Salir (<?php echo $this->session->userdata('usuario');?>)</a>
               </li>
             </ul>
           </div>
         </nav>
       </header>
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
         <?php if($cantidad>0){ ?>

                <!--modal mensaje encuesta -->
                <div class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" id="modal_mensaje_encuesta" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                      <div class="modal-content">
                          <div class="modal-header">
                                <h3 class="modal-title text-center text-info">Mensaje de Encuesta</h3>
                          </div>
                          <div class="modal-body text-center" id="modal_encuesta_body">  <h4 class='text-danger'>Tienes una Encuesta Pendiente</h4></div>
                          <div class="modal-footer text-center">
                            <a href='#' class='btn btn-primary' id='link_form_encuesta'>Realizar Encuesta</a>
                          </div>
                    </div>
                  </div>
                </div>
                <!--fin del modal mensaje encuesta -->
           <?php  } ?>

           <!--modal form encuesta -->
           <div class="modal fade bs-example-modal-lg" data-backdrop="static" tabindex="-1" role="dialog" id="modal_form_encuesta" aria-labelledby="myModalLabel" aria-hidden="true">
             <div class="modal-dialog modal-lg">
                 <div class="modal-content">
                     <div class="modal-header">
                           <h3 class="modal-title text-center text-info">Danos tu Opinion Sobre el Soporte Realizado </h3>
                     </div>
                     <div class="modal-body text-center" id="modal_form_encuesta_body">
                       <?php echo form_open(base_url('usuarios/Usuarios/registrar_encuesta'),array('class'=>'form center-block novalidate','role'=>'form','id'=>'form_encuesta'));?>
                        <caption><p class='text-danger text-left'>Todos los Campos son Obligatorios</p></caption>
                        <div class='form-group'>
                          <p class='text-left text-primary'>1)¿Cual es la apreciación en cuanto a la calidad del servicio prestado ?</p>
                          <select class='form-control' name='1' id='1' required>
                            <option value=''>Selecciones una Opcion</option>
                            <option value='regular' data-valor='1'>Regular</option>
                            <option value='bueno' data-valor='2'>Bueno</option>
                            <option value='excelente' data-valor='3'>Excelente</option>
                          </select>
                          <div class='alert alert-danger' id='error_1'></div>
                        </div>

                        <div class='form-group'>
                          <p class='text-left text-primary'>2)¿Considera usted, que el tiempo invertido en el soporte de su solicitud fue el adecuado ?</p>
                          <select class='form-control' name='2' id='2' required>
                            <option value=''>Selecciones una Opcion</option>
                            <option value='regular' data-valor='1'>Regular</option>
                            <option value='bueno' data-valor='2'>Bueno</option>
                            <option value='excelente' data-valor='3'>Excelente</option>
                          </select>
                          <div class='alert alert-danger' id='error_2'></div>
                        </div>

                        <div class='form-group'>
                          <p class='text-left text-primary'>3)¿Como considera usted, el desempeño de su equipo luego del soporte realizado ?</p>
                          <select class='form-control' name='3' id='3' required>
                            <option value=''>Selecciones una Opcion</option>
                            <option value='regular' data-valor='1'>Regular</option>
                            <option value='bueno' data-valor='2'>Bueno</option>
                            <option value='excelente' data-valor='3'>Excelente</option>
                          </select>
                          <div class='alert alert-danger' id='error_3'></div>
                        </div>

                        <div class='form-group'>
                          <p class='text-left text-primary'>4)¿Como considera usted, la presencia y profesionalidad del tecnico que ejecuto el soporte ?</p>
                          <select class='form-control' name='4' id='4' required>
                            <option value=''>Selecciones una Opcion</option>
                            <option value='regular' data-valor='1'>Regular</option>
                            <option value='bueno' data-valor='2'>Bueno</option>
                            <option value='excelente' data-valor='3'>Excelente</option>
                          </select>
                          <div class='alert alert-danger' id='error_4'></div>
                        </div>

                        <div class='form-group' id='opcion_regular'>
                          <p class='text-left text-primary'>Si el nivel de valoracion es regular exprese ¿ porque ?</p>
                          <textarea type='text' class='form-control' name='porque_regular' id='porque_regular'></textarea>
                          <div class='alert alert-danger text-left' id='error_porque_regular'></div>
                        </div>
                        <div class='form-group text-center'>
                          <button type='submit' class='btn btn-primary'>Registrar <span class='glyphicon glyphicon-ok-sign'></span></button>
                          <button type='reset' class='btn btn-danger'>Limpiar <span class='glyphicon glyphicon-remove-sign'></span></button>
                        </div>
                        <div class='form-group'>
                        <input type='hidden' name='idsolicitud' id='idsolicitud' value=<?php echo $encuesta[0]['idsolicitud'];?>>
                        </div>
                       <?php echo form_close()?>
                     </div>
               </div>
             </div>
           </div>
           <!--fin del modal form encuesta -->

       <section id="section">

           <div class='table table-responsive'>
           <table class='table table-hover'>
             <caption><h3 class='text-center center-block text-primary'>Bitacora de Solicitud, Soporte y Asistencia Tecnica</h3></caption>
             <thead>
               <tr>
                 <th class='text-info'>Numero Solicitud</th>
                 <th class='text-info'>Motivo Solicitud</th>
                 <th class='text-info'>Fecha Solicitud</th>
                 <th class='text-info'>Estado Solicitud</th>
               </tr>
             </thead>
             <tbody>

                <?php foreach ($solicitudes as $solicitud ): ?>
                  </tr>
                    <td><?php echo $solicitud['idsolicitud']?></td>
                    <td><?php echo $solicitud['motivo']?></td>
                    <td><?php echo $solicitud['fecha_solicitud']?></td>
                    <?php
                    if($solicitud['estado_solicitud']==0)
                    {
                      ?>
                        <td class='text-danger'>Pendiente</td>
                      <?php
                    }
                    else
                    {
                      ?>
                        <td class='text-success'>Procesado</td>
                      <?php
                    }
                     ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
          </table>
        </div>
       </section>
     </div>

   </div>
 </div>
  </body>
</html>
