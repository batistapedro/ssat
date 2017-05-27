
<?php if($cantidad>0):?>

<div class='table table-responsive'>
  <table class='table table-hover table-bordered'>
    <caption><h3 class='text-primary text-center'>Reportes de Encuestas</h3></caption>
    <thead>
      <tr>
        <th class='text-primary'>Unidad Solicitante</th>
        <th class='text-primary'>Nombre Apellido</th>
        <th class='text-primary'>Cargo</th>
        <th class='text-primary'>Motivo Solicitud</th>
        <th class='text-primary'>Fecha Solicitud</th>
        <th class='text-primary'>Fecha Respuesta</th>
        <th class='text-primary'>Fecha Encuesta</th>
        <th class='text-primary'>R1</th>
        <th class='text-primary'>R2</th>
        <th class='text-primary'>R3</th>
        <th class='text-primary'>R4</th>
        <th class='text-primary'>Observacion</th>
        <th class='text-primary'>Calificacion</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($todas as $toda):?>
      <tr>
        <?php if($toda['coordinacion']=='n/a'):?>
        <td><?php echo $toda['gerencia'];?></td>
        <?php else :?>
        <td><?php echo $toda['gerencia'].' / '.$toda['coordinacion'];?></td>
        <?php endif;?>
        <td><?php echo $toda['nombre'].' '.$toda['apellido'];?></td>
        <td><?php echo $toda['cargo'];?></td>
        <td><?php echo $toda['motivo'];?></td>
        <td><?php echo $toda['fecha_solicitud'];?></td>
        <td><?php echo $toda['fecha_respuesta'];?></td>
        <td><?php echo $toda['fecha_encuesta'];?></td>
        <td><?php echo $toda['r1'];?></td>
        <td><?php echo $toda['r2'];?></td>
        <td><?php echo $toda['r3'];?></td>
        <td><?php echo $toda['r4'];?></td>
        <td><button class='btn btn-dafault' id='btn_observacion_encuesta' data-observacion="<?php echo $toda['observacion'];?>"> Ver <span class='glyphicon glyphicon-eye-open'></span></button></td>
        <?php if($toda['calificacion']=='excelente'):?>
        <td class='text-success'><?php echo ucwords($toda['calificacion']);?></td>
      <?php else: if($toda['calificacion']=='bueno'):?>
        <td class='text-info'><?php echo ucwords($toda['calificacion']);?></td>
        <?php else :?>
        <td class='text-danger'><?php echo ucwords($toda['calificacion']);?></td>
      <?php endif;endif;?>
      <tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='text-center'>Cantidad de Encuesta Hasta la Fecha: <span class='badge'><?php echo $cantidad;?></span></div>
  <br>
  <br>
  <table class='table table-bordered table-hover' style='width:300px;'>
    <thead>
      <tr>
        <th class='text-primary'>Calificaciones</th>
        <th class='text-primary'>Cantidad</th>
      </tr>
    </thead>
    <tbody>
  <?php foreach($porcentajes as $porcentaje):?>
    <tr>
      <?php if($porcentaje['calificacion']=='excelente'):?>
      <td class='text-success'><?php echo ucwords($porcentaje['calificacion']);?></td>
      <td><?php echo $porcentaje['cantidad'];?></td>
    <?php else: if($porcentaje['calificacion']=='bueno'):?>
      <td class='text-info'><?php echo ucwords($porcentaje['calificacion']);?></td>
      <td><?php echo $porcentaje['cantidad'];?></td>
      <?php else :?>
      <td class='text-danger'><?php echo ucwords($porcentaje['calificacion']);?></td>
      <td><?php echo $porcentaje['cantidad'];?></td>
    <?php endif;endif;?>
    </tr>
     <?php endforeach;?>
   </tbody>
    </table>
</div>
<?php else :?>
<h3 class='text-center text-danger' id='cantidad_solicitudes'>No Hay Encuesta Registrada Hasta la Fecha</h3>

<?php endif;?>