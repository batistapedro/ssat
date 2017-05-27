<?php

class Administrador_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function registrar_usuarios($usuario='')
  {
    if($this->db->insert('usuarios',$usuario))
    {
      return TRUE;
    }
    else
    {
      return FALSE;
    }
  }

  public function buscar_usuarios($usuario='')
  {
    $this->db->select('u.idusuario,u.nombre,u.apellido,u.cedula,u.usuario,u.clave,u.cargo,u.correo,u.estado_usuario,g.idgerencia,g.gerencia,c.idcoordinacion,c.coordinacion');
    $this->db->like('u.usuario',$usuario);
    $this->db->from('usuarios as u');
    $this->db->join('gerencias_departamentos as g', 'g.idgerencia = u.gerencias_departamentos_idgerencia');
    $this->db->join('coordinaciones as c','c.idcoordinacion = u.coordinaciones_idcoordinacion');
    $query = $this->db->get();
    return $query->result_array();
  }

  public function extraer_usuarios()
  {

    $this->db->select('u.idusuario,u.nombre,u.apellido,u.cedula,u.usuario,u.clave,u.cargo,u.correo,u.estado_usuario,g.idgerencia,g.gerencia,c.idcoordinacion,c.coordinacion');
    $this->db->from('usuarios as u');
    $this->db->join('gerencias_departamentos as g', 'g.idgerencia = u.gerencias_departamentos_idgerencia');
    $this->db->join('coordinaciones as c','c.idcoordinacion = u.coordinaciones_idcoordinacion');
    $query = $this->db->get();
    return $query->result_array();
  }

  public function editar_datos_usuarios($id='',$campo='',$nuevovalor='')
  {
    $this->db->where('idusuario',$id);
    if($this->db->update('usuarios',array($campo=>$nuevovalor)))
    {
      return TRUE;
    }
    else
    {
      return FALSE;
    }
  }

  public function cambiar_clave($id='',$clave='',$tipoUsuario='',$nclave='')
  {
    $this->db->where('id',$id);
    $this->db->where('clave',$clave);
    $this->db->where('tipo_usuario',$tipoUsuario);
    if($this->db->update('usuarios',array('clave'=>$nclave)))
    {
      return TRUE;
    }
    else
    {
      return FALSE;
    }

  }

  public function procesar_solicitud_pendiente($id='',$fecha_respuesta='')
  {
    $this->db->where('idsolicitud',$id);
    if($this->db->update('solicitudes',array('fecha_respuesta'=>$fecha_respuesta,'estado_solicitud'=>1)))
    {
      return TRUE;
    }
    else
    {
      return FALSE;
    }

  }



  public function extraer_gerencias()
  {
    $this->db->order_by('idgerencia','ASC');
    $query = $this->db->get('gerencias_departamentos');
    return $query->result_array();
  }

  public function extraer_coordinacion($valor='')
  {
    $this->db->select('idcoordinacion,coordinacion');
    $this->db->where('gerencias_departamentos_idgerencia',intval($valor));
    $this->db->order_by('coordinacion','ASC');
    $query = $this->db->get('coordinaciones');
    return $query->result_array();
  }

  public function extrar_datos_usuarios_correo($idusuario='')
  {
    $this->db->select('u.nombre,u.apellido,u.usuario,u.correo,s.idsolicitud,s.fecha_solicitud');
    $this->db->where('u.idusuario',$idusuario);
    $this->db->from('usuarios as u');
    $this->db->join('solicitudes as s', 's.usuarios_idusuario ='.$idusuario);
    $query = $this->db->get();
    return $query->result_array();
  }



public function extraer_solicitudes_pendiente()
{
	$this->db->select('u.idusuario,u.nombre,u.apellido,u.usuario,u.cargo,s.idsolicitud,s.motivo,s.fecha_solicitud,s.descripcion,g.gerencia,c.coordinacion');
	$this->db->where('u.tipo_usuario',1);
	$this->db->where('s.estado_solicitud',0);
	$this->db->from('usuarios as u');
	$this->db->join('solicitudes as s', 's.usuarios_idusuario = u.idusuario');
  $this->db->join('gerencias_departamentos as g', 'g.idgerencia = u.gerencias_departamentos_idgerencia');
  $this->db->join('coordinaciones as c','c.idcoordinacion= u.coordinaciones_idcoordinacion');
	$query = $this->db->get();
	return $query->result_array();
}

public function extraer_solicitud_reportes_todas($fecha_desde='',$fecha_hasta='')
{
      $this->db->select('u.nombre,u.apellido,u.cargo,s.fecha_solicitud,s.fecha_respuesta,s.motivo,s.via_solicitud,g.gerencia,c.coordinacion');
      $this->db->where('s.fecha_solicitud >=',$fecha_desde);
      $this->db->where('s.fecha_solicitud <=',$fecha_hasta);
      $this->db->order_by('s.fecha_solicitud','ASC');
      $this->db->from('usuarios as u');
      $this->db->join('solicitudes as s','s.usuarios_idusuario = u.idusuario');
      $this->db->join('gerencias_departamentos as g','g.idgerencia = u.gerencias_departamentos_idgerencia');
      $this->db->join('coordinaciones as c','c.idcoordinacion = u.coordinaciones_idcoordinacion');
      //$this->db->join('encuestas','encuestas.solicitudes_idsolicitud = solicitudes.idsolicitud');
      $query = $this->db->get();
      return $query->result_array();

}

public function extraer_solicitud_reportes_gerencias($gerencias='',$fecha_desde='',$fecha_hasta='')
{
  $this->db->select('u.nombre,u.apellido,u.cargo,s.fecha_solicitud,s.fecha_respuesta,s.motivo,s.via_solicitud,g.gerencia,c.coordinacion');
  $this->db->where('g.idgerencia',$gerencias);
  $this->db->where('s.fecha_solicitud >=',$fecha_desde);
  $this->db->where('s.fecha_solicitud <=',$fecha_hasta);
  $this->db->order_by('s.fecha_solicitud','ASC');
  $this->db->from('usuarios as u');
  $this->db->join('solicitudes as s','s.usuarios_idusuario = u.idusuario');
  $this->db->join('gerencias_departamentos as g','g.idgerencia = u.gerencias_departamentos_idgerencia');
  $this->db->join('coordinaciones as c','c.idcoordinacion = u.coordinaciones_idcoordinacion');
  $query = $this->db->get();
  return $query->result_array();
}


public function extraer_encuesta_todas($fecha_desde='',$fecha_hasta='')
{
  $this->db->select('u.nombre,u.apellido,u.cargo,s.fecha_solicitud,s.fecha_respuesta,s.motivo,g.gerencia,c.coordinacion,e.r1,e.r2,e.r3,e.r4,e.fecha_encuesta,e.observacion,e.calificacion');
  $this->db->where('e.fecha_encuesta >=',$fecha_desde);
  $this->db->where('e.fecha_encuesta <=',$fecha_hasta);
  $this->db->order_by('s.fecha_solicitud','ASC');
  $this->db->from('usuarios as u');
  $this->db->join('solicitudes as s','s.usuarios_idusuario = u.idusuario');
  $this->db->join('gerencias_departamentos as g','g.idgerencia = u.gerencias_departamentos_idgerencia');
  $this->db->join('coordinaciones as c','c.idcoordinacion = u.coordinaciones_idcoordinacion');
  $this->db->join('encuestas as e','e.solicitudes_idsolicitud = s.idsolicitud');
  $query = $this->db->get();
  return $query->result_array();
}

public function encuestas_porcentajes($fecha_desde,$fecha_hasta)
{
  $query = $this->db->query("SELECT encuestas.calificacion , COUNT( encuestas.idencuesta ) AS cantidad FROM encuestas where encuestas.fecha_encuesta between '$fecha_desde' and '$fecha_hasta' GROUP BY encuestas.calificacion ORDER BY encuestas.calificacion;");
  return $query->result_array();
}

public function extraer_encuesta_pendiente_todas($fecha_desde='',$fecha_hasta='')
{
  $this->db->select('u.nombre,u.apellido,u.cargo,s.fecha_solicitud,s.fecha_respuesta,s.motivo,g.gerencia,c.coordinacion');
  $this->db->where('s.estado_encuesta',0);
  $this->db->where('s.fecha_solicitud >=',$fecha_desde);
  $this->db->where('s.fecha_solicitud <=',$fecha_hasta);
  $this->db->order_by('s.fecha_solicitud','ASC');
  $this->db->from('usuarios as u');
  $this->db->join('solicitudes as s','s.usuarios_idusuario = u.idusuario');
  $this->db->join('gerencias_departamentos as g','g.idgerencia = u.gerencias_departamentos_idgerencia');
  $this->db->join('coordinaciones as c','c.idcoordinacion = u.coordinaciones_idcoordinacion');
  //$this->db->join('encuestas as e','e.solicitudes_idsolicitud = s.idsolicitud');
  $query = $this->db->get();
  return $query->result_array();
}


public function modificar_gerencia_coordinacion($usuario,$idgerencia,$idcoordinacion)
{
  $this->db->where('usuario',$usuario);
  if($this->db->update('usuarios',array('gerencias_departamentos_idgerencia'=>$idgerencia,'coordinaciones_idcoordinacion'=>$idcoordinacion)))
  {
    return TRUE;
  }
  else
  {
    return FALSE;
  }
}



}




 ?>
