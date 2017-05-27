<style>
  #form_enviar_solicitud
  {
    width:60%;
  }
</style>

<?php echo form_open(base_url('usuarios/Usuarios/registrar_solicitud'),array('class'=>'form center-block','role'=>'form','id'=>'form_enviar_solicitud'));?>
  <caption><h3 class='text-center text-info'>Registrar Solicitud</h3></caption>
  <div class='form-group'>
    <label for='solicitud' class='text-left text-primary'>Motivo de la solicitud :</label>
    <input type='text' class="form-control" id='solicitud' name='solicitud' placeholder="Digite el motivo de la solicitud" maxlength="30" required>
  </div>
  <div class='form-group'>
    <label for='descripcion' class='text-left text-primary'>Descripcion de la solicitud :</label>
    <textarea type='text' class="form-control textarea" id='descripcion' name='descripcion' placeholder="Digite la descripcion de la solicitud" required></textarea>
  </div>
  <div class='form-group text-center'>
    <button type='submit' class='btn btn-primary' >Enviar <span class='glyphicon glyphicon-ok-sign'></span></button>
    <button type='reset' class='btn btn-danger' >Limpiar <span class='glyphicon glyphicon-remove-sign'></span></button>
  </div>
<?php echo form_close();?>
