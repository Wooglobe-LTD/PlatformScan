<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Third_Party_Staff_Model extends APP_Model {

	public function __construct() {
        parent::__construct();

        
    }
	
	public function getAllMembers($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'tp.id DESC',$colums = '')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}
		$whereFields = '';
		$condition = '';
		if(!empty($search) && is_array($colums)){
			
			$count = count($colums);

			unset($colums[$count-1]);

			$condition = ' AND (';
			
			$whereFields = $colums;
	
			foreach($whereFields as $field){
	
				$condition .= ' '.$field['name'].' LIKE "%'.$search.'%" OR';
			}

			$condition = rtrim($condition,'OR').')';
		}
		

		$query = '
			SELECT '.$fields.'
		    FROM third_party_staff tp
		    WHERE tp.deleted = 0
		';
		if(!empty($condition)){

			$query .= $condition;

		}
		if(!empty($orderby)){

			$query .= ' ORDER BY '.$orderby;

		}

		if($limit > 0){

			$query .= ' LIMIT '.$start.','.$limit;

		}
		
		$result = $this->db->query($query);

		return $result;
		
	}
	
	public function getMemberByUsername($username)
	{
		
		$query = '
			SELECT tp.*
		    FROM third_party_staff tp
		    WHERE tp.username = "'.$username.'"
		    AND tp.deleted = 0
		
		';
		
		$result = $this->db->query($query);
		
		return $result;
		
	}

    public function getMemberByEmail($email)
    {

        $query = '
			SELECT tp.*
		    FROM third_party_staff tp
		    WHERE tp.email = "'.$email.'"
		    AND tp.deleted = 0
		';

        $result = $this->db->query($query);

        return $result;

    }
	
	public function getMemberById($id,$fields='*')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}

		$query = '
			SELECT '.$fields.'
		    FROM third_party_staff tp
		    WHERE id = '.$id.' AND deleted = 0';
		
		$result = $this->db->query($query);
		
		if($result->num_rows() > 0){

			return $result->row();

		}else{

			return false;

		}

		
	}




}
