<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
class Audit_model extends MY_Model
{
	protected $table = 'audit';
	
	public function trace($pseudo,$page, $action){
		
		$options_echappees = array(
        			'SESSION_ID' =>session_id(),
        			'IP_ADRESSE' =>$_SERVER['REMOTE_ADDR'],
        			'UT_LOGIN' =>$pseudo,
        			'MOD_CODE' =>$page,
        			'AUD_ACTION' =>$action,
        			'AUD_NAVIGATEUR' =>$this->input->user_agent()  
        			
        			);
        	$options_non_echappees = array(
        		'AUD_DATEHEURE' =>'NOW()'
        	);
        	
        	$this->create($options_echappees,$options_non_echappees);
	}
}