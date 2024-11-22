<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Content_Model extends APP_Model {

	public function __construct() {
        parent::__construct();

        
    }
	
	public function getAllPages($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'c.id DESC',$colums = '')
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
			FROM content c
		    WHERE c.deleted = 0
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
	
	public function getPageByTitle($title)
	{
		
		$query = '
			SELECT *
		    FROM content c
			WHERE c.title = "'.$title.'"
		';
		
		$result = $this->db->query($query);
		
		return $result;
		
	}
	
	public function getPageById($id,$fields='*')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}

		$query = '
			SELECT '.$fields.'
		    FROM content c
		    WHERE c.id = '.$id.'
		';
		
		$result = $this->db->query($query);
		
		if($result->num_rows() > 0){

			return $result->row();

		}else{

			return false;

		}

		
	}

	public function getParentCategories($fields='*')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}

		$query = '
			SELECT '.$fields.'
		    FROM categories c
			WHERE c.parent_id = 0
			AND c.status = 1
			AND c.deleted = 0
			ORDER BY c.title ASC
		';
		
		$result = $this->db->query($query);
		
		return $result;

	}

	public function getSubCategories($fields='*',$id)
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}

		$query = '
			SELECT '.$fields.'
		    FROM categories c
			WHERE c.parent_id = '.$id.'
			AND c.status = 1
			AND c.deleted = 0
			ORDER BY c.title ASC
		';
		
		$result = $this->db->query($query);
		
		return $result;

	}

	public function getAllUsers($fields='*')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}

		$query = '
			SELECT '.$fields.'
		    FROM users u
			WHERE u.status = 1
			AND u.deleted = 0
			ORDER BY u.full_name ASC
		';
		
		$result = $this->db->query($query);
		
		return $result;

	}

	public function getAllVideoTypes($fields='*')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}

		$query = '
			SELECT '.$fields.'
		    FROM videos_types vt
			WHERE vt.status = 1
			AND vt.deleted = 0
			ORDER BY vt.id ASC
		';
		
		$result = $this->db->query($query);
		
		return $result;

	}


	public function getVideoDetailById($id,$fields='*')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}

		$query = '
			SELECT '.$fields.'
			FROM videos v
			JOIN categories c
			ON c.id = v.category_id
		    WHERE v.id = '.$id.'
		';
		
		$result = $this->db->query($query);
		
		if($result->num_rows() > 0){

			return $result->row();

		}else{

			return false;

		}

		
	}
}
