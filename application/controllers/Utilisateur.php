<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Utilisateur extends CI_Controller {

	public function __construct(){

		parent::__construct();

		// if ( !$this->session->user OR $this->session->user->ETAT_UT != 1)
		// {
		// 	redirect(base_url('connexion'));
		// }
		// elseif ( $this->session->user->ID_ROL < 2 )
		// {
		// 	redirect(base_url('accueil'));
		// }
		
		$this->load->helper('cookie');
	    $this->load->helper('security');

		$this->load->library('encrypt');
	    $this->load->library('session');
	    $this->load->library('user_agent');
	    $this->load->library('form_validation');

	    $this->load->model('role_model', 'roleManager');	  
		$this->load->model('users_model', 'usersManager');
	    $this->load->model('role_users_model', 'roleuserManager');	  
	}


	public function index()
	{
		$data['outputfooter'] = $this->load->view('Shared/footer',NULL,TRUE);	//	injection du footer
		$this->layout->set_page_js($this->load->view('shared/user_js',NULL,TRUE));

		$data['user_nav'] = 'active';
		$data['title'] = "Liste des utilisateurs";
		$data['heading'] = "Utilisateurs";
		
		$data['dataRole'] = $this->roleManager->read();
		$data['dataUsers'] = $this->roleuserManager->read();

		$this->layout->view('user/index', $data);
	}



	public function create()
	{
		$this->layout->set_page_js($this->load->view('shared/user_js',NULL,TRUE));

		$data['title'] = "Utilisateurs";
		$data['securite_nav'] = "active";
		$data['utilisateur_nav'] = "active";
		$data['breadcrumb'] =  "<ol class='breadcrumb'>
									<li class='breadcrumb-item'><a href='".base_url('accueil')."'>Tableau de bord</a></li>
									<li class='breadcrumb-item'><a href='".base_url('accueil')."'>Sécurité</a></li>
									<li class='breadcrumb-item'><strong><a href='#'>Utilisateurs</a></strong></ol>";

		if ( $this->session->user->ID_ROL == 2 )
		{
			$data['dataUsers'] = $this->roleUserViewManager->read("*", array('ID_ROL <' => 2));
		}
		else
		{
			$data['dataUsers'] = $this->roleUserViewManager->read();
		}

		$data['dataRole'] = $this->RoleManager->read();

		if ($this->input->post())
		{
			$this->form_validation->set_rules('NOM_UT', 'Non','required', array('required' => 'Nom réquis'));
			$this->form_validation->set_rules('LOGIN_UT', 'Login','required', array('required' => 'Login utilisateur réquis'));
			$this->form_validation->set_rules('EMAIL_UT', 'Adresse e-mail','required', array('required' => 'Adresse e-mail réquis'));
			$this->form_validation->set_rules('ID_ROL', 'Service','required', array('required' => 'Privilège réquis'));
			$this->form_validation->set_rules('PASSWORD_UT', 'Mot de passe','required', array('required' => 'Mot de passe réquis'));			
			$this->form_validation->set_rules('PASSWORD_UT_CONF', 'Confime mot de passe','required', array('required' => 'Confirmation de mot de passe réquis'));
			
			if ($this->form_validation->run() == FALSE)
			{
				$data['info'] = "Echec d'enregistrement du l'utilisateur.";
				$data['alert'] = "Attention !";
				$data['class'] = "alert alert-warning animated shake";
				$data['faclass'] = "fa fa-warning m-r-sm";

	    		$this->layout->view('utilisateur/index', $data);				
			}
			else
			{
				if ( $this->input->post('PASSWORD_UT') != $this->input->post('PASSWORD_UT_CONF') )
				{
					$data['info'] = "Echec d'enregistrement du l'utilisateur, <b>mots de passe non-identiques<b>.";
					$data['alert'] = "Attention !";
					$data['class'] = "alert alert-warning animated shake";
					$data['faclass'] = "fa fa-warning m-r-sm";

					$this->layout->view('utilisateur/index', $data);
				}
				else
				{
					if ( strlen($this->input->post('PASSWORD_UT_CONF'))>= "8" )
					{
						if ( $this->input->post('PASSWORD_UT') == $this->input->post('PASSWORD_UT_CONF') )
						{
							# verification du mail
							if ( filter_var($this->input->post('EMAIL_UT'), FILTER_VALIDATE_EMAIL) ) 
							{
								if ( ctype_digit($this->input->post('ID_ROL')) )
								{
									$verificationContact = true;

									if ( $this->input->post('MOBILE_UT') )
									{
										$verificationContact = $this->verificationDigit($this->input->post('MOBILE_UT'));

										if ( $verificationContact == false )
										{
											$data['info'] = "Echec d'enregistrement du l'utilisateur, <b>contact incorrect</b>.";
											$data['alert'] = "Attention !";
											$data['class'] = "alert alert-warning animated shake";
											$data['faclass'] = "fa fa-close m-r-sm";

											$data['error_contact'] = "Contact incorrect !";

											$this->layout->view('utilisateur/index', $data);
										}
										else
										{
											$MOBILE_UT = $this->input->post('MOBILE_UT');
										}
									}

									if ( $verificationContact == true )		//--- verification effectuée
									{
										# verification email...
										$verificationEmail = $this->userManager->read('*', array('EMAIL_UT' => $this->input->post('EMAIL_UT')));

										if ( count($verificationEmail)==0 )
										{
											# verification du login
											$verificationlogin = $this->userManager->read('*', array('LOGIN_UT' => $this->input->post('LOGIN_UT')));

											if ( count($verificationlogin)==0) 
											{
												$key = md5(@pisam2019);

												$options_echappees = array(
													
								        			'NOM_UT' => strtoupper($this->input->post('NOM_UT')),

								        			'LOGIN_UT' => $this->input->post('LOGIN_UT'),	# login n'est jamais modifier il es unique a vie
								        			'EMAIL_UT' => $this->input->post('EMAIL_UT'),
								        			
								        			'ID_ROL' => $this->input->post('ID_ROL'),
								        			'MOBILE_UT' => $MOBILE_UT,

								        			'PASSWORD_UT' => $this->encrypt->encode($this->input->post('PASSWORD_UT'), $key),

								        			'DATE_INSERT_UT' => strftime("%Y-%m-%d %H:%M:%S")."",
									        		'DATE_MODIF_UT' => strftime("%Y-%m-%d %H:%M:%S")."",
								        			'ETAT_UT' =>'0'
								        			);						        		
									        		
												$reps=$this->userManager->create($options_echappees);

												if ($reps)
												{
													$this->session->set_flashdata('success_flash', '<p class="alert alert-success animated fadein"><span class="fa fa-check m-r-sm"></span>Succès utilisateur ajouté.</p>');
													redirect(base_url('utilisateur'));							
												}
												else
												{
													$data['info'] = "Echec d'enregistrement du l'utilisateur, merci de bien vouloir réessayer plutard.";
													$data['alert'] = "Attention !";
													$data['class'] = "alert alert-warning animated shake";
													$data['faclass'] = "fa fa-close m-r-sm";

													$this->layout->view('utilisateur/index', $data);
												}					
											}
											else
											{
												$data['info'] = "Echec d'enregistrement du l'utilisateur, <b>login est déja utilisé</b>.";
												$data['alert'] = "Attention !";
												$data['class'] = "alert alert-warning animated shake";
												$data['faclass'] = "fa fa-close m-r-sm";

												$data['error_login'] = "Ce Login est déja utilisé !";

												$this->layout->view('utilisateur/index', $data);
											}
										}
										else
										{
											$data['info'] = "Echec d'enregistrement du l'utilisateur, <b>adresse e-mail est déja enregistré</b>.";
											$data['alert'] = "Attention !";
											$data['class'] = "alert alert-warning animated shake";
											$data['faclass'] = "fa fa-close m-r-sm";

											$data['error_email'] = "Cet adresse est déja enregistré !";

											$this->layout->view('utilisateur/index', $data);
										}
									}
								}
								else
								{
									$data['info'] = "Echec d'enregistrement du l'utilisateur, <b>Role incorrect</b>.";
									$data['alert'] = "Attention !";
									$data['class'] = "alert alert-warning animated shake";
									$data['faclass'] = "fa fa-close m-r-sm";

									$data['error_role'] = "Role incorrect !";

									$this->layout->view('utilisateur/index', $data);
								}
							}
							else
							{
								$data['info'] = "Echec d'enregistrement du l'utilisateur, <b>l'adresse e-mail est invalide</b>, Veuilliez reessayer.";
								$data['alert'] = "Attention !";
								$data['class'] = "alert alert-warning animated shake";
								$data['faclass'] = "fa fa-close m-r-sm";

								$data['error_email'] = "Adresse e-mail invalide";

								$this->layout->view('utilisateur/index', $data);
							}					
						}
						else
						{
							$data['info'] = "Echec d'enregistrement de l'utilisateur, <b>Mot de passe non-identiques</b>.";
							$data['alert'] = "Attention !";
							$data['class'] = "alert alert-warning animated shake";
							$data['faclass'] = "fa fa-close m-r-sm";

				    		$this->layout->view('utilisateur/index', $data);
						}
					}
					else
					{
						$data['info'] = "Echec d'enregistrement de l'utilisateur, <b>Mot de passe trop court</b>, 8 caractères au minimum, veuilliez reéssayer.";
						$data['alert'] = "Attention !";
						$data['class'] = "alert alert-warning animated shake";
						$data['faclass'] = "fa fa-warning m-r-sm";

						$this->layout->view('utilisateur/index', $data);
					}
				}
			}
		}
		else
		{
			redirect(base_url('utilisateur'));
		}
	}



	public function getAjax($id=NULL)
	{
	  	if($this->input->post())
	  	{
	  	 	$id=$this->input->post('id');
	  	}
	  	else
	  	{
	  		redirect(base_url('utilisateur'));
	  	}
    	
    	$dataJson=array();
	   	$json = array();

	  	$userInfo = $this->userManager->read('*',array('ID_UT'=>$id)) ;

	  	if(count($userInfo)>0)
	  	{
	  		foreach($userInfo as $user):

		  		$table['UT']=$user->ID_UT ;
		  		$table['RL']=$user->ID_ROL ;
			   	$table['NU']=$user->NOM_UT ;
			   	$table['MU']=$user->MOBILE_UT ;
			   	$table['LU']=$user->LOGIN_UT ;
			   	$table['EU']=$user->EMAIL_UT ;
			   	
			   	$json[]=$table;

		   	endforeach;

		   	$dataJson=array('statut'=>1,'data'=>$json);
	  	}
		else
		{
			$dataJson=array('statut'=>0);
		}

	   	echo json_encode($dataJson);
	}



	public function update($id=NULL)
	{
		$this->layout->set_page_js($this->load->view('shared/user_js',NULL,TRUE));

		$data['title'] = "Utilisateurs";
		$data['securite_nav'] = "active";
		$data['utilisateur_nav'] = "active";
		$data['breadcrumb'] =  "<ol class='breadcrumb'>
									<li class='breadcrumb-item'><a href='".base_url('accueil')."'>Tableau de bord</a></li>
									<li class='breadcrumb-item'><a href='".base_url('accueil')."'>Sécurité</a></li>
									<li class='breadcrumb-item'><strong><a href='#'>Utilisateurs</a></strong></ol>";

		if ( $this->session->user->ID_ROL == 2 )
		{
			$data['dataUsers'] = $this->roleUserViewManager->read("*", array('ID_ROL <' => 2));
		}
		else
		{
			$data['dataUsers'] = $this->roleUserViewManager->read();
		}

		$data['dataRole'] = $this->RoleManager->read();

		if ($this->input->post())
		{
			if ($this->input->post('ID_UT') != NULL)
			{
				if ( ctype_digit($this->input->post("ID_ROL")) )
				{
					$this->form_validation->set_rules('NOM_UT', 'Non','required', array('required' => 'Nom réquis'));
					$this->form_validation->set_rules('ID_ROL', 'Service','required', array('required' => 'Privilège réquis'));

					if ( $this->input->post("PASSWORD_UT") )
					{
						$this->form_validation->set_rules('PASSWORD_UT_CONF', 'Confime mot de passe','required', array('required' => 'Confirmation de mot de passe réquis'));
					}					

					if ($this->form_validation->run() == FALSE)
					{
						$data['info'] = "Echec modification de l'utilisateur.";
						$data['alert'] = "Attention !";
						$data['class'] = "alert alert-warning animated shake";
						$data['faclass'] = "fa fa-close m-r-sm";

			    		$this->layout->view('utilisateur/index', $data);
					}
					else
					{
						$key = md5(@pisam2019);
						$verificationData = $this->userManager->query(" SELECT * FROM UTILISATEUR WHERE ID_UT = ".$this->input->post("ID_UT"));	# recuperation des anciennes informations

						if ( count($verificationData)>0 ) 
						{
							if ( $this->input->post("PASSWORD_UT") )  # mot de passe
							{
								if ( strlen($this->input->post('PASSWORD_UT_CONF'))>= "8" )
								{
									if ( $this->input->post('PASSWORD_UT') == $this->input->post('PASSWORD_UT_CONF') ) 
									{
										# update
										$options_echappees = array(
						        			'NOM_UT' => strtoupper($this->input->post('NOM_UT')),

						        			'ID_ROL' => $this->input->post('ID_ROL'),
						        			'PASSWORD_UT' => $this->encrypt->encode($this->input->post('PASSWORD_UT_CONF'), $key),

							        		'DATE_MODIF_UT' => strftime("%Y-%m-%d %H:%M:%S")."",
						        			'ETAT_UT' => '2'		# initialisation du compte
						        			);

										$where = array(
						        			'ID_UT' => $this->input->post("ID_UT"),
						        			);

										$reps=$this->userManager->update($where,$options_echappees);

										if ($reps)
										{
											# mise a jour de la session
											if ( $this->input->post("ID_UT")==$this->session->user->ID_UT )
											{
												$this->session->user->NOM_UT = $options_echappees['NOM_UT'];
												$this->session->user->ID_ROL = $options_echappees['ID_ROL'];
												$this->session->user->PASSWORD_UT = $options_echappees['PASSWORD_UT'];
												$this->session->user->DATE_MODIF_UT = $options_echappees['DATE_MODIF_UT'];
												// $this->session->user->EMAIL_UT = $options_echappees['EMAIL_UT'];
											}
											
											$this->session->set_flashdata('success_flash', '<p class="alert alert-success animated fadein"><span class="fa fa-check m-r-sm"></span>Succès utilisateur mis à jour.</p>');
											redirect(base_url('utilisateur'));							
										}
										else
										{
											$data['info'] = "Une erreur s'est produite, Echec modification de l'utilisateur, merci de bien vouloir réessayer plutard.";
											$data['alert'] = "Attention !";
											$data['class'] = "alert alert-danger animated shake";
											$data['faclass'] = "fa fa-close m-r-sm";

								    		$this->layout->view('utilisateur/index', $data);
										}
									}
									else
									{
										$data['info'] = "Echec de modification de l'utilisateur, <b>Mot de passe non-identiques</b>.";
										$data['alert'] = "Attention !";
										$data['class'] = "alert alert-warning animated shake";
										$data['faclass'] = "fa fa-close m-r-sm";

							    		$this->layout->view('utilisateur/index', $data);
									}
								}
								else
								{
									$data['info'] = "Echec de modification de l'utilisateur, <b>Mot de passe trop court</b>, 8 caractères au minimum, veuilliez reéssayer.";
									$data['alert'] = "Attention !";
									$data['class'] = "alert alert-warning animated shake";
									$data['faclass'] = "fa fa-warning m-r-sm";

									$this->layout->view('utilisateur/index', $data);
								}
							}
							else  # pas de mot de passe
							{
								# update
								$options_echappees = array(
				        			'NOM_UT' => strtoupper($this->input->post('NOM_UT')),

				        			'ID_ROL' => $this->input->post('ID_ROL'),

					        		'DATE_MODIF_UT' => strftime("%Y-%m-%d %H:%M:%S")."",
				        			'ETAT_UT' => '2'		# initialisation du compte
				        			);

								$where = array(
				        			'ID_UT' => $this->input->post("ID_UT"),
				        			);

								$reps=$this->userManager->update($where,$options_echappees);

								if ($reps)
								{
									# mise a jour de la session
									if ( $this->input->post("ID_UT")==$this->session->user->ID_UT )
									{
										$this->session->user->NOM_UT = $options_echappees['NOM_UT'];
										$this->session->user->ID_ROL = $options_echappees['ID_ROL'];
										$this->session->user->DATE_MODIF_UT = $options_echappees['DATE_MODIF_UT'];
										// $this->session->user->EMAIL_UT = $options_echappees['EMAIL_UT'];
									}
									
									$this->session->set_flashdata('success_flash', '<p class="alert alert-success animated fadein"><span class="fa fa-check m-r-sm"></span>Succès utilisateur mis à jour.</p>');
									redirect(base_url('utilisateur'));							
								}
								else
								{
									$data['info'] = "Une erreur s'est produite, Echec modification de l'utilisateur, merci de bien vouloir réessayer plutard.";
									$data['alert'] = "Attention !";
									$data['class'] = "alert alert-danger animated shake";
									$data['faclass'] = "fa fa-close m-r-sm";

						    		$this->layout->view('utilisateur/index', $data);
								}
							}
						}
						else
						{
							$data['info'] = "Echec de modification de l'utilisateur, merci de bien vouloir réessayer plutard.";
							$data['alert'] = "Attention !";
							$data['class'] = "alert alert-danger animated shake";
							$data['faclass'] = "fa fa-close m-r-sm";

				    		$this->layout->view('utilisateur/index', $data);
						}
					}
				}
				else
				{
					$data['info'] = "Echec modification de l'utilisateur, <b>Role incorrect</b>.";
					$data['alert'] = "Attention !";
					$data['class'] = "alert alert-warning animated shake";
					$data['faclass'] = "fa fa-close m-r-sm";

					$data['error_role_update'] = "Role incorrect !";

					$this->layout->view('utilisateur/index', $data);
				}
			}
			else
			{
				$data['info'] = "Echec modification de l'utilisateur, merci de bien vouloir réessayer plutard.";
				$data['alert'] = "Attention !";
				$data['class'] = "alert alert-danger animated shake";
				$data['faclass'] = "fa fa-close m-r-sm";

	    		$this->layout->view('utilisateur/index', $data);
			}
		}
		else
		{
			redirect(base_url('utilisateur'));
		}
	}



	public function delete($id=NULL)
	{
		$this->layout->set_page_js($this->load->view('shared/user_js',NULL,TRUE));
		
		$data['title'] = "Utilisateurs";
		$data['securite_nav'] = "active";
		$data['utilisateur_nav'] = "active";
		$data['breadcrumb'] =  "<ol class='breadcrumb'>
									<li class='breadcrumb-item'><a href='".base_url('accueil')."'>Tableau de bord</a></li>
									<li class='breadcrumb-item'><a href='".base_url('accueil')."'>Sécurité</a></li>
									<li class='breadcrumb-item'><strong><a href='#'>Utilisateurs</a></strong></ol>";

		if ( $this->session->user->ID_ROL == 2 )
		{
			$data['dataUsers'] = $this->roleUserViewManager->read("*", array('ID_ROL <' => 2));
		}
		else
		{
			$data['dataUsers'] = $this->roleUserViewManager->read();
		}

		$data['dataRole'] = $this->RoleManager->read();

		if ($this->input->post())
		{
			if ($this->input->post("ID_UT") != NULL)
			{
				$options_echappees = array(
        			'ETAT_UT' => '-1'			# on ne supprime pas un user le statut passe a 2
        			);

				$where = array(
        			'ID_UT' => $this->input->post("ID_UT"),
        			);

				$reps=$this->userManager->update($where,$options_echappees);

				if ($reps)
				{
					$this->session->set_flashdata('success_flash', '<p class="alert alert-success animated fadein"><span class="fa fa-check m-r-sm"></span>Succès utilisateur supprimé.</p>');
					redirect(base_url('utilisateur'));
				}
				else
				{
					$data['info'] = "Echec suppression de l'utilisateur, merci de bien vouloir réessayer plutard.";
					$data['alert'] = "Attention !";
					$data['class'] = "alert alert-warning animated shake";
					$data['faclass'] = "fa fa-close m-r-sm";

					$this->layout->view('utilisateur/index', $data);
				}
			}
			else
			{
				$data['info'] = "Echec suppression de l'utilisateur, merci de bien vouloir réessayer plutard.";
				$data['alert'] = "Attention !";
				$data['class'] = "alert alert-warning animated shake";
				$data['faclass'] = "fa fa-close m-r-sm";

				$this->layout->view('utilisateur/index', $data);
			}
		}
		else
		{
			redirect(base_url('utilisateur'));
		}
	}


	public function etatAjax($id=NULL)
    {
        if($this->input->post())
        {
            $id=$this->input->post('id');
        }
        else
        {
            redirect(base_url('pays'));
        }
        
        $dataJson=array();
        $json = array();

        $infoUser = $this->userManager->read('*', array('ID_UT' => $id, 'ETAT_UT <>' => '-1'));

        if ( count($infoUser)>0 )
        {
            if ( $infoUser[0]->ETAT_UT == "1" )
            {
                $ETAT = 3;
            }
            elseif ( $infoUser[0]->ETAT_UT == "3" )
            {
                $ETAT = 1;
            }
                
            $options_echappees = array( 'ETAT_UT' => $ETAT );

            $where = array( 'ID_UT' => $id );

            $reps = $this->userManager->update($where,$options_echappees);

            if($reps)
            {
                $dataJson=array('statut'=>1);
            }
            else
            {
                $dataJson=array('statut'=>0);
            }

            echo json_encode($dataJson);
        }
        else
        {
            $dataJson=array('statut'=>0);
        }
    }



	public function verificationDigit($contact)
	{
		if ( ctype_digit($contact) && strlen($contact) == 8)
		{
			$reps =  true;
		}
		else
		{
			$reps = false;
		}

		return $reps;
	}
}
/* End of file Utilisateur.php */
/* Location: ./application/controllers/Utilisateur.php */