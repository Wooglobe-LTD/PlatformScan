<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff_Model extends APP_Model {

	public function __construct() {
        parent::__construct();

        
    }
	
	public function getAllMembers($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'a.id DESC',$colums = '')
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
		    FROM admin a
		    INNER JOIN admin_roles ar
		    ON a.admin_role_id = ar.id
		    AND ar.deleted = 0
		    WHERE a.deleted = 0
		    AND a.id > 1
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
			SELECT a.*
		    FROM admin a
		    WHERE a.username = "'.$username.'"
		    AND a.deleted = 0
		
		';
		
		$result = $this->db->query($query);
		
		return $result;
		
	}

    public function getMemberByEmail($email)
    {

        $query = '
			SELECT a.*
		    FROM admin a
		    WHERE a.email = "'.$email.'"
		    AND a.deleted = 0
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
		    FROM admin a
		    INNER JOIN admin_roles ar
		    ON a.admin_role_id = ar.id
		    AND ar.deleted = 0
		    WHERE a.deleted = 0
		    AND a.id > 1
		    AND a.id = '.$id.'
		    
		';
		
		$result = $this->db->query($query);
		
		if($result->num_rows() > 0){

			return $result->row();

		}else{

			return false;

		}

		
	}


}
