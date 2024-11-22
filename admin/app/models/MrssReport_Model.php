<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MrssReport_Model extends APP_Model {

	public function __construct() {
        parent::__construct();

        
    }
	
	public function getReports($fields = '*',$start = 0,$limit = 0,$whereCondition = NULL,$group='',$orderby = 'mp.publication_date DESC')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}


        $query_check='';
		// $query = '
		// 	SELECT '.$fields.'
        //     FROM `feed_video` fv
        //     LEFT JOIN mrss_feeds mf
        //     ON mf.id = fv.feed_id
        //     LEFT JOIN videos v
        //     ON v.id = fv.video_id
        //     LEFT JOIN video_leads vl
        //     ON vl.id = v.lead_id
        //     LEFT JOIN edited_video ed
        //     ON ed.video_id = v.id
		// 	LEFT JOIN mrss_publication mp
		// 	ON mp.video_id = v.id
        //     WHERE vl.deleted = 0
		// ';

		$query = 'SELECT * from video_leads vl
		LEFT JOIN videos v ON vl.id = v.lead_id
		LEFT JOIN mrss_publication mp ON mp.video_id = v.id 
		LEFT JOIN feed_video fv ON mp.video_id = fv.video_id 
		LEFT JOIN mrss_feeds mf ON mf.id = mp.feed_id
		LEFT JOIN edited_video ed ON v.id = ed.video_id
		WHERE vl.deleted = 0 ';


		if(!empty($whereCondition)){
            $query .= $whereCondition;
		}
        $query .= $group;
        if(!empty($orderby)){

            $query .= ' ORDER BY '.$orderby;

        }
		if($limit > 0){

			$query .= ' LIMIT '.$start.','.$limit;

		}

		$result = $this->db->query($query);
		return $result;
		
	}
	
	public function getExceptionReports($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'vl.id DESC',$colums = '',$whereCondition = NULL)
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}
		$condition = '';
		if(!empty($search) && is_array($colums)){
			
			$count = count($colums);

			unset($colums[$count-1]);

			$condition = ' AND (';
			
			$whereFields = $colums;
	
			foreach($whereFields as $field){
			    if($field['name'] != 'action'){
                    $condition .= ' '.$field['name'].' LIKE "%'.$search.'%" OR';
                }
	

			}

			$condition = rtrim($condition,'OR').')';
		}

		$query = '
			SELECT '.$fields.'
			FROM video_leads       vl
			LEFT JOIN videos       v        ON v.lead_id = vl.id AND v.deleted = 0
			LEFT JOIN raw_video    rv       ON vl.id     = rv.lead_id
			LEFT JOIN edited_video ev       ON v.id      = ev.video_id					
			LEFT JOIN users        u 	    ON u.id      = vl.client_id AND u.deleted = 0
		    WHERE vl.deleted = 0
		'; 

		if(!empty($condition)){

			$query .= $condition;

		}

		if(!empty($whereCondition)){
			$query .= $whereCondition;
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

	public function  getGeneralMRSSCatNameby($feed_id){
        $query = '
			SELECT id,title FROM `mrss_feeds` WHERE `id` in ('.$feed_id.') AND partner_id = 0';
        $result = $this->db->query($query);

        return $result;
    }
    public function  getGeneralMRSSCatNamebyFeedidandVdieoid($feed_id,$video_id){
        $query = '
			SELECT mf.title FROM mrss_feeds mf INNER JOIN feed_video fv ON fv.feed_id = mf.id WHERE fv.feed_id != '.$feed_id.' AND video_id = '.$video_id.' AND mf.partner_id = 0';
        $result = $this->db->query($query);

        return $result;
    }
    public function  getPartnerMRSSCatNameby($feed_id){
        $query = '
			SELECT id,title FROM `mrss_feeds` WHERE `id` in ('.$feed_id.') AND `partner_id` != 0';
        $result = $this->db->query($query);

        return $result;
    }
    public function  getPartnerMRSSCatNamebyFeedidandVideoid($feed_id,$video_id){
        $query = '
			SELECT mf.title FROM mrss_feeds mf INNER JOIN feed_video fv ON fv.feed_id = mf.id WHERE fv.feed_id != '.$feed_id.' AND video_id = '.$video_id.' AND mf.partner_id != 0';
        $result = $this->db->query($query);

        return $result;
    }
}
