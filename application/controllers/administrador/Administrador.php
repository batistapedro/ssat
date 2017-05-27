<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administrador extends CI_Controller
{


	 public function __construct()
  {
    parent::__construct();
    $this->load->helper(array('form','security','file','download'));
    $this->load->library(array('form_validation','Pdf'));
	$this->load->model('Administrador_model');

  }


  public function index()
  {
    if($this->session->userdata('tipo_usuario')=='0')
    {
			$solicitudes['solicitudes'] = $this->Administrador_model->extraer_solicitudes_pendiente();
			$solicitudes['cantidad'] = count($solicitudes['solicitudes']);
		$this->load->view('administrador/inicio',$solicitudes);
    }
  }

	public function form_registrar_usuarios()
	{
		if($this->session->userdata('tipo_usuario')==0)
		{
			if($this->input->is_ajax_request())
			{
				$gerencias['gerencias'] = $this->Administrador_model->extraer_gerencias();
				$this->load->view('administrador/form_registrar_usuarios',$gerencias);
			}
			else
			{
				show_404();
			}
		}
	}

	public function form_reportes_encuesta_usuarios_pendientes()
	{
		if($this->input->is_ajax_request())
		{
			$this->load->view('administrador/form_encuesta_usuarios_pendientes');
		}
		else
		{
			show_404();
		}
	}

	public function ver_encuestas_pendientes()
	{
		if($this->session->userdata('tipo_usuario')==0)
		{
			if($this->input->is_ajax_request())
			{
				$this->form_validation->set_rules('form_encuesta_pendientes_fecha_desde','Fecha Desde','trim|required|regex_match[/^[0-9]{4}[\/]{1}[0-9]{2}[\/]{1}[0-9]+$/]|exact_length[10]');
				$this->form_validation->set_rules('form_encuesta_pendientes_fecha_hasta','Fecha Hasta','trim|required|regex_match[/^[0-9]{4}[\/]{1}[0-9]{2}[\/]{1}[0-9]+$/]|exact_length[10]');

				$this->form_validation->set_message('regex_match','<h3 class="text-center text-danger">Error en campo %s formato no valido</h3>');
				$this->form_validation->set_message('exact_length','<h3 class="text-danger text-center">Error en campo %s debe tener %s caracteres exactos</h3>');

				if($this->form_validation->run()===FALSE)
				{
					echo form_error('form_encuesta_pendientes_fecha_desde').' '.form_error('form_encuesta_pendientes_fecha_hasta');
				}
				else
				{
					$fecha_desde =  xss_clean( $this->input->post('form_encuesta_pendientes_fecha_desde'));
					$fecha_hasta =  xss_clean($this->input->post('form_encuesta_pendientes_fecha_hasta'));

					if($fecha_desde>$fecha_hasta)
					{
						echo '<h3 class="text-danger text-center">Error Fecha Desde no puede ser mayor a Fecha Hasta</h3>';
					}
					else
					{

							$todas_encuesta['todas'] = $this->Administrador_model->extraer_encuesta_pendiente_todas($fecha_desde,$fecha_hasta);
							$todas_encuesta['cantidad'] = count($todas_encuesta['todas']);

							$this->load->view('administrador/ver_encuesta_pendientes',$todas_encuesta);


					}

				}
			}
			else
			{
				show_404();
			}
		}
	}

	public function extraer_gerencias()
	{
		if($this->session->userdata('tipo_usuario')==0)
		{
			if($this->input->is_ajax_request())
			{
				$gerencias = $this->Administrador_model->extraer_gerencias();
				echo json_encode($gerencias);
			}
			else
			{
				show_404();
			}
		}

	}

	public function extraer_coordinacion()
	{
		if($this->input->is_ajax_request())
		{
			$valor = $this->input->post('valor');
			$respuesta = $this->Administrador_model->extraer_coordinacion($valor);

			echo json_encode($respuesta);
		}
		else
		{
			show_404();
		}
	}



	public function registrar_usuarios()
	{
		if($this->session->userdata('tipo_usuario')==0)
		{
			if($this->input->is_ajax_request())
			{
				$this->form_validation->set_rules('nombre','Nombre Usuario','trim|required|min_length[3]|max_length[15]|regex_match[/^[a-zA-zñ-Ñ]+$/]');
				$this->form_validation->set_rules('apellido','Apellido Usuario','trim|required|min_length[3]|max_length[15]|regex_match[/^[a-zA-zñ-Ñ]+$/]');
				$this->form_validation->set_rules('usuario','Usuario','trim|required|min_length[3]|max_length[15]|regex_match[/^[a-zA-zñ-Ñ]+$/]|is_unique[usuarios.usuario]');
				$this->form_validation->set_rules('cedula','Cedula Usuario','trim|required|min_length[7]|max_length[8]|regex_match[/^[0-9]+$/]|is_unique[usuarios.cedula]');
				$this->form_validation->set_rules('gerencias_departamentos','Gerencia / Departemento','trim|required|min_length[1]|max_length[2]|regex_match[/^[0-9]+$/]');
				$this->form_validation->set_rules('coordinacion','Coordinacion','trim|required|min_length[1]|max_length[3]|regex_match[/^[0-9a-z\/]+$/]');
				$this->form_validation->set_rules('cargo','Cargo Usuario','trim|required|min_length[4]|max_length[20]|regex_match[/^[a-zA-Zñ-Ñ\s]+$/]');
				$this->form_validation->set_rules('correo','Correo Electronico','trim|required|min_length[24]|max_length[35]|valid_email|is_unique[usuarios.correo]');
				$this->form_validation->set_rules('tipo_usuario','Tipo Usuario','trim|required|in_list[0,1]');
				$this->form_validation->set_rules('estado_usuario','Estado Usuario','trim|required|in_list[0,1]');

				$this->form_validation->set_message('required','El campo %s es requerido');
				$this->form_validation->set_message('min_length','El campo %s debe ser mayor o igual a %s caracteres');
				$this->form_validation->set_message('max_length','El campo %s debe ser menor o igual a %s caracteres');
				$this->form_validation->set_message('regex_match','Formato no permitido para el campo %s');
				$this->form_validation->set_message('in_list','Formato no permitido para el campo %s');
				$this->form_validation->set_message('valid_email','El campo %s es invalido');
				$this->form_validation->set_message('is_unique','Error en el campo %s , ya se encuentra registrado en la base de datos');

				if($this->form_validation->run()===FALSE)
				{
					$mensaje = array(
						'respuesta'=>'error',
						'error_nombre'=>form_error('nombre'),
						'error_apellido'=>form_error('apellido'),
						'error_usuario'=>form_error('usuario'),
						'error_cedula'=>form_error('cedula'),
						'error_gerencias_departamentos'=>form_error('gerencias_departamentos'),
						'error_coordinacion'=>form_error('coordinacion'),
						'error_cargo'=>form_error('cargo'),
						'error_correo'=>form_error('correo'),
						'error_tipo_usuario'=>form_error('tipo_usuario'),
						'error_estado_usuario'=>form_error('estado_usuario')
					);
				}
				else
				{
					//fondobolivar.gob.ve
					$correo = xss_clean($this->input->post('correo'));
					$picar_correo = strtoupper(substr($correo,-19));
					if($picar_correo!='FONDOBOLIVAR.GOB.VE')
					{
						$mensaje = array(
							'respuesta'=>'error',
							'error_correo'=>'Error en el campo Correo Usuario este correo no pertence a la empresa'
						);
					}
					else
					{
						$usuario = array(
							'nombre'=>ucwords(xss_clean($this->input->post('nombre'))),
							'apellido'=>ucwords(xss_clean($this->input->post('apellido'))),
							'cedula'=>xss_clean($this->input->post('cedula')),
							'usuario'=>xss_clean($this->input->post('usuario')),
							'clave'=>do_hash($this->input->post('cedula'),'md5'),
							'cargo'=>ucwords(xss_clean($this->input->post('cargo'))),
							'correo'=>xss_clean($this->input->post('correo')),
							'tipo_usuario'=>intval(xss_clean($this->input->post('tipo_usuario'))),
							'estado_usuario'=>intval(xss_clean($this->input->post('estado_usuario'))),
							'gerencias_departamentos_idgerencia'=>xss_clean($this->input->post('gerencias_departamentos')),
							'coordinaciones_idcoordinacion'=>xss_clean($this->input->post('coordinacion'))
						);

						$dato = $this->Administrador_model->registrar_usuarios($usuario);
						if($dato ==TRUE)
						{
							$mensaje = array(
								'respuesta'=>'exito',
								'exito'=>'Usuario Registrado Con Exito'
							);
						}
						else
						{
							$mensaje = array(
								'respuesta'=>'error',
								'error_db'=>'oop ocurrio un error al registrar el usuario'
							);
						}

					}

				}
				echo json_encode($mensaje);

			}
			else
			{
				show_404();
			}
		}
		else
		{
			show_404();
		}

	}

	public function editar_datos_usuarios()
	{
		if($this->session->userdata('tipo_usuario')==0)
		{
			$campo = xss_clean($this->input->post('campo'));
			$id = intval($this->input->post('id'));

			switch ($campo)
			{
				case 'nombre':
						$this->form_validation->set_rules('nuevovalor','Nombre Usuario','trim|required|min_length[3]|max_length[15]|regex_match[/^[a-zA-zñ-Ñ]+$/]');

						$this->form_validation->set_message('required','El campo %s es requerido');
						$this->form_validation->set_message('min_length','El campo %s debe ser mayor o igual a %s caracteres');
						$this->form_validation->set_message('max_length','El campo %s debe ser menor o igual a %s caracteres');
						$this->form_validation->set_message('regex_match','Formato no permitido para el campo %s');

						if($this->form_validation->run()==FALSE)
						{
							$mensaje = array(

								'respuesta'=>'error',
								'error'=>form_error('nuevovalor')
							);
						}
						else
						{
							$nuevovalor =ucwords(xss_clean($this->input->post('nuevovalor')));
							$data = $this->Administrador_model->editar_datos_usuarios($id,$campo,$nuevovalor);
							if($data==TRUE)
							{
								$mensaje = array(

									'respuesta'=>'exito',
									'exito'=>'Dato nombre actualizado con exito'
								);
							}
							else
							{
								$mensaje = array(

									'respuesta'=>'error',
									'error'=>'Error en base datos'
								);
							}
						}
						echo json_encode($mensaje);
				break;

				case 'apellido':
				$this->form_validation->set_rules('nuevovalor','Apellido Usuario','trim|required|min_length[3]|max_length[15]|regex_match[/^[a-zA-zñ-Ñ]+$/]');

				$this->form_validation->set_message('required','El campo %s es requerido');
				$this->form_validation->set_message('min_length','El campo %s debe ser mayor o igual a %s caracteres');
				$this->form_validation->set_message('max_length','El campo %s debe ser menor o igual a %s caracteres');
				$this->form_validation->set_message('regex_match','Formato no permitido para el campo %s');

				if($this->form_validation->run()==FALSE)
				{
					$mensaje = array(

						'respuesta'=>'error',
						'error'=>form_error('nuevovalor')
					);
				}
				else
				{
					$nuevovalor =ucwords(xss_clean($this->input->post('nuevovalor')));
					$data = $this->Administrador_model->editar_datos_usuarios($id,$campo,$nuevovalor);
					if($data==TRUE)
					{
						$mensaje = array(

							'respuesta'=>'exito',
							'exito'=>'Dato apellido actualizado con exito'
						);
					}
					else
					{
						$mensaje = array(

							'respuesta'=>'error',
							'error'=>'Error en base datos'
						);
					}
				}
				echo json_encode($mensaje);
				break;

				case 'cedula':
				$this->form_validation->set_rules('nuevovalor','Cedula','trim|required|min_length[7]|max_length[8]|regex_match[/^[0-9]+$/]|is_unique[usuarios.cedula]');

				$this->form_validation->set_message('required','El campo %s es requerido');
				$this->form_validation->set_message('min_length','El campo %s debe ser mayor o igual a %s caracteres');
				$this->form_validation->set_message('max_length','El campo %s debe ser menor o igual a %s caracteres');
				$this->form_validation->set_message('regex_match','Formato no permitido para el campo %s');
				$this->form_validation->set_message('is_unique','Error en dato %s ya se encuetra registrado en el sistema');

				if($this->form_validation->run()==FALSE)
				{
					$mensaje = array(

						'respuesta'=>'error',
						'error'=>form_error('nuevovalor')
					);
				}
				else
				{
					$nuevovalor =xss_clean($this->input->post('nuevovalor'));
					$data = $this->Administrador_model->editar_datos_usuarios($id,$campo,$nuevovalor);
					if($data==TRUE)
					{
						$mensaje = array(

							'respuesta'=>'exito',
							'exito'=>'Dato nombre actualizado con exito'
						);
					}
					else
					{
						$mensaje = array(

							'respuesta'=>'error',
							'error'=>'Error en base datos'
						);
					}
				}
				echo json_encode($mensaje);
				break;

				case 'usuario':
				$this->form_validation->set_rules('nuevovalor','Usuario','trim|required|min_length[3]|max_length[15]|regex_match[/^[a-zA-zñ-Ñ]+$/]|is_unique[usuarios.usuario]');

				$this->form_validation->set_message('required','El campo %s es requerido');
				$this->form_validation->set_message('min_length','El campo %s debe ser mayor o igual a %s caracteres');
				$this->form_validation->set_message('max_length','El campo %s debe ser menor o igual a %s caracteres');
				$this->form_validation->set_message('regex_match','Formato no permitido para el campo %s');
				$this->form_validation->set_message('is_unique','Error el campo %s ya esta registrado en el sistema');

				if($this->form_validation->run()==FALSE)
				{
					$mensaje = array(

						'respuesta'=>'error',
						'error'=>form_error('nuevovalor')
					);
				}
				else
				{
					$nuevovalor =xss_clean($this->input->post('nuevovalor'));
					$data = $this->Administrador_model->editar_datos_usuarios($id,$campo,$nuevovalor);
					if($data==TRUE)
					{
						$mensaje = array(

							'respuesta'=>'exito',
							'exito'=>'Dato nombre actualizado con exito'
						);
					}
					else
					{
						$mensaje = array(

							'respuesta'=>'error',
							'error'=>'Error en base datos'
						);
					}
				}
				echo json_encode($mensaje);
				break;

				case 'clave':
				$this->form_validation->set_rules('nuevovalor','clave Usuario','trim|required|min_length[6]|max_length[12]');

				$this->form_validation->set_message('required','El campo %s es requerido');
				$this->form_validation->set_message('min_length','El campo %s debe ser mayor o igual a %s caracteres');
				$this->form_validation->set_message('max_length','El campo %s debe ser menor o igual a %s caracteres');

				if($this->form_validation->run()==FALSE)
				{
					$mensaje = array(

						'respuesta'=>'error',
						'error'=>form_error('nuevovalor')
					);
				}
				else
				{
					$nuevovalor = do_hash(xss_clean($this->input->post('nuevovalor')),'md5');
					$data = $this->Administrador_model->editar_datos_usuarios($id,$campo,$nuevovalor);
					if($data==TRUE)
					{
						$mensaje = array(
							'respuesta'=>'exito',
							'exito'=>'Dato clave actualizado con exito'
						);
					}
					else
					{
						$mensaje = array(

							'respuesta'=>'error',
							'error'=>'Error en base datos'
						);
					}
				}
				echo json_encode($mensaje);
				break;

				case 'departamento_gerencia':
				$this->form_validation->set_rules('nuevovalor','Gerencia / Departamento','trim|required|min_length[6]|max_length[70]|regex_match[/^[a-zA-zñ-Ñ]+$/]');

				$this->form_validation->set_message('required','El campo %s es requerido');
				$this->form_validation->set_message('min_length','El campo %s debe ser mayor o igual a %s caracteres');
				$this->form_validation->set_message('max_length','El campo %s debe ser menor o igual a %s caracteres');
				$this->form_validation->set_message('regex_match','Formato no permitido para el campo %s');

				if($this->form_validation->run()==FALSE)
				{
					$mensaje = array(

						'respuesta'=>'error',
						'error'=>form_error('nuevovalor')
					);
				}
				else
				{
					$nuevovalor =xss_clean($this->input->post('nuevovalor'));
					$data = $this->Administrador_model->editar_datos_usuarios($id,$campo,$nuevovalor);
					if($data==TRUE)
					{
						$mensaje = array(

							'respuesta'=>'exito',
							'exito'=>'Dato Departamento o Gerencia actualizado con exito'
						);
					}
					else
					{
						$mensaje = array(

							'respuesta'=>'error',
							'error'=>'Error en base datos'
						);
					}
				}
				echo json_encode($mensaje);
				break;

				case 'coordinacion':
				$this->form_validation->set_rules('nuevovalor','Coordinacion','trim|required|min_length[6]|max_length[50]|regex_match[/^[a-zA-zñ-Ñ]+$/]');

				$this->form_validation->set_message('required','El campo %s es requerido');
				$this->form_validation->set_message('min_length','El campo %s debe ser mayor o igual a %s caracteres');
				$this->form_validation->set_message('max_length','El campo %s debe ser menor o igual a %s caracteres');
				$this->form_validation->set_message('regex_match','Formato no permitido para el campo %s');

				if($this->form_validation->run()==FALSE)
				{
					$mensaje = array(

						'respuesta'=>'error',
						'error'=>form_error('nuevovalor')
					);
				}
				else
				{
					$nuevovalor =xss_clean($this->input->post('nuevovalor'));
					$data = $this->Administrador_model->editar_datos_usuarios($id,$campo,$nuevovalor);
					if($data==TRUE)
					{
						$mensaje = array(

							'respuesta'=>'exito',
							'exito'=>'Dato coordinacion actualizado con exito'
						);
					}
					else
					{
						$mensaje = array(

							'respuesta'=>'error',
							'error'=>'Error en base datos'
						);
					}
				}
				echo json_encode($mensaje);
				break;

				case 'cargo':
					$this->form_validation->set_rules('nuevovalor','Cargo Usuario','trim|required|min_length[4]|max_length[20]|regex_match[/^[a-zA-zñ-Ñ\s]+$/]');

					$this->form_validation->set_message('required','El campo %s es requerido');
					$this->form_validation->set_message('min_length','El campo %s debe ser mayor o igual a %s caracteres');
					$this->form_validation->set_message('max_length','El campo %s debe ser menor o igual a %s caracteres');
					$this->form_validation->set_message('regex_match','Formato no permitido para el campo %s');

					if($this->form_validation->run()==FALSE)
					{
						$mensaje = array(

							'respuesta'=>'error',
							'error'=>form_error('nuevovalor')
						);
					}
					else
					{
						$nuevovalor =ucwords(xss_clean($this->input->post('nuevovalor')));
						$data = $this->Administrador_model->editar_datos_usuarios($id,$campo,$nuevovalor);
						if($data==TRUE)
						{
							$mensaje = array(

								'respuesta'=>'exito',
								'exito'=>'Dato cargo actualizado con exito'
							);
						}
						else
						{
							$mensaje = array(

								'respuesta'=>'error',
								'error'=>'Error en base datos'
							);
						}
					}
					echo json_encode($mensaje);
				break;

				case 'correo':
					$this->form_validation->set_rules('nuevovalor','Correo Usuario','trim|required|min_length[22]|max_length[70]|valid_email|is_unique[usuarios.correo]');

					$this->form_validation->set_message('required','El campo %s es requerido');
					$this->form_validation->set_message('min_length','El campo %s debe ser mayor o igual a %s caracteres');
					$this->form_validation->set_message('max_length','El campo %s debe ser menor o igual a %s caracteres');
					$this->form_validation->set_message('valid_email','Error en campo %s no valido');
					$this->form_validation->set_message('is_unique','Error este correo ya esta registrado en el sistema');

					if($this->form_validation->run()==FALSE)
					{
						$mensaje = array(

							'respuesta'=>'error',
							'error'=>form_error('nuevovalor')
						);
					}
					else
					{
						$correo = xss_clean($this->input->post('nuevovalor'));
						$picar_correo = strtoupper(substr($correo,-19));
						if($picar_correo!='FONDOBOLIVAR.GOB.VE')
						{
							$mensaje = array(
								'respuesta'=>'error',
								'error'=>'Error en el campo Correo Usuario este correo no pertence a la empresa'
							);
						}
						else
						{
							$nuevovalor =xss_clean($this->input->post('nuevovalor'));
							$data = $this->Administrador_model->editar_datos_usuarios($id,$campo,$nuevovalor);
							if($data==TRUE)
							{
								$mensaje = array(

									'respuesta'=>'exito',
									'exito'=>'Dato correo actualizado con exito'
								);
							}
							else
							{
								$mensaje = array(

									'respuesta'=>'error',
									'error'=>'Error en base datos'
								);
							}
						}

					}
					echo json_encode($mensaje);
				break;
				case 'estado_usuario':
				$this->form_validation->set_rules('nuevovalor','Estado Usuario','trim|required|in_list[0,1]');

				$this->form_validation->set_message('required','El campo %s es requerido');
				$this->form_validation->set_message('in_list','El campo %s formato no valido');

				if($this->form_validation->run()==FALSE)
				{
					$mensaje = array(

						'respuesta'=>'error',
						'error'=>form_error('nuevovalor')
					);
				}
				else
				{
					$nuevovalor =xss_clean($this->input->post('nuevovalor'));
					$data = $this->Administrador_model->editar_datos_usuarios($id,$campo,$nuevovalor);
					if($data==TRUE)
					{
						$mensaje = array(

							'respuesta'=>'exito',
							'exito'=>'Dato estado actualizado con exito'
						);
					}
					else
					{
						$mensaje = array(

							'respuesta'=>'error',
							'error'=>'Error en base datos'
						);
					}
				}
				echo json_encode($mensaje);
			break;


				default:
					# code...
					break;
			}
		}
		else
		{
			show_404();
		}
	}

	public function modificar_gerencia_coordinacion()
	{
		$usuario = xss_clean($this->input->post('usuario'));
		$idgerencia = intval(xss_clean($this->input->post('idgerencia')));
		$idcoordinacion = intval(xss_clean($this->input->post('idcoordinacion')));


		$valida = $this->Administrador_model->modificar_gerencia_coordinacion($usuario,$idgerencia,$idcoordinacion);

		if($valida===TRUE)
		{
			$mensaje = array(
				'respuesta'=>'exito',
				'exito'=>'Datos de usuarios actualizado con exito'
			);

		}
		else
		{
			$mensaje = array(
				'respuesta'=>'error',
				'error'=>'ocurrio un error al modificar este dato'
			);

		}
		echo json_encode($mensaje);

	}

	public function reportes_solicitudes_usuarios()
	{
		if($this->session->userdata('tipo_usuario')==0)
		{
			if($this->input->is_ajax_request())
			{
				$gerencias['gerencias'] = $this->Administrador_model->extraer_gerencias();
				$this->load->view('administrador/form_solicitud_usuario',$gerencias);
			}
			else
			{

			}
		}
		else
		{
			show_404();
		}

	}

	public function buscar_solicitud_usuarios()
	{
		if($this->session->userdata('tipo_usuario')==0)
		{
			if($this->input->is_ajax_request())
			{

				$this->form_validation->set_rules('solicitud_usuario_fecha_desde','Fecha Desde','trim|required|regex_match[/^[0-9]{4}[\/]{1}[0-9]{2}[\/]{1}[0-9]+$/]|exact_length[10]');
				$this->form_validation->set_rules('solicitud_usuario_fecha_hasta','Fecha Hasta','trim|required|regex_match[/^[0-9]{4}[\/]{1}[0-9]{2}[\/]{1}[0-9]+$/]|exact_length[10]');

				$this->form_validation->set_message('regex_match','<h3 class="text-center text-danger">Error en campo %s formato no valido</h3>');
				$this->form_validation->set_message('exact_length','<h3 class="text-danger text-center">Error en campo %s debe tener %s caracteres exactos</h3>');

				if($this->form_validation->run()===FALSE)
				{
					echo form_error('solicitud_usuario_fecha_desde').' '.form_error('solicitud_usuario_fecha_hasta');
				}
				else
				{
					$select_reportes =  xss_clean( $this->input->post('select_reportes_opcion'));
					$fecha_desde =  xss_clean( $this->input->post('solicitud_usuario_fecha_desde'));
					$fecha_hasta =  xss_clean($this->input->post('solicitud_usuario_fecha_hasta'));

					if($fecha_desde>$fecha_hasta)
					{
						echo '<h3 class="text-danger text-center">Error Fecha Desde no puede ser mayor a Fecha Hasta</h3>';
					}
					else
					{
						if($select_reportes=='todos')
						{
							$todas_solicitud['todas'] = $this->Administrador_model->extraer_solicitud_reportes_todas($fecha_desde,$fecha_hasta);
							$todas_solicitud['cantidad'] = count($todas_solicitud['todas']);
							$todas_solicitud['url']='/'.$fecha_desde.'/'.$fecha_hasta;

							$this->load->view('administrador/ver_solicitudes_todos_departamento',$todas_solicitud);
						}
						else
						{
							$gerencias=xss_clean( $this->input->post('reportes_gerencias'));
							$reportes_gerencias['gerencias'] = $this->Administrador_model->extraer_solicitud_reportes_gerencias($gerencias,$fecha_desde,$fecha_hasta);
							$reportes_gerencias['cantidad'] = count($reportes_gerencias['gerencias']);
							$reportes_gerencias['url']='/'.$gerencias.'/'.$fecha_desde.'/'.$fecha_hasta;
							$this->load->view('administrador/ver_solicitudes_departamento',$reportes_gerencias);
						}
					}


				}


			}
			else
			{
				show_404();
			}
		}
	}

	public function repostes_encuesta_usuario()
	{
		if($this->session->userdata('tipo_usuario')==0)
		{
			if($this->input->is_ajax_request())
			{
				$this->load->view('administrador/form_encuesta');
			}
			else
			{
				show_404();
			}

		}
	}

	public function ver_encuestas()
	{
		if($this->session->userdata('tipo_usuario')==0)
		{
			if($this->input->is_ajax_request())
			{
				$this->form_validation->set_rules('encuesta_usuario_fecha_desde','Fecha Desde','trim|required|regex_match[/^[0-9]{4}[\/]{1}[0-9]{2}[\/]{1}[0-9]+$/]|exact_length[10]');
				$this->form_validation->set_rules('encuesta_usuario_fecha_hasta','Fecha Hasta','trim|required|regex_match[/^[0-9]{4}[\/]{1}[0-9]{2}[\/]{1}[0-9]+$/]|exact_length[10]');

				$this->form_validation->set_message('regex_match','<h3 class="text-center text-danger">Error en campo %s formato no valido</h3>');
				$this->form_validation->set_message('exact_length','<h3 class="text-danger text-center">Error en campo %s debe tener %s caracteres exactos</h3>');

				if($this->form_validation->run()===FALSE)
				{
					echo form_error('encuesta_usuario_fecha_desde').' '.form_error('encuesta_usuario_fecha_hasta');
				}
				else
				{
					$fecha_desde =  xss_clean($this->input->post('encuesta_usuario_fecha_desde'));
					$fecha_hasta =  xss_clean($this->input->post('encuesta_usuario_fecha_hasta'));


					if($fecha_desde>$fecha_hasta)
					{
						echo '<h3 class="text-danger text-center">Error Fecha Desde no puede ser mayor a Fecha Hasta</h3>';
					}
					else
					{
							$todas_encuesta['todas'] = $this->Administrador_model->extraer_encuesta_todas($fecha_desde,$fecha_hasta);
							$todas_encuesta['cantidad'] = count($todas_encuesta['todas']);
							$todas_encuesta['porcentajes'] = $this->Administrador_model->encuestas_porcentajes($fecha_desde,$fecha_hasta);
							$this->load->view('administrador/ver_encuesta_usuario',$todas_encuesta);


					}

				}
			}
			else
			{
				show_404();
			}

		}
	}

	public function extraer_usuarios()
	{
		if($this->session->userdata('tipo_usuario')==0)
		{
			if($this->input->is_ajax_request())
			{
				$usuarios['usuarios'] = $this->Administrador_model->extraer_usuarios();
				$usuarios['cantida_usuario'] = count($usuarios['usuarios']);
				$this->load->view('administrador/ver_usuarios',$usuarios);
			}
			else
			{
				show_404();
			}
		}
		else
		{
			show_404();
		}

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

	public function pdf_solicitudes_departamento($id,$ano_desde,$mes_desde,$dia_desde,$ano_hasta,$mes_hasta,$dia_hasta)
	{
		if($this->session->userdata('tipo_usuario')==0)
		{
			$fecha_desde=$ano_desde.'/'.$mes_desde.'/'.$dia_desde;
			$fecha_hasta=$ano_hasta.'/'.$mes_hasta.'/'.$dia_hasta;
			$this->pdf = new Pdf();
		 // Agregamos una página
			$this->pdf->AddPage('L');
			$this->pdf->SetFillColor(153,255,100);
			$this->pdf->SetFont('Arial','',8);
			$this->pdf->Cell(10,8,'ITEM',1,0,'C');
			$this->pdf->Cell(70,8,'UNIDAD SOLICITANTE',1,0,'C');
			$this->pdf->Cell(35,8,'NOMBRE APELLIDO',1,0,'C');
			$this->pdf->Cell(25,8,'CARGO',1,0,'C');
			$this->pdf->Cell(30,8,'VIA DESOLICITUD',1,0,'C');
			$this->pdf->Cell(53,8,'MOTIVO SOLICITUD',1,0,'C');
			$this->pdf->Cell(28,8,'FECHA SOLICITUD',1,0,'C');
			$this->pdf->Cell(29,8,'FECHA RESPUESTA',1,1,'C');
			///dato
			$datas = $this->Administrador_model->extraer_solicitud_reportes_gerencias($id,$fecha_desde,$fecha_hasta);
			$i=1;
			foreach($datas as $data)
			{
				$this->pdf->Cell(10,5,$i++,1,0,'L');
				if(trim($data['coordinacion'])=='n/a')
				{
					$this->pdf->Cell(70,5,utf8_decode(trim($data['gerencia'])),1,0,'L');
				}
				else
				{
					$this->pdf->Cell(70,5,utf8_decode(trim($data['coordinacion'])),1,0,'L');
				}

				$this->pdf->Cell(35,5,utf8_decode(trim($data['nombre']).' '.trim($data['apellido'])),1,0,'L');
				$this->pdf->Cell(25,5,utf8_decode(trim($data['cargo'])),1,0,'L');
				$this->pdf->Cell(30,5,utf8_decode($data['via_solicitud']),1,0,'L');
				$this->pdf->Cell(53,5,utf8_decode(trim($data['motivo'])),1,0,'L');
				$this->pdf->Cell(28,5,trim($data['fecha_solicitud']),1,0,'L');
				$this->pdf->Cell(29,5,trim($data['fecha_respuesta']),1,1,'L');

			}
			$this->pdf->Output('Reportes de solicitudes y asistencia tecnica','I');

		}

	}

	public function pdf_solicitudes_todas($ano_desde,$mes_desde,$dia_desde,$ano_hasta,$mes_hasta,$dia_hasta)
	{
		if($this->session->userdata('tipo_usuario')==0)
		{
			$fecha_desde=$ano_desde.'/'.$mes_desde.'/'.$dia_desde;
			$fecha_hasta=$ano_hasta.'/'.$mes_hasta.'/'.$dia_hasta;
			$this->pdf = new Pdf();
		 // Agregamos una página
			$this->pdf->AddPage('L');
			$this->pdf->SetFillColor(153,255,100);
			$this->pdf->SetFont('Arial','',8);
			$this->pdf->Cell(10,8,'ITEM',1,0,'C');
			$this->pdf->Cell(70,8,'UNIDAD SOLICITANTE',1,0,'C');
			$this->pdf->Cell(35,8,'NOMBRE APELLIDO',1,0,'C');
			$this->pdf->Cell(25,8,'CARGO',1,0,'C');
			$this->pdf->Cell(30,8,'VIA DESOLICITUD',1,0,'C');
			$this->pdf->Cell(53,8,'MOTIVO SOLICITUD',1,0,'C');
			$this->pdf->Cell(28,8,'FECHA SOLICITUD',1,0,'C');
			$this->pdf->Cell(29,8,'FECHA RESPUESTA',1,1,'C');
			///dato
			$datas = $this->Administrador_model->extraer_solicitud_reportes_todas($fecha_desde,$fecha_hasta);
			$i=1;
			foreach($datas as $data)
			{
				$this->pdf->Cell(10,5,$i++,1,0,'L');
				if(trim($data['coordinacion'])=='n/a')
				{
					$this->pdf->Cell(70,5,utf8_decode(trim($data['gerencia'])),1,0,'L');
				}
				else
				{
					$this->pdf->Cell(70,5,utf8_decode(trim($data['coordinacion'])),1,0,'L');
				}

				$this->pdf->Cell(35,5,utf8_decode(trim($data['nombre']).' '.trim($data['apellido'])),1,0,'L');
				$this->pdf->Cell(25,5,utf8_decode(trim($data['cargo'])),1,0,'L');
				$this->pdf->Cell(30,5,utf8_decode($data['via_solicitud']),1,0,'L');
				$this->pdf->Cell(53,5,utf8_decode(trim($data['motivo'])),1,0,'L');
				$this->pdf->Cell(28,5,trim($data['fecha_solicitud']),1,0,'L');
				$this->pdf->Cell(29,5,trim($data['fecha_respuesta']),1,1,'L');

			}
			$this->pdf->Output('Reportes de Solicitudes y Soporte Tecnico fecha entre '.$fecha_desde.' y '.$fecha_hasta,'I');
		}

	}

	public function procesar_solicitud_pendiente()
	{
		if($this->session->userdata('tipo_usuario')==0)
		{
			if($this->input->is_ajax_request())
			{
				$this->form_validation->set_rules('fecha','Fecha Respuesta','trim|required|regex_match[/^[0-9]{4}[\/\-]{1}[0-9]{2}[\/\-]{1}[0-9]{2}+$/]');
				$this->form_validation->set_message('required','El campo %s es requerido');
				$this->form_validation->set_message('regex_match','Error el campo %s formato no permitido');

				if($this->form_validation->run()===FALSE)
				{
					$mensaje = array(
						'respuesta'=>'error',
						'error'=>form_error('fecha_respuesta')
					);
				}
				else
				{
					$id = intval(xss_clean($this->input->post('id')));
					$idusuario =intval(xss_clean($this->input->post('idusuario')));
					$fecha_respuesta = xss_clean($this->input->post('fecha'));
					$cantidad = intval($this->input->post('cantidad'));
					$cantidad = $cantidad-1;

					$respuesta = $this->Administrador_model->procesar_solicitud_pendiente($id,$fecha_respuesta);
					if($respuesta==TRUE)
					{

						$mensaje = array(
							'respuesta'=>'exito',
							'exito'=>'Solicitud Procesada con Exito',
							'cantidad'=>$cantidad
						);
						//funcion que solo extraer nombre,apellido,usuario,correo,idsolicitudes,fecha_solicitudes
						$usuario = $this->Administrador_model->extrar_datos_usuarios_correo($idusuario);
						//$this->enviar_mensaje_de_solicitud($usuario);
					}
					else
					{
						$mensaje = array(
							'respuesta'=>'error',
							'error'=>'Ohh a ocurrido, no se pudo procesar su solicitud'
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

	public function enviar_mensaje_de_solicitud($usuario)
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

			 $this->email->from('fondobolivar1@gmail.com','Departamento de Telematica y Sistema');
			 $this->email->to(''.$usuario[0]['correo'].'');
			 $this->email->subject('Solicitud de Soporte');
		 	 $this->email->message("<h3 style='color:green;'>Su Soporte Ha Sido Procesado.</h3><p style='color:black';><b>Usuario</b> : ".$usuario[0]['usuario'].".</p><p style='color:black';><b>Nombre y Apellido</b> : ".$usuario[0]['nombre']." ". $usuario[0]['apellido'].".</p> <br><p style='color:black';>Su soporte numero # ".$usuario[0]['idsolicitud'].", de fecha ".$usuario[0]['fecha_solicitud']." , ha sido procesado, Usted debe realizar la encuesta de satisfacción para la confirmación de dicha solicitud. </p><br><p style='color:red';><b>Nota </b>: De no realizar dicha encuesta, no podra solicitar otro soporte y asistencia técnica.</p><br><p style='color:blue;'>Departamento de Telemática y Sistema - FONDO BOLíVAR.  </p>");
			 if (!$this->email->send())
			 {

			 }
			 else
			 {

			 }

	}

	public function respaldar_bd()
 {
	 if($this->session->userdata('tipo_usuario')=='0')
		{
		$this->load->dbutil();
			$backup = $this->dbutil->backup();

			//write_file('./respaldo_DB/ssat'.date('d-m-Y').' .gz', $backup);

			force_download('ssat'.date('d-m-Y').' .gz', $backup);


		}else
		{
			show_404();
		}
 }

 public function buscarUsuarios()
 {
	 if($this->input->is_ajax_request())
	 {
		 $this->form_validation->set_rules('buscarUsuariosUsuario','Buscar Usuario','trim|regex_match[/^[a-zA-Zñ-Ñ]+$/]');

		 $this->form_validation->set_message('regex_match','Error en el campo %s solo se permiten letras');

		 if($this->form_validation->run()===FALSE)
		 {
			 $mensaje = array(
				 'respuesta'=>'error',
				 'error'=>form_error('buscarUsuariosUsuario')
			 );
		 }
		 else
		 {
			  $usuario = $this->input->post('buscarUsuariosUsuario');
				$respuesta = $this->Administrador_model->buscar_usuarios($usuario);
				if(count($respuesta)>=1)
				{

					$mensaje = array(
						'respuesta'=>'exito',
						'usuario'=>$respuesta
					);

				}
				else
				{
					$mensaje = array(
						'respuesta'=>'error',
						'error_usuario'=>'usuario no encontrado'
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

?>
