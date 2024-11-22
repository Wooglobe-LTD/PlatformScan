<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group_Model extends APP_Model {

	public function __construct() {
        parent::__construct();

        
    }
	
	public function getAllgroups($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'ar.id DESC',$colums = '')
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
		    FROM admin_groups ar
		    WHERE ar.deleted = 0
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
	
	public function getGroupByTitle($title)
	{
		
		$query = '
			SELECT ar.*
		    FROM admin_groups ar
		    WHERE ar.title = "'.$title.'"
		    AND ar.deleted = 0
		    AND ar.id > 1
		';
		
		$result = $this->db->query($query);
		
		return $result;
		
	}
	
	public function getGroupById($id,$fields='*')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}

		$query = '
			SELECT '.$fields.'
		    FROM admin_groups ar
		    WHERE ar.id = '.$id.'
		    
		';
		
		$result = $this->db->query($query);
		
		if($result->num_rows() > 0){

			return $result->row();

		}else{

			return false;

		}

		
	}


	public function getGroupPermissions($group_id){

        $permision = array();

        $menus = $this->db->query('
                SELECT arp.*
                FROM admin_group_permissions arp
                WHERE arp.admin_group_id = '.$group_id.'
                AND arp.menu_action_id = 0
                GROUP BY arp.menu_id
        ');

        foreach ($menus->result() as $menu){
            $permision[$menu->menu_id] = array();
            $actions = $this->db->query('
                    SELECT arp.*,ma.action_name
                    FROM admin_group_permissions arp
                    LEFT JOIN menu_actions ma
                    ON arp.menu_id = ma.id
                    WHERE arp.admin_group_id = '.$group_id.'
                    AND arp.menu_id = '.$menu->menu_id.'
            ');
            foreach($actions->result() as $action){
                $permision[$menu->menu_id][$action->menu_action_id] = $action->action_name;
            }
        }

        return $permision;

    }

    public function getActiveGroups($fields='*'){

        if(is_array($fields)){

            $fields = implode(',',$fields);

        }

        $query = '
			SELECT '.$fields.'
		    FROM admin_groups ar
		    WHERE ar.status = 1
		    AND ar.deleted = 0
		    
		';

        $result = $this->db->query($query);

        return $result;

    }


}
