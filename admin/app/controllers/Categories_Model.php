<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories_Model extends APP_Model {

	public function __construct() {
        parent::__construct();


    }

	public function getAllCategories($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'c.id DESC',$colums = '')
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
                if($field['name'] != 'action' && $field['name'] != 'c.action'){
					$condition .= ' '.$field['name'].' LIKE "%'.$search.'%" OR';
				}

			}

			$condition = rtrim($condition,'OR').')';
		}


		$query = '
			SELECT '.$fields.'
			FROM mrss_feeds c
			LEFT JOIN mrss_feeds cc
			ON c.parent_id = cc.id
			AND cc.deleted = 0
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

	public function getCategoryByTitle($title, $partner_id = '')
	{

		$query = '
			SELECT *
		    FROM mrss_feeds c
			WHERE c.title = "'.$title.'"';
		if(!empty($partner_id) and is_numeric($partner_id)){
		    $query .= ' AND partner_id='.$partner_id;
        }

		$result = $this->db->query($query);

		return $result;

	}
	public function getCategoryByURL($url, $partner_id='')
	{
        if(!empty($partner_id) and is_numeric($partner_id)){
            $this->db->select('full_name');
            $this->db->where('id',$partner_id);
            $query=$this->db->get('users');
            $result=$query->result();
            $url = slug($result[0]->full_name,'mrss_feeds','url')."/".slug($url,'mrss_feeds','url');;

        }
		$query = '
			SELECT *
		    FROM mrss_feeds c
			WHERE c.url = "'.$url.'"
		';
       // /echo $query;exit;

		$result = $this->db->query($query);

		return $result;

	}

	public function getCategoryById($id,$fields='*')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}

		$query = '
			SELECT '.$fields.'
			FROM mrss_feeds c
		    WHERE cc.id = '.$id;

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
		    FROM mrss_feeds c
			WHERE c.parent_id = 0
			AND c.status = 1
			AND c.deleted = 0
			ORDER BY c.title ASC
		';

		$result = $this->db->query($query);

		return $result;

	}

	public function getMrss(){
        $query = '
			SELECT *
			FROM mrss_feeds c
		    WHERE c.deleted = 0
		  
		    AND c.status = 1
		';
        return $this->db->query($query);
    }

    public function getMrssPartners(){
        $query = '
			SELECT c.id, title, partner_id, slug, url, u.full_name
			FROM mrss_feeds c, users u
		    WHERE c.deleted = 0
		    AND c.type = 0
		    AND c.status = 1
		    AND u.role_id=2
		    AND c.partner_id = u.id
		';
        return $this->db->query($query);
    }

    public function getPartnerName($partner){
	    $query = 'SELECT full_name from users where id='.$partner;
        $result = $this->db->query($query);
        return $result->row();
    }
    public function getPartnerData($partner){
        $query = 'SELECT id,url from mrss_feeds where partner_id='.$partner;
        $result = $this->db->query($query);
        if($result->num_rows() > 0){
            return $result->result_array();
        }
        else{
            return false;
        }
    }
    public function getFeedDataByVideoId($id){
        $query = 'SELECT feed_id,exclusive_to_partner from feed_video where video_id='.$id;
        $result = $this->db->query($query);
        if($result->num_rows() > 0){
            return $result->result_array();
        }
        else{
            return false;
        }
    }
    public function nonExclusivePartnerdataByVideoId($id){
	    $query ='SELECT * FROM `feed_video` fv, mrss_feeds mf WHERE mf.id=fv.feed_id and fv.video_id='.$id.' and exclusive_to_partner=0 and mf.partner_id!=0';
        $result = $this->db->query($query);
        if($result->num_rows() > 0){
            return $result->result_array();
        }
        else{
            return false;
        }
    }
    public function getFeedVideos($feed_id, $partner_id=0){
        $query = 'SELECT v.id,v.title, v.youtube_id,v.description,v.tags,ev.portal_url,ev.portal_thumb,v.created_at, fv.feed_id
            FROM videos v
            INNER JOIN feed_video fv
            ON v.id = fv.video_id
            INNER JOIN edited_video ev
            ON ev.video_id = v.id
            WHERE v.status = 1
             AND v.deleted = 0
             AND v.is_wooglobe_video = 1
             AND v.mrss = 1
             AND fv.feed_id='.$feed_id.'
             ORDER BY v.created_at DESC';//echo $query;exit ;

        return $this->db->query($query);
    }
    public function getExclusiveVideos($partner_id){// get videos which are exclusive to partner other than the given partner
        $query = 'SELECT GROUP_CONCAT(video_id) as vid FROM feed_video WHERE exclusive_to_partner NOT IN (0, '.$partner_id.')';
        return $this->db->query($query)->row();
    }
     public function getFeedDataByUrl($url){
	    $query = 'SELECT id, title, status, partner_id FROM mrss_feeds WHERE url = "' . $url . '"';
	    return $this->db->query($query)->row();
     }
     public function deleteFeedVideo($video_id, $feed_id){
	    $query = 'DELETE FROM feed_video where video_id='.$video_id.' AND feed_id='.$feed_id;
         return $this->db->query($query);
     }
     public function getVideosForFeed($feed_ids, $exclusive){
	    $ids = '';
	    foreach($feed_ids->result() as $id){
	        if($ids != ''){$ids.=',';}
	        $ids .= $id->id;
        }
        $query = 'SELECT v.id,v.title, vl.unique_key 
                  FROM videos v 
                  Inner join video_leads vl 
                  on vl.id=v.lead_id 
                  INNER JOIN edited_video ev 
                  ON ev.video_id = v.id 
                  WHERE v.status = 1 
                  AND v.deleted = 0 
                  AND v.is_wooglobe_video = 1 
                  AND v.mrss = 1';
	    if(!empty($ids)){
	        $query .= ' AND v.id NOT IN ('.$ids.')';
         }
         if(!empty($exclusive->vid)){
             $query .= ' AND v.id NOT IN ('.$exclusive->vid.')';
         }
         //echo $query;exit;

         return $this->db->query($query);
     }

    public function addFeedVideo($video_id, $feed_id){
	    $data = array();
	    foreach($video_id as $id){
	        $data[] = array('video_id'=>$id, 'feed_id'=>$feed_id);
        }
        return $this->db->insert_batch('feed_video', $data);

    }

    public function updateFeedStatus($feed_id, $status){
        $this->db->where('id', $feed_id);
        return $this->db->update('mrss_feeds', array('status' => $status));
    }



}
