<style>
  #form_clave
  {
    width: 55%;
    height: auto;
  }
  #errorClave, #errorNuevaClave, #errorConfirmarNuevaClave
  {
    display: none;
  }
</style>
<script>
  $(document).ready(function(){

    $("#form_clave").on('submit',function(e){
      e.preventDefault();
      $.ajax({
        type : $(this).attr('method'),
        url : $(this).attr('action'),
        data : $(this).serialize(),
        success: function(respuesta)
        {
          json = JSON.parse(respuesta);
          $("#errorClave,#errorNuevaClave,#errorConfirmarNuevaClave").css('display','none').html('');
          if(json.respuesta=="error")
          {
            if(json.clave)
            {
              $("#errorClave").css('display','block').append(json.clave);
            }
            if(json.nueva)
            {
              $("#errorNuevaClave").css('display','block').append(json.nueva);
            }
            if(json.confignueva)
            {
              $("#errorConfirmarNuevaClave").css('display','block').append(json.confignueva);
            }

            if(json.validar)
            {
              $("#mensajetodos").css({'display':'block','background-color':'red'}).html("<h3>"+json.validar+"</h3>").fadeOut(6000);
            }

          }
          else
          {
              //$("#mensajetodos").css({'display':'block','background-color':'green'}).html("<h3>"+json.exito+"</h3>").fadeOut(6000);
              $("#distintosErrores_body").html("<h3 class='text-success'>"+json.exito+"</h3>");
              $("#distintosErrores_title").html('<h3 class="text-primary">Mensaje del Sistema</h3>');
              $("#distintosErrores").modal('show');
              $("#claveActual, #nuevaClave, #confirmarNuevaClave").val('');
          }
        }
      });
    });
    $("#limpiarClave").on('click',function(){
      $("#errorClave,#errorNuevaClave,#errorConfirmarNuevaClave").css('display','none').html('');
      $("#claveActual, #nuevaClave, #confirmarNuevaClave").val('');
    });

  });
</script>

<?php echo form_open(base_url('ConfigClave/cambiar_clave'),array('class'=>'form center-block','role'=>'form','id'=>'form_clave'));?>
  <caption><h3 class="text-center text-info">Cambiar Clave</h3></caption>
  <p class="text-left text-danger">Todos Los Campos son Obligatorio</p>
<div class="form-group">
<label for="claveActual" class="text-info">Clave Actual :</label>
<input type="password" class="form-control" name="claveActual" id="claveActual">
</div>
<div class="alert alert-danger text-center" id="errorClave" role="alert"></div>
<div class="form-group">
<label for="nuevaClave" class="text-info">Nueva Clave :</label>
<input type="password" class="form-control" name="nuevaClave" id="nuevaClave">
</div>
<div class="alert alert-danger text-center" id="errorNuevaClave" role="alert"></div>
<div class="form-group">
<label for="nuevaClave" class="text-info">Confirmar Nueva Clave :</label>
<input type="password" class="form-control" name="confirmarNuevaClave" id="confirmarNuevaClave">
</div>
<div class="alert alert-danger text-center" id="errorConfirmarNuevaClave" role="alert"></div>
<div class="form-group text-center">
<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok-sign"></span> Cambiar Clave</button>
<button type="button" class="btn btn-danger" id="limpiarClave"><span class="glyphicon glyphicon-remove-sign"></span> Limpiar</button>
</div>
<?php echo form_close();?>
