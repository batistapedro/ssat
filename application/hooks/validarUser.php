<?php
if (!defined( 'BASEPATH')) exit('No direct script access allowed');
class validarUser
{
	private $ci;
	public function __construct()
	{
		$this->ci =& get_instance();
		!$this->ci->load->library('session') ? $this->ci->load->library('session') : false;
		!$this->ci->load->helper('url') ? $this->ci->load->helper('url') : false;
	}

	public function check_login()
	{
		if($this->ci->uri->segment(1) == '' && $this->ci->session->userdata('id') == true)
	    {
	            redirect(base_url('Direccionar'));
	    }
	    else if($this->ci->session->userdata('id') == false && $this->ci->uri->segment(1) != 'Inicio')
	    {
	        	redirect(base_url('Inicio'));
	    }
	    else if($this->ci->uri->segment(1) == 'administrador' && $this->ci->session->userdata('tipo_usuario')!='0')
	    {
	          redirect(base_url('Direccionar'));
	    }
	    else if($this->ci->uri->segment(1) == 'usuarios' && $this->ci->session->userdata('tipo_usuario')!='1')
	    {
	          redirect(base_url('Direccionar'));
	    }
		}
}
