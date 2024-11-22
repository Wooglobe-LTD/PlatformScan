<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Social_Model extends APP_Model {

	public function __construct() {
        parent::__construct();

        
    }
	
	public function getAllSocials($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'ss.id DESC',$colums = '')
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
			FROM social_sources ss
		    WHERE ss.deleted = 0
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
	
	public function getSocialByTitle($title)
	{
		
		$query = '
			SELECT *
		    FROM social_sources ss
			WHERE ss.sources = "'.$title.'"
		';
		
		$result = $this->db->query($query);
		
		return $result;
		
	}
	
	public function getSocialById($id,$fields='*')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}

		$query = '
			SELECT '.$fields.'
		    FROM social_sources ss
		    WHERE ss.id = '.$id.'
		';
		
		$result = $this->db->query($query);
		
		if($result->num_rows() > 0){

			return $result->row();

		}else{

			return false;

		}

		
	}


}
