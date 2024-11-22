<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Earning_Type_Model extends APP_Model {

	public function __construct() {
        parent::__construct();

        
    }
	
	public function getAllEarningTypes($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'et.id DESC',$colums = '')
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
			FROM earning_types et
		    WHERE et.deleted = 0
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


    public function getAllEarningTypesActive($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'et.id DESC',$colums = '')
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
			FROM earning_types et
		    WHERE et.deleted = 0
		    AND et.status = 1
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
	public function getEarningByTitle($title)
	{
		
		$query = '
			SELECT *
		    FROM earning_types et
			WHERE et.earning_type = "'.$title.'"
		';
		
		$result = $this->db->query($query);
		
		return $result;
		
	}
	
	public function getEarningTypeById($id,$fields='*')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}

		$query = '
			SELECT '.$fields.'
		    FROM earning_types et
		    WHERE et.id = '.$id.'
		';
		
		$result = $this->db->query($query);
		
		if($result->num_rows() > 0){

			return $result->row();

		}else{

			return false;

		}

		
	}


}
