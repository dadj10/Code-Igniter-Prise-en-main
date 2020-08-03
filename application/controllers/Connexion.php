<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Connexion extends CI_Controller {

	/**
	 * parametres de connexion admin system
	 *
	 * This class object is the super class that every library in
	 * CodeIgniter will be assigned to.
	 *
	 * @error reporting by different environments [ 'development' | 'testing' | 'production' ]
	 *
	 * @package		wow-ci
	 * @author		dadjdev, Analyste Programmeur, Hyper Access Systems
	 * @subpackage	Libraries
	 * @link		http://hyperaccesss.com/
	 */

	public function __construct(){

		parent::__construct();

		if ($this->session->user)	$this->session->sess_destroy();

		$this->load->helper('cookie');
	    $this->load->helper('security');

		$this->load->library('encrypt');
	    $this->load->library('session');
	    $this->load->library('user_agent');
	    $this->load->library('form_validation');

	    $this->load->model('users_model', 'usersManager');
	    $this->load->model('role_users_model', 'roleuserManager');	    
	}



	public function index()
	{
		if ($this->input->post())
		{
			$this->form_validation->set_rules('LOGIN', 'Nom d\'utilisateur','required', array('required' => 'Nom d\'utilisateur requis'));
			$this->form_validation->set_rules('PASSWORD_UT', 'Mot de passe','required', array('required' => 'Mot de passe requis'));

			if ($this->form_validation->run() == FALSE)
			{
				$this->session->set_flashdata('flashdata', '<p class="alert alert-danger animated fadeIn text-justify well-sm">Veuillez renseiger correctement tous les champs.</p>');

				$data['LOGIN'] = $this->input->post('LOGIN');

				$this->load->view('connexion/index', $data);
			}
			else
			{				
				$auth = array('EMAIL_UT' => $this->input->post('LOGIN'));

				# recuperation des donnée liées au login
				$dataUt = $this->roleuserManager->read('*', $auth);

				$dataUtilisateur=(count($dataUt)>0)? $dataUt[0] : $dataUt;

				# Verification de l'existence l'utilisateur
				if($dataUtilisateur)
				{
					$mot_de_passe_poster=$this->input->post('PASSWORD_UT');

					$mot_de_passe_enregistrer=$this->encrypt->decode($dataUtilisateur->PASSWORD_UT);
					var_dump($mot_de_passe_poster);
					var_dump($mot_de_passe_enregistrer);
					exit();

					# Verification de son mot de passe
					if($mot_de_passe_enregistrer==$mot_de_passe_poster)
					{
						# Verification du statut de l'utilisateur
						if($dataUtilisateur->ETAT==1)
						{
							# Creation de la session de l'utilisateur
							$this->session->set_userdata('user', $dataUtilisateur);

							set_cookie('pseudo',$this->encrypt->encode($this->input->post('UT_LOGIN')),$this->config->item('sess_expiration_cookie') ); 
							set_cookie('mp',$this->encrypt->encode($motpasse), $this->config->item('sess_expiration_cookie')); 

							if ( $this->session->user )
							{
								# generation de ID_SESSION...
								$user_options = array (
									'ID_SESSION' => md5($this->session->session_id.''.$dataUtilisateur->PASSWORD_UT),
									'IP' => $this->get_ip()
								);

								$where = array (
									'ID_UT' => $this->session->user->ID_UT
								);

								$update = $this->UtilisateursManager->update($where, $user_options);

								if ( $update ) 
								{
									$this->session->user->ID_SESSION = $user_options["ID_SESSION"];

									$this->session->user->IP = $user_options["IP"];		

									redirect(base_url('index'));
								}
							}
							else
							{
								$this->session->set_flashdata('danger_flash', '<p class="alert alert-danger animated shake"><span class="fa fa-close mr-sm"></span>Echec de connexion, Une erreur s\'est produite lors du lancement de la session utilisateur.</p>');

								redirect(base_url('connexion'));
							}
							
						}
						else
						{
							if($dataUtilisateur->ETAT==0)
							{
								$this->session->set_flashdata('warning_flash', '<p class="alert alert-warning animated shake"><span class="fa fa-warning mr-sm"></span>Echec de connexion, votre compte est desectivé,\nmerci de contacter votre administrateur.</p>');

								redirect(base_url('connexion'));
							}
							if($dataUtilisateur->ETAT==-1)
							{
								$this->session->set_flashdata('warning_flash', '<p class="alert alert-warning animated shake"><span class="fa fa-warning mr-sm"></span>Echec de connexion, votre compte à été supprimé,\nmerci de contacter votre administrateur.</p>');

								redirect(base_url('connexion'));
							}
							else
							{
								$this->session->set_flashdata('warning_flash', '<p class="alert alert-warning animated shake"><span class="fa fa-warning mr-sm"></span>Echec de connexion, votre compte est desectivé,\nmerci de bien vouloir l\'activer en cliquant sur lien contenir dans le mail que vous avez recu.</p>');

								redirect(base_url('connexion'));
							}
						}					
					}
					else
					{
						$this->session->set_flashdata('warning_flash', '<p class="alert alert-warning animated shake"><span class="fa fa-warning mr-sm"></span>Echec de connexion, erreur de mot de passe .</p>');

						redirect(base_url('connexion'));
					}
				}
				else
				{
					$this->session->set_flashdata('warning_flash', '<p class="alert alert-warning animated shake"><span class="fa fa-warning mr-sm"></span>Echec de connexion, aucun compte ne correspond a vos login .</p>');

					redirect(base_url('connexion'));
				}
			}
		}
		else
		{
			$this->load->view('connexion/index');
		}
	}


	public function logout()
	{
			
		$this->session->sess_destroy();

		delete_cookie('pseudo');

		delete_cookie('mp');

		redirect(base_url('connexion'));
	}

	
    public function get_ip()
    {
    	if ( $this->session->user )
    	{
	        if ( isset ( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
	        {
	            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	        }
	        elseif ( isset ( $_SERVER['HTTP_CLIENT_IP'] ) )
	        {
	            $ip  = $_SERVER['HTTP_CLIENT_IP'];
	        }
	        else
	        {
	            $ip = $_SERVER['REMOTE_ADDR'];
	        }
	        return $ip;
    	}
    	else
    	{
    		redirect(base_url('connexion'));
    	}
    }


    public function sessionAjax()
	{
		if ( $this->session->user )
		{
		  	$id=$this->session->user->ID_UT;

		   	$json = array();

	    	$dataJson=array();

		  	$sessionInfo = $this->roleuserManager->read('*',array('ID_UT'=>$id)) ;

		  	if(count($sessionInfo)>0)
		  	{
		  		$statut=0;

		  		foreach($sessionInfo as $session):

		  			if ( $this->session->user->ID_SESSION != $session->ID_SESSION )
		  			{
			  			$table['IP']=$session->IP ;

		  				$statut=1;
		  			}
		  			else
		  			{
			  			$table['IP']=$session->IP ;	# compte desactivé

			  			$statut=0;
		  			}

					$json[]=$table;

		   			$dataJson=array('statut'=>$statut,'data'=>$json);

			   	endforeach;
		  	}
			else
			{
				$dataJson=array('statut'=>2);		# compte est supprimé
			}

		   	echo json_encode($dataJson);
		}
		else
		{
			redirect(base_url('connexion'));
		}
	}
}


/* End of file Connexion.php */
/* Location: ./application/controllers/Connexion.php */