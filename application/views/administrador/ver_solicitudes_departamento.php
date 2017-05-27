<?php if($cantidad>0): ?>

<div class='table table-responsive'>
  <table class='table table-hover table-bordered'>
    <caption><h3 class='text-primary text-center'>Reportes de Las Solicitudes Por Gerencia o Departamento</h3></caption>
    <thead>
      <tr>
        <th class='text-primary'>Unidad Solicitante</th>
        <th class='text-primary'>Nombre Apellido</th>
        <th class='text-primary'>Cargo</th>
        <th class='text-primary'>Motivo Solicitud</th>
        <th class='text-primary'>Fecha Solicitud</th>
        <th class='text-primary'>Fecha Respuesta</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($gerencias as $gerencia):?>
      <tr>
        <?php if($gerencia['coordinacion']=='n/a'):?>
        <td><?php echo $gerencia['gerencia'];?></td>
        <?php else :?>
        <td><?php echo $gerencia['gerencia'].' / '.$gerencia['coordinacion'];?></td>
        <?php endif;?>
        <td><?php echo $gerencia['nombre'].' '.$gerencia['apellido'];?></td>
        <td><?php echo $gerencia['cargo'];?></td>
        <td><?php echo $gerencia['motivo'];?></td>
        <td><?php echo $gerencia['fecha_solicitud'];?></td>
        <td><?php echo $gerencia['fecha_respuesta'];?></td>
      <tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='text-center' id='cantidad_solicitudes'>Numeros de Solicitudes: <span class='badge'><?php echo $cantidad;?></span></div>
  <br>
    <a href=<?php echo base_url('administrador/Administrador/pdf_solicitudes_departamento').$url;?> class='btn btn-success btn_generar_pdf_departamento'><span class='glyphicon glyphicon-file'></span> Generar PDF<span class='glyphicon glyphicon-cloud-download'></span></a>
</div>
<?php else :?>
<h3 class='text-center text-danger' id='cantidad_solicitudes'>No Hay Solicitudes Para Esta Gerencia o Departamento</h3>
<?php endif;?>
