<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MobileAPPKeyword extends APP_Model {

    public function __construct() {
        parent::__construct();


    }

        public function getAllKeywords($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'k.id DESC',$colums = '')
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
                if($field['name'] != 'action' && $field['name'] != 'k.action') {
                    $condition .= ' ' . $field['name'] . ' LIKE "%' . $search . '%" OR';
                }
            }

            $condition = rtrim($condition,'OR').')';
        }


        $query = '
			SELECT '.$fields.'
			FROM mobile_application_keywords k
			
			
		
		    WHERE k.deleted = 0
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

    public function getKeywordByTitle($title,$parent_id)
    {

        $query = '
			SELECT *
		    FROM mobile_application_keywords k
			WHERE k.keyword = "'.$title.'"
			
		';

        $result = $this->db->query($query);

        return $result;

    }

    public function getKeywordById($id,$fields='*')
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }

        $query = '
			SELECT '.$fields.'
		    FROM mobile_application_keywords k
		    WHERE k.id = '.$id.'
		    and deleted=0;
		';

        $result = $this->db->query($query);

        if($result->num_rows() > 0){

            return $result->row();

        }else{

            return false;

        }


    }

}
