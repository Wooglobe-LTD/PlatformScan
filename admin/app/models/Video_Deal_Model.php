<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video_Deal_Model extends APP_Model {

	public function __construct() {
        parent::__construct();

        
    }
	// Testing changes local
	public function getAllDeals($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'vl.id DESC',$colums = '')
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
	
				$condition .= ' '.$field['name'].' LIKE "%'.$search.'%" OR';
			}

			$condition = rtrim($condition,'OR').')';
		}
		

		$query = '
			SELECT '.$fields.'
			FROM video_leads vl
		    WHERE vl.deleted = 0
		    AND vl.status > 1
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
	
    public function getDealsByStatus($fields = "*", $status = 1){
        $query = '
			SELECT '.$fields.'
			FROM video_leads vl
            INNER JOIN raw_video rv
            ON rv.lead_id = vl.id
		    WHERE vl.deleted = 0
		    AND vl.status >= '.$status.'
            AND vl.lead_duration = 0
		';

        $result = $this->db->query($query);
        return $result;
    }
	
	public function getDealById($id,$fields='*')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}

		$query = '
			SELECT '.$fields.'
		    FROM video_leads vl
		    LEFT JOIN lead_action_dates lad
	        ON vl.id = lad.lead_id
	        LEFT JOIN admin ad
	        ON ad.id = vl.staff_id
            LEFT JOIN deal_comments dc
            ON dc.lead_id = vl.id
		    WHERE vl.id = '.$id.'
		';

		$result = $this->db->query($query);

		if($result->num_rows() > 0){

			return $result->row();

		}else{

			return false;

		}
		
	}

    public function getDealsByUniqueKeys($keys,$fields='*')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}

        if(is_array($keys)){
            $keys = "'" . implode("','", $keys) . "'";
        }

		$query = '
			SELECT '.$fields.'
		    FROM video_leads vl
		    LEFT JOIN lead_action_dates lad
	        ON vl.id = lad.lead_id
	        LEFT JOIN admin ad
	        ON ad.id = vl.staff_id
            LEFT JOIN raw_video rv 
            ON rv.lead_id = vl.id
		    WHERE vl.unique_key IN ('.$keys.')
		';

		$result = $this->db->query($query);

		return $result;
		
	}


	public function getDealDataById($id){
		$query = '
			SELECT vl.*
		    FROM video_leads vl
		    INNER JOIN lead_action_dates lad
	        ON vl.id = lad.lead_id
		    WHERE vl.id = '.$id.'
		';

		$result = $this->db->query($query);

		if($result->num_rows() > 0){

			return $result->row();

		}else{

			return false;

		}
	}

    public function getEmailByLeadId($id)
    {
        $query = 'SELECT vl.email
                   FROM  video_leads vl
                   WHERE vl.id = '.$id.'
                   ';
        $result = $this->db->query($query);
        return $result->row();
	}

    public function getUserByLeadId($id)
    {
        $query = 'SELECT u.*
                   FROM  video_leads vl
                   INNER JOIN users u 
                   ON vl.client_id = u.id
                   WHERE vl.id = '.$id.'
                   ';
        $result = $this->db->query($query);
        return $result->row();

	}

    public function getVideosByLeadId($id)
    {
        $query = 'SELECT v.*,wv.s3_url,sv.s3_url soical_url
                   FROM  video_leads vl
                   INNER JOIN videos v 
                   ON vl.id = v.lead_id
                   LEFT JOIN watermark_videos wv
                   ON v.id = wv.video_id
                   LEFT JOIN social_videos sv
                   ON v.id = sv.video_id
                   WHERE vl.id = '.$id.'
                   ';
        $result = $this->db->query($query);
        return $result->row();
	}

	public function getLeadMailStatusByLeadId($lead_id){

        $query = '
			SELECT *
		    FROM intro_email im
		    WHERE im.video_lead_id = '.$lead_id.'
		';
        $result = $this->db->query($query);
        return $result;

    }

	public function getMaxActivityTimeForLead(){
		$query = 'SELECT vl.id, MAX(act.created_at) as last_activity 
				  FROM video_leads vl, action_taken act 
				  WHERE vl.status 
				  IN (10, 2, 3, 6) 
				  AND vl.id=act.lead_id 
				  GROUP BY act.lead_id';
		$result = $this->db->query($query);
		$data = $result->result_array();
		$data_array = array();
		foreach($data as $list){
			$data_array[$list['id']] = $list['last_activity'];
		}
		return $data_array;
	}


	public function getRatedDeals($sortColumn = 'vl.created_at',$sort = 'DESC', $limit = 10, $offset = 0, $additional_info = []){
		$action_table_join = '';
		$join_cond = '';
        $adminid = $this->sess->userdata('adminId');

		if (isset($additional_info['join_stage_filter']) && $additional_info['join_stage_filter'] == true) {
			$join_cond = ' AND action = "Lead converted to deal"';
		}		
		
		if ($sortColumn == 'at.created_at') {
			$action_table_join = 'LEFT JOIN action_taken at
								  ON vl.id = at.lead_id '.$join_cond;
		}
        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }

		
        $query = '
	        SELECT vl.id, vl.first_name, vl.last_name,vl.email, vl.video_title, vl.reminder_sent, vl.closing_date, vl.status, vl.rating_point, vl.revenue_share,lad.lead_rated_date,ad.name staff_name,vl.unique_key,vl.simple_video,vl.report_issue_type,vl.report_issue_desc
	        FROM video_leads vl
	        INNER JOIN lead_action_dates lad
	        ON vl.id = lad.lead_id
			'.$action_table_join.'
			LEFT JOIN admin ad
	        ON ad.id = vl.staff_id
	        WHERE vl.status = 10
	        AND vl.deleted = 0
	        '.$staff_check.'
	        ORDER BY '.$sortColumn.' '.$sort.' LIMIT '.$limit.' OFFSET '.$offset;//echo $query;exit;

        $result = $this->db->query($query);


        return $result;
    }

    public function getTemplate()
    {
        $query = 'SELECT * 
                  FROM email_templates 
                  WHERE id = 2';
        $result = $this->db->query($query);
        if($result->num_rows() > 0){

            return $result->row();

        }else{

            return false;

        }
    }

	public function getTemplateId($lead_id, $template_id=2)
	{
		$query = 'SELECT * 
                  FROM email_notification_history 
                  WHERE lead_id = '.$lead_id.' 
                  AND email_template_id = '.$template_id
		;
		$result = $this->db->query($query);
		return $result->row();

    }
    public function getsecondsigner($uid)
    {
        $query = "SELECT * 
                  FROM second_signer 
                  WHERE uid = '$uid' "
        ;
        $result = $this->db->query($query);
        return $result->result();

    }
    public function getappreancerelease($uid)
    {
        $query = "SELECT * 
                  FROM appreance_release 
                  WHERE uid = '$uid' "
        ;
        $result = $this->db->query($query);
        return $result->result();

    }
    public function getLeadRelaseLink($uid, $type=null)
    {

            $query = "SELECT * 
                  FROM release_link 
                  WHERE unique_key = '$uid'"  ;
            if(!empty($type)){
                $query .= " AND link_type = '$type'";
            }

            $result = $this->db->query($query);
            return $result->result();

    }
    public function getSentContracts($sortColumn = 'vl.created_at',$sort = 'DESC', $limit = 10, $offset = 0)
    {
        $query = '
	        SELECT vl.id, vl.first_name, vl.last_name, vl.email, vl.video_title, vl.reminder_sent, vl.closing_date, vl.status, vl.rating_point, vl.revenue_share, lad.contract_sent_date,vl.simple_video
	        FROM video_leads vl
	        INNER JOIN lead_action_dates lad
	        ON vl.id = lad.lead_id
	        WHERE vl.status = 2
	        AND deleted = 0
	        ORDER BY '.$sortColumn.' '.$sort.' LIMIT '.$limit.' OFFSET '.$offset;
        $result = $this->db->query($query);

        return $result;

    }
    public function getSignedDeals($sortColumn = 'vl.created_at',$sort = 'DESC', $limit = 10, $offset = 0){

	    $query = '
	        SELECT vl.id, vl.first_name, vl.last_name,vl.email, vl.video_title, vl.reminder_sent, vl.closing_date, vl.status, vl.rating_point, vl.revenue_share,vl.simple_video
	        FROM video_leads vl
	        WHERE vl.status = 3
	        AND vl.deleted = 0
	        AND vl.client_id = 0
	        ORDER BY '.$sortColumn.' '.$sort.'
	    ';

	    $result = $this->db->query($query);


	    return $result;
    }

    public function getRejectedDeals($sortColumn = 'vl.created_at',$sort = 'DESC')
    {
        $query = '
	        SELECT vl.*, MAX(act.created_at) as last_activity
	        FROM video_leads vl
	        INNER JOIN action_taken act
	        ON vl.id = act.lead_id
	        WHERE vl.status = 5
	        AND deleted = 0
	        GROUP BY act.lead_id
	       ORDER BY '.$sortColumn.' '.$sort.'
	    ';

        $result = $this->db->query($query);
        return $result;
    }





    public function getCorrespondingEmails($email,$lead_id)
    {
        $query = 'SELECT * , COUNT(*) as email_count FROM 
                  (SELECT * 
                  FROM gmail_conversation
                  WHERE (from_email = "'.$email.'"
                  OR to_email = "'.$email.'")
                  AND subject LIKE "%'.$lead_id.'%"
                  ORDER BY converted_date_time DESC
                  ) as gc 
                  GROUP BY thread_id ORDER BY converted_date_time DESC
                 ';
        $result = $this->db->query($query);
        if($result->num_rows() > 0){

            return $result;

        }else{

            return false;

        }
    }
    public function getAllEmails($email)
    {

        /*$query = 'SELECT *
                  FROM gmail_conversation
                  WHERE (from_email = "'.$email.'"
                  OR to_email = "'.$email.'")
                  ORDER BY converted_date_time DESC
                 ';*/
        $query = 'SELECT * , COUNT(*) as email_count FROM 
                  (SELECT *
                  FROM gmail_conversation
                  WHERE (from_email = "'.$email.'"
                  OR to_email = "'.$email.'")
                  ORDER BY converted_date_time DESC
                  ) as gc 
                  GROUP BY thread_id ORDER BY converted_date_time DESC
                 ';

        $result = $this->db->query($query);
        if($result->num_rows() > 0){

            return $result;

        }else{

            return false;

        }
    }

    public function getEmailById($id)
    {
        $query = 'SELECT * 
                  FROM gmail_conversation
                  WHERE id = '.$id.'
                 ';
        $result = $this->db->query($query);
        if($result->num_rows() > 0){

            return $result->row();

        }else{

            return false;

        }

    }

    public function getDealInformation($sortColumn = 'vl.created_at',$sort = 'DESC', $limit = 10, $offset = 0){

        $query = '
	        SELECT vl.id, vl.first_name, vl.last_name,vl.email, vl.video_title, vl.reminder_sent, vl.closing_date, vl.status, vl.rating_point, vl.revenue_share,u.status AS ustatus,vl.simple_video
	        FROM video_leads vl
	        INNER JOIN users u
	        ON vl.client_id = u.id
	        AND u.deleted = 0
	        AND u.password IS NOT NULL
	        LEFT JOIN videos v
	        ON v.lead_id = vl.id
	        AND v.deleted = 0
	        AND v.status = 1
	        AND v.lead_id = vl.id
	        WHERE vl.status = 3
	        AND vl.deleted = 0
	        AND vl.information_pending = 0
	       ORDER BY '.$sortColumn.' '.$sort.'
	    ';

        $result = $this->db->query($query);


        return $result;


    }

    public function getAccountCreatedDeals($sortColumn = 'vl.created_at',$sort = 'DESC', $limit = 10, $offset = 0){

        $query = '
	        SELECT vl.id, vl.first_name, vl.last_name,vl.email, vl.video_title, vl.reminder_sent, vl.closing_date, vl.status, vl.rating_point, vl.revenue_share, vl.client_id,u.is_first_time_login,u.status AS ustatus,vl.simple_video
	        FROM video_leads vl
	        INNER JOIN users u
	        ON vl.client_id = u.id
	        AND u.password IS NULL
	        AND u.deleted = 0
	        WHERE vl.status = 3
	        AND vl.deleted = 0 
	        ORDER BY '.$sortColumn.' '.$sort.' LIMIT '.$limit.' OFFSET '.$offset;

        $result = $this->db->query($query);


        return $result;


    }

    public function getDealInformationReceived($sortColumn = 'vl.closing_date',$sort = 'DESC', $limit = 10, $offset = 0, $additional_info = []){

		$action_table_join = '';
		$join_cond = '';
        $adminid = $this->sess->userdata('adminId');
		
		if (isset($additional_info['join_stage_filter']) && $additional_info['join_stage_filter'] == true) {
			$join_cond = ' AND action = "Payment info updated successfully"';
		}		
		
		if ($sortColumn == 'at.created_at') {
			$action_table_join = 'LEFT JOIN action_taken at
								  ON vl.id = at.lead_id '.$join_cond;
		}
        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }

        $query = '
	        SELECT vl.id, vl.client_id,vl.load_view, vl.first_name, vl.last_name,vl.email, vl.video_title, vl.reminder_sent, vl.closing_date, vl.status, vl.rating_point, vl.revenue_share,v.id AS video_id,ad.name staff_name,vl.unique_key,vl.simple_video,vl.report_issue_type,vl.report_issue_desc,vl.scout_resolved
	        FROM video_leads vl
	       /* INNER JOIN users u
	        ON vl.client_id = u.id
	        AND u.deleted = 0
	        AND u.password IS NOT NULL*/
	        INNER JOIN videos v
	        ON vl.id = v.lead_id
			'.$action_table_join.'
	        AND v.deleted = 0
	        '.$staff_check.'
	        AND v.video_verified = 0
	        LEFT JOIN admin ad
	        ON ad.id = vl.staff_id
	        WHERE vl.status = 3
	        AND vl.deleted = 0
	        AND vl.information_pending = 1 
	        AND vl.uploaded_edited_videos = 0
	        AND ( #v.real_deciption_updated = 0
	            v.question_video_taken IS NOT NULL
	            AND v.question_when_video_taken IS NOT NULL
	            AND v.question_video_context IS NOT NULL
	            #AND v.question_video_information IS NOT NULL 
	         )
	        ORDER BY '.$sortColumn.' '.$sort.' LIMIT '.$limit.' OFFSET '.$offset;

        $result = $this->db->query($query);


        return $result;


    }

    public function getDealUploadVideos($sortColumn = 'vl.created_at',$sort = 'DESC', $limit = 10, $offset = 0, $additional_info = [])
    {
		$action_table_join = '';
		$join_cond = '';
        $adminid = $this->sess->userdata('adminId');
		
		if (isset($additional_info['join_stage_filter']) && $additional_info['join_stage_filter'] == true) {
			$join_cond = ' AND action = "Video verified"';
		}		
		
		if ($sortColumn == 'at.created_at') {
			$action_table_join = 'LEFT JOIN action_taken at
								  ON vl.id = at.lead_id '.$join_cond;
		}
        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
		
        $query = '
	        SELECT vl.id, vl.first_name, vl.last_name,vl.email, vl.video_title, vl.reminder_sent, vl.closing_date, vl.status, vl.rating_point, vl.revenue_share,u.is_first_time_login,v.id AS video_id,u.status AS ustatus,v.watermark,v.video_editing_description,ad.name staff_name,vl.unique_key,vl.simple_video,vl.report_issue_type,vl.report_issue_desc
	        FROM video_leads vl
	        INNER JOIN users u
	        ON vl.client_id = u.id
	        AND u.deleted = 0
	        INNER JOIN videos v			
	        ON vl.id = v.lead_id
			'.$action_table_join.'			
	        AND v.deleted = 0
	        AND v.video_verified = 1
	        LEFT JOIN admin ad
	        ON ad.id = vl.staff_id
	        WHERE vl.status = 6
	        AND vl.deleted = 0
	        '.$staff_check.'
	        AND vl.information_pending = 1
	        AND vl.uploaded_edited_videos = 0
	        AND ( v.real_deciption_updated = 1 
	            AND v.question_video_taken IS NOT NULL
	            AND v.question_when_video_taken IS NOT NULL
	            AND v.question_video_context IS NOT NULL
	            #AND v.question_video_information IS NOT NULL 
	         )
	        ORDER BY '.$sortColumn.' '.$sort.' LIMIT '.$limit.' OFFSET '.$offset;

        $result = $this->db->query($query);


        return $result;

    }
    public function getRawVideos()
    {
        $query = 'SELECT lead_id,url,s3_url,client_link,s3_document_url,id
                  FROM raw_video
                 ';
        $result = $this->db->query($query);
        return $result;
    }

    public function getRawVideoByLeadId($id)
    {
        $query = 'SELECT vl.id,rv.video_id,rv.url,rv.lead_id,rv.s3_url,rv.landscape_converted_url,rv.client_link,rv.s3_document_url,rv.id,rv.dropbox_status,rv.video_duration,vl.status, vl.lead_duration
                  FROM raw_video rv
                  LEFT JOIN video_leads vl ON rv.lead_id = vl.id
                  WHERE lead_id = '.$id.'
                 ';
        $result = $this->db->query($query);

        return $result;
    }

    public function getRawVideosByVideoId($id)
    {
        $query = 'SELECT rv.video_id,rv.url,rv.lead_id,rv.s3_url,rv.client_link,rv.s3_document_url,rv.id,rv.dropbox_status,rv.video_duration,vl.status, vl.lead_duration
        FROM raw_video rv
        LEFT JOIN video_leads vl ON rv.lead_id = vl.id
        WHERE rv.video_id = '.$id.'
        ';
        $result = $this->db->query($query);

        return $result;
    }

    public function getDealDistributeVideos($sortColumn = 'vl.closing_date',$sort = 'DESC', $limit = 10, $offset = 0, $additional_info = [])
    {
		$action_table_join = '';
		$join_cond = '';
        $adminid = $this->sess->userdata('adminId');

        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }



		if (isset($additional_info['join_stage_filter']) && $additional_info['join_stage_filter'] == true) {
			$join_cond = ' AND action = "Edited files uploaded"';
		}		
		
		if ($sortColumn == 'at.created_at') {
			$action_table_join = 'LEFT JOIN action_taken at
								  ON vl.id = at.lead_id '.$join_cond;
		}		
		
        $query = '
	        SELECT vl.id, vl.first_name, vl.last_name,vl.email, vl.video_title, vl.unique_key, vl.reminder_sent, vl.closing_date, vl.status, vl.rating_point, vl.revenue_share,vl.published_yt,vl.published_fb,vl.published_portal,u.is_first_time_login,v.id AS video_id,v.facebook_repub,v.youtube_repub,u.status AS ustatus,
				ev.fb_url, ev.yt_url,ad.name staff_name,vl.unique_key,vl.report_issue_type,vl.report_issue_desc,rv.dropbox_status,v.mrss, mp.publication_date mrss_date
				FROM video_leads vl
				INNER JOIN users u
				ON vl.client_id = u.id
				AND u.deleted = 0
				INNER JOIN videos v
				ON vl.id = v.lead_id
				AND v.deleted = 0
				LEFT JOIN edited_video ev ON v.id = ev.video_id 
				'.$action_table_join.'
				LEFT JOIN admin ad
	            ON ad.id = vl.staff_id
                LEFT JOIN raw_video rv
                ON rv.lead_id = vl.id
                LEFT JOIN mrss_publication mp
                ON mp.video_id = v.id
				WHERE vl.status = 6
				AND vl.deleted = 0
				/*'.$staff_check.'*/
				#AND vl.published_yt = 0
				AND vl.information_pending = 1
				AND vl.uploaded_edited_videos = 1
                AND vl.is_cn_updated = 1
				#AND ev.yt_url != " "
				AND ( v.real_deciption_updated = 1
				AND v.question_video_taken IS NOT NULL
				AND v.real_deciption_updated IS NOT NULL
				AND v.question_video_context IS NOT NULL
				#AND v.question_video_information IS NOT NULL
				)
                AND ( vl.published_yt != 1
                OR rv.dropbox_status != "success"
                OR mp.publication_date IS NULL
                )
                GROUP BY vl.id
	        ORDER BY '.$sortColumn.' '.$sort.' LIMIT '.$limit.' OFFSET '.$offset;

            

        $result = $this->db->query($query);

        return $result;

    }
    public function getDealDropboxDistributeVideos($sortColumn = 'vl.closing_date',$sort = 'DESC', $limit = 10, $offset = 0, $additional_info = [])
    {
		$action_table_join = '';
		$join_cond = '';
        $adminid = $this->sess->userdata('adminId');

        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }

		if (isset($additional_info['join_stage_filter']) && $additional_info['join_stage_filter'] == true) {
			//$join_cond = ' AND action = "Edited files uploaded"';
		}		
		
		if ($sortColumn == 'at.created_at') {
			$action_table_join = 'LEFT JOIN action_taken at
								  ON vl.id = at.lead_id '.$join_cond;
		}		
		
        $query = '
	        SELECT vl.id, vl.first_name, vl.last_name,vl.email, vl.video_title, vl.unique_key, vl.reminder_sent, vl.closing_date, vl.status, vl.rating_point, vl.revenue_share,vl.published_yt,vl.published_fb,vl.published_portal,u.is_first_time_login,v.id AS video_id,v.facebook_repub,v.youtube_repub,u.status AS ustatus,
				ev.fb_url, ev.yt_url,ad.name staff_name,vl.unique_key,vl.report_issue_type,vl.report_issue_desc,rv.dropbox_status,v.mrss
				FROM video_leads vl
				INNER JOIN users u
				ON vl.client_id = u.id
				AND u.deleted = 0
				INNER JOIN videos v
				ON vl.id = v.lead_id
				AND v.deleted = 0
				LEFT JOIN edited_video ev ON v.id = ev.video_id 
				'.$action_table_join.'
				LEFT JOIN admin ad
	            ON ad.id = vl.staff_id
                LEFT JOIN raw_video rv
                ON rv.lead_id = vl.id
				WHERE vl.status = 6
				AND vl.deleted = 0
				/*'.$staff_check.'*/
				AND vl.information_pending = 1
				AND vl.uploaded_edited_videos = 1
                AND vl.is_cn_updated = 1
                AND rv.dropbox_status = "success"
				AND ( v.real_deciption_updated = 1
				AND v.question_video_taken IS NOT NULL
				AND v.real_deciption_updated IS NOT NULL
				AND v.question_video_context IS NOT NULL
				#AND v.question_video_information IS NOT NULL
            )
	        ORDER BY '.$sortColumn.' '.$sort.' LIMIT '.$limit.' OFFSET '.$offset;
            

        $result = $this->db->query($query);

        return $result;

    }
    public function getDealMRSSDistributeVideos($sortColumn = 'vl.closing_date',$sort = 'DESC', $limit = 10, $offset = 0, $additional_info = [])
    {
		$action_table_join = '';
		$join_cond = '';
        $adminid = $this->sess->userdata('adminId');

        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
		
		if (isset($additional_info['join_stage_filter']) && $additional_info['join_stage_filter'] == true) {
			//$join_cond = ' AND action = "Youtube Data updated"';
		}		
		
		if ($sortColumn == 'at.created_at') {
			$action_table_join = 'LEFT JOIN action_taken at
								  ON vl.id = at.lead_id '.$join_cond;
		}		
		
        $query = '
	        SELECT vl.id, vl.first_name, vl.last_name,vl.email, vl.video_title, vl.unique_key, vl.reminder_sent, vl.closing_date, vl.status, vl.rating_point, vl.revenue_share,vl.published_yt,vl.published_fb,vl.published_portal,u.is_first_time_login,v.id AS video_id,v.facebook_repub,v.youtube_repub,u.status AS ustatus,
				ev.fb_url, ev.yt_url,ad.name staff_name,vl.unique_key,vl.report_issue_type,vl.report_issue_desc,v.mrss
				FROM video_leads vl
				INNER JOIN users u
				ON vl.client_id = u.id
				AND u.deleted = 0
				INNER JOIN videos v
				ON vl.id = v.lead_id
				AND v.deleted = 0
				LEFT JOIN edited_video ev ON v.id = ev.video_id 
				'.$action_table_join.'
				LEFT JOIN admin ad
	            ON ad.id = vl.staff_id
                INNER JOIN feed_video fv
                ON fv.video_id = v.id
                LEFT JOIN mrss_publication mp
                ON mp.video_id = v.id
				WHERE vl.status = 6
				AND vl.deleted = 0
				'.$staff_check.'
                AND v.mrss = 1
                AND mp.publication_date IS NOT NULL
				AND ( v.real_deciption_updated = 1
				AND v.question_video_taken IS NOT NULL
				AND v.real_deciption_updated IS NOT NULL
				AND v.question_video_context IS NOT NULL
				#AND v.question_video_information IS NOT NULL
				)
                GROUP BY vl.id
	        ORDER BY '.$sortColumn.' '.$sort.' LIMIT '.$limit.' OFFSET '.$offset;


        $result = $this->db->query($query);

        return $result;

    }
    public function getDealUploadEditedVideos($sortColumn = 'default',$sort = 'DESC', $limit = 10, $offset = 0, $additional_info = [])
    {
        $action_table_join = '';
        $join_cond = '';
        $adminid = $this->sess->userdata('adminId');

        if (isset($additional_info['join_stage_filter']) && $additional_info['join_stage_filter'] == true) {
            $join_cond = ' AND action = "Video verified"';
        }

        if ($sortColumn == 'at.created_at') {
            $action_table_join = 'LEFT JOIN action_taken at
								  ON vl.id = at.lead_id '.$join_cond;
        }
        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
        if($sortColumn == 'default') {
            $query = "
                SELECT vl.id, vl.first_name, vl.last_name,vl.email, vl.video_title, vl.reminder_sent, vl.closing_date, vl.status, vl.rating_point, vl.revenue_share, vl.uploaded_edited_videos, vl.is_cn_updated, /*u.is_first_time_login,*/v.id AS video_id,/*u.status AS ustatus,*/v.watermark,v.video_editing_description,ad.name staff_name,vl.unique_key,vl.simple_video,vl.report_issue_type,vl.report_issue_desc,vl.priority
                FROM video_leads vl
                /*INNER JOIN users u
                ON vl.client_id = u.id
                AND u.deleted = 0*/
                INNER JOIN videos v			
                ON vl.id = v.lead_id
                ".$action_table_join."			
                AND v.deleted = 0
                AND v.video_verified = 1
                LEFT JOIN admin ad
                ON ad.id = vl.staff_id
                WHERE vl.status = 6
                AND vl.deleted = 0
                ".$staff_check."
                AND vl.information_pending = 1
                AND vl.priority = 'High'
                AND ( v.real_deciption_updated = 1 
                    AND v.question_video_taken IS NOT NULL
                    AND v.question_when_video_taken IS NOT NULL
                    AND v.question_video_context IS NOT NULL
                    #AND v.question_video_information IS NOT NULL 
                ) AND ( vl.uploaded_edited_videos != 1
                    OR vl.is_cn_updated != 1)
                UNION
                (SELECT vl.id, vl.first_name, vl.last_name,vl.email, vl.video_title, vl.reminder_sent, vl.closing_date, vl.status, vl.rating_point, vl.revenue_share, vl.uploaded_edited_videos, vl.is_cn_updated, /*u.is_first_time_login,*/v.id AS video_id,/*u.status AS ustatus,*/v.watermark,v.video_editing_description,ad.name staff_name,vl.unique_key,vl.simple_video,vl.report_issue_type,vl.report_issue_desc,vl.priority
                FROM video_leads vl
                /*INNER JOIN users u
                ON vl.client_id = u.id
                AND u.deleted = 0*/
                INNER JOIN videos v			
                ON vl.id = v.lead_id
                ".$action_table_join."	
                AND v.deleted = 0
                AND v.video_verified = 1
                LEFT JOIN admin ad
                ON ad.id = vl.staff_id
                WHERE vl.status = 6
                AND vl.deleted = 0
                ".$staff_check."
                AND vl.information_pending = 1
                AND vl.priority != 'High'
                AND ( v.real_deciption_updated = 1 
                    AND v.question_video_taken IS NOT NULL
                    AND v.question_when_video_taken IS NOT NULL
                    AND v.question_video_context IS NOT NULL
                    #AND v.question_video_information IS NOT NULL 
                ) AND ( vl.uploaded_edited_videos != 1
                    OR vl.is_cn_updated != 1)
                ORDER BY vl.closing_date DESC) LIMIT ".$limit." OFFSET ".$offset;
        }
        else {
            $query = '
                SELECT vl.id, vl.first_name, vl.last_name,vl.email, vl.video_title, vl.reminder_sent, vl.closing_date, vl.status, vl.rating_point, vl.revenue_share, vl.uploaded_edited_videos, vl.is_cn_updated, /*u.is_first_time_login,*/v.id AS video_id,/*u.status AS ustatus,*/v.watermark,v.video_editing_description,ad.name staff_name,vl.unique_key,vl.simple_video,vl.report_issue_type,vl.report_issue_desc,vl.priority
                FROM video_leads vl
                /*INNER JOIN users u
                ON vl.client_id = u.id
                AND u.deleted = 0*/
                INNER JOIN videos v			
                ON vl.id = v.lead_id
                '.$action_table_join.'			
                AND v.deleted = 0
                AND v.video_verified = 1
                LEFT JOIN admin ad
                ON ad.id = vl.staff_id
                WHERE vl.status = 6
                AND vl.deleted = 0
                '.$staff_check.'
                AND vl.information_pending = 1
                AND ( v.real_deciption_updated = 1 
                    AND v.question_video_taken IS NOT NULL
                    AND v.question_when_video_taken IS NOT NULL
                    AND v.question_video_context IS NOT NULL
                    #AND v.question_video_information IS NOT NULL 
                ) AND ( vl.uploaded_edited_videos != 1
                    OR vl.is_cn_updated != 1)';
                if ($sortColumn == 'vl.priority')
                {
                    $query = $query." ORDER BY CASE WHEN priority = 'High' then 1 WHEN priority = 'Medium' then 2 WHEN priority = 'Low' then 3 END ASC LIMIT ".$limit." OFFSET ".$offset;
                }
                else if ($sortColumn != 'vl.created_at')
                {
                    $query = $query.' ORDER BY '.$sortColumn.' '.$sort.', vl.created_at DESC LIMIT '.$limit.' OFFSET '.$offset;
                }
                else
                {
                    $query = $query.' ORDER BY '.$sortColumn.' '.$sort.' LIMIT '.$limit.' OFFSET '.$offset;
                }
        }

        $result = $this->db->query($query);

        return $result;

    }
    public function getDealUploadEditedVideosCount()
    {
        $adminid = $this->sess->userdata('adminId');

        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
        $query = '
	        SELECT vl.id, vl.first_name, vl.last_name,vl.email, vl.video_title, vl.reminder_sent, vl.closing_date, vl.status, vl.rating_point, vl.revenue_share, vl.uploaded_edited_videos, vl.is_cn_updated, /*u.is_first_time_login,*/v.id AS video_id,/*u.status AS ustatus,*/v.watermark,v.video_editing_description,ad.name staff_name,vl.unique_key,vl.simple_video,vl.report_issue_type,vl.report_issue_desc,vl.priority
	        FROM video_leads vl
	        /*INNER JOIN users u
	        ON vl.client_id = u.id
	        AND u.deleted = 0*/
	        INNER JOIN videos v			
	        ON vl.id = v.lead_id		
	        AND v.deleted = 0
	        AND v.video_verified = 1
	        LEFT JOIN admin ad
	        ON ad.id = vl.staff_id
	        WHERE vl.status = 6
	        AND vl.deleted = 0
	        '.$staff_check.'
	        AND vl.information_pending = 1
	        AND ( v.real_deciption_updated = 1 
	            AND v.question_video_taken IS NOT NULL
	            AND v.question_when_video_taken IS NOT NULL
	            AND v.question_video_context IS NOT NULL
	            #AND v.question_video_information IS NOT NULL 
	        ) AND ( vl.uploaded_edited_videos != 1
                OR vl.is_cn_updated != 1)';

        return $this->db->query($query)->num_rows();

    }

    public function getDealYTDistributeVideos($sortColumn = 'vl.closing_date',$sort = 'DESC', $limit = 10, $offset = 0, $additional_info = [])
    {
		$action_table_join = '';
		$join_cond = '';
        $adminid = $this->sess->userdata('adminId');

        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
		
		if (isset($additional_info['join_stage_filter']) && $additional_info['join_stage_filter'] == true) {
			$join_cond = ' AND action = "Youtube Data updated"';
		}		
		
		if ($sortColumn == 'at.created_at') {
			$action_table_join = 'LEFT JOIN action_taken at
								  ON vl.id = at.lead_id '.$join_cond;
		}		
		
        $query = '
	        SELECT vl.id, vl.first_name, vl.last_name,vl.email, vl.video_title, vl.unique_key, vl.reminder_sent, vl.closing_date, vl.status, vl.rating_point, vl.revenue_share,vl.published_yt,vl.published_fb,vl.published_portal,u.is_first_time_login,v.id AS video_id,v.facebook_repub,v.youtube_repub,u.status AS ustatus,
				ev.fb_url, ev.yt_url,ad.name staff_name,vl.unique_key,vl.report_issue_type,vl.report_issue_desc
				FROM video_leads vl
				INNER JOIN users u
				ON vl.client_id = u.id
				AND u.deleted = 0
				INNER JOIN videos v
				ON vl.id = v.lead_id
				AND v.deleted = 0
				LEFT JOIN edited_video ev ON v.id = ev.video_id 
				'.$action_table_join.'
				LEFT JOIN admin ad
	            ON ad.id = vl.staff_id
				WHERE vl.status = 6
				AND vl.deleted = 0
				'.$staff_check.'
				AND vl.published_yt = 1
				AND vl.information_pending = 1
				AND vl.uploaded_edited_videos = 1
                AND vl.is_cn_updated = 1
				AND v.youtube_id != " "
                AND v.youtube_id != ""
				AND ( v.real_deciption_updated = 1
				AND v.question_video_taken IS NOT NULL
				AND v.real_deciption_updated IS NOT NULL
				AND v.question_video_context IS NOT NULL
				#AND v.question_video_information IS NOT NULL
				)
	        ORDER BY '.$sortColumn.' '.$sort.' LIMIT '.$limit.' OFFSET '.$offset;


        $result = $this->db->query($query);

        return $result;

    }

    public function getDealWon($search = '',$start = 0,$limit = 0,$orderby = 'vl.id DESC',$colums = ''){
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
	        SELECT vl.id,vl.status,`video_title`,`video_url`,`unique_key`,`email`,published_yt,youtube_id,published_fb,facebook_id ,ac.created_at 
	        FROM video_leads vl 
	        INNER JOIN videos 
	        ON videos.lead_id= vl.id 
	        INNER JOIN action_taken ac 
	        ON ac.lead_id = vl.id 
	        WHERE vl.status > 3 
	        AND vl.status != 11 
	        AND vl.status != 10 
	        AND vl.deleted = 0 
	        AND ac.action = "Info updated successfully"
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
    public function getDealLost($sortColumn = 'vl.created_at',$sort = 'DESC'){

        $query = '
	        SELECT vl.*,u.is_first_time_login,u.status AS ustatus, MAX(act.created_at) as last_activity
	        FROM video_leads vl
	        INNER JOIN users u
	        ON vl.client_id = u.id
	        AND u.deleted = 0
	        INNER JOIN videos v
	        ON vl.id = v.lead_id
	        AND v.deleted = 0
	        INNER JOIN action_taken act
            ON vl.id = act.lead_id
	        WHERE vl.status = 9
	        AND vl.deleted = 0
	        GROUP BY act.lead_id
	        ORDER BY '.$sortColumn.' '.$sort.'
	    ';

        $result = $this->db->query($query);


        return $result;


    }

    public function getNotInterested($sortColumn = 'vl.created_at',$sort = 'DESC'){

        $query = '
	        SELECT vl.*,u.is_first_time_login,u.status AS ustatus, MAX(act.created_at) as last_activity
	        FROM video_leads vl
	        left JOIN users u
	        ON vl.client_id = u.id
	        AND u.deleted = 0
	        left JOIN videos v
	        ON vl.id = v.lead_id
	        AND v.deleted = 0
	        INNER JOIN action_taken act
            ON vl.id = act.lead_id
	        WHERE vl.status = 11
	        AND vl.deleted = 0
	        GROUP BY act.lead_id
	        ORDER BY '.$sortColumn.' '.$sort.'
	    ';

        $result = $this->db->query($query);


        return $result;


    }

	public function getEditedVideos()
	{
		$query = '
	        SELECT group_concat(v.id separator \',\') as id
	        FROM video_leads vl
	        INNER JOIN users u
	        ON vl.client_id = u.id
	        AND u.deleted = 0
	        INNER JOIN videos v
	        ON vl.id = v.lead_id
	        AND v.deleted = 0
	        WHERE vl.status = 6
	        AND vl.deleted = 0
	        AND vl.information_pending = 1
	        AND vl.uploaded_edited_videos = 1
	        AND ( v.real_deciption_updated = 1 
	            AND v.question_video_taken IS NOT NULL
	            AND v.real_deciption_updated IS NOT NULL
	            AND v.question_video_context IS NOT NULL
	            #AND v.question_video_information IS NOT NULL 
	         )
	    ';

		$result = $this->db->query($query);
		$data  = $result->row_array();
		$data['id'] = rtrim($data['id'],',');
		if($data){
            $edited_query = 'SELECT video_id, fb_url, yt_url from edited_video WHERE video_id IN ('.$data['id'].') ';
        }else{
            $edited_query='';
        }
		$res = $this->db->query($edited_query);
		$data = $res->result_array();
		$data_array = array();
		foreach($data as $list){
			$data_array[$list['video_id']]['fb_url'] = $list['fb_url'];
			$data_array[$list['video_id']]['yt_url'] = $list['yt_url'];
		}

		return $data_array;

	}
	public function getEditedVideoById($video_id){
		$edited_query = 'SELECT * from edited_video WHERE video_id IN ('.$video_id.') GROUP BY video_id ';
		$res = $this->db->query($edited_query);
		return $res;
	}

    public function getCancelContract()
    {
        $query = 'SELECT * 
                  FROM `video_leads` 
                  WHERE `deleted` = 1 
                 ';
        $result = $this->db->query($query);

        return $result;
    }

    public function getVideoByLeadId($id,$fields='*'){

        if(is_array($fields)){

            $fields = implode(',',$fields);

        }

        $query = '
			SELECT '.$fields.'
		    FROM videos v
		    WHERE v.lead_id = '.$id.'
		';

        $result = $this->db->query($query);

        if($result->num_rows() > 0){

            return $result->row();

        }else{

            return false;

        }


    }

    public function getDealEmailNotifictionHIstory($lead_id){

        $query = '
	        SELECT enh.id,enh.lead_id,enh.email_template_id,enh.email_title,DATE_FORMAT(enh.send_datime,"%a %m/%d/%Y") as send_date,enh.ids
	        FROM email_notification_history enh
	        WHERE enh.lead_id = '.$lead_id.'
	        ORDER BY enh.send_datime DESC
	    ';

        $result = $this->db->query($query);


        return $result;

    }

    public function getWatermarkVideo($id)
    {
       $query = 'SELECT *
                  FROM watermark_videos
                  WHERE video_id = '.$id.'
                  ';
        $result = $this->db->query($query);

        if($result->num_rows() > 0){
            return $result->row();
        }
        else{
            return false;
        }


    }

    public function getPortalVideo($id)
    {
       $query = 'SELECT *
                  FROM edited_video
                  WHERE video_id = '.$id.'
                  ';
        $result = $this->db->query($query);

        if($result->num_rows() > 0){
            return $result->row();
        }
        else{
            return false;
        }


    }


    public function updatePortalUrl($data,$id)
    {
       $query = 'UPDATE videos
                 SET url = "'.$data['url'].'",
                 thumbnail =  "'.$data['thumbnail'].'"
                 WHERE id = '.$id.'
                ';
        $result = $this->db->query($query);
        return $result;
    }

    public function updateStatus($id)
    {
        $query = 'UPDATE video_leads
                 SET published_portal = 1
                 WHERE id = '.$id.'
                ';
        $result = $this->db->query($query);
        return $result;
    }

    public function updateYoutubeStatus($id)
    {
        $query = 'UPDATE video_leads
                 SET published_yt = 1
                 WHERE id = '.$id.'
                ';
        $result = $this->db->query($query);
        return $result;
    }
    public function updateFacebookStatus($id)
    {
        $query = 'UPDATE video_leads
                 SET published_fb = 1
                 WHERE id = '.$id.'
                ';
        $result = $this->db->query($query);
        return $result;
    }

    public function getPublishData($video_id,$type){

        $query = 'SELECT *
                  FROM video_publishing_scheduling
                  WHERE video_id = '.$video_id.'
                  AND publish_type = "'.$type.'"
                  ';
        $result = $this->db->query($query);

        if($result->num_rows() > 0){
            return $result->row();
        }
        else{
            return false;
        }
    }

    public function getEmailData($id){

        $query = 'SELECT *
                  FROM gmail_conversation
                  WHERE `thread_id` = "'.$id.'" ORDER BY `converted_date_time` ASC
                  ';
        $result = $this->db->query($query);

        if($result->num_rows() > 0){
            return $result->result();
        }
        else{
            return false;
        }
    }

    public function dealStatusChangeFromDistributeToWon($lead_id){
        $deal = $this->getDealById($lead_id);
        if($deal){
            if($deal->published_portal == 1 && $deal->published_yt == 1 && $deal->published_fb == 1){
                $data = array(
                    'status' => 8
                );

                $this->db->where('id', $lead_id);
                $this->db->update('video_leads',$data);
            }
        }
        return true;
    }

    public function getLastActivityByLeadId($lead_id){

	    $result = $this->db->query('
	            SELECT DATE_FORMAT(created_at,"%a %m/%d/%Y") as created_at,
	            `action`
	            FROM action_taken
	            WHERE lead_id = '.$lead_id.'
	            ORDER BY id DESC
	            LIMIT 0,1 
	            
	    ');

	    if($result->num_rows() > 0){

	        return $result->row();

        }else{

	        return false;

        }

    }
	public function getUniquekeyByVideoId($video_id){
		$query = 'select unique_key from video_leads vl, videos v where v.lead_id=vl.id and v.id='.$video_id;
		$result = $this->db->query($query);
		if($result->num_rows() > 0){

			return $result->row();

		}else{

			return false;

		}
	}


    public function updateResComm($id,$resComm){
        $query = '
            SELECT * 
            FROM deal_comments 
            WHERE lead_id='.$id;
        $result = $this->db->query($query);
        if($result->num_rows() > 0)
        {
            $query = '
                UPDATE deal_comments
                SET researcher_comment='.$this->db->escape($resComm).'
                WHERE lead_id = '.$id;
            $result = $this->db->query($query);
        }
        else {
            $query = '
                INSERT INTO deal_comments (lead_id, researcher_comment)
                VALUES ('.$id.', '.$this->db->escape($resComm).')';
            $result = $this->db->query($query);
        }
    }

    public function updateMgrComm($id,$mgrComm){
        $query = '
            SELECT * 
            FROM deal_comments 
            WHERE lead_id='.$id;
        $result = $this->db->query($query);
        if($result->num_rows() > 0)
        {
            $query = '
                UPDATE deal_comments
                SET manager_comment='.$this->db->escape($mgrComm).'
                WHERE lead_id = '.$id;
            $result = $this->db->query($query);
        }
        else {
            $query = '
                INSERT INTO deal_comments (lead_id, manager_comment)
                VALUES ('.$id.', '.$this->db->escape($mgrComm).')';
            $result = $this->db->query($query);
        }
    }

    public function addCommentAttachment($lead_id, $wgid){
        $query = '
            SELECT * 
            FROM deal_comments 
            WHERE lead_id='.$lead_id;
        $result = $this->db->query($query);
        if($result->num_rows() > 0)
        {
            $query = '
                UPDATE deal_comments
                SET local_url='.$this->db->escape("uploads/".$wgid."/attachments").'
                WHERE lead_id = '.$lead_id;
            $result = $this->db->query($query);
        }
        else {
            $query = '
                INSERT INTO deal_comments (lead_id, local_url)
                VALUES ('.$lead_id.', '.$this->db->escape("uploads/".$wgis."/attachments").')';
            $result = $this->db->query($query);
        }
    }
    
    public function updateclosingdate($id,$updatedate){
        $query = '
			UPDATE video_leads
			SET closing_date="'.$updatedate.'"
			WHERE id = '.$id;
        $result = $this->db->query($query);

        return $result;
    }
    public function updaterevenue($id,$updaterevenue){
        $query = '
			UPDATE video_leads
			SET revenue_share="'.$updaterevenue.'"
			WHERE id = '.$id;
        $result = $this->db->query($query);

        return $result;
    }
    public function updatetitle($id,$updatetitle){
        $query = '
			UPDATE video_leads
			SET video_title="'.$updatetitle.'"
			WHERE id = '.$id;
        $title_check_query='SELECT * FROM `videos` WHERE `lead_id` ='.$id;
        if($title_check_query){
            $video_tile_query = '
            UPDATE videos
            SET title
			='.$this->db->escape($updatetitle).'
			WHERE lead_id = '.$id;
            $result_video = $this->db->query($video_tile_query);
        }
        $result = $this->db->query($query);

        return $result;
    }
    public function updatetitle2($id,$updatetitle){
        $query = 'SELECT video_title_2 FROM video_leads WHERE id = '.$id;
        $result = $this->db->query($query);
        if ($result){
            // dd($result);exit();
            // $row = $result->fetch_assoc();
            // dd($row);exit();
            // $videoTitle2 = $row['video_title_2'];
            // dd($videoTitle2);exit();
            // if ($videoTitle2 != NULL && strlen($videoTitle2) != 0){
                // return TRUE;
            // }
        }
        $query = '
			UPDATE video_leads
			SET video_title_2='.$this->db->escape($updatetitle).'
			WHERE id = '.$id;
        $result = $this->db->query($query);

        return $result;
    }
    public function updateTargets($id,$update_targets){
        $query = '
			UPDATE videos
			SET seo_keywords="'.$update_targets.'"
			WHERE lead_id = '.$id;
        $result = $this->db->query($query);

        return $result;
    }
    public function updateurl($id,$updatetitle){
        $query = '
			UPDATE video_leads
			SET video_url="'.$updatetitle.'"
			WHERE id = '.$id;
        $title_check_query='SELECT * FROM `videos` WHERE `lead_id` ='.$id;
        if($title_check_query){
            $video_tile_query = '
			UPDATE videos
			SET url="'.$this->db->escape($updatetitle).'"
			WHERE lead_id = '.$id;
            $result_video = $this->db->query($video_tile_query);
        }
        $result = $this->db->query($query);

        return $result;
    }

    public function update_video_url($id,$url){
        $query = '
			UPDATE video_leads
			SET video_url="'.$url.'"
			WHERE id = '.$id;
        $title_check_query='SELECT * FROM `videos` WHERE `lead_id` ='.$id;
        if($title_check_query){
            $video_tile_query = '
			UPDATE videos
			SET url="'.$this->db->escape($url).'"
			WHERE lead_id = '.$id;
            $result_video = $this->db->query($video_tile_query);
        }
        $result = $this->db->query($query);

        return $result;
    }

    public function updatedes($id,$updatedes){
        $query = '
			UPDATE videos
			SET description='.$this->db->escape($updatedes).'
			WHERE lead_id = '.$id;
        $result = $this->db->query($query);

        return $result;
    }
    public function updateLeadEmail($id,$updatedes){
        $query = "
			UPDATE video_leads
			SET email='".$updatedes."'
			WHERE id = ".$id;
        $result = $this->db->query($query);
        
        $lead = $this->getDealById($id);
        if($lead){
            $query = "
			UPDATE users
			SET email='".$updatedes."'
			WHERE id = ".$lead->client_id;
        }

        return $result;
    }
    public function updatetags($id,$updatetags){
        $query = '
			UPDATE videos
			SET tags="'.$updatetags.'"
			WHERE lead_id = '.$id;
        $result = $this->db->query($query);

        return $result;
    }
    public function updatemessage($id,$updatemessage){
        $query = '
			UPDATE video_leads
			SET message="'.$updatemessage.'"
			WHERE id = '.$id;
        $result = $this->db->query($query);

        return $result;
    }
    public function updateratings($id,$updateratings){
        $query = '
			UPDATE video_leads
			SET rating_point="'.$updateratings.'"
			WHERE id = '.$id;
        $result = $this->db->query($query);

        return $result;
    }
    public function updateratingcomment($id,$updateratingcomment){
        $query = '
			UPDATE video_leads
			SET rating_comments="'.$updateratingcomment.'"
			WHERE id = '.$id;
        $result = $this->db->query($query);

        return $result;
    }
    public function updatedealmrss($id,$updatemrss){
        $query = '
			UPDATE videos
			SET mrss_categories="'.$updatemrss.'"
			WHERE lead_id = '.$id;
        $mrss_check_query='SELECT mrss FROM `videos` WHERE `lead_id` ='.$id;
        if($mrss_check_query == 0){
            $video_mrss_query = '
			UPDATE videos
			SET mrss="1"
			WHERE lead_id = '.$id;
            $result_mrss = $this->db->query($video_mrss_query);
        }
        $result = $this->db->query($query);

        return $result;
    }

    public function updateq0($id,$updatename){
        
        // $name = trim($updatename);
        // $lastname = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
        // $firstname = trim( preg_replace('#'.preg_quote($lastname,'#').'#', '', $name ) );
        $updatename = trim($updatename);
        $parts = explode(" ", $updatename);
        if(count($parts) > 1) {
            $lastname = array_pop($parts);
            $firstname = implode(" ", $parts);
        }
        else
        {
            $firstname = $updatename;
            $lastname = " ";
        }

        $query = '
			UPDATE second_signer
			SET first_name="'.$firstname.'", last_name="'.$lastname.'"
			WHERE uid = (SELECT unique_key FROM video_leads WHERE id = '.$id.' LIMIT 1)';
        $result = $this->db->query($query);

        return $this->db->affected_rows();
    }

    public function updateq1($id,$updatedate){
        $query = '
			UPDATE videos
			SET question_when_video_taken="'.$updatedate.'"
			WHERE lead_id = '.$id;
        $result = $this->db->query($query);

        return $result;
    }

    public function updateq2($id,$updatedate){
        $query = '
			UPDATE videos
			SET question_video_taken="'.$updatedate.'"
			WHERE lead_id = '.$id;
        $result = $this->db->query($query);

        return $result;
    }

    public function updateq3($id,$updatedate){
        $query = '
			UPDATE videos
			SET 	question_video_context="'.$updatedate.'"
			WHERE lead_id = '.$id;
        $result = $this->db->query($query);

        return $result;
    }
    public function updatefacebook($id,$updatefacebook){

        $query_video = '
			UPDATE videos
			SET facebook_id="'.$updatefacebook.'", facebook_repub=0
			WHERE lead_id = '.$id;
        $result_video = $this->db->query($query_video);
        $video_id_query = 'SELECT id,title,description,youtube_id FROM `videos` WHERE `lead_id` = '.$id;
        $result_vid = $this->db->query($video_id_query);
        $result_array=$result_vid->result();
        $vid_id=$result_array[0]->id;
        $vid_title=$result_array[0]->title;
        $vid_desc=$result_array[0]->description;
        $vid_youtube_id=$result_array[0]->youtube_id;
        $pub_time=date("Y-m-d h:i:s");

        $video_id_edited_check_query = 'SELECT id,portal_thumb FROM `edited_video` WHERE `video_id` ='.$vid_id;
        $result_edited = $this->db->query($video_id_edited_check_query);
        $result_edited_array=$result_edited->result();
        $edited_id='';
        $edited_portal_thumb='';
        if(isset($result_edited_array[0])){
            $edited_id=$result_edited_array[0]->id;
            $edited_portal_thumb=$result_edited_array[0]->portal_thumb;
        }
        if($edited_id){
            if($vid_youtube_id && $edited_portal_thumb){
                $query_lead = '
			UPDATE video_leads
			SET published_fb=1,status=8
			WHERE id = '.$id;
            }else{
                $query_lead = '
			UPDATE video_leads
			SET published_fb=1
			WHERE id = '.$id;
            }
            $result_lead = $this->db->query($query_lead);
        }else{
            $query_lead = '
			UPDATE video_leads
			SET published_fb=1
			WHERE id = '.$id;
            $result_lead = $this->db->query($query_lead);
            $query_edited = '
			INSERT INTO `edited_video` (`video_id`, `fb_url`, `yt_url`, `fb_thumb`, `yt_thumb`) 
			VALUES ('.$vid_id.', \'Manual Upload\', \'Manual Upload\', \'Manual Upload\', \'Manual Upload\');';
            $result_edited = $this->db->query($query_edited );
        }
        if($edited_portal_thumb){
            $query_portal_thumb = '
			UPDATE video_leads
			SET uploaded_edited_videos=1
			WHERE id = '.$id;
            $result_portal_thumb = $this->db->query($query_portal_thumb);
        }
        $video_id_schudul_check_query = 'SELECT id FROM `video_publishing_scheduling` WHERE `video_id` ='.$vid_id.' AND publish_type = "FaceBook"';
        $result_schudul = $this->db->query($video_id_schudul_check_query);
        $result_schudul_array=$result_schudul->result();
        $schedul_id='';
        if(isset($result_schudul_array[0])){
            $schedul_id=$result_schudul_array[0]->id;
        }
        if($schedul_id){
            $query_scheduling = '
			UPDATE video_publishing_scheduling
			SET publish_now=1,published=1
			WHERE id = '.$schedul_id;
        }else{
            $query_scheduling = '
			INSERT INTO `video_publishing_scheduling` (`video_id`, `publish_type`, `publish_now`, `repeat_post`, `published`, `publish_datetime`, `video_title`, `video_description`, `youtube_category`, `youtube_publish_status`) 
			VALUES ('.$vid_id.', \'FaceBook\', \'1\', \'0\', \'1\', "'.$pub_time.'", "'.$vid_title.'", "'.$vid_desc.'",\'0\', \'unlisted\');';
        }
        if($result_lead && $result_video && $result_edited){
            $result = $this->db->query($query_scheduling );
        }
        return $result;
    }
    public function updateyoutube($id,$updateyoutube){
        $query_video = '
			UPDATE videos
			SET youtube_id="'.$updateyoutube.'", youtube_repub=0
			WHERE lead_id = '.$id;
        $result_video = $this->db->query($query_video);
        $video_id_query = 'SELECT id,title,description,facebook_id FROM `videos` WHERE `lead_id` = '.$id;
        $result_vid = $this->db->query($video_id_query);
        $result_array=$result_vid->result();
        $vid_id=$result_array[0]->id;
        $vid_title=$result_array[0]->title;
        $vid_desc=$result_array[0]->description;
        $vid_fb_id=$result_array[0]->facebook_id;
        $pub_time=date("Y-m-d h:i:s");

        $video_id_edited_check_query = 'SELECT id,fb_url,portal_thumb FROM `edited_video` WHERE `video_id` ='.$vid_id;
        $result_edited = $this->db->query($video_id_edited_check_query);
        $result_edited_array=$result_edited->result();
        $edited_id='';
        $edited_portal_thumb='';
        if(isset($result_edited_array[0])){
            $edited_id=$result_edited_array[0]->id;
            $edited_fb_url=$result_edited_array[0]->fb_url;
            $edited_portal_thumb=$result_edited_array[0]->portal_thumb;
        }
        if($edited_id){
            if($vid_fb_id && $edited_portal_thumb){
                $query_lead = '
			UPDATE video_leads
			SET published_yt=1,status=8
			WHERE id = '.$id;
            } elseif (empty($edited_fb_url) && $edited_portal_thumb) {
            	$query_lead = '
			UPDATE video_leads
			SET published_yt=1,status=8
			WHERE id = '.$id;
            } else{
                $query_lead = '
			UPDATE video_leads
			SET published_yt=1
			WHERE id = '.$id;
            }
            $result_lead = $this->db->query($query_lead);
        }else{
            $query_lead = '
			UPDATE video_leads
			SET published_yt=1
			WHERE id = '.$id;
            $result_lead = $this->db->query($query_lead);
            $query_edited = '
			INSERT INTO `edited_video` (`video_id`, `fb_url`, `yt_url`, `fb_thumb`, `yt_thumb`) 
			VALUES ('.$vid_id.', \'Manual Upload\', \'Manual Upload\', \'Manual Upload\', \'Manual Upload\');';
            $result_edited = $this->db->query($query_edited );
        }
        if($edited_portal_thumb){
            $query_portal_thumb = '
			UPDATE video_leads
			SET uploaded_edited_videos=1
			WHERE id = '.$id;
            $result_portal_thumb = $this->db->query($query_portal_thumb);
        }

        $video_id_schudul_check_query = 'SELECT id FROM `video_publishing_scheduling` WHERE `video_id` ='.$vid_id.' AND publish_type = "YouTube"';
        $result_schudul = $this->db->query($video_id_schudul_check_query);
        $result_schudul_array=$result_schudul->result();
        $schedul_id='';
        if(isset($result_schudul_array[0])){
            $schedul_id=$result_schudul_array[0]->id;
        }
        if($schedul_id){
            $query_scheduling = '
			UPDATE video_publishing_scheduling
			SET publish_now=1,published=1
			WHERE id = '.$schedul_id;
        }else{
            $query_scheduling = '
			INSERT INTO `video_publishing_scheduling` (`video_id`, `publish_type`, `publish_now`, `repeat_post`, `published`, `publish_datetime`, `video_title`, `video_description`,`youtube_channel`, `youtube_category`, `youtube_publish_status`) 
			VALUES ('.$vid_id.', \'YouTube\', \'1\', \'0\', \'1\', "'.$pub_time.'", "'.$vid_title.'", "'.$vid_desc.'",\'UCbbtHuBeqqlRB9yNr_Hpc7w\',\'1\', \'unlisted\');';
        }

        if($result_lead && $result_video && $result_edited){
            $result = $this->db->query($query_scheduling );
        }
        return $result;
    }
    public function updateraws3($id,$updateraw){

        $video_id_query = 'SELECT id FROM `videos` WHERE `lead_id` = '.$id;
        $result_vid = $this->db->query($video_id_query);
        $result_array=$result_vid->result();
        $vid_id=$result_array[0]->id;
        $video_id_raw_video_check_query = 'SELECT id,url FROM `raw_video` WHERE `video_id` ='.$vid_id;
        $result_raw_video = $this->db->query($video_id_raw_video_check_query);
        $result_raw_video_array=$result_raw_video->result();
        $raw_video_id='';
        if(isset($result_raw_video_array[0])){
            $raw_video_id=$result_raw_video_array[0]->id;
            $raw_video_url=$result_raw_video_array[0]->url;
        }
        if($raw_video_id){
            $raw_delete_query = 'DELETE FROM `raw_video` WHERE `lead_id` = '.$id;
            $result_del = $this->db->query($raw_delete_query);
            $query_raw_upload = '
			INSERT INTO `raw_video` (`video_id`, `lead_id`, `url`, `s3_url`) 
			VALUES ('.$vid_id.', '.$id.', "'.$raw_video_url.'", "'.$updateraw.'");';
        }else{
            $query_raw_upload = '
			INSERT INTO `raw_video` (`video_id`, `lead_id`, `url`, `s3_url`) 
			VALUES ('.$vid_id.', '.$id.', \'Manual Upload\', "'.$updateraw.'");';
        }
        $result = $this->db->query($query_raw_upload);
        return $vid_id;
    }
    public function updatedocs3($id,$updatedoc){

        $video_id_query = 'SELECT id FROM `videos` WHERE `lead_id` = '.$id;
        $result_vid = $this->db->query($video_id_query);
        $result_array=$result_vid->result();
        $vid_id=$result_array[0]->id;
        $video_id_raw_video_check_query = 'SELECT id,url FROM `raw_video` WHERE `video_id` ='.$vid_id;
        $result_raw_video = $this->db->query($video_id_raw_video_check_query);
        $result_raw_video_array=$result_raw_video->result();
        $raw_video_id='';
        if(isset($result_raw_video_array[0])){
            $raw_video_id=$result_raw_video_array[0]->id;
            $raw_video_url=$result_raw_video_array[0]->url;
        }
        if($raw_video_id){
            $query_raw_upload = 'UPDATE raw_video
			SET s3_document_url="'.$updatedoc.'"
			WHERE lead_id = '.$id;
        }else{
            $query_raw_upload = '
			INSERT INTO `raw_video` (`video_id`, `lead_id`, `url`, `s3_document_url`) 
			VALUES ('.$vid_id.', '.$id.', \'Manual Upload\', "'.$updatedoc.'");';
        }
        $result = $this->db->query($query_raw_upload);
        return $vid_id;
    }
    public function updateediteds3($id,$updateediteds3){

        $video_id_query = 'SELECT id FROM `videos` WHERE `lead_id` = '.$id;
        $result_vid = $this->db->query($video_id_query);
        $result_array=$result_vid->result();
        $vid_id=$result_array[0]->id;
        $video_id_edited_video_check_query = 'SELECT id,portal_thumb FROM `edited_video` WHERE `video_id` ='.$vid_id;
        $result_edited_video = $this->db->query($video_id_edited_video_check_query);
        $result_edited_video_array=$result_edited_video->result();
        $editeds3_video_id='';
        $edited_portal_thumb='';
        if(isset($result_edited_video_array[0])){
            $editeds3_video_id=$result_edited_video_array[0]->id;
            $edited_portal_thumb=$result_edited_video_array[0]->portal_thumb;
        }
        if($editeds3_video_id){
            $query_editeds3_upload = '
			UPDATE edited_video
			SET portal_url="'.$updateediteds3.'"
			WHERE id = '.$editeds3_video_id;
        }else{
            $query_editeds3_upload = '
			INSERT INTO `edited_video` (`video_id`, `fb_url`, `yt_url`, `portal_url`, `fb_thumb`, `yt_thumb`) 
			VALUES ('.$vid_id.', \'Manual Upload\', \'Manual Upload\', "'.$updateediteds3.'", \'Manual Upload\', \'Manual Upload\');';

        }
        if($edited_portal_thumb){
            $query_portal_thumb = '
			UPDATE video_leads
			SET uploaded_edited_videos=1
			WHERE id = '.$id;
            $result_portal_thumb = $this->db->query($query_portal_thumb);
        }
        $result = $this->db->query($query_editeds3_upload);
        return $result;
    }
    public function updatethumbs3($id,$updatethumbs3){

        $video_id_query = 'SELECT id,facebook_id,youtube_id FROM `videos` WHERE `lead_id` = '.$id;
        $result_vid = $this->db->query($video_id_query);
        $result_array=$result_vid->result();
        $vid_id=$result_array[0]->id;
        $vid_facebook_id=$result_array[0]->facebook_id;
        $vid_youtube_id=$result_array[0]->youtube_id;
        $video_id_edited_thumb_check_query = 'SELECT id FROM `edited_video` WHERE `video_id` ='.$vid_id;
        $result_edited_thumb = $this->db->query($video_id_edited_thumb_check_query);
        $result_edited_thumb_array=$result_edited_thumb->result();
        $editeds3_thumb_id='';
        if(isset($result_edited_thumb_array[0])){
            $editeds3_thumb_id=$result_edited_thumb_array[0]->id;
        }
        if($editeds3_thumb_id){
            $query_editeds3_upload = '
			UPDATE edited_video
			SET portal_thumb="'.$updatethumbs3.'"
			WHERE id = '.$editeds3_thumb_id;
        }else{
            $query_editeds3_upload = '
			INSERT INTO `edited_video` (`video_id`,`fb_url`,`yt_url`,`portal_url`,`fb_thumb`,`yt_thumb`,`portal_thumb`) 
			VALUES ('.$vid_id.', \'Manual Upload\', \'Manual Upload\', \'Manual Upload\', \'Manual Upload\', \'Manual Upload\',"'.$updatethumbs3.'");';

        }
            $query_portal_thumb = '
			UPDATE video_leads
			SET uploaded_edited_videos=1
			WHERE id = '.$id;
            $result_portal_thumb = $this->db->query($query_portal_thumb);
        if($vid_facebook_id && $vid_youtube_id){
            $query_portal_thumb = '
			UPDATE video_leads
			SET status=8
			WHERE id = '.$id;
            $result_portal_thumb = $this->db->query($query_portal_thumb);
        }
        $result = $this->db->query($query_editeds3_upload);
        return $result;
    }
    public function updateconfidence($id,$updateconfidence){
        $query = '
			UPDATE video_leads
			SET confidence_level="'.$updateconfidence.'"
			WHERE id = '.$id;
        $result = $this->db->query($query);

        return $result;
    }
    public function updatevideocomment($id,$updatevideocomment){
        $query = '
			UPDATE video_leads
			SET video_comment="'.$updatevideocomment.'"
			WHERE id = '.$id;
        $result = $this->db->query($query);

        return $result;
    }
    public function getRatedDealsCount() {
        $adminid = $this->sess->userdata('adminId');

        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
        $query = '
	        SELECT vl.id, vl.first_name, vl.last_name,vl.email, vl.video_title, vl.reminder_sent, vl.closing_date, vl.status, vl.rating_point, vl.revenue_share,lad.lead_rated_date,vl.report_issue_type,vl.report_issue_desc
	        FROM video_leads vl
	        INNER JOIN lead_action_dates lad
	        ON vl.id = lad.lead_id
	       
	        WHERE vl.status = 10
	        '.$staff_check.'
	        AND vl.deleted = 0';
	        
        return $this->db->query($query)->num_rows();
	}
	
    public function getDealInformationReceivedCount() {
        $adminid = $this->sess->userdata('adminId');

        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
        $query = '
            SELECT vl.id, vl.client_id,vl.load_view, vl.first_name, vl.last_name,vl.email, vl.video_title, vl.reminder_sent, vl.closing_date, vl.status, vl.rating_point, vl.revenue_share,v.id AS video_id,ad.name staff_name,vl.unique_key,vl.simple_video,vl.report_issue_type,vl.report_issue_desc,vl.scout_resolved
            FROM video_leads vl
            /* INNER JOIN users u
            ON vl.client_id = u.id
            AND u.deleted = 0
            AND u.password IS NOT NULL*/
            INNER JOIN videos v
            ON vl.id = v.lead_id
			'.$action_table_join.'
            AND v.deleted = 0
            '.$staff_check.'
            AND v.video_verified = 0
            LEFT JOIN admin ad
            ON ad.id = vl.staff_id
            WHERE vl.status = 3
            AND vl.deleted = 0
            AND vl.information_pending = 1 
            AND vl.uploaded_edited_videos = 0
            AND ( #v.real_deciption_updated = 0
                v.question_video_taken IS NOT NULL
                AND v.question_when_video_taken IS NOT NULL
                AND v.question_video_context IS NOT NULL
                #AND v.question_video_information IS NOT NULL 
            )';

        return $this->db->query($query)->num_rows();
    }
	
    public function getDealUploadVideosCount()
    {
        $adminid = $this->sess->userdata('adminId');

        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
        $query = '
	        SELECT vl.id, vl.first_name, vl.last_name,vl.email, vl.video_title, vl.reminder_sent, vl.closing_date, vl.status, vl.rating_point, vl.revenue_share,u.is_first_time_login,v.id AS video_id,u.status AS ustatus,vl.report_issue_type,vl.report_issue_desc
	        FROM video_leads vl
	        INNER JOIN users u
	        ON vl.client_id = u.id
	        AND u.deleted = 0
	        INNER JOIN videos v
	        ON vl.id = v.lead_id
			'.$action_table_join.'	
	        AND v.deleted = 0
	        AND v.video_verified = 1
	        LEFT JOIN admin ad
	        ON ad.id = vl.staff_id
	        WHERE vl.status = 6
	        AND vl.deleted = 0
	        '.$staff_check.'
	        AND vl.information_pending = 1
	        AND vl.uploaded_edited_videos = 0
	        AND ( v.real_deciption_updated = 1 
	            AND v.question_video_taken IS NOT NULL
	            AND v.question_when_video_taken IS NOT NULL
	            AND v.question_video_context IS NOT NULL
	            #AND v.question_video_information IS NOT NULL 
	         )';
			 
        return $this->db->query($query)->num_rows();

    }	

    public function getDealDistributeVideosCount()
    {
        $adminid = $this->sess->userdata('adminId');

        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
        $query = '
	        SELECT vl.id, vl.first_name, vl.last_name,vl.email, vl.video_title, vl.unique_key, vl.reminder_sent, vl.closing_date, vl.status, vl.rating_point, vl.revenue_share,vl.published_yt,vl.published_fb,vl.published_portal,u.is_first_time_login,v.id AS video_id,v.facebook_repub,v.youtube_repub,u.status AS ustatus,
				ev.fb_url, ev.yt_url,ad.name staff_name,vl.unique_key,vl.report_issue_type,vl.report_issue_desc,rv.dropbox_status,v.mrss, mp.publication_date mrss_date
				FROM video_leads vl
				INNER JOIN users u
				ON vl.client_id = u.id
				AND u.deleted = 0
				INNER JOIN videos v
				ON vl.id = v.lead_id
				AND v.deleted = 0
				LEFT JOIN edited_video ev ON v.id = ev.video_id 
				'.$action_table_join.'
				LEFT JOIN admin ad
	            ON ad.id = vl.staff_id
                LEFT JOIN raw_video rv
                ON rv.lead_id = vl.id
                LEFT JOIN mrss_publication mp
                ON mp.video_id = v.id
				WHERE vl.status = 6
				AND vl.deleted = 0
				/*'.$staff_check.'*/
				#AND vl.published_yt = 0
				AND vl.information_pending = 1
				AND vl.uploaded_edited_videos = 1
                AND vl.is_cn_updated = 1
				#AND ev.yt_url != " "
				AND ( v.real_deciption_updated = 1
				AND v.question_video_taken IS NOT NULL
				AND v.real_deciption_updated IS NOT NULL
				AND v.question_video_context IS NOT NULL
				#AND v.question_video_information IS NOT NULL
				)
                AND ( vl.published_yt != 1
                OR rv.dropbox_status != "success"
                OR mp.publication_date IS NULL
                )
                GROUP BY vl.id';

        return $this->db->query($query)->num_rows();

    }
    public function getDealDropboxDistributeVideosCount()
    {
        $adminid = $this->sess->userdata('adminId');

        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
        $query = '
	        SELECT vl.id, vl.first_name, vl.last_name,vl.email, vl.video_title, vl.unique_key, vl.reminder_sent, vl.closing_date, vl.status, vl.rating_point, vl.revenue_share,vl.published_yt,vl.published_fb,vl.published_portal,u.is_first_time_login,v.id AS video_id,v.facebook_repub,v.youtube_repub,u.status AS ustatus,
				ev.fb_url, ev.yt_url,ad.name staff_name,vl.unique_key,vl.report_issue_type,vl.report_issue_desc,rv.dropbox_status,v.mrss
				FROM video_leads vl
				INNER JOIN users u
				ON vl.client_id = u.id
				AND u.deleted = 0
				INNER JOIN videos v
				ON vl.id = v.lead_id
				AND v.deleted = 0
				LEFT JOIN edited_video ev ON v.id = ev.video_id 
				'.$action_table_join.'
				LEFT JOIN admin ad
	            ON ad.id = vl.staff_id
                LEFT JOIN raw_video rv
                ON rv.lead_id = vl.id
				WHERE vl.status = 6
				AND vl.deleted = 0
				/*'.$staff_check.'*/
				AND vl.information_pending = 1
				AND vl.uploaded_edited_videos = 1
                AND vl.is_cn_updated = 1
                AND rv.dropbox_status = "success"
				AND ( v.real_deciption_updated = 1
				AND v.question_video_taken IS NOT NULL
				AND v.real_deciption_updated IS NOT NULL
				AND v.question_video_context IS NOT NULL
				#AND v.question_video_information IS NOT NULL
            )';

        return $this->db->query($query)->num_rows();

    }
    public function getDealMRSSDistributeVideosCount()
    {
        $adminid = $this->sess->userdata('adminId');

        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
       if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
        $query = '
            SELECT vl.id, vl.first_name, vl.last_name,vl.email, vl.video_title, vl.unique_key, vl.reminder_sent, vl.closing_date, vl.status, vl.rating_point, vl.revenue_share,vl.published_yt,vl.published_fb,vl.published_portal,u.is_first_time_login,v.id AS video_id,v.facebook_repub,v.youtube_repub,u.status AS ustatus,
            ev.fb_url, ev.yt_url,ad.name staff_name,vl.unique_key,vl.report_issue_type,vl.report_issue_desc,v.mrss
            FROM video_leads vl
            INNER JOIN users u
            ON vl.client_id = u.id
            AND u.deleted = 0
            INNER JOIN videos v
            ON vl.id = v.lead_id
            AND v.deleted = 0
            LEFT JOIN edited_video ev ON v.id = ev.video_id 
            '.$action_table_join.'
            LEFT JOIN admin ad
            ON ad.id = vl.staff_id
            INNER JOIN feed_video fv
            ON fv.video_id = v.id
            LEFT JOIN mrss_publication mp
            ON mp.video_id = v.id
            WHERE vl.status = 6
            AND vl.deleted = 0
            '.$staff_check.'
            AND v.mrss = 1
            AND mp.publication_date IS NOT NULL
            AND ( v.real_deciption_updated = 1
            AND v.question_video_taken IS NOT NULL
            AND v.real_deciption_updated IS NOT NULL
            AND v.question_video_context IS NOT NULL
            #AND v.question_video_information IS NOT NULL
            )
            GROUP BY vl.id';


        return $this->db->query($query)->num_rows();

    }

    public function getDealYTDistributeVideosCount()
    {
        $adminid = $this->sess->userdata('adminId');

        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
       if ($role_id != 1){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
        $query = '
	        SELECT vl.id, vl.first_name, vl.last_name,vl.email, vl.video_title, vl.unique_key, vl.reminder_sent, vl.closing_date, vl.status, vl.rating_point, vl.revenue_share,vl.published_yt,vl.published_fb,vl.published_portal,u.is_first_time_login,v.id AS video_id,v.facebook_repub,v.youtube_repub,u.status AS ustatus,
				ev.fb_url, ev.yt_url,vl.report_issue_type,vl.report_issue_desc
				FROM video_leads vl
				INNER JOIN users u
				ON vl.client_id = u.id
				AND u.deleted = 0
				INNER JOIN videos v
				ON vl.id = v.lead_id
				AND v.deleted = 0
				LEFT JOIN edited_video ev ON v.id = ev.video_id 
				'.$action_table_join.'
				LEFT JOIN admin ad
	            ON ad.id = vl.staff_id
				WHERE vl.status = 6
				AND vl.deleted = 0
				'.$staff_check.'
				AND vl.published_yt = 1
				AND vl.information_pending = 1
				AND vl.uploaded_edited_videos = 1
                AND vl.is_cn_updated = 1
				AND v.youtube_id != " "
                AND v.youtube_id != ""
				AND ( v.real_deciption_updated = 1
				AND v.question_video_taken IS NOT NULL
				AND v.real_deciption_updated IS NOT NULL
				AND v.question_video_context IS NOT NULL
				#AND v.question_video_information IS NOT NULL
				)';


        return $this->db->query($query)->num_rows();

    }

    public function move_closewon($id){
		$query = '
			UPDATE video_leads
			SET status = 8
			WHERE id = '.$id;
        $result = $this->db->query($query);

        return $result;
    }
    public function getNextPayment($id)
    {

        $default_currency = getDefaultCurrency();
        $sum = 0;
        $query = 'SELECT e.currency_id, e.partner_currency, e.client_net_earning, e.conversion_rate 
                FROM videos v
                INNER JOIN earnings e
                ON v.id = e.video_id
                AND v.user_id = '.$id.'
                WHERE e.paid = 0
                AND e.status = 1
                AND e.deleted = 0
                AND e.video_id = v.id';

        $result = $this->db->query($query)->result_array();

        foreach($result as $row){
            $to = $row["currency_id"];
            $from = $row["partner_currency"];
            $amount = $row["client_net_earning"];
            $rate = $row["conversion_rate"];

            list($conved_payment, $conved_rate) = convertPaymentCurrency($from, $to, $amount, $rate, $default_currency["id"]);
            $sum += $conved_payment;
            
        }
        return round($sum, 2);
    }
    public function paid($id)
    {
        $default_currency =  getCurrencyByCode('USD');;
        
        $sum = 0;
        $query = 'SELECT e.currency_id, e.partner_currency, e.client_net_earning, e.conversion_rate,e.paid_amount
                FROM videos v
                INNER JOIN earnings e
                ON v.id = e.video_id
                AND v.user_id = '.$id.'
                WHERE e.paid = 1
                AND e.status = 1
                AND e.deleted = 0
                AND e.video_id = v.id';

        $result = $this->db->query($query)->result_array();

        foreach($result as $row){
            $sum += $row["paid_amount"];
        }


        return round($sum, 2);
    }
    public function getCNchecks($id)
    {
        $query = '
            SELECT *
            FROM videos v
            WHERE v.title_updated = 1
            AND v.description_updated = 1
            AND v.tags_updated = 1
            AND v.lead_id ='.$id;

        return ($this->db->query($query)->num_rows() > 0);
    }
}
