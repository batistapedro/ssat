<script>
$(document).ready(function(){
  $('#form_encuesta_pendientes_fecha_desde,#form_encuesta_pendientes_fecha_hasta').datepicker({
  dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
  changeYear: true,
  changeMonth:true,
  monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
  minDate: new Date(2016, 1 - 1, 1),
  dateFormat:"yy/mm/dd"
});
});
</script>

<?php echo form_open(base_url('administrador/Administrador/ver_encuestas_pendientes'),array('class'=>'form center-block','role'=>'form','id'=>'ver_encuestas_pendientes'));?>
<caption><h3 class='text-center text-primary'>Formulario de Encuestas Pendientes</h3></caption>

<div class='row'>
  <div class='col-sm-6 col-md-6 col-lg-6'>
    <div class="form-group">
      <label class='text-primary'>Fecha Desde :</label>
      <input type='text' class='form-control ' name='form_encuesta_pendientes_fecha_desde' id='form_encuesta_pendientes_fecha_desde' placeholder='Digite la fecha desde YYYY/MM/DD' required>
    </div>
  </div>

  <div class='col-sm-6 col-md-6 col-lg-6'>
    <div class='form-group'>
      <label class='text-primary'>Fecha Hasta :</label>
      <input type='text' class='form-control ' name='form_encuesta_pendientes_fecha_hasta' id='form_encuesta_pendientes_fecha_hasta' placeholder='Digite la fecha hasta YYYY/MM/DD' required>
    </div>
  </div>
</div>
<div class='form-group text-center'>
  <button type='submit' class='btn btn-success '> <span class='glyphicon glyphicon-search'></span> Buscar</button>
  <button type='reset' class='btn btn-danger '> <span class='glyphicon glyphicon-remove-sign'></span> Limpiar</button>
</div>
<?php echo form_close();?>
<hr>
<div id='section_encuesta_pendiente_usuario'></div>
