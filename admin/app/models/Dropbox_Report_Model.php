<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dropbox_Report_Model extends APP_Model {

	public function __construct() {
        parent::__construct();

        
    }
    public function update_all_durations(){
        $result = $this->deal->getDealsByStatus("vl.id, rv.s3_url, vl.status, rv.video_duration, rv.video_id", 6)->result_array();
        foreach($result as $row){
            // echo $row["id"]."\n";
            if($row["lead_duration"] == 0)
                $this->update_durations($row["id"]);
            
        }
    }
	public function update_durations($lead_id = NULL){
        // TODO:: Call this in Video_Deals controller to update duration where its 0
        if($lead_id != NULL){
            $result = $this->deal->getRawVideoByLeadId($lead_id)->result_array();
        }
        else{
            return;
        }


        $sum_duration = 0;
        foreach($result as $row){
            // TODO: Save edited_video duration in seaparate column as well
            $is_edited = FALSE;
            $edited_url = "";
            $edited_video = $this->deal->getEditedVideoById($row["video_id"]);

            $edited_result = $edited_video->result();
            if(isset($edited_result[0])){
                if(!empty($edited_result[0]->portal_url)){
                    $edited_url = $edited_result[0]->portal_url;
                    $is_edited = TRUE;
                } 
            }

            $video_src = $row["s3_url"];
            if ($is_edited){
                $video_src = $edited_url;
            }

            if(!isset($video_src) || $video_src === "0" || $video_src === "http://manualupload.com" || $video_src === "Manual Upload"){
                continue;
            }
            $duration = 0;
            try{
                $encoded_url = preg_replace_callback('#://([^/]+)/([^?]+)#', function ($match) {
                    return '://' . $match[1] . '/' . join('/', array_map('rawurlencode', explode('/', $match[2])));
                }, $video_src);
                // echo ($encoded_url)."\n";
                $duration = $this->get_ffprobe_duration(($encoded_url));
            }catch(Exception $e){
                // var_dump($e);
            }

            if($is_edited){
                $sum_duration = $duration;
                break;
            }

            $sum_duration += $duration;               

        }
        
        $this->db->query("UPDATE video_leads SET lead_duration = $sum_duration WHERE id = ".$lead_id);

        $response["message"] = "Video durations are updated";
        $response["code"] = 200;
        $response["duration"] = $sum_duration;

        return json_encode($response);
    }
    public function get_ffprobe_duration($video_src){
        $ffprobe = FFMpeg\FFProbe::create([
            'ffprobe.binaries' => 'ffprobe',
            'ffmpeg.binaries' => 'ffmpeg'
        ]);

        return $ffprobe
                ->streams($video_src)
                ->videos()
                ->first()
                ->get('duration');
    }
	public function getReports($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'rv.dropbox_status DESC',$colums = '',$whereCondition = NULL)
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}
		$condition = '';
        $adminid = $this->sess->userdata('adminId');
        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
		if(!empty($search) && is_array($colums)){
			
			$count = count($colums);

			unset($colums[$count-1]);

			$condition = ' AND (';
			
			$whereFields = $colums;
	
			foreach($whereFields as $field){
			    if($field['name'] != "" && $field['name'] != 'action'){
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
		    WHERE 1 = 1
		    '.$staff_check.' '; 

        // $query = 'SELECT '.$fields.'
        //     FROM video_leads vl 
        //     INNER JOIN raw_video    rv  ON  rv.lead_id = vl.id 
        // ';

		if(!empty($condition)){

			$query .= $condition;

		}

		if(!empty($whereCondition)){
			$query .= $whereCondition;
		}
        $query .= ' GROUP BY rv.video_id, rv.dropbox_status';
		if(!empty($orderby)){

			$query .= ' ORDER BY '.$orderby;

		}

		if($limit > 0){

			$query .= ' LIMIT '.$start.','.$limit;

		}

		$result = $this->db->query($query);

		return $result;
		
	}
    public function getleadReports($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'vl.id DESC',$colums = '',$whereCondition = NULL)
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }
        $condition = '';
        $adminid = $this->sess->userdata('adminId');
        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
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
        /*
            SELECT 		*, videos.id as video_id, video_leads.id as video_lead_id
            FROM        videos
            JOIN        video_leads ON video_leads.id = videos.lead_id
            LEFT JOIN   raw_video   ON video_leads.id = raw_video.lead_id
        */
        $query = '
			SELECT '.$fields.'
			FROM video_leads       vl
		    WHERE vl.deleted = 0
		    '.$staff_check.'
		';
        /* 		$query = '
                    SELECT '.$fields.'
                    FROM video_leads vl
                    LEFT JOIN videos v ON v.lead_id = vl.id AND v.deleted = 0
                    LEFT JOIN users u ON u.id = vl.client_id AND u.deleted = 0
                    WHERE vl.deleted = 0
                '; */
        if(!empty($condition)){

            $query .= $condition;

        }

        if(!empty($whereCondition)){
            $query .= $whereCondition;
        }
        $query .= ' GROUP BY vl.id';
        if(!empty($orderby)){

            $query .= ' ORDER BY '.$orderby;

        }

        if($limit > 0){

            $query .= ' LIMIT '.$start.','.$limit;

        }
        //echo $query;exit;
        $result = $this->db->query($query);

        return $result;

    }
    public function getsignedReports($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'vl.id DESC',$colums = '',$whereCondition = NULL)
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }
        $condition = '';
        $adminid = $this->sess->userdata('adminId');
        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
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
        /*
            SELECT 		*, videos.id as video_id, video_leads.id as video_lead_id
            FROM        videos
            JOIN        video_leads ON video_leads.id = videos.lead_id
            LEFT JOIN   raw_video   ON video_leads.id = raw_video.lead_id
        */
        $query = '
			SELECT '.$fields.'
			FROM video_leads       vl
			LEFT JOIN videos       v        ON v.lead_id = vl.id AND v.deleted = 0
			LEFT JOIN raw_video    rv       ON vl.id     = rv.lead_id
			LEFT JOIN edited_video ev       ON v.id      = ev.video_id					
			LEFT JOIN users        u 	    ON u.id      = vl.client_id AND u.deleted = 0
		    WHERE vl.deleted = 0
		    '.$staff_check.'
		    AND vl.status in (3)
		    AND vl.load_view != 5
		';
        /* 		$query = '
                    SELECT '.$fields.'
                    FROM video_leads vl
                    LEFT JOIN videos v ON v.lead_id = vl.id AND v.deleted = 0
                    LEFT JOIN users u ON u.id = vl.client_id AND u.deleted = 0
                    WHERE vl.deleted = 0
                '; */
        if(!empty($condition)){

            $query .= $condition;

        }

        if(!empty($whereCondition)){
            $query .= $whereCondition;
        }
        $query .= ' GROUP BY vl.id';
        if(!empty($orderby)){

            $query .= ' ORDER BY '.$orderby;

        }

        if($limit > 0){

            $query .= ' LIMIT '.$start.','.$limit;

        }
        /*echo '<pre>';
        print_r($query);
        exit();*/
        $result = $this->db->query($query);

        return $result;

    }

    public function getsignedAndrejectReports($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'vl.id DESC',$colums = '',$whereCondition = NULL)
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }
        $condition = '';
        $adminid = $this->sess->userdata('adminId');
        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
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
        /*
            SELECT 		*, videos.id as video_id, video_leads.id as video_lead_id
            FROM        videos
            JOIN        video_leads ON video_leads.id = videos.lead_id
            LEFT JOIN   raw_video   ON video_leads.id = raw_video.lead_id
        */
        $query = '
			SELECT '.$fields.'
			FROM video_leads       vl
			LEFT JOIN videos       v        ON v.lead_id = vl.id AND v.deleted = 0
			LEFT JOIN raw_video    rv       ON vl.id     = rv.lead_id
			LEFT JOIN edited_video ev       ON v.id      = ev.video_id					
			LEFT JOIN users        u 	    ON u.id      = vl.client_id AND u.deleted = 0
		    WHERE vl.deleted = 0
		    '.$staff_check.'
		    AND vl.status in (3)
		    AND vl.load_view = 5
		';
        /* 		$query = '
                    SELECT '.$fields.'
                    FROM video_leads vl
                    LEFT JOIN videos v ON v.lead_id = vl.id AND v.deleted = 0
                    LEFT JOIN users u ON u.id = vl.client_id AND u.deleted = 0
                    WHERE vl.deleted = 0
                '; */
        if(!empty($condition)){

            $query .= $condition;

        }

        if(!empty($whereCondition)){
            $query .= $whereCondition;
        }
        $query .= ' GROUP BY vl.id';
        if(!empty($orderby)){

            $query .= ' ORDER BY '.$orderby;

        }

        if($limit > 0){

            $query .= ' LIMIT '.$start.','.$limit;

        }
        /*echo '<pre>';
        print_r($query);
        exit();*/
        $result = $this->db->query($query);

        return $result;

    }

    public function getAcquiredReports($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'vl.id DESC',$colums = '',$whereCondition = NULL)
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }
        $condition = '';
        $adminid = $this->sess->userdata('adminId');
        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
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
        /*
            SELECT 		*, videos.id as video_id, video_leads.id as video_lead_id
            FROM        videos
            JOIN        video_leads ON video_leads.id = videos.lead_id
            LEFT JOIN   raw_video   ON video_leads.id = raw_video.lead_id
        */
        $query = '
			SELECT '.$fields.'
			FROM video_leads       vl
			LEFT JOIN videos       v        ON v.lead_id = vl.id AND v.deleted = 0
			LEFT JOIN raw_video    rv       ON vl.id     = rv.lead_id
			LEFT JOIN edited_video ev       ON v.id      = ev.video_id					
			LEFT JOIN users        u 	    ON u.id      = vl.client_id AND u.deleted = 0
		    WHERE vl.deleted = 0
		    '.$staff_check.'
		    AND vl.status in (6,7,8)
		';
        /* 		$query = '
                    SELECT '.$fields.'
                    FROM video_leads vl
                    LEFT JOIN videos v ON v.lead_id = vl.id AND v.deleted = 0
                    LEFT JOIN users u ON u.id = vl.client_id AND u.deleted = 0
                    WHERE vl.deleted = 0
                '; */
        if(!empty($condition)){

            $query .= $condition;

        }

        if(!empty($whereCondition)){
            $query .= $whereCondition;
        }
        $query .= ' GROUP BY vl.id';
        if(!empty($orderby)){

            $query .= ' ORDER BY '.$orderby;

        }

        if($limit > 0){

            $query .= ' LIMIT '.$start.','.$limit;

        }
        /*echo '<pre>';
        print_r($query);
        exit(); */
        $result = $this->db->query($query);

        return $result;

    }

    public function getrejectedReports($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'vl.id DESC',$colums = '',$whereCondition = NULL)
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }
        $condition = '';
        $adminid = $this->sess->userdata('adminId');
        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
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
        /*
            SELECT 		*, videos.id as video_id, video_leads.id as video_lead_id
            FROM        videos
            JOIN        video_leads ON video_leads.id = videos.lead_id
            LEFT JOIN   raw_video   ON video_leads.id = raw_video.lead_id
        */
        $query = '
			SELECT '.$fields.'
			FROM video_leads       vl
			LEFT JOIN videos       v        ON v.lead_id = vl.id AND v.deleted = 0
			LEFT JOIN raw_video    rv       ON vl.id     = rv.lead_id
			LEFT JOIN edited_video ev       ON v.id      = ev.video_id					
			LEFT JOIN users        u 	    ON u.id      = vl.client_id AND u.deleted = 0
		    WHERE vl.deleted = 0
		    '.$staff_check.'
		    AND vl.load_view = 5
		';
        /* 		$query = '
                    SELECT '.$fields.'
                    FROM video_leads vl
                    LEFT JOIN videos v ON v.lead_id = vl.id AND v.deleted = 0
                    LEFT JOIN users u ON u.id = vl.client_id AND u.deleted = 0
                    WHERE vl.deleted = 0
                '; */
        if(!empty($condition)){

            $query .= $condition;

        }

        if(!empty($whereCondition)){
            $query .= $whereCondition;
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

    public function getCancelReports($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'vl.id DESC',$colums = '',$whereCondition = NULL)
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }
        $condition = '';
        $adminid = $this->sess->userdata('adminId');
        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
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
        /*
            SELECT 		*, videos.id as video_id, video_leads.id as video_lead_id
            FROM        videos
            JOIN        video_leads ON video_leads.id = videos.lead_id
            LEFT JOIN   raw_video   ON video_leads.id = raw_video.lead_id
        */
        $query = '
			SELECT '.$fields.'
			FROM video_leads       vl
			LEFT JOIN videos       v        ON v.lead_id = vl.id AND v.deleted = 0
			LEFT JOIN raw_video    rv       ON vl.id     = rv.lead_id
			LEFT JOIN edited_video ev       ON v.id      = ev.video_id					
			LEFT JOIN users        u 	    ON u.id      = vl.client_id AND u.deleted = 0
		    WHERE 1 = 1 and vl.deleted = 1
		    '.$staff_check.'
		';
        /* 		$query = '
                    SELECT '.$fields.'
                    FROM video_leads vl
                    LEFT JOIN videos v ON v.lead_id = vl.id AND v.deleted = 0
                    LEFT JOIN users u ON u.id = vl.client_id AND u.deleted = 0
                    WHERE vl.deleted = 0
                '; */
        if(!empty($condition)){

            $query .= $condition;

        }

        if(!empty($whereCondition)){
            $query .= $whereCondition;
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
    public function getPoorReports($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'vl.id DESC',$colums = '',$whereCondition = NULL)
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }
        $condition = '';
        $adminid = $this->sess->userdata('adminId');
        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
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
        /*
            SELECT 		*, videos.id as video_id, video_leads.id as video_lead_id
            FROM        videos
            JOIN        video_leads ON video_leads.id = videos.lead_id
            LEFT JOIN   raw_video   ON video_leads.id = raw_video.lead_id
        */
        $query = '
			SELECT '.$fields.'
			FROM video_leads       vl
			LEFT JOIN videos       v        ON v.lead_id = vl.id AND v.deleted = 0
			LEFT JOIN raw_video    rv       ON vl.id     = rv.lead_id
			LEFT JOIN edited_video ev       ON v.id      = ev.video_id					
			LEFT JOIN users        u 	    ON u.id      = vl.client_id AND u.deleted = 0
		    WHERE vl.deleted = 0 and vl.status = 5
		    '.$staff_check.'
		';
        /* 		$query = '
                    SELECT '.$fields.'
                    FROM video_leads vl
                    LEFT JOIN videos v ON v.lead_id = vl.id AND v.deleted = 0
                    LEFT JOIN users u ON u.id = vl.client_id AND u.deleted = 0
                    WHERE vl.deleted = 0
                '; */
        if(!empty($condition)){

            $query .= $condition;

        }

        if(!empty($whereCondition)){
            $query .= $whereCondition;
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
    public function getNotInterestReports($fields = '*', $search = '', $start = 0, $limit = 0, $orderby = 'vl.id DESC', $colums = '', $whereCondition = NULL)
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }
        $condition = '';
        $adminid = $this->sess->userdata('adminId');
        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
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
        /*
            SELECT 		*, videos.id as video_id, video_leads.id as video_lead_id
            FROM        videos
            JOIN        video_leads ON video_leads.id = videos.lead_id
            LEFT JOIN   raw_video   ON video_leads.id = raw_video.lead_id
        */
        $query = '
			SELECT '.$fields.'
			FROM video_leads       vl
			LEFT JOIN videos       v        ON v.lead_id = vl.id AND v.deleted = 0
			LEFT JOIN raw_video    rv       ON vl.id     = rv.lead_id
			LEFT JOIN edited_video ev       ON v.id      = ev.video_id					
			LEFT JOIN users        u 	    ON u.id      = vl.client_id AND u.deleted = 0
		    WHERE vl.deleted = 0 and vl.status = 11
		    '.$staff_check.'
		';
        /* 		$query = '
                    SELECT '.$fields.'
                    FROM video_leads vl
                    LEFT JOIN videos v ON v.lead_id = vl.id AND v.deleted = 0
                    LEFT JOIN users u ON u.id = vl.client_id AND u.deleted = 0
                    WHERE vl.deleted = 0
                '; */
        if(!empty($condition)){

            $query .= $condition;

        }

        if(!empty($whereCondition)){
            $query .= $whereCondition;
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

    public function getTakeActionReports($fields = '*', $search = '', $start = 0, $limit = 0, $orderby = 'vl.id DESC', $colums = '', $whereCondition = NULL)
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }
        $condition = '';
        $adminid = $this->sess->userdata('adminId');
        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
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
        /*
            SELECT 		*, videos.id as video_id, video_leads.id as video_lead_id
            FROM        videos
            JOIN        video_leads ON video_leads.id = videos.lead_id
            LEFT JOIN   raw_video   ON video_leads.id = raw_video.lead_id
        */
        $query = '
			SELECT '.$fields.'
			FROM video_leads       vl
			LEFT JOIN videos       v        ON v.lead_id = vl.id AND v.deleted = 0
			LEFT JOIN raw_video    rv       ON vl.id     = rv.lead_id
			LEFT JOIN edited_video ev       ON v.id      = ev.video_id					
			LEFT JOIN users        u 	    ON u.id      = vl.client_id AND u.deleted = 0
		    WHERE vl.deleted = 0 and vl.status = 10
		    '.$staff_check.'
		';
        /* 		$query = '
                    SELECT '.$fields.'
                    FROM video_leads vl
                    LEFT JOIN videos v ON v.lead_id = vl.id AND v.deleted = 0
                    LEFT JOIN users u ON u.id = vl.client_id AND u.deleted = 0
                    WHERE vl.deleted = 0
                '; */
        if(!empty($condition)){

            $query .= $condition;

        }

        if(!empty($whereCondition)){
            $query .= $whereCondition;
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

    public function getNotRateReports($fields = '*', $search = '', $start = 0, $limit = 0, $orderby = 'vl.id DESC', $colums = '', $whereCondition = NULL)
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }
        $condition = '';
        $adminid = $this->sess->userdata('adminId');
        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
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
        /*
            SELECT 		*, videos.id as video_id, video_leads.id as video_lead_id
            FROM        videos
            JOIN        video_leads ON video_leads.id = videos.lead_id
            LEFT JOIN   raw_video   ON video_leads.id = raw_video.lead_id
        */
        $query = '
			SELECT '.$fields.'
			FROM video_leads       vl
			LEFT JOIN videos       v        ON v.lead_id = vl.id AND v.deleted = 0
			LEFT JOIN raw_video    rv       ON vl.id     = rv.lead_id
			LEFT JOIN edited_video ev       ON v.id      = ev.video_id					
			LEFT JOIN users        u 	    ON u.id      = vl.client_id AND u.deleted = 0
		    WHERE vl.deleted = 0 and vl.status = 1
		    '.$staff_check.'
		';
        /* 		$query = '
                    SELECT '.$fields.'
                    FROM video_leads vl
                    LEFT JOIN videos v ON v.lead_id = vl.id AND v.deleted = 0
                    LEFT JOIN users u ON u.id = vl.client_id AND u.deleted = 0
                    WHERE vl.deleted = 0
                '; */
        if(!empty($condition)){

            $query .= $condition;

        }

        if(!empty($whereCondition)){
            $query .= $whereCondition;
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
    function getFullDetails(){

        $response = array();

        // Select record
        $query = 'SELECT `first_name`,`last_name`,`email`,`video_title`,video_leads.status,videos.description,videos.tags,
        `video_url`,videos.thumbnail,videos.mrss,videos.mrss_categories,`unique_key`,video_leads.`created_at`,
        `rating_point`,`revenue_share`,`shotVideo`,
        `haveOrignalVideo`,`confidence_level`,`reject_comments`,`video_comment`,raw_video.url,
        raw_video.s3_url,raw_video.s3_document_url,edited_video.portal_url,edited_video.portal_thumb,videos.youtube_id,videos.facebook_id
        FROM video_leads 
        LEFT JOIN raw_video ON video_leads.id = raw_video.lead_id 
        LEFT JOIN videos ON video_leads.id = videos.lead_id 
        LEFT JOIN edited_video ON edited_video.video_id = videos.id
                   ';
        $result = $this->db->query($query);
        $response = $result->result_array();

        return $response;
    }

    public function getPieReports($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'vl.id DESC',$colums = '',$whereCondition = NULL)
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }
        $condition = '';
        $adminid = $this->sess->userdata('adminId');
        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
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
        /*
            SELECT 		*, videos.id as video_id, video_leads.id as video_lead_id
            FROM        videos
            JOIN        video_leads ON video_leads.id = videos.lead_id
            LEFT JOIN   raw_video   ON video_leads.id = raw_video.lead_id
        */
        $query = '
			SELECT COUNT(DISTINCT(vl.id)) as y,vl.rating_point as label
			FROM video_leads       vl
			LEFT JOIN videos       v        ON v.lead_id = vl.id AND v.deleted = 0
			LEFT JOIN raw_video    rv       ON vl.id     = rv.lead_id
			LEFT JOIN edited_video ev       ON v.id      = ev.video_id					
			LEFT JOIN users        u 	    ON u.id      = vl.client_id AND u.deleted = 0
		    WHERE vl.deleted = 0
		    '.$staff_check.'
		';
        /* 		$query = '
                    SELECT '.$fields.'
                    FROM video_leads vl
                    LEFT JOIN videos v ON v.lead_id = vl.id AND v.deleted = 0
                    LEFT JOIN users u ON u.id = vl.client_id AND u.deleted = 0
                    WHERE vl.deleted = 0
                '; */
        if(!empty($condition)){

            $query .= $condition;

        }

        if(!empty($whereCondition)){
            $query .= $whereCondition;
        }

        $query .= ' GROUP BY vl.rating_point';

        if(!empty($orderby)){

            $query .= ' ORDER BY '.$orderby;

        }
        //echo $query;exit;

        $result = $this->db->query($query);
        $response = array();
        $total = 0;
        foreach($result->result() as $row){
            $total = $total+$row->y;
        }

        foreach($result->result() as $row){
            $response[] = array(
                'y'=>(($row->y/$total)*100),
                'label'=>'Rating Point '.$row->label,
                );
        }

        return $response;

    }

    public function getPieMultiReports($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'vl.id DESC',$colums = '',$whereCondition = NULL)
    {
        //echo $whereCondition;exit;
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }
        $condition = '';
        $adminid = $this->sess->userdata('adminId');
        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
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
        /*
            SELECT 		*, videos.id as video_id, video_leads.id as video_lead_id
            FROM        videos
            JOIN        video_leads ON video_leads.id = videos.lead_id
            LEFT JOIN   raw_video   ON video_leads.id = raw_video.lead_id
        */
        $query = '
			SELECT COUNT(DISTINCT(vl.id)) as y,a.name as label
			FROM video_leads       vl
			LEFT JOIN videos       v        ON v.lead_id = vl.id AND v.deleted = 0
			LEFT JOIN raw_video    rv       ON vl.id     = rv.lead_id
			LEFT JOIN edited_video ev       ON v.id      = ev.video_id					
			LEFT JOIN users        u 	    ON u.id      = vl.client_id AND u.deleted = 0
			LEFT JOIN admin a ON a.id = vl.staff_id
		    WHERE vl.deleted = 0
		    '.$staff_check.'
		';
        /* 		$query = '
                    SELECT '.$fields.'
                    FROM video_leads vl
                    LEFT JOIN videos v ON v.lead_id = vl.id AND v.deleted = 0
                    LEFT JOIN users u ON u.id = vl.client_id AND u.deleted = 0
                    WHERE vl.deleted = 0
                '; */
        if(!empty($condition)){

            $query .= $condition;

        }

        if(!empty($whereCondition)){
            $query .= $whereCondition;
        }

        $query .= ' GROUP BY vl.staff_id';

        if(!empty($orderby)){

            $query .= ' ORDER BY '.$orderby;

        }
        //echo $query;exit;

        $result = $this->db->query($query);
        $response = array();
        $total = 0;
        foreach($result->result() as $row){
            $total = $total+$row->y;
        }

        foreach($result->result() as $row){
            $response[] = array(
                'y'=>(($row->y/$total)*100),
                'label'=>$row->label,
            );
        }

        return $response;

    }
}
