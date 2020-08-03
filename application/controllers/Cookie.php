<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cookie extends CI_Controller {
	
	public function __construct(){

		parent::__construct();	# Obligatoire

		if(!$this->session->has_userdata('user'))	redirect(base_url('connexion'));

		$this->load->library('encrypt');

    	$this->load->library('user_agent');


		$this->load->helper('cookie');

    	$this->load->helper('security');


    	$this->load->model('UtilisateursRolesView_model', 'UtilisateursRolesViewManager');

    	$this->load->model('Utilisateurs_model', 'UtilisateurManager');		
	}



	public function index()
	{    	
		redirect(base_url('accueil'));		# redirection : appel du controlleur 'connexion'
	}
}