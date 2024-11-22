<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_Model extends APP_Model {

	public function __construct() {
        parent::__construct();

        
    }
	
	public function getAllMenus($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'm.sort_no ASC',$colums = '')
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
                if($field['name'] != 'action') {
                    $condition .= ' ' . $field['name'] . ' LIKE "%' . $search . '%" OR';
                }
			}

			$condition = rtrim($condition,'OR').')';
		}
		

		$query = '
			SELECT '.$fields.'
		    FROM menus m
		    WHERE m.deleted = 0
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
	
	public function getMenuByName($name)
	{
		
		$query = '
			SELECT m.*
		    FROM menus m
		    WHERE m.menu_name = "'.$name.'"
		    AND m.deleted = 0
		';
		
		$result = $this->db->query($query);
		
		return $result;
		
	}

    public function getMenuBySort($sort)
    {

        $query = '
			SELECT m.*
		    FROM menus m
		    WHERE m.sort_no = '.$sort.'
		    AND m.deleted = 0
		';

        $result = $this->db->query($query);

        return $result;

    }
	
	public function getMenuById($id,$fields='*')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}

		$query = '
			SELECT '.$fields.'
		    FROM menus m
		    WHERE m.id = '.$id.'
		    
		';
		
		$result = $this->db->query($query);
		
		if($result->num_rows() > 0){

			return $result->row();

		}else{

			return false;

		}

		
	}

	public function getActiveMenus($fields='*'){

        if(is_array($fields)){

            $fields = implode(',',$fields);

        }

        $query = '
			SELECT '.$fields.'
		    FROM menus m
		    WHERE m.status = 1
		    AND m.deleted = 0
		    ORDER BY m.menu_name ASC
		';

        $result = $this->db->query($query);

        return $result;

    }
}
