<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Channel_Model extends APP_Model {

	public function __construct() {
        parent::__construct();

        
    }
	
	public function getAllChannels($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'c.id DESC',$colums = '')
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
		    FROM channels c
		    LEFT JOIN users u
		    ON c.user_id = u.id
		    AND u.deleted = 0
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

    public function getUsers($fields='*')
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
	
	public function getChannelByName($name)
	{
		
		$query = '
			SELECT *
		    FROM channels c
		    WHERE c.name = "'.$name.'"
		';
		
		$result = $this->db->query($query);
		
		return $result;
		
	}
	
	public function getChannelById($id,$fields='*')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}

		$query = '
			SELECT '.$fields.'
		    FROM channels c
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
