<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	if ( ! function_exists('site_url'))
	{
		function site_url($uri = '')
		{
			if( ! is_array($uri))
			{
				// Tous les paramètres sont insérés dans un tableau
				$uri = func_get_args();
			}
			// On ne modifie rien ici
			$CI =& get_instance();
			return $CI->config->site_url($uri);
		}
	}
	
	if ( ! function_exists('url'))
	{
		function url($text, $uri = '')
		{
			if( ! is_array($uri))
			{
				// Suppression de la variable $text
				$uri = func_get_args();
				array_shift($uri);
			}
			echo '<a href="' . site_url($uri) . '">' . htmlentities($text) .
			'</a>';
			return '';
		}
	}
	
	if ( ! function_exists('href_url'))
	{
		function href_url($uri = '')
		{
			return site_url($uri);
		}
	}
        
      if ( ! function_exists('getRelativeTime')){
          
        function getRelativeTime($date){
            $date_a_comparer = new DateTime($date);
            $date_actuelle = new DateTime("now");

            $intervalle = $date_a_comparer->diff($date_actuelle);

            if ($date_a_comparer > $date_actuelle){
                    $prefixe = 'dans ';
            }
            else{
                    $prefixe = 'il y a ';
            }

            $ans = $intervalle->format('%y');
            $mois = $intervalle->format('%m');
            $jours = $intervalle->format('%d');
            $heures = $intervalle->format('%h');
            $minutes = $intervalle->format('%i');
            $secondes = $intervalle->format('%s');

            if ($ans != 0)	{
                    $relative_date = $prefixe . $ans . ' an' . (($ans > 1) ? 's' : '');
                    if ($mois >= 6) $relative_date .= ' et demi';
            }
            elseif ($mois != 0){
                    $relative_date = $prefixe . $mois . ' mois';
                    if ($jours >= 15) $relative_date .= ' et demi';
            }
            elseif ($jours != 0){
                    $relative_date = $prefixe . $jours . ' jour' . (($jours > 1) ? 's' : '');
            }
            elseif ($heures != 0){
                    $relative_date = $prefixe . $heures . ' heure' . (($heures > 1) ? 's' : '');
            }
            elseif ($minutes != 0){
                    $relative_date = $prefixe . $minutes . ' minute' . (($minutes > 1) ? 's' : '');
            }
            else{
                    $relative_date = $prefixe . ' quelques secondes';
            }

            return $relative_date;
        }
    } 
	
?>