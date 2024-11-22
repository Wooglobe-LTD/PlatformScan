<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video_Expense_Model extends APP_Model {

	public function __construct() {
        parent::__construct();

        
    }
	
	public function getAllExpense($video_id = 0,$fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 've.id DESC',$colums = '')
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

		if(!empty($video_id) && $video_id > 0){

            $video_id = ' AND ve.video_id = '.$video_id.'';
        }else{

            $video_id = '';

        }

		$query = '
			SELECT '.$fields.'
			FROM video_expense ve
			LEFT JOIN videos v
			ON ve.video_id = v.id
			AND v.status = 1
			AND v.deleted = 0
			WHERE ve.deleted = 0
		';
		$query .= $video_id;
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


    public function getAllExpenseRequests($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 've.id DESC',$colums = '')
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
			FROM video_expense ve
			LEFT JOIN videos v
			ON ve.video_id = v.id
			AND v.status = 1
			AND v.deleted = 0
			WHERE ve.deleted = 0
			AND ve.status = 0
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


	

	
	public function getExpenseById($id,$fields='*')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}

		$query = '
			SELECT '.$fields.'
		    FROM video_expense ve
			LEFT JOIN videos v
			ON ve.video_id = v.id
			AND v.status = 1
			AND v.deleted = 0
			WHERE ve.id = '.$id.'
		';
		
		$result = $this->db->query($query);
		
		if($result->num_rows() > 0){

			return $result->row();

		}else{

			return false;

		}

		
	}


}
