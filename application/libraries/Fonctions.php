<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Fonctions{
	private $CI;
	
	public function __construct(){
		$this->CI = get_instance();
		
		//$this->CI->load->model('privilege_model', 'privilegeManager');
				
	}
	
	public function control($module){

		//var_dump($module);

		if(!$this->CI->session->has_userdata('pseudo'))			
			redirect(base_url('Login'));
			
		if(!$this->module_access($this->CI->session->userdata('pseudo'),$module))	
			redirect(base_url('Home'));
	}

	/*public function module_access_action($pseudo, $module, $action){
		$sql ="SELECT privilege.* FROM utilisateur INNER JOIN typeutilisateur ON (utilisateur.TPU_CODE = typeutilisateur.TPU_CODE )INNER JOIN privilege ON (privilege.TPU_CODE = typeutilisateur.TPU_CODE)WHERE UT_LOGIN='$pseudo' AND privilege.MOD_CODE=$module AND $action";
		
		$nb=count($this->CI->privilegeManager->query($sql)) ;
		
		
		if($nb==0){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
	public function list_module_access($pseudo){
		$sql ="SELECT DISTINCT privilege.MOD_CODE  FROM utilisateur INNER JOIN typeutilisateur ON (utilisateur.TPU_CODE = typeutilisateur.TPU_CODE )INNER JOIN privilege ON (privilege.TPU_CODE = typeutilisateur.TPU_CODE) INNER JOIN module ON ( privilege.MOD_CODE = module.MOD_CODE) WHERE UT_LOGIN='$pseudo' ORDER BY  module.MOD_ORDRE ";
		
		return $this->CI->privilegeManager->query($sql) ;
	}
	
	public function module_access($pseudo, $module){
		$sql ="SELECT DISTINCT privilege.MOD_CODE  FROM utilisateur INNER JOIN typeutilisateur ON (utilisateur.TPU_CODE = typeutilisateur.TPU_CODE )INNER JOIN privilege ON (privilege.TPU_CODE = typeutilisateur.TPU_CODE) INNER JOIN module ON ( privilege.MOD_CODE = module.MOD_CODE) WHERE UT_LOGIN='$pseudo' AND privilege.MOD_CODE='$module' ORDER BY  module.MOD_ORDRE ";
		
		return ( count($this->CI->privilegeManager->query($sql)) == 0 ? FALSE : TRUE) ;
	}*/
	
	public function GetDateFormat($date)
    {
		if (preg_match("/-/i", $date)) {
			// format anglais
			return $date;
		}else{
			// format français
			list($day,$month,$year) = explode("/", $date);
			return "$year-$month-$day";
			
		}	
    }
	
	public function GetDateFrench($date)
    {
		if (preg_match("/-/i", $date)) {
			// format anglais
			list($year ,$month,$day) = explode("-", $date);
			return "$day/$month/$year";
		}else{
			return $date;
			// format français
			
			
		}	
    }
	
	public function validateDate($date, $format = 'd/m/Y'){
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}
}
/* End of file Fonctions.php */
/* Location: ./application/libraries/Fonctions.php */