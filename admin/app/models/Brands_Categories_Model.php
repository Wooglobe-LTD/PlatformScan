<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brands_Categories_Model extends APP_Model {

	public function __construct() {
        parent::__construct();


    }

	public function getAllCategories($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = '',$colums = '')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}
        // var_dump($fields);exit();
		$whereFields = '';
		$condition = '';
		if(!empty($search) && is_array($colums)){

			$count = count($colums);

			unset($colums[$count-1]);

			$condition = ' AND (';

			$whereFields = $colums;

			foreach($whereFields as $field){
			    if(!empty($field['name'])){
                    if($field['name'] != 'action' && $field['name'] != 'c.action'){
                        $condition .= ' '.$field['name'].' LIKE "%'.$search.'%" OR';
                    }
                }
			}

			$condition = rtrim($condition,'OR').')';
		}


		$query = '
			SELECT '.$fields.'
			FROM brands_mrss_feeds bmf
            LEFT JOIN mrss_brands mb ON mb.id = bmf.brand_id
            LEFT JOIN users u ON u.id = bmf.partner_id
            WHERE u.role_id = 2
            AND u.deleted = 0
            # AND mb.deleted = 0
            AND bmf.deleted = 0
			
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

    public function existsPartnerBrand($partner_id, $brand_id){
        $query = '
            SELECT EXISTS (
                SELECT 1
                FROM brands_mrss_feeds bmf
                WHERE bmf.partner_id = "'.$partner_id.'"
                AND bmf.brand_id = "'.$brand_id.'"
            ) as exist LIMIT 1
        ';
        $result = $this->db->query($query)->result()[0];

        
		return $result->exist === "1" ? TRUE : FALSE;
    }

	public function getCategoryByTitle($title, $partner_id = '')
	{

		$query = '
			SELECT *
		    FROM brands_mrss_feeds c
			WHERE c.title = "'.$title.'"
			AND deleted = 0
			';
		if(!empty($partner_id) and is_numeric($partner_id)){
		    $query .= ' AND partner_id='.$partner_id;
        }
        // else{
        //     $query .= 'AND type= 1';
        // }

		$result = $this->db->query($query);

		return $result;

	}
    public function getCategoryByPartnerIdBrandId($partner_id, $brand_id){
        $query = '
			SELECT *
		    FROM brands_mrss_feeds c
			WHERE c.partner_id = "'.$partner_id.'"
            AND c.brand_id = "'.$brand_id.'"
			AND deleted = 0
			';

        $result = $this->db->query($query);

		return $result->row();
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
    public function getUserDataByPartnerid($partner_id)
    {

        $query = '
			SELECT full_name,password
		    FROM users 
		    WHERE id = '.$partner_id;

        $result = $this->db->query($query);

        return $result;

    }
    public function getSecureValueByPartnerid($partner_id,$feed_id){
        $query = '
			SELECT `secure`,`url` FROM `mrss_feeds` WHERE `partner_id` = '.$partner_id.' AND id ='.$feed_id;

        $result = $this->db->query($query);

        return $result;
    }
    public function updatePartnerFeed($partner_id,$secure,$url,$feed_id){
        $query = '
			UPDATE `mrss_feeds` SET `url` = "'.$url.'", `secure` = '.$secure.' WHERE `partner_id` = '.$partner_id.' AND id ='.$feed_id;

        $this->db->query($query);
    }
	public function getCategoryById($id,$fields='*')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}

		$query = '
			SELECT '.$fields.'
		    FROM brands_mrss_feeds bmf
            LEFT JOIN brands_mrss_info bmi ON bmi.brands_feed_id = bmf.id
		    WHERE bmf.id = '.$id.'
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
		    FROM brands_mrss_feeds c
			WHERE 
            #c.parent_id = 0 AND 
            c.status = 1
			AND c.deleted = 0
			ORDER BY c.title ASC
		';

		$result = $this->db->query($query);

		return $result;

	}

    public function getBrands($fields="*"){
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}

		$query = '
			SELECT '.$fields.'
		    FROM mrss_brands mb
		';

		$result = $this->db->query($query)->result();

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

    public function getMrssPartners2($ids,$type=1){
        $query = '
			SELECT c.id, title, partner_id, slug, url, u.full_name
			FROM mrss_feeds c, users u
		    WHERE c.deleted = 0
		    AND c.type = 0
		    AND c.status = 1
		    AND u.role_id=2
		    AND c.partner_id = u.id
            AND c.partner_id in ('.$ids.')
            AND c.partner_type = '.$type.'
		';
        return $this->db->query($query);
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
    public function getPartnerName($partner){
	    $query = 'SELECT full_name from users where id='.$partner;
        $result = $this->db->query($query);
        return $result->row()->full_name;
    }
    public function getBrandName($brand_id){
        $query = 'SELECT brand_name from mrss_brands where id='.$brand_id;
        $result = $this->db->query($query);
        return $result->row()->brand_name;
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

    public function exclusiveParnterByVideoId($id) {
        $query = 'SELECT partner_id FROM `feed_video` WHERE video_id='.$id;
        $result = $this->db->query($query);
        if($result->num_rows() > 0){
            return $result->result_array();
        }
        else{
            return false;
        }
    }

    public function getExclusivePartnerByVideoId ($id, $fields = '*') {
        $query =  'SELECT * 
                   FROM  `feed_video`
                   JOIN   mrss_feeds ON mrss_feeds.id = feed_video.feed_id
                   JOIN   users ON feed_video.exclusive_to_partner = users.id 
                   WHERE  video_id = '.$id;
        $result = $this->db->query($query);
        if($result->num_rows() > 0){
            return $result->result_array();
        }
        else{
            return false;
        }
    }

    public function getNonExclusivePartnersByVideoId ($id, $fields = '*') {
        $query =  'SELECT * 
                   FROM  `feed_video`
                   JOIN   mrss_feeds ON mrss_feeds.id = feed_video.feed_id
                   JOIN   users ON mrss_feeds.partner_id = users.id
                   WHERE  video_id = '.$id;
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
    public function nonExclusivePartnerdata(){
        $query ='SELECT * FROM `feed_video` fv, mrss_feeds mf WHERE mf.id=fv.feed_id and exclusive_to_partner=0 and mf.partner_id!=0';
        $result = $this->db->query($query);
        if($result->num_rows() > 0){
            return $result->result_array();
        }
        else{
            return false;
        }
    }

    public function clearPartnersExclusiveFeedsByVideoId ($video_id)
    {
        $video_ids = $this->db->query('SELECT DISTINCT feed_video.id FROM `feed_video`, `mrss_feeds` 
                  WHERE video_id = '.$video_id.' AND (exclusive_to_partner <> 0 OR mrss_feeds.id = feed_video.feed_id)')->result_array();

        $this->db->reset_query();

        if (!empty($video_ids)) {

            $query = 'DELETE FROM feed_video WHERE feed_video.id IN ('.implode(', ', array_column($video_ids, 'id')).')';

            return $this->db->query($query);
        }

    }

    // remove not-partner specific feeds
    public function clearGeneralFeedsByVideoId ($video_id)
    {
        $video_ids = $this->db->query('SELECT DISTINCT feed_video.id FROM `feed_video` JOIN `mrss_feeds` ON mrss_feeds.id = feed_video.feed_id
                        AND feed_video.video_id = '.$video_id.' AND mrss_feeds.partner_id <> 0')->result_array();

        $this->db->reset_query();

        if (!empty($video_ids)) {
            $query = 'DELETE FROM feed_video 
                  WHERE feed_video.id NOT IN ('.implode(', ', array_column($video_ids, 'id')).') 
                  AND video_id = '.$video_id;

            return $this->db->query($query);
        }

    }


    public function getFeedVideos($feed_id, $partner_id=0){
        //$query = 'SELECT v.id,v.title, v.lead_id, vl.unique_key, v.youtube_id,v.description,v.tags,ev.portal_url,ev.portal_thumb,v.created_at, fv.feed_id
 /*       $query = 'SELECT v.id,v.title, v.youtube_id,v.description,v.tags,ev.portal_url,ev.portal_thumb,v.created_at, fv.feed_id
            FROM videos v
            INNER JOIN feed_video fv
            ON v.id = fv.video_id
            INNER JOIN edited_video ev
            ON ev.video_id = v.id
            INNER JOIN video_leads vl
            ON v.lead_id = vl.id
            WHERE v.status = 1
             AND v.deleted = 0
             AND v.is_wooglobe_video = 1
             AND v.mrss = 1
             AND fv.feed_id='.$feed_id.'
             ORDER BY v.created_at DESC';
             ///echo $query;exit ;*/

        $query = 'SELECT v.*, vl.unique_key, vl.created_at,fd.feed_id
            FROM videos v
            INNER JOIN video_leads vl
            ON v.lead_id = vl.id
            INNER JOIN feed_video fd ON v.id = fd.video_id
            WHERE v.status = 1
             AND v.deleted = 0
             AND v.is_wooglobe_video = 1
             AND v.mrss = 1
             AND fd.feed_id = '.$feed_id.'
             group by v.id
             ORDER BY v.created_at DESC
             ';

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

    public function getGeneralCategories ($select_fields_str = '*')
    {
        return $this->db->select($select_fields_str)->from('mrss_feeds')->where('status', '1')->where('type', '1')->where('partner_id', '0')->get()->result_array();
    }

    public function getVideoSelectedCategoriesByVideoId ($video_id, $select_fields_str = '*')
    {
        $query = 'SELECT '.$select_fields_str.' FROM mrss_feeds 
                  JOIN  feed_video ON mrss_feeds.id = feed_video.feed_id 
                  WHERE feed_video.video_id = '.$video_id.' AND partner_id = 0';

        return $this->db->query($query)->result_array();
    }

}
