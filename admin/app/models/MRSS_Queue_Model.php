<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MRSS_Queue_Model extends APP_Model {

	public function __construct() {
        parent::__construct();

        
    }
	
	public function getAllQueue(){
        $query = 'SELECT * FROM mrss_queue';
        $result = $this->db->query($query);

		return $result;
    }

    public function getQueueByVideoId($video_id){
        $query = 'SELECT * FROM mrss_queue WHERE video_id = '.$video_id;
        $result = $this->db->query($query);

		return $result;
    }
    public function getFeedCounts(){
        $query = "SELECT feed_id, count(*) as count FROM mrss_queue GROUP BY feed_id";
        $result = $this->db->query($query)->result_array();
        return db_result_to_array_map($result);
    }

    public function getQueueByFeedId($fields = '*', $feed_id, $search = '', $start = 0, $limit = 0, $orderby = 'mq.id DESC', $colums=''){
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
                if($field['name'] != 'action' && $field['name'] != 'ev.action'){
					$condition .= ' '.$field['name'].' LIKE "%'.$search.'%" OR';
				}

			}

			$condition = rtrim($condition,'OR').')';
		}
        $query = '
            SELECT '.$fields.'
            FROM mrss_queue mq
            LEFT JOIN videos v
            ON v.id = mq.video_id
            LEFT JOIN video_leads vl
            ON vl.id = v.lead_id
            WHERE feed_id = '.$feed_id;
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

    public function getQueueByLeadId($lead_id){
        $query = '
            SELECT vl.unique_key, mq.feed_id, mf.title
            FROM mrss_queue mq
            LEFT JOIN videos vd
            ON vd.id = mq.video_id
            LEFT JOIN video_leads vl
            ON vl.id = vd.lead_id
            LEFT JOIN mrss_feeds mf
            ON mf.id = mq.feed_id
            WHERE vl.id = '
            .$lead_id
        ;

        $result = $this->db->query($query);

		return $result;
    }
}
