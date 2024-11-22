<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compilation_Model extends APP_Model {

	public function __construct() {
        parent::__construct();


    }

	public function getAllCompilations($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'c.id DESC',$colums = '')
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
                if($field['name'] != 'action' && $field['name'] != 'c.action') {
                    $condition .= ' ' . $field['name'] . ' LIKE "%' . $search . '%" OR';
                }
			}

			$condition = rtrim($condition,'OR').')';
		}


		$query = '
			SELECT '.$fields.'
		    FROM compilations c
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


    public function getAllUsersActive($type = 0,$fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'u.id DESC',$colums = '')
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
		    FROM users u
		    WHERE u.deleted = 0
		    AND u.status = 1
		';
        if($type > 0){
            $query .=  ' AND u.role_id = '.$type;
        }
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


	public function getCompilationById($id,$fields='*')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}

		$query = '
			SELECT '.$fields.'
		    FROM compilations c
		    WHERE c.id = '.$id.'
		';

		$result = $this->db->query($query);

		if($result->num_rows() > 0){

			return $result->row();

		}else{

			return false;

		}


	}

}
