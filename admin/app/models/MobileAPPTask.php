<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MobileAPPTask extends APP_Model {

    public function __construct() {
        parent::__construct();


    }

        public function getAllTasks($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 't.id DESC',$colums = '')
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }
        $whereFields = '';
        $condition = '';
        if(!empty($search) && is_array($colums)){

            $count = count($colums);

            unset($colums[$count-1]);

            $condition = ' WHERE (';

            $whereFields = $colums;

            foreach($whereFields as $field){
                if($field['name'] != 'action' && $field['name'] != 't.action') {
                    $condition .= ' ' . $field['name'] . ' LIKE "%' . $search . '%" OR';
                }
            }

            $condition = rtrim($condition,'OR').')';
        }


        $query = '
			SELECT '.$fields.'
			FROM ma_tasks t
			
			
		
		    
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

    public function getTaskByTitle($task,$parent_id)
    {

        $query = '
			SELECT *
		    FROM ma_tasks t
			WHERE t.tasks = "'.$task.'"
			
		';

        $result = $this->db->query($query);

        return $result;

    }

    public function getTaskById($id,$fields='*')
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }

        $query = '
			SELECT '.$fields.'
		    FROM ma_tasks t
		    WHERE t.id = '.$id.'
		    
		';

        $result = $this->db->query($query);

        if($result->num_rows() > 0){

            return $result->row();

        }else{

            return false;

        }


    }

}
