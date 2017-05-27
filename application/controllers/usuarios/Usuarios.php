<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller
{


	 public function __construct()
  {
    parent::__construct();
    $this->load->helper(array('form','security'));
    $this->load->library('form_validation');
		$this->load->model('Usuarios_model');

  }

  public function index()
  {

			$id = $this->session->userdata('id');
			//aqui esta extraendo el id de la solicitud
			$solicitudes['encuesta'] = $this->Usuarios_model->extraer_encuesta($id);
			$solicitudes['cantidad']= count($solicitudes['encuesta']);
			$solicitudes['solicitudes'] =$this->Usuarios_model->extraer_solicitudes_procesada($id);
      $this->load->view('usuarios/inicio',$solicitudes);

  }

	public function form_solicitud()
	{
		if($this->input->is_ajax_request())
		{
			$this->load->view('usuarios/form_solicitud');
		}
		else
		{
			show_404();
		}
	}

	public function registrar_solicitud()
	{
		if($this->input->is_ajax_request())
		{
			$this->form_validation->set_rules('solicitud','Solicitud','trim|required|min_length[5]|max_length[30]');
			$this->form_validation->set_rules('descripcion','Descripcion','trim|required');
			$this->form_validation->set_message('required','El campo %s es requerido');
			$this->form_validation->set_message('max_length','El campo %s debe ser menor a %s caracteres');
			$this->form_validation->set_message('min_length','El campo %s debe ser mayor a %s caracteres');

			if($this->form_validation->run()===FALSE)
			{
				$mensaje = array(

						'respuesta'=>'error',
						'error_solicitud'=>form_error('solicitud')
				);
			}
			else
			{
				$solicitud =ucwords(xss_clean($this->input->post('solicitud')));
				$descripcion = ucwords(xss_clean($this->input->post('descripcion')));
				@ $fecha = Date('Y-m-d');
				$id = $this->session->userdata('id');
				$id_solicitudes = $this->Usuarios_model->extraer_id_solicitudes($id);
				$respuesta_solicitud =$this->verificar_solicitudes($id,$id_solicitudes);
				if($respuesta_solicitud==1)
				{
					$mensaje = $this->verificar_encuesta($id,$fecha,$solicitud,$id_solicitudes,$descripcion);
				}
				else
				{
					$mensaje = array(
						'respuesta'=>'error',
						'sin_procesar'=>'Usted No Puede Realizar Esta Solicitud Ya Que Usted Tiene Una Solicitud Pendiente'
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

	public function enviar_mensaje_de_solicitud()
	{
		$this->load->library("email");

			 //configuracion para gmail
			 $configGmail = array(
			 'protocol' => 'smtp',
			 'smtp_host' => 'ssl://smtp.gmail.com',
			 'smtp_port' => 465,
			 'smtp_user' => 'fondobolivar1@gmail.com',
			 'smtp_pass' => '271188pmbs',
			 'mailtype' => 'html',
			 'charset' => 'utf-8',
			 'newline' => "\r\n"
			 );

			 $this->email->initialize($configGmail);

			 $this->email->from('fondobolivar1@gmail.com');
			 $this->email->to('batistapedro271188@gmail.com,pbatista@fondobolivar.gob.ve');
			 $this->email->subject('Solicitud de Soporte');
			 $this->email->message('<h3>Soporte Solicitado</h3><p><b>Fecha de la Solicitud :</b> '.Date('Y-m-d').' </p> <p><b>Usuario :</b> '.$this->session->userdata('usuario').'</p> <p><b>Gerencia o Departamento :</b> '.$this->session->userdata('gerencias_departamentos').'</p> <p><b>Coordinacion :</b> '.$this->session->userdata('coordinacion').'</p>');
			 if (!$this->email->send())
			 {

			 }
			 else
			 {

			 }
	}

	public function verificar_solicitudes($id,$id_solicitudes)
	{
		if(empty($id_solicitudes[0]['idsolicitud'])==TRUE)
		{
				return 1;
		}
		else
		{
				$verificar_solicitud = $this->Usuarios_model->verificar_solicitud($id,$id_solicitudes[0]['idsolicitud']);

			 	if(intval($verificar_solicitud[0]['estado_solicitud'])==1)
				{
					return 1;
				}
				else
				{
					return 0;
				}
		}

	}

	public function verificar_encuesta($id,$fecha,$solicitud,$id_solicitudes,$descripcion)
	{
		if(empty($id_solicitudes[0]['idsolicitudes'])==TRUE)
		{
			$resultado = $this->Usuarios_model->registrar_solicitud($id,$fecha,$solicitud,$descripcion);
			if($resultado==TRUE)
			{
				//$correo = $this->enviar_mensaje_de_solicitud();

				$mensaje = array(
					'respuesta'=>'exito',
					'exito'=>'Solicitud Registrada con Exito',
				);


					}
					else
					{
						$mensaje = array(
							'respuesta'=>'error',
							'error_solicitud'=>'Ocurrio un Error en el Sitema, la Solicitud no fue Enviada'
						);
					}
		}
		else
		{
			$verificar = $this->Usuarios_model->verificar_solicitud_encuesta($id,$id_solicitudes[0]['idsolicitud']);

				if((intval($verificar[0]['estado_encuesta'])!=0))
				{
					$resultado = $this->Usuarios_model->registrar_solicitud($id,$fecha,$solicitud);
					if($resultado==TRUE)
					{
						$mensaje = array(
							'respuesta'=>'exito',
							'exito'=>'Gracias Por Darnos Su clasificación'
						);

							//$this->enviar_mensaje_de_solicitud();
					}
					else
					{
						$mensaje = array(
							'respuesta'=>'error',
							'error_solicitud'=>'Ocurrio un Error en el Sitema, la Solicitud no fue Enviada'
						);
					}
				}
				else
				{
					$mensaje = array(
						'respuesta'=>'error',
						'error_solicitud'=>'Debes Primero Realizar la Encuesta de la Solicitud Anterior'
					);
				}
		}
		return $mensaje;
	}

	public function form_cambiar_clave()
	{
		if($this->input->is_ajax_request())
		{
			redirect(base_url('ConfigClave'));
		}
		else
		{
			show_404();
		}
	}


	public function registrar_encuesta()
	{
		if($this->input->is_ajax_request())
		{
			$this->form_validation->set_rules('1','Pregunta 1','trim|required|in_list[regular,bueno,excelente]');
			$this->form_validation->set_rules('2','Pregunta 2','trim|required|in_list[regular,bueno,excelente]');
			$this->form_validation->set_rules('3','Pregunta 3','trim|required|in_list[regular,bueno,excelente]');
			$this->form_validation->set_rules('4','Pregunta 4','trim|required|in_list[regular,bueno,excelente]');

			$this->form_validation->set_message('required','El campo %s es requerido');
			$this->form_validation->set_message('in_list','Error en campo %s formato no valido');

			if($this->form_validation->run()===FALSE)
			{
				$mensaje = array(
					'respuesta'=>'error',
					'error_1'=>form_error('1'),
					'error_2'=>form_error('2'),
					'error_3'=>form_error('3'),
					'error_4'=>form_error('4')
				);
			}
			else
			{
				$observacion = $this->input->post('porque_regular');
				$idsolicitud = intval(xss_clean($this->input->post('idsolicitud')));
				if($observacion==NULL)
				{
					$observacion = 'N/A';
				}

				$puntos = array($this->input->post('1'),$this->input->post('2'),$this->input->post('3'),$this->input->post('4'));

				$calificacion = $this->generar_notas_cualitativas($puntos);

				$encuesta = array(
					'fecha_encuesta'=>@ date('Y-m-d'),
					'r1'=>xss_clean($this->input->post('1')),
					'r2'=>xss_clean($this->input->post('2')),
					'r3'=>xss_clean($this->input->post('3')),
					'r4'=>xss_clean($this->input->post('4')),
					'observacion'=>$observacion,
					'calificacion'=>$calificacion,
					'solicitudes_idsolicitud'=>$idsolicitud
				);
				
				$estado = $this->Usuarios_model->registrar_encuesta($encuesta);
				if($estado===TRUE)
				{
					$mensaje =array(
						'respuesta'=>'exito',
						'exito'=>'Encuesta Guardada con Exito'

					);
				}
				else
				{
					$mensaje = array(
						'respuesta'=>'error',
						'error_db'=>'Ha Ocurrido un error no se Pudo Registrar Encuesta'
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

	public function generar_notas_cualitativas($puntos)
	{

	$valor = array();


		for($i=0;$i<count($puntos);$i++)
		{
			if($puntos[$i]=='regular')
			{
				$valor[$i]=1;
			}
			else if($puntos[$i]=='bueno')
			{
				$valor[$i]=2;
			}
			else if($puntos[$i]=='excelente')
			{
				$valor[$i]=3;
			}

		}

		$suma = array_sum($valor);
		$total = $suma/4;
		$total = round($total);

		switch ($total) {
			case '1':
				return 'regular';
				# code...
				break;

			case '2':
					return 'bueno';
				break;

			case '3':
					return 'excelente';
				break;

			default:
				# code...
				break;
		}

	}


}
?>
