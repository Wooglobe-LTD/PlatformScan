<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_Model extends APP_Model {

	public function __construct() {
        parent::__construct();

        
    }

    public function getLeadsReport($whereCondition = NULL)
    {
        $query = '
            SELECT COUNT(DISTINCT(vl.id)) as y2,a.name as label2
            FROM video_leads       vl
            LEFT JOIN videos       v        ON v.lead_id = vl.id AND v.deleted = 0
            LEFT JOIN raw_video    rv       ON vl.id     = rv.lead_id
            LEFT JOIN edited_video ev       ON v.id      = ev.video_id					
            LEFT JOIN users        u 	    ON u.id      = vl.client_id AND u.deleted = 0
            LEFT JOIN admin        a        ON a.id      = vl.staff_id
            WHERE vl.deleted = 0
        ';

        if(!empty($whereCondition)){
            $query .= $whereCondition;
        }

        $query .= ' GROUP BY vl.staff_id';

        $result = $this->db->query($query);

        $response = array();
        foreach($result->result() as $row){
            if($row->label2 != NULL) {
                $response[] = array(
                    'y2'=>$row->y2,
                    'label2'=>$row->label2,
                );
            }
        }

        return $response;

    }

    public function getLeadReportByTime($date_from, $date_to)
    {
        $query = "
            SELECT COUNT(DISTINCT(vl.id)) y1, DATE(vl.created_at) label1
            FROM video_leads       vl
            LEFT JOIN videos       v        ON v.lead_id = vl.id AND v.deleted = 0
            LEFT JOIN raw_video    rv       ON vl.id     = rv.lead_id
            LEFT JOIN edited_video ev       ON v.id      = ev.video_id					
            LEFT JOIN users        u 	    ON u.id      = vl.client_id AND u.deleted = 0
            LEFT JOIN admin        a        ON a.id      = vl.staff_id
            WHERE vl.deleted = 0
            AND vl.created_at BETWEEN '$date_from' AND '$date_to'
            GROUP BY DATE(vl.created_at)";

        $result = $this->db->query($query);

        $response = array();
        foreach($result->result() as $row){
            if($row->label1 != NULL) {
                $response[] = array(
                    'y1'=>$row->y1,
                    'label1'=>$row->label1,
                );
            }
        }
        return $response;
    }

    public function getTotalLeadsAtTime($time, $where='')
    {
        return $result;
    }

    public function getNotRatedReports($where = NULL)
    {
        $query = '
			SELECT COUNT(DISTINCT(vl.id)) as y2, a.name as label2
			FROM video_leads       vl
			LEFT JOIN videos       v        ON v.lead_id = vl.id AND v.deleted = 0
			LEFT JOIN raw_video    rv       ON vl.id     = rv.lead_id
			LEFT JOIN edited_video ev       ON v.id      = ev.video_id					
			LEFT JOIN users        u 	    ON u.id      = vl.client_id AND u.deleted = 0
            LEFT JOIN admin        a        ON a.id      = vl.staff_id
		    WHERE vl.deleted = 0 and vl.status = 1
		';
        if(!empty($where)){
            $query .= $where;
        }
        $query .= ' GROUP BY vl.staff_id';

        $result = $this->db->query($query);

        $response = array();
        foreach($result->result() as $row){
            if($row->label2 != NULL) {
                $response[] = array(
                    'y2'=>$row->y2,
                    'label2'=>$row->label2,
                );
            }
        }

        return $response;
    }

    public function getAcquiredReports($where = NULL)
    {
        $query = '
			SELECT COUNT(DISTINCT(vl.id)) as y2, a.name as label2
			FROM video_leads       vl
			LEFT JOIN videos       v        ON v.lead_id = vl.id AND v.deleted = 0
			LEFT JOIN raw_video    rv       ON vl.id     = rv.lead_id
			LEFT JOIN edited_video ev       ON v.id      = ev.video_id					
			LEFT JOIN users        u 	    ON u.id      = vl.client_id AND u.deleted = 0
            LEFT JOIN admin        a        ON a.id      = vl.staff_id
		    WHERE vl.deleted = 0
		    AND vl.status in (6,7,8)
		';
        if(!empty($where)){
            $query .= $where;
        }
        $query .= ' GROUP BY vl.staff_id';
        $result = $this->db->query($query);

        $response = array();
        foreach($result->result() as $row){
            if($row->label2 != NULL) {
                $response[] = array(
                    'y2'=>$row->y2,
                    'label2'=>$row->label2,
                );
            }
        }

        return $response;
    }

    public function getsignedReports($where = NULL)
    {
        $query = '
            SELECT COUNT(DISTINCT(vl.id)) as y2, a.name as label2
			FROM video_leads       vl
			LEFT JOIN videos       v        ON v.lead_id = vl.id AND v.deleted = 0
			LEFT JOIN raw_video    rv       ON vl.id     = rv.lead_id
			LEFT JOIN edited_video ev       ON v.id      = ev.video_id					
			LEFT JOIN users        u 	    ON u.id      = vl.client_id AND u.deleted = 0
            LEFT JOIN admin        a        ON a.id      = vl.staff_id
		    WHERE vl.deleted = 0
		    AND vl.status in (3)
		    AND vl.load_view != 5
		';
        if(!empty($where)){
            $query .= $where;
        }
        $query .= ' GROUP BY vl.staff_id';
        $result = $this->db->query($query);

        $response = array();
        foreach($result->result() as $row){
            if($row->label2 != NULL) {
                $response[] = array(
                    'y2'=>$row->y2,
                    'label2'=>$row->label2,
                );
            }
        }

        return $response;
    }
}
?>