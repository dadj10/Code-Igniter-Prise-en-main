<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accueil extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();

		if(!$this->session->has_userdata('user'))	redirect(base_url('connexion'));

		$this->load->library('encrypt');
        $this->load->library('user_agent');

		$this->load->helper('cookie');
        $this->load->helper('security');

        $this->load->model('users_model', 'usersManager');
	    $this->load->model('role_users_model', 'roleuserManager');	 
	}


	public function index()
	{
		$data['outputfooter'] = $this->load->view('Shared/footer',NULL,TRUE);	//	injection du footer
		$this->layout->set_page_js($this->load->view('shared/accueil/accueil_js',NULL,TRUE));	// injection du JS dans notre vue

		$data['accueil_nav'] = 'active';
		$data['title'] = "Tableau de bord";

		$this->layout->view('accueil/index',$data);
	}


	
	public function langue()
	{
		$langue= get_cookie('user_lang');
		
		$lang = $langue=="fr" ? "en": "fr";

		set_cookie('user_lang',$lang,$this->config->item('sess_expiration'));

		redirect(base_url('index'));
	}
}