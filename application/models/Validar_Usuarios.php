<?php

class Validar_Usuarios extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
   $this->load->database();
  }


  public function validar_usuario($usuario='', $clave='')
  {
    $this->db->select('usuarios.idusuario,usuarios.usuario,usuarios.tipo_usuario,usuarios.estado_usuario,gerencias_departamentos.gerencia,coordinaciones.coordinacion');
    $this->db->where('usuario',$usuario);
    $this->db->where('clave',$clave);
    $this->db->from('usuarios');
    $this->db->join('gerencias_departamentos','gerencias_departamentos.idgerencia = usuarios.gerencias_departamentos_idgerencia');
    $this->db->join('coordinaciones','coordinaciones.idcoordinacion = usuarios.coordinaciones_idcoordinacion');
    $query = $this->db->get();
    return $query->row_array();
  }


}



 ?>
