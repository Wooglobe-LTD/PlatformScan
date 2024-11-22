<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_Action_Model extends APP_Model {

	public function __construct() {
        parent::__construct();

        
    }
	
	public function getAllActions($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'ma.id DESC',$colums = '')
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
		    FROM menu_actions ma
		    INNER JOIN menus m
		    ON ma.menu_id = m.id
		    AND m.deleted = 0
		    WHERE ma.deleted = 0
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
	
	public function getActionByNameAndMenu($name,$menu_id)
	{
		
		$query = '
			SELECT ma.*
		    FROM menu_actions ma
		    WHERE ma.action_name = "'.$name.'"
		    AND ma.menu_id = '.$menu_id.'
		    AND ma.deleted = 0
		';
		
		$result = $this->db->query($query);
		
		return $result;
		
	}

    public function getActionByUriAndMenu($uri,$menu_id)
    {

        $query = '
			SELECT ma.*
		    FROM menu_actions ma
		    WHERE ma.action_uri = "'.$uri.'"
		    AND ma.menu_id = '.$menu_id.'
		    AND ma.deleted = 0
		';

        $result = $this->db->query($query);

        return $result;

    }
	
	public function getActionById($id,$fields='*')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}

		$query = '
			SELECT '.$fields.'
		    FROM menu_actions ma
		    INNER JOIN menus m
		    ON ma.menu_id = m.id
		    AND m.deleted = 0
		    WHERE ma.id = '.$id.'
		    
		';
		
		$result = $this->db->query($query);
		
		if($result->num_rows() > 0){

			return $result->row();

		}else{

			return false;

		}

		
	}
}
