<style>
.editableUsuario span{
display:block;
}
.editableUsuario span:hover{
background:url("./public/img/editar.png") 90% 50% no-repeat;
cursor:pointer;
}
.guardarEditableUsuario{
background:url("./public/img/guardar.png") 0 0 no-repeat;
}
a.enlace{

  display:inline-block;
  width:24px;
  height:24px;
  margin:0 0 0 5px;
  overflow:hidden;
  text-indent:-999em;
  vertical-align:middle;
}
.cancelarEditableUsuario{
background:url("./public/img/cancelar.png") 0 0 no-repeat;
}
td input{
height:24px;
width:140px;
border:1px solid #ddd;
padding:0 5px;
margin:0;
border-radius:6px;
vertical-align:middle;
}
#formBuscarUsuarios
{
  width: 40%;
  min-width:40%;
  padding: 3px;
}
#errorFormBuscarUsuario
{
  display:none;
}
</style>
<script>
  $(document).on('keyup','#formBuscarUsuarios',function(e){
    e.preventDefault();
    $.ajax({
      'type':$(this).attr('method'),
      'url':$(this).attr('action'),
      'data':$(this).serialize(),
      success: function(respuesta){
        json = JSON.parse(respuesta);
        $("#errorFormBuscarUsuario").css('display','none').html('');
        if(json.respuesta=='exito')
        {
          $('#tablaUsuario tbody').empty();
          for(i=0;i<json.usuario.length;i++)
          {
            if(json.usuario[i].estado_usuario==1)
            {
              estado_usuario="<button type='button' class='btn btn-success' id='activador_usuarios' title='desactivar'>Activo</button>";
            }
            else
            {
              estado_usuario="<button type='button' class='btn btn-default'  id='activador_usuarios' title='activar' >Inactivo</button>";
            }
            $('#tablaUsuario #tbodyUsuario').append("<tr><td class='hide url'>http://localhost/ssat/administrador/Administrador/editar_datos_usuarios</td>"
            +"<td class='hide id'>"+json.usuario[i].idusuario+"</td>"
            +"<td class='hide idgerencia'>"+json.usuario[i].idgerencia+"</td>"
            +"<td class='hide idcoordinacion'>"+json.usuario[i].idcoordinacion+"</td>"
            +"<td class='editableUsuario' data-campo='nombre'><span>"+json.usuario[i].nombre+"</span></td>"
            +"<td class='editableUsuario' data-campo='apellido'><span>"+json.usuario[i].apellido+"</span></td>"
            +"<td class='editableUsuario' data-campo='cedula'><span>"+json.usuario[i].cedula+"</span></td>"
            +"<td class='editableUsuario usuario' data-campo='usuario'><span>"+json.usuario[i].usuario+"</span></td>"
            +"<td class='editableUsuario' data-campo='clave'><span>"+json.usuario[i].clave+"</span></td>"
            +"<td class='editableUsuario gerencia' data-campo='gerencia'><span>"+json.usuario[i].gerencia+"</span></td>"
            +"<td class='editableUsuario coordinacion' data-campo='coordinacion'><span>"+json.usuario[i].coordinacion+"</span></td>"
            +"<td class='editableUsuario' data-campo='cargo'><span>"+json.usuario[i].cargo+"</span></td>"
            +"<td class='editableUsuario' data-campo='correo'><span>"+json.usuario[i].correo+"</span></td>"
            +"<td>"+estado_usuario+"</td>"+"</tr>");

          }
        }
        else
        {
          if(json.error)
          {
            $("#errorFormBuscarUsuario").css('display','block').append(""+json.error+"");
          }
          else
          {
            if(json.error_usuario)
            {
              $("#errorFormBuscarUsuario").css('display','block').append(""+json.error_usuario+"");
            }

          }

        }
      }

    });

  });
</script>
<?=form_open(base_url('administrador/Administrador/buscarUsuarios'),array('class'=>'form-inline ','role'=>'form','id'=>'formBuscarUsuarios'));?>
  <div class='form-group'>
    <label for='buscarUsuariosUsuario' class='text-primary' >Buscar Usuarios: </label>
    <input type='text' class='form-control' name='buscarUsuariosUsuario' placeholder="Digite Usuario">
  </div>
  <div class="form-group">
    <div class="form-control alert alert-danger" role='alert' id='errorFormBuscarUsuario'></div>
  </div>

<?=form_close();?>
<div class='table table-responsive'>

  <table class='table table-bordered' id='tablaUsuario'>
    <caption><h3 class='text-center text-primary'>Reportes de Usuarios</h3></caption>
    <thead>
      <tr>
        <th class='hide'>Id</th>
        <th class='hide'>idgerencia</th>
        <th class='hide'>idcoordinacion</th>
        <th class='text-info text-center'>Nombre</th>
        <th class='text-info text-center'>Apellido</th>
        <th class='text-info text-center'>Cedula</th>
        <th class='text-info text-center'>Usuario</th>
        <th class='text-info text-center'>Clave</th>
        <th class='text-info text-center'>Gerencia / Departamento</th>
        <th class='text-info text-center'>Coordinacion</th>
        <th class='text-info text-center'>Cargo</th>
        <th class='text-info text-center'>Correo</th>
        <th class='text-info text-center'>Estado Usuario</th>
      </tr>
    </thead>
    <tbody id='tbodyUsuario'>
        <?php foreach($usuarios as $usuario):?>
      <tr>
          <td class='hide url'><?php echo base_url('administrador/Administrador/editar_datos_usuarios');?></td>
          <td class='hide id'><?php echo $usuario['idusuario'];?></td>
          <td class='hide idgerencia'><?php echo $usuario['idgerencia'];?></td>
          <td class='hide idcoordinacion'><?php echo $usuario['idcoordinacion'];?></td>
          <td class='editableUsuario' data-campo='nombre'><span><?php echo $usuario['nombre'];?></span></td>
          <td class='editableUsuario' data-campo='apellido'><span><?php echo $usuario['apellido'];?></span></td>
          <td class='editableUsuario' data-campo='cedula'><span><?php echo $usuario['cedula'];?></span></td>
          <td class='editableUsuario usuario' data-campo='usuario'><span><?php echo $usuario['usuario'];?></span></td>
          <td class='editableUsuario' data-campo='clave'><span><?php echo $usuario['clave'];?></span></td>
          <td class='editableUsuario gerencia' data-campo='gerencia'><span><?php echo $usuario['gerencia'];?></span></td>
          <td class='editableUsuario coordinacion' data-campo='coordinacion'><span><?php echo $usuario['coordinacion'];?></span></td>
          <td class='editableUsuario' data-campo='cargo'><span><?php echo $usuario['cargo'];?></span></td>
          <td class='editableUsuario' data-campo='correo'><span><?php echo $usuario['correo'];?></span></td>
          <td class='text-center'>
            <?php if($usuario['estado_usuario']==1):?>
              <button type='button' class='btn btn-success' id='activador_usuarios' title='desactivar'>Activo</button>
            <?php else :?>
              <button type='button' class='btn btn-default'  id='activador_usuarios' title='activar' >Inactivo</button>
            <?php endif;?>
          </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div id='cantida_usuario'>Cantidad : <?php echo $cantida_usuario;?></div>
</div>
