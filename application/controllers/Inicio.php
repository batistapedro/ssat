<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller {


	 public function __construct()
  {
    parent::__construct();
    $this->load->helper(array('form','security'));
    $this->load->library('form_validation');
    $this->load->model('Validar_Usuarios');

  }

	public function index()
	{
		$this->load->view('formInicio');
	}

	public function login()
	{
		if($this->input->is_ajax_request())
		{
			$this->form_validation->set_rules('usuario','Usuarios','trim|required|min_length[4]|max_length[15]|regex_match[/^[a-zA-Zñ-Ñ]+$/]');
			$this->form_validation->set_rules('clave','Clave','trim|required|min_length[6]|max_length[15]');

			$this->form_validation->set_message('required','El campo %s es requerido');
			$this->form_validation->set_message('min_length','El campo %s debe ser mayor a %s caracteres');
			$this->form_validation->set_message('max_length','El campo %s debe ser menor a %s caracteres');
			$this->form_validation->set_message('regex_match','El campo %s debe ser solo letras');

			if($this->form_validation->run()===FALSE)
			{

				$mensaje = array(
					'respuesta'=>'error',
					'error_usuario'=> form_error('usuario'),
					'error_clave' => form_error('clave')
				);
			}
			else
			{
					$usuario = xss_clean($this->input->post('usuario'));
					$clave = do_hash(xss_clean($this->input->post('clave')),'md5');

					$data =$this->Validar_Usuarios->validar_usuario($usuario,$clave);

					if(is_array($data))
					{

					if($data['estado_usuario']==1)
					{
						$usuario = array(
							'id'=>$data['idusuario'],
							'usuario'=>$data['usuario'],
							'tipo_usuario'=>$data['tipo_usuario'],
							'gerencias_departamentos'=>$data['gerencia'],
							'coordinacion'=>$data['coordinacion'],
							'log'=>TRUE
						);

						$this->session->set_userdata($usuario);

							$mensaje = array(
							 'respuesta'=>'exito',
							 'usuario'=>$data['usuario'],
								'url'=> base_url("Direccionar")
							 );
					}
					else
					{
						$mensaje = array(
							'respuesta'=>'error',
							'error_validar'=>'Este usuario esta temporamente suspendido, debe comunicarse con el Departamento de Telematica y Sistema'
						);
					}

					}
					else
					{
						$mensaje = array(
							'respuesta'=>'error',
							'error_validar'=>'<p>usuario o clave invalido</p>'

						);
					}

			}

			echo json_encode($mensaje);

		}
		else
		{
			show_404();
		}
	}
}
