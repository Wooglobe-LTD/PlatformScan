<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MobileAPPYoutubeChannel extends APP_Model {

    public function __construct() {
        parent::__construct();


    }

    public function getAllYoutubeChannels($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'id DESC',$colums = '')
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }
        $whereFields = '';
        $condition = '';
        if(!empty($search) && is_array($colums)){

            $count = count($colums);

            unset($colums[$count-1]);

            $condition = ' where (';

            $whereFields = $colums;

            foreach($whereFields as $field){
                if($field['name'] != 'action' && $field['name'] != 'action') {
                    $condition .= ' ' . $field['name'] . ' LIKE "%' . $search . '%" OR';
                }
            }

            $condition = rtrim($condition,'OR').')';
        }


        $query = '
			SELECT '.$fields.'
			FROM ma_youtube_channels 
			
			
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

    public function getYoutubeChannelyByTitle($title,$parent_id)
    {

        $query = '
			SELECT *
		    FROM ma_mrss_feeds mr
			WHERE mr.title = "'.$title.'"
		';

        $result = $this->db->query($query);

        return $result;

    }

    public function getYoutubeChannelById($id,$fields='*')
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }

        $query = '
			SELECT '.$fields.'
		    FROM ma_youtube_channels
		    WHERE id = '.$id.'
		';

        $result = $this->db->query($query);

        if($result->num_rows() > 0){

            return $result->row();

        }else{

            return false;

        }


    }

}