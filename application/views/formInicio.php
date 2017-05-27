<!doctype>
<html lang='es'>
	<head>
		<meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="icon" href='./favicon.ico'>
		<title>Solicitud de Soporte y Asistencia Tecnica</title>
		<link rel="stylesheet" href="./public/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="./public/bootstrap/css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="./public/jqueryui/jquery-ui.min.css">
  <script src="./public/jquery/jquery.js"></script>
  <script src="./public/bootstrap/js/bootstrap.min.js"></script>
  <script src="./public/jqueryui/jquery-ui.min.js"></script>

	<style>
		#form_login
		{
			width:60%;
			border-radius: 12px 12px 12px 12px;
			padding: 23px;
			box-shadow: 0px 0px 38px rgba(0,0,0,0.5);
			margin-top: 40px;
		}
		#error_usuario, #error_clave
		{
			display:none;
		}
		footer
		{
			width:100%;
			position: absolute;
			bottom: 0 !important;
			bottom: -1px;
      border-radius: 5px;
      padding:8px;
		}

	</style>

	<script>
		$(document).ready(function(){

			$('#form_login').on('submit',function(e){
				e.preventDefault();
				$.ajax({
					type : $(this).attr('method'),
					url: $(this).attr('action'),
					data : $(this).serialize(),
					success : function(respuesta)
					{
						console.log(respuesta);
						json = JSON.parse(respuesta);
						$('#error_usuario, #error_clave').css('display','none').html('');
						if(json.respuesta=='error')
						{
							if(json.error_usuario)
							{
								$('#error_usuario').css('display','block').append(json.error_usuario);
							}

							if(json.error_clave)
							{
								$('#error_clave').css('display','block').append(json.error_clave);
							}
							if(json.error_validar)
							{
								$('#error_validar_usuario_body').html('<h3 class="text-danger">'+json.error_validar+'</h3>')
								$('#error_validar_usuario').modal('show');
							}
						}
						else if(json.respuesta=='exito')
				  	{
							$("#sesionusuariobody").html('<h3 class="text-info">Bienvenido al sistema, '+json.usuario+'</h3>');
							$("#sesionusuarioir").attr('href',json.url);
							$("#sesionusuario").modal('show');
				  	}
					}
				});
			});

			$('#limpiar').on('click',function(){
					$('#error_usuario, #error_clave').css('display','none').html('');
			});

		});
	</script>
	</head>
	<body>
		<div class='container'>
			<div class='row'>
					<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>

						<!--modal error usuario o  clave-->
						<div class="modal fade" tabindex="-1" role="dialog" id="error_validar_usuario" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
									<div class="modal-content">
											<div class="modal-header">
														<h3 class="modal-title text-center text-info">Error al Entrar al Sietema</h3>
											</div>
											<div class="modal-body text-center" id="error_validar_usuario_body"></div>
								</div>
							</div>
						</div>
						<!--fin modal error usuario o clave -->

						<!--modal de session usuario-->
						<div class="modal fade" tabindex="-1" data-backdrop="static" role="dialog" id="sesionusuario" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
									<div class="modal-content">
											<div class="modal-header">
														<h3 class="modal-title text-center text-info">Entrar al Sistema</h3>
											</div>
											<div class="modal-body text-center" id="sesionusuariobody"></div>
											<div class="modal-footer">
												<a href="" class="btn btn-lg btn-primary" id="sesionusuarioir" title="pulse aqui para entrar al sistema"> Aceptar  <span class="glyphicon glyphicon-log-in"></span> </a>
											</div>
								</div>
							</div>
						</div>
<!--fin modal de session usuario-->
						<?php echo form_open(base_url('Inicio/login'),array('class'=>'form center-block','role'=>'form','id'=>'form_login'));?>
							<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
								<div class='row'>
									<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6'>
										<img src="./public/img/Logofondo.png" class='img img-responsive center-block'>
									</div>
									<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6'>
										<img src='./public/img/ISO_9001.png' class='img img-responsive center-block' style='width:90px;'>
									</div>
								</div>
							</div>
							<caption><h3 class='text-center text-info'>Iniciar Sesion</h3></caption>
							<div class='form-group'>
								<label class='text-primary text-left' for='usuario'>Usuario :</label>
								<input type='text' class='form-control' name='usuario' id='usuario' placeholder='Digiste Usuario'>
							</div>
							<div class='alert alert-danger' id='error_usuario'></div>
							<div class='form-group'>
								<label class='text-primary text-left' for='clave'>Clave :</label>
								<input type='password' class='form-control' name='clave' id='clave' placeholder="Digite Clave">
							</div>
							<div class='alert alert-danger' id='error_clave'></div>
							<div class='form-group text-center'>
								<button type='submit' class='btn btn-primary'>Entrar <span class='glyphicon glyphicon-ok-sign'></span></button>
								<button type='reset' class='btn btn-default' id='limpiar'>Limpiar <span class='glyphicon glyphicon-remove-sign'></span></button>
							</div>
						<?=form_close();?>
					</div>

			</div>

		</div>
		<footer class='text-center'>
			<p>
					 Fondo para el Desarrollo Económico del Estado Bolívar, (FONDO BOLÍVAR). <br>
					 copy-left 2016 | Desarrollado Por El Departamento de Telematica y Sistema. <br>
			</p>
			 </footer>
		</header>
	</body>
</html>
