<script>
$(document).ready(function(){
  $('#solicitud_usuario_fecha_desde,#solicitud_usuario_fecha_hasta').datepicker({
  dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
  changeYear: true,
  changeMonth:true,
  monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
  minDate: new Date(2016, 1 - 1, 1),
  dateFormat:"yy/mm/dd"
});


});
</script>
<?php echo form_open(base_url('administrador/Administrador/buscar_solicitud_usuarios'),array('class'=>'form center-block','role'=>'form','id'=>'form_buscar_solicitud'));?>
  <caption><h3 class='text-center text-primary'>Reportes Solicitud Usuarios</h3></caption>
  <div class='form-group'>
    <label class='text-primary'>Generar Reportes Por :</label>
    <select class='form-control' name='select_reportes_opcion' id='select_reportes_opcion' required>
      <option value=''>Selecciones una Opción</option>
      <option value='gerencias'>Gerencias o Departamentos</option>
      <option value='todos'>Todos</option>
    </select>
  </div>
  <div class='form-group select_gerencias hide'>
    <label class='text-primary'>Seleccione la Gerencia o Departamento:</label>
    <select class='form-control' name='reportes_gerencias' id='reportes_gerencias'>
      <option value=''>Selecione una Opción</option>
      <?php foreach($gerencias as $gerencia): ?>
      <option value=<?php echo $gerencia['idgerencia'];?>><?php echo $gerencia['gerencia'];?></option>
      <?php endforeach;?>
    </select>
  </div>
  <div class='row'>
    <div class='col-sm-6 col-md-6 col-lg-6'>
      <div class="form-group">
        <label class='text-primary'>Fecha Desde :</label>
        <input type='text' class='form-control ' name='solicitud_usuario_fecha_desde' id='solicitud_usuario_fecha_desde' placeholder='Digite la fecha desde YYYY/MM/DD' required>
      </div>
    </div>

    <div class='col-sm-6 col-md-6 col-lg-6'>
      <div class='form-group'>
        <label class='text-primary'>Fecha Hasta :</label>
        <input type='text' class='form-control ' name='solicitud_usuario_fecha_hasta' id='solicitud_usuario_fecha_hasta' placeholder='Digite la fecha hasta YYYY/MM/DD' required>
      </div>
    </div>
  </div>
  <div class='form-group text-center'>
    <button type='submit' class='btn btn-success '> <span class='glyphicon glyphicon-search'></span> Buscar</button>
    <button type='reset' class='btn btn-danger '> <span class='glyphicon glyphicon-remove-sign'></span> Limpiar</button>
  </div>
<?php echo form_close();?>
<hr>
<div class='section_solictiud_usuario' id='section_solictiud_usuario'></div>
