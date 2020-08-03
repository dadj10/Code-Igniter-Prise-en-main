<?php

	if (!defined('BASEPATH'))	exit('No direct script access allowed');

	class MY_Model extends CI_Model
	{
		protected $table = '';

		/*
		 * Insère une nouvelle ligne dans la base de données.
		 *
		 */
		public function create($options_echappees = array(), $options_non_echappees = array())
		{
			/* Vérification des données à insérer */
			if(empty($options_echappees) AND empty($options_non_echappees))
			{
				return FALSE;
			}

			$reponse = (bool) $this->db->set($options_echappees)
									   ->set($options_non_echappees, NULL, FALSE)
									   ->insert($this->table);

			return $reponse;
		}


		/*
		 * Récupère des données dans la base de données.
		 *
		 */
		public function read($select = '*', $where = array(), $orderby='', $direction = '', $escape = NULL, $nb = NULL, $debut = NULL)
		{
			return $this->db->select($select)
							->from($this->table)
							->where($where)
							->order_by($orderby, $direction, $escape)
							->limit($nb, $debut)
							->get()
							->result();
		}

		public function row($select = '*', $where = array())
		{
			return $this->db->select($select)
							->from($this->table)
							->where($where)
							->get()
							->first_row();;
		}


		/*
		 * sélection de la ligne ayant la valeur la plus grande en fonction de la colonne
		 *
		 */
		public function max($select = '*', $where = array())
		{
			return $this->db->select_max($select)
							->from($this->table)
							->where($where)
							->get()
							->first_row();;
		}


		/*
		 * Modifie une ou plusieurs lignes dans la base de données.
		 *
		 */
		public function update($where, $options_echappees = array(), $options_non_echappees = array())
		{
			/* Vérification des données à mettre à jour */
			if(empty($options_echappees) AND empty($options_non_echappees))
			{
				return FALSE;
			}
			
			/* Raccourci dans le cas où on sélectionne l'id */
			if(is_integer($where))
			{
				$where = array('ID' => $where);
			}

			return (bool) $this->db->set($options_echappees)
								   ->set($options_non_echappees, NULL, FALSE)
								   ->where($where)
								   ->update($this->table);
		}


		/*
		 * Supprime une ou plusieurs lignes de la base de données.
		 *
		 */
		public function delete($where)
		{
			if(is_integer($where))
			{
				$where = array('ID' => $where);
			}
			
			return (bool) $this->db->where($where)
								   ->delete($this->table);
		}


		/*
		 * Retourne le nombre de résultats.
		 *
		 */
		public function count($champ = array(), $valeur = NULL)
		{
			/* Si $champ est un array, la variable $valeur sera ignorée par la méthode 	where() */
			return (int) $this->db->where($champ, $valeur)
								  ->from($this->table)
								  ->count_all_results();
		}

		
		public function query($query)
		{
			return $this->db->query($query)
							->result();
		}

		
		public function majquery($query)
		{
			return $this->db->query($query)
							->affected_rows();
		}	
	}
/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */