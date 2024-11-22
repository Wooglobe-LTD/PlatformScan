<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publication_Queue_Model extends APP_Model {

	public function __construct() {
        parent::__construct();
    }
	
	public function getPublicationQueue($fields = '*',$search = '',$start = 0,$limit = 0,$where = NULL,$orderby = '',$columns = '')
	{
		if(is_array($fields)){
			$fields = implode(',',$fields);
		}

		$whereFields = '';
		$condition = '';
		if(!empty($search) && is_array($columns)){
			if(!empty($where)) {
				$condition = ' AND (';
			}
			else {
				$condition = ' WHERE (';
			}
			$whereFields = $columns;
			foreach($whereFields as $field){
                if($field['searchable'] != "false") {
                    $condition .= ' ' . $field['name'] . ' LIKE "%' . $search . '%" OR';
                }
			}
			$condition = rtrim($condition,'OR').')';
		}

		$query = '
			SELECT '.$fields.'
		    FROM video_leads vl
			LEFT JOIN videos v
			ON v.lead_id = vl.id
			INNER JOIN raw_video rv
			ON rv.lead_id = vl.id
			LEFT JOIN mrss_publication mp
			ON mp.video_id = v.id
			LEFT JOIN deal_comments dc
			ON dc.lead_id = vl.id
			LEFT JOIN video_publishing_scheduling vps
			ON vps.video_id = v.id
		';

		if(!empty($where)) {
			$query .= $where;
		}
		if(!empty($condition)){
			$query .= $condition;
		}

		$query .= ' GROUP BY vl.id';
		
		if(!empty($orderby)){
			$query .= ' ORDER BY '.$orderby;
		}

		if($limit > 0){
			$query .= ' LIMIT '.$start.','.$limit;
		}
		
		$result = $this->db->query($query);

		return $result;
		
	}
	
	public function getMrssFeedsByVideoId($video_id)
	{
		$query = '
			SELECT mf.title
			FROM mrss_feeds mf
			LEFT JOIN mrss_publication mp
			ON mp.feed_id = mf.id
			WHERE mp.video_id = ' . $video_id;
		$result = $this->db->query($query);

		return $result->result_array();
	}
	
	public function getVideoEnqueuedFeedsByVideoId($video_id)
	{
		$query = '
			SELECT mf.title
			FROM mrss_feeds mf
			LEFT JOIN mrss_queue mq
			ON mq.feed_id = mf.id
			WHERE mq.video_id = ' . $video_id;
		$result = $this->db->query($query);

		return $result->result_array();
	}
	
    public function getCategoriesByLeadId ($lead_id, $fields = '*')
    {
        $query = '
			SELECT '.$fields.'
			FROM mrss_feeds mf
			JOIN feed_video fv
			ON mf.id = fv.feed_id
			LEFT JOIN videos v
			ON v.id = fv.video_id
			WHERE v.lead_id = '.$lead_id.'
			AND partner_id = 0
		';

        return $this->db->query($query)->result_array();
    }
}
