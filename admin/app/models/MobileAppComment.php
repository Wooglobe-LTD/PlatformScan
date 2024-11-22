<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MobileAppComment extends APP_Model {

    public function __construct() {
        parent::__construct();


    }

    public function getAllComments($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'vc.id DESC',$colums = '')
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
			SELECT *,u.full_name,vc.id FROM videos_comments vc inner JOIN videos v ON vc.video_id = v.id INNER JOIN users u ON vc.user_id = u.id Where vc.deleted = 0
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

    public function getCommentById($id,$fields='*')
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }

        $query = '
			SELECT '.$fields.'
		    FROM videos_comments vc
		    WHERE vc.id = '.$id.'
		';

        $result = $this->db->query($query);

        if($result->num_rows() > 0){

            return $result->row();

        }else{

            return false;

        }


    }

    public function getCategoryById($id,$fields='*')
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }

        $query = '
			SELECT '.$fields.'
		    FROM mobile_app_categories c
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
		    FROM mobile_app_categories c
			WHERE c.parent_id = 0
			AND c.status = 1
			AND c.deleted = 0
			ORDER BY c.title ASC
		';

        $result = $this->db->query($query);

        return $result;

    }
}
