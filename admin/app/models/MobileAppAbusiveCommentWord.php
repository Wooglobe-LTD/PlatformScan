<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MobileAppAbusiveCommentWord extends APP_Model {

    public function __construct() {
        parent::__construct();


    }

        public function getAllAbusiveCommentWords($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'acw.id DESC',$colums = '')
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
                if($field['name'] != 'action' && $field['name'] != 'acw.action') {
                    $condition .= ' ' . $field['name'] . ' LIKE "%' . $search . '%" OR';
                }
            }

            $condition = rtrim($condition,'OR').')';
        }


        $query = '
			SELECT '.$fields.'
			FROM ma_abusive_comment_words acw
			
			
		
		    WHERE acw.deleted = 0
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

    public function getAbusiveWord($word)
    {

        $query = '
			SELECT *
		    FROM ma_abusive_comment_words acw
			WHERE acw.word = "'.$word.'"
			
		';

        $result = $this->db->query($query);

        return $result;

    }

    public function getWordById($id,$fields='*')
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }

        $query = '
			SELECT '.$fields.'
		    FROM ma_abusive_comment_words acw
		    WHERE acw.id = '.$id.'
		';

        $result = $this->db->query($query);

        if($result->num_rows() > 0){

            return $result->row();

        }else{

            return false;

        }


    }

}
