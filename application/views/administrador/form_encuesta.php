<script>
$(document).ready(function(){
  $('#encuesta_usuario_fecha_desde,#encuesta_usuario_fecha_hasta').datepicker({
  dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
  changeYear: true,
  changeMonth:true,
  monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
  minDate: new Date(2016, 1 - 1, 1),
  dateFormat:"yy/mm/dd"
});


});
</script>
<?php echo form_open(base_url('administrador/Administrador/ver_encuestas'),array('class'=>'form center-block','role'=>'form','id'=>'form_encuesta'));?>
<caption><h3 class='text-center text-primary'>Reportes de Encuestas</h3></caption>

<div class='row'>
  <div class='col-sm-6 col-md-6 col-lg-6'>
    <div class="form-group">
      <label class='text-primary'>Fecha Desde :</label>
      <input type='text' class='form-control ' name='encuesta_usuario_fecha_desde' id='encuesta_usuario_fecha_desde' placeholder='Digite la fecha desde YYYY/MM/DD' required>
    </div>
  </div>

  <div class='col-sm-6 col-md-6 col-lg-6'>
    <div class='form-group'>
      <label class='text-primary'>Fecha Hasta :</label>
      <input type='text' class='form-control ' name='encuesta_usuario_fecha_hasta' id='encuesta_usuario_fecha_hasta' placeholder='Digite la fecha hasta YYYY/MM/DD' required>
    </div>
  </div>
</div>
<div class='form-group text-center'>
  <button type='submit' class='btn btn-success '> <span class='glyphicon glyphicon-search'></span> Buscar</button>
  <button type='reset' class='btn btn-danger '> <span class='glyphicon glyphicon-remove-sign'></span> Limpiar</button>
</div>
<?php echo form_close();?>
<hr>
<div id='section_encuesta_usuario'></div>
