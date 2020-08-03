<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Parametre extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

        if(!$this->session->has_userdata('user'))
            redirect(base_url('connexion'));

		$this->load->library('encrypt');

        $this->load->helper('cookie');

        $this->load->helper('security');

        $this->load->library('user_agent');

        $this->load->model('Parametre_model', 'parametreManager');
	}


    public function index()
    {
        $data['title'] = "Parametre";

        $data['breadcrumb'] = "<li><a href='".base_url('index')."'>Tableau de bord</a></li>
								   <li>Système</li>
									<li class='active'>Parametre</li>";
                                    
        $data['outputfooter'] = $this->load->view('shared/footer',NULL,TRUE);

        // Cette instruction permet d'afficher la liste des catégorie
        $data['Parametres'] = $this->parametreManager->read('*', array('ETAT <> '=>0));

        $data['systeme_nav'] = "active";
        
        $data['parametre_nav'] = "active";

        $this->layout->view('parametre/parametre',$data);
    }


    /*******************************************************************
     *************Fonction de création d'un pays de produit*************
     *******************************************************************/
    public function create()
    {
        /*var_dump($this->input->post());
        exit();*/

        $data['title'] = "Paramètre";
        $data['systeme_nav'] = "active";
        $data['parametre_nav'] = "active";
        $data['breadcrumb'] = "<li><a href='".base_url('index')."'>Tableau de bord</a></li>
								   <li>Système</li>
								   <li>Localisation</li>
									<li class='active'>Paramètre</li>";

        if ($this->input->post())
        {
            $this->form_validation->set_rules('RAISONSOCIAL', 'Désignation','required', array('required' => 'Désignation est réquis'));
            $this->form_validation->set_rules('ADRESSE', 'Désignation','required', array('required' => 'Désignation est réquis'));
            $this->form_validation->set_rules('TELEPHONE', 'Désignation','required', array('required' => 'Désignation est réquis'));
            $this->form_validation->set_rules('MOBILE', 'Désignation','required', array('required' => 'Désignation est réquis'));
            $this->form_validation->set_rules('FAX', 'Désignation','required', array('required' => 'Désignation est réquis'));
            $this->form_validation->set_rules('EMAIL', 'Désignation','required', array('required' => 'Désignation est réquis'));
            $this->form_validation->set_rules('HORAIREOUVERTURE', 'Désignation','required', array('required' => 'Désignation est réquis'));
            $this->form_validation->set_rules('RCCM', 'Désignation','required', array('required' => 'Désignation est réquis'));
            /*$this->form_validation->set_rules('COMPTEOM', 'Désignation','required', array('required' => 'Désignation est réquis'));
            $this->form_validation->set_rules('COMPTEMOMO', 'Désignation','required', array('required' => 'Désignation est réquis'));
            $this->form_validation->set_rules('COMPTEVISA', 'Désignation','required', array('required' => 'Désignation est réquis'));*/

            if ($this->form_validation->run() == FALSE)
            {
                $data['info'] = "L'enrégistrement du pays a échoué.";
                $data['alert'] = "Attention !";
                $data['class'] = "alert alert-warning animated shake";
                $data['faclass'] = "fa fa-warning m-r-sm";

                $this->layout->view('parametre/parametre',$data);
            }
            else
            {

                if ( strlen($this->input->post('RAISONSOCIAL')) <= "3" )
                {
                    $data['info'] = "Veuillez saisir une raison sociale";
                    $data['alert'] = "Attention !";
                    $data['class'] = "alert alert-warning animated shake";
                    $data['faclass'] = "fa fa-warning m-r-sm";

                    $this->layout->view('parametre/parametre',$data);
                }else{

                    $data = array(
                        'RAISONSOCIAL' => $this->input->post('RAISONSOCIAL'),
                        'ADRESSE' => $this->input->post('ADRESSE'),
                        'TELEPHONE' => $this->input->post('TELEPHONE'),
                        'MOBILE' => $this->input->post('MOBILE'),
                        'FAX' => $this->input->post('FAX'),
                        'EMAIL' => $this->input->post('EMAIL'),
                        'HORAIREOUVERTURE' => $this->input->post('HORAIREOUVERTURE'),
                        'LOGO' => $this->input->post('LOGO'),
                        'RCCM' => $this->input->post('RCCM'),
                        'COMPTEOM' => $this->input->post('COMPTEOM'),
                        'COMPTEMOMO' => $this->input->post('COMPTEMOMO'),
                        'COMPTEFLOOZ' => $this->input->post('COMPTEFLOOZ'),
                        'COMPTEVISA' => $this->input->post('COMPTEVISA')
                    );

                    $reps = $this->parametreManager->create($data);

                    if ($reps)
                    {
                        $this->session->set_flashdata('success_flash', '<p class="alert alert-success animated fadeIn"><span class="fa fa-check mr-sm"></span>Paramètre créé avec succès.</p>');
                        redirect(base_url('parametre'));
                    }
                    else
                    {
                        $data['info'] = "Enregistrement à échoué.";
                        $data['alert'] = "Attention !";
                        $data['class'] = "alert alert-warning animated shake";
                        $data['faclass'] = "fa fa-close m-r-sm";

                        $this->layout->view('parametre/parametre',$data);
                    }
                }
            }
        }
        else
        {
            redirect(base_url('parametre'));
        }
    }




    /******************************************************************
     ******Fonction de mise à jour d'une catégorie de produit**********
     ******************************************************************/
    public function findOneById()
    {
        if($this->input->post())
        {
            $id=$this->input->post('ID_SOCIETE');
        }
        else
        {
            redirect(base_url('parametre'));
        }

        $dataJson=array();
        $json = array();

        $data = $this->parametreManager->read('*',array('ID_SOCIETE'=>$id)) ;

        if(count($data) > 0)
        {
            foreach($data as $parametre):

                $table['ID_SOCIETE']=$parametre->ID_SOCIETE ;
                $table['RAISONSOCIAL']=$parametre->RAISONSOCIAL ;
                $table['RCCM']=$parametre->RCCM ;
                $table['ADRESSE']=$parametre->ADRESSE ;
                $table['TELEPHONE']=$parametre->TELEPHONE ;
                $table['MOBILE']=$parametre->MOBILE ;
                $table['FAX']=$parametre->FAX ;
                $table['EMAIL']=$parametre->EMAIL ;
                $table['HORAIREOUVERTURE']=$parametre->HORAIREOUVERTURE ;
                $table['LOGO']=$parametre->LOGO ;
                $table['RCCM']=$parametre->RCCM ;
                $table['COMPTEOM']=$parametre->COMPTEOM ;
                $table['COMPTEMOMO']=$parametre->COMPTEMOMO ;
                $table['COMPTEFLOOZ']=$parametre->COMPTEFLOOZ ;
                $table['COMPTEVISA']=$parametre->COMPTEVISA ;

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


    /******************************************************************
     ******Fonction de mise à jour d'une catégorie de produit**********
     ******************************************************************/
    public function delete()
    {
        /*var_dump($this->input->post());
        exit();*/

        // Mes variables
        $data['title'] = "Paramètre";
        $data['systeme_nav'] = "active";
        $data['parametre_nav'] = "active";
        $data['breadcrumb'] = "<li><a href='".base_url('index')."'>Tableau de bord</a></li>
								   <li>Système</li>
									<li class='active'>Paramètre</li>";

        if ($this->input->post())
        {
            $id = $this->input->post('ID_SOCIETE');

            if ($id)
            {
                $data = array('ETAT' =>0);

                $id_parametre = array(
                    'ID_SOCIETE' => $this->input->post('ID_SOCIETE')
                );

                $response = $this->parametreManager->update($id_parametre, $data);

                if($response)
                {
                    $this->session->set_flashdata('success_flash', '<p class="alert alert-success animated fadeIn"><span class="fa fa-check mr-sm"></span>Paramètre supprimé avec succès.</p>');
                    redirect(base_url('parametre'));
                }
                else
                {
                    $data['info'] = "La suppression a échoué.";
                    $data['alert'] = "Attention !";
                    $data['class'] = "alert alert-warning animated shake";
                    $data['faclass'] = "fa fa-close m-r-sm";

                    $this->layout->view('parametre/parametre',$data);
                }
            }else{
                $this->session->set_flashdata('success_flash', '<p class="alert alert-danger animated fadeIn"><span class="fa fa-check mr-sm"></span>La suppression a échoué.</p>');
                redirect(base_url('parametre'));
            }
        }
        else
        {
            redirect(base_url('parametre'));
        }
    }


    /*******************************************************************
     ***********Fonction de permet de mise à jour un pays***************
     *******************************************************************/
    public function update()
    {
        /*var_dump($this->input->post());
        exit();*/

        // Mes variables
        $data['title'] = "Paramètre";
        $data['systeme_nav'] = "active";
        $data['parametre_nav'] = "active";
        $data['breadcrumb'] = "<li><a href='".base_url('index')."'>Tableau de bord</a></li>
								   <li>Système</li>
									<li class='active'>Paramètre</li>";

        if ($this->input->post())
        {
            $this->form_validation->set_rules('RAISONSOCIAL', 'Désignation','required', array('required' => 'Désignation est réquis'));
            $this->form_validation->set_rules('ADRESSE', 'Désignation','required', array('required' => 'Désignation est réquis'));
            $this->form_validation->set_rules('TELEPHONE', 'Désignation','required', array('required' => 'Désignation est réquis'));
            $this->form_validation->set_rules('MOBILE', 'Désignation','required', array('required' => 'Désignation est réquis'));
            $this->form_validation->set_rules('FAX', 'Désignation','required', array('required' => 'Désignation est réquis'));
            $this->form_validation->set_rules('EMAIL', 'Désignation','required', array('required' => 'Désignation est réquis'));
            $this->form_validation->set_rules('HORAIREOUVERTURE', 'Désignation','required', array('required' => 'Désignation est réquis'));
            $this->form_validation->set_rules('RCCM', 'Désignation','required', array('required' => 'Désignation est réquis'));
            /*$this->form_validation->set_rules('COMPTEOM', 'Désignation','required', array('required' => 'Désignation est réquis'));
            $this->form_validation->set_rules('COMPTEMOMO', 'Désignation','required', array('required' => 'Désignation est réquis'));
            $this->form_validation->set_rules('COMPTEVISA', 'Désignation','required', array('required' => 'Désignation est réquis'));*/


            if ($this->form_validation->run() == FALSE)
            {
                $data['info'] = "L'enrégistrement du paramètre a échoué.";
                $data['alert'] = "Attention !";
                $data['class'] = "alert alert-warning animated shake";
                $data['faclass'] = "fa fa-warning m-r-sm";

                $this->layout->view('parametre/parametre',$data);
            }
            else
            {
                if($this->input->post('ID_SOCIETE') > 0){ //pour une Mise à jour d'un commune

                    $data = array(
                        'RAISONSOCIAL' => $this->input->post('RAISONSOCIAL'),
                        'ADRESSE' => $this->input->post('ADRESSE'),
                        'TELEPHONE' => $this->input->post('TELEPHONE'),
                        'MOBILE' => $this->input->post('MOBILE'),
                        'FAX' => $this->input->post('FAX'),
                        'EMAIL' => $this->input->post('EMAIL'),
                        'HORAIREOUVERTURE' => $this->input->post('HORAIREOUVERTURE'),
                        'LOGO' => $this->input->post('LOGO'),
                        'RCCM' => $this->input->post('RCCM'),
                        'COMPTEOM' => $this->input->post('COMPTEOM'),
                        'COMPTEMOMO' => $this->input->post('COMPTEMOMO'),
                        'COMPTEFLOOZ' => $this->input->post('COMPTEFLOOZ'),
                        'COMPTEVISA' => $this->input->post('COMPTEVISA')
                    );

                    $id = array(
                        'ID_SOCIETE' => $this->input->post('ID_SOCIETE')
                    );

                    $response = $this->parametreManager->update($id, $data);

                    if ($response)
                    {
                        $this->session->set_flashdata('success_flash', '<p class="alert alert-success animated fadeIn"><span class="fa fa-check mr-sm"></span>Paramètre mis à jour avec succès.</p>');
                        redirect(base_url('parametre'));
                    }
                    else
                    {
                        $data['info'] = "La mise à jour du parametre a échoué.";
                        $data['alert'] = "Attention !";
                        $data['class'] = "alert alert-warning animated shake";
                        $data['faclass'] = "fa fa-close m-r-sm";

                        $this->layout->view('parametre/parametre',$data);
                    }

                }
            }
        }
        else
        {
            redirect(base_url('parametre'));
        }
    }
}
