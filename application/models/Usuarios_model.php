<?php

/**
 *
 */
class Usuarios_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function verifcar_usuario($id='',$clave='',$tipoUsuario='')
  {
    $this->db->where('idusuario',$id);
    $this->db->where('clave',$clave);
    $this->db->where('tipo_usuario',$tipoUsuario);
    $query = $this->db->get('usuarios');
    return $query->num_rows();
  }


  public function extraer_solicitudes_procesada($id='')
  {
    $this->db->select('idsolicitud,motivo,fecha_solicitud,estado_solicitud,estado_encuesta');
    $this->db->limit(10);
    $this->db->order_by('idsolicitud','desc');
    $this->db->where('usuarios_idusuario',$id);
    $query = $this->db->get('solicitudes');
    return $query->result_array();

  }

  public function extraer_encuesta($id='')
  {
    $this->db->select('idsolicitud');
    $this->db->limit(1);
    $this->db->where('usuarios_idusuario',$id);
    $this->db->where('estado_solicitud',1);
    $this->db->where('estado_encuesta',0);
    $query = $this->db->get('solicitudes');
    return $query->result_array();
  }


  public function cambiar_clave($id='',$clave='',$tipoUsuario='',$nclave='')
  {
    $this->db->where('idusuario',$id);
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

  public function extraer_id_solicitudes($id='')
  {
    $this->db->select_max('idsolicitud');
    $this->db->where('usuarios_idusuario',$id);
    $query = $this->db->get('solicitudes');
    return $query->result_array();
  }

  public function verificar_solicitud($id='',$idsolicitud='')
  {
    $this->db->select('estado_solicitud');
    $this->db->where('usuarios_idusuario',$id);
    $this->db->where('idsolicitud',$idsolicitud);
    $query = $this->db->get('solicitudes');
    return $query->result_array();
  }

  public function verificar_solicitud_encuesta($id='',$idsolicitud='')
  {
    $this->db->select('estado_encuesta');
    $this->db->where('usuarios_id',$id);
    $this->db->where('idsolicitudes',$idsolicitud);
    $query = $this->db->get('solicitudes');
    return $query->result_array();
  }

  public function registrar_solicitud($id='',$fecha='',$solicitud='',$descripcion='')
  {
    $solicitud = array(
      'motivo'=>$solicitud,
      'descripcion'=>$descripcion,
      'via_solicitud'=>'Online',
      'fecha_solicitud'=>$fecha,
      'fecha_respuesta'=>NULL,
      'estado_solicitud'=>0,
      'estado_encuesta'=>0,
      'usuarios_idusuario'=>intval($id)
    );
    if($this->db->insert('solicitudes',$solicitud))
    {
      return TRUE;
    }
    else
    {
      return FALSE;
    }
  }

  public function solicitud_modificar_estado_encuesta($idsolicitud='')
  {
    $this->db->where('idsolicitud',$idsolicitud);
    if($this->db->update('solicitudes',array('estado_encuesta'=>1)))
    {
      return TRUE;
    }
    else
    {
      return FALSE;
    }
  }

  public function registrar_encuesta($encuesta='')
  {
    $cambio = $this->solicitud_modificar_estado_encuesta($encuesta['solicitudes_idsolicitud']);
    if($cambio==TRUE)
    {
      if($this->db->insert('encuestas',$encuesta))
      {
        return TRUE;
      }
      else
      {
        return FALSE;
      }
    }
    else
    {
      return FALSE;
    }

  }

}




 ?>
