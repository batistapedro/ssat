<style>
#error_nombre,#error_apellido,#error_usuario,#error_cedula,#error_gerencia_departamento,#error_coordinacion,#error_cargo,#error_correo,#error_tipo_usuario,#error_estado_usuario
{
  display: none;
}
</style>

<script>
  $(document).ready(function(){
    var usuario;
    var correo='@fondobolivar.gob.ve';

    $('#nombre').blur(function(){
      usuario = $('#nombre').val().slice(0,1)+ $('#apellido').val();
      $("#usuario").val(usuario);
      $('#correo').val(usuario+correo);
    });

    $('#apellido').blur(function(){
      usuario = $('#nombre').val().slice(0,1)+$('#apellido').val();
      $("#usuario").val(usuario);
      $('#correo').val(usuario+correo);
    });
  });
</script>

<?php echo form_open(base_url('administrador/Administrador/registrar_usuarios'),array('class'=>'form center-block','role'=>'form','id'=>'form_registrar_usuarios'));?>
<caption><h3 class='text-center text-info'>Registrar Usuarios</h3></caption>
<p class='text-danger'>Todo Los Campos Son Obligatorios</p>
<div class='row'>
  <div class='col-sm-6 col-md-6 col-lg-6'>
    <div class="form-group">
      <label for='nombre' class='text-info'>Nombre : </label>
      <input type='text' class='form-control' name='nombre' id='nombre' placeholder='Digite Nombre' required maxlength="15">
      <div class='alert alert-danger' id='error_nombre'></div>
    </div>

    <div class="form-group">
      <label for='apellido' class='text-info'>Apellido : </label>
      <input type='text' class='form-control' name='apellido' id='apellido' placeholder='Digite Apellido' required maxlength="15">
      <div class='alert alert-danger' id='error_apellido'></div>
    </div>

    <div class="form-group">
      <label for='clave' class='text-info'>Cedula : </label>
      <input type='text' class='form-control' name='cedula' id='cedula' placeholder='Digite cedula 19871554' required maxlength="12">
      <div class='alert alert-danger' id='error_cedula'></div>
    </div>

    <div class="form-group">
      <label for='usuario' class='text-info'>Usuario : </label>
      <input type='text' class='form-control' name='usuario' id='usuario' placeholder='Digite Usuario' required maxlength="20">
      <div class='alert alert-danger' id='error_usuario'></div>
    </div>



    <div class="form-group">
      <label for='gerencia_departamento' class='text-info'>Gerencia o Departamento : </label>
      <!--<input type='text' class='form-control' name='gerencia_departamento' id='gerencia_departamento' placeholder='Digite Gerencia o Departemento' required maxlength="70">-->
      <select class='form-control' name='gerencias_departamentos' id='gerencias_departamentos' data-url=<?php echo base_url('administrador/administrador/extraer_coordinacion');?>>
        <option value=''>Seleccione Departamento o Gerencia</option>
        <?php foreach($gerencias as $gerencia):?>
          <option value=<?php echo $gerencia['idgerencia'];?>><?php echo $gerencia['gerencia'];?></option>
        <?php endforeach;?>
      </select>
      <div class='alert alert-danger' id='error_gerencia_departamento'></div>
    </div>

  </div>

  <div class='col-sm-6 col-md-6 col-lg-6'>
    <div class="form-group">
      <label for='coordinacion' class='text-info'>Coordinacion : </label>
      <!--<input type='text' class='form-control' name='coordinacion' id='coordinacion' placeholder='Digite Coordinacion' required maxlength="70">-->
      <select class='form-control' name='coordinacion' id='coordinacion'>
      </select>
      <div class='alert alert-danger' id='error_coordinacion'></div>
    </div>

    <div class="form-group">
      <label for='cargo' class='text-info'>Cargo : </label>
      <input type='text' class='form-control' name='cargo' id='cargo' placeholder='Digite Cargo' required maxlength="20">
      <div class='alert alert-danger' id='error_cargo'></div>
    </div>

    <div class="form-group">
      <label for='correo' class='text-info'>Correo Electronico : </label>
      <input type='text' class='form-control' name='correo' id='correo' placeholder='Digite Correo Electronico' required maxlength="70">
      <div class='alert alert-danger' id='error_correo'></div>
    </div>

    <div class="form-group">
      <label for='tipo_usuario' class='text-info'>Tipo Usuario : </label>
      <select class='form-control' name='tipo_usuario' id='tipo_usuario' required>
        <option value=''>Seleccione El Tipo De Usuario</option>
        <option value='1'>Usuario</option>
        <option value='0'>Administrador</option>
      </select>
      <div class='alert alert-danger' id='error_tipo_usuario'></div>
    </div>

    <div class="form-group">
      <label for='estado_usuario' class='text-info'>Estado Usuario : </label>
      <select class='form-control' name='estado_usuario' id='estado_usuario' required>
        <option value='1'>Activo</option>
        <option value='0'>No Activo</option>
      </select>
      <div class='alert alert-danger' id='error_estado_usuario'></div>
    </div>
  </div>
</div>

<div class="form-group text-center">
  <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok-sign"></span> Registrar</button>
  <button type="reset" class="btn btn-danger"><span class="glyphicon glyphicon-remove-sign"></span> Limpiar</button>
</div>
<?php echo form_close();?>
