<?php

class Direccionar extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
  }

  public function index()
  {

    switch ($this->session->userdata('tipo_usuario'))
    {
      case '0':
        redirect(base_url('administrador'));
        break;

      case '1':
        redirect(base_url('usuarios'));
        break;


      default:
          redirect(base_url('Inicio'));
        break;
   }


  }


}
