<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Layout{
	private $CI;
	private $var = array();
	
	private $theme = 'default';
	/*
	|===============================================================================
	| Constructeur
	|===============================================================================
	*/
	public function __construct(){
		$this->CI = get_instance();
		$this->var['output'] = '';
		
		// Le titre est compos� du nom de la m�thode et du nom du contr�leur
		// La fonction ucfirst permet d'ajouter une majuscule
		$this->var['titre'] = ucfirst($this->CI->router->fetch_method()) . '-' . ucfirst($this->CI->router->fetch_class());
		
		$this->var['menu']='';
		$this->var['page_js']=array();
		
		// Nous initialisons la variable $charset avec la m�me valeur que
		// la cl� de configuration initialis�e dans le fichier config.php
		$this->var['charset'] = $this->CI->config->item('charset');
		
		$this->var['description'] = $this->var['titre'];
		$this->var['keywords'] = "babidev,informatique,forum,articles,emploi";
		
		//Variables pour ajouter les css et javascript
		$this->var['css'] = array();
		$this->var['js'] = array();
	}
	/*
	|===============================================================================
	| M�thodes pour charger les vues
	| . view
	| . views
	|===============================================================================
	*/
	public function view($name, $data = array()){
		$this->var['output'] .= $this->CI->load->view($name, $data, true);
		
		$this->CI->load->view('../themes/' . $this->theme, $this->var);
	}
	
	public function views($name, $data = array()){
		$this->var['output'] .= $this->CI->load->view($name, $data, true);
		return $this;
	}
	
	/*
	|===============================================================================
	| M�thodes pour modifier les variables envoy�es au layout
	| . set_titre
	| . set_charset
	|===============================================================================
	*/
	public function set_titre($titre){
		if(is_string($titre) AND !empty($titre)){
			$this->var['titre'] = $titre;
			return true;
		}
		return false;
	}
	
	public function set_charset($charset){
		if(is_string($charset) AND !empty($charset)){
			$this->var['charset'] = $charset;
			return true;
		}
		return false;
	}
	public function set_description($description)
		{
			if(is_string($description) AND !empty($description))
			{
				$this->var['description'] = $description;
				return true;
			}
			return false;
		}
		public function set_keywords($keywords)
		{
			if(is_string($keywords) AND !empty($keywords))
			{
				$this->var['keywords'] = $keywords;
				return true;
			}
			return false;
		}	
		
		public function ajouter_css($nom)
		{
			if(is_string($nom) AND !empty($nom) AND file_exists('./assets/css/' . $nom .
			'.css'))
			{
				$this->var['css'][] = css_url($nom);
				return true;
			}
			return false;
		}

		public function ajouter_js($nom)
		{
			if(is_string($nom) AND !empty($nom) AND file_exists('./assets/javascript/' .
			$nom . '.js'))
			{
				$this->var['js'][] = js_url($nom);
				return true;
			}
			return false;
		}



		public function charger_js($nom)
		{
			if(is_string($nom) AND !empty($nom) AND file_exists('./js/' .
			$nom . '.js'))
			{
				$this->var['js'][] = js_url($nom);
				return true;
			}
			return false;
		}


		public function set_theme($theme)
		{
			if(is_string($theme) AND !empty($theme) AND
			file_exists('./application/themes/' . $theme . '.php'))
			{
				$this->theme = $theme;
				return true;
			}
			return false;
		}
		
		public function ajouter_css_url($url)
		{
			
			if( !empty($url))
			{
				$this->var['css'][] = $url;
				return true;
			}
			return false;
		}
		public function ajouter_js_url($url)
		{
			
			if( !empty($url) )
			{
				$this->var['js'][] = $url;
				return true;
			}
			return false;
		}
		
		public function set_menu($menu){
			if(is_string($menu) AND !empty($menu)){
				$this->var['menu'] = $menu;
				return true;
			}
			return false;
	}
	
		public function set_page_js($page_js){
			if(!empty($page_js)){
				$this->var['page_js'][] = $page_js;
				return true;
			}
			return false;
	}
}
/* End of file layout.php */
/* Location: ./application/libraries/layout.php */