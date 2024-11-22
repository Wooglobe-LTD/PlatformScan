<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video_Lead_Model extends APP_Model {

	public function __construct() {
        parent::__construct();

        
    }
	
	public function getAllLeads($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'vl.id DESC',$colums = '')
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
			    if($field['name'] != 'action'){
                    $condition .= ' '.$field['name'].' LIKE "%'.$search.'%" OR';
                }
	

			}

			$condition = rtrim($condition,'OR').')';
		}
        $adminid = $this->sess->userdata('adminId');
        $adminRoleId = $this->sess->userdata('adminRoleId');
		$query = '
			SELECT '.$fields.'
			FROM video_leads vl
		    WHERE vl.deleted = 0
            AND vl.status in (0,1)
		';
        if($adminRoleId != 1){
            $query .= " AND (vl.staff_id = $adminid OR vl.staff_id = 31) ";
        }
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
	
    public function getAllVideos()
    {
        $query = '
            SELECT rv.*, vl.unique_key, ev.portal_url, wv.s3_url watermark_url, vl.created_at
            FROM raw_video rv
            LEFT JOIN video_leads vl
            ON rv.lead_id = vl.id
            LEFT JOIN edited_video ev
            ON rv.video_id = ev.video_id
            LEFT JOIN watermark_videos wv
            ON rv.video_id = wv.video_id
            WHERE vl.deleted = 0
            AND vl.status >= 6
            AND vl.status <= 8
            ORDER BY rv.video_id';
        $result = $this->db->query($query);

        return $result;
    }
	
	public function getLeadById($id,$fields='*')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}
        $query = '
			SELECT '.$fields.'
            FROM video_leads vl 
            left join raw_video rv on (vl.id=rv.lead_id)
		    WHERE vl.id = '.$id.'
		    AND vl.status in (0,1)
		';
		
		$result = $this->db->query($query);
		
		if($result->num_rows() > 0){

			return $result->row();

		}else{

			return false;

		}

		
	}

    public function getRawUrls($id)
    {
        $query = '
			SELECT rv.url
            FROM raw_video rv
		    WHERE rv.lead_id = '.$id.'
		    AND rv.url IS NOT NULL
		';
		
		$result = $this->db->query($query);

        return $result->result_array();
    }

    public function getLeadByIdAllStatus($id,$fields='*')
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }

        $query = '
			SELECT '.$fields.'
		    FROM video_leads vl
		    WHERE vl.id = '.$id.'
		';

        $result = $this->db->query($query);

        if($result->num_rows() > 0){

            return $result->row();

        }else{

            return false;

        }


    }

    public function getUniqueKey($lead_id)
    {
        $query = '
			SELECT unique_key
		    FROM video_leads vl
		    WHERE vl.id = '.$lead_id
		;
        $result = $this->db->query($query);

        if($result->num_rows() > 0){

            return $result->row();

        }else{

            return false;

        }
	}

	public function getLeadByUniqueKey($id,$fields='*')
        {
            if(is_array($fields)){

                $fields = implode(',',$fields);

            }

            $query = '
			SELECT '.$fields.'
		    FROM video_leads vl
		    LEFT JOIN videos v 
		    ON vl.id = v.lead_id
		    WHERE vl.unique_key = "'.$id.'"
		';
            $result = $this->db->query($query);

            if($result->num_rows() > 0){

                return $result->row();

            }else{

                return false;

            }
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

    public function getPartyById($table,$id){

        $query = '
			SELECT name
		    FROM '.$table.'
		    WHERE id = '.$id.'
		';
        $result = $this->db->query($query);
        return $result;

    }

    public function action_dates($curr_time,$lead_id)
    {
        $query = 'UPDATE lead_action_dates 
                  SET lead_rated_date = "'.$curr_time.'"
                  WHERE lead_id = '.$lead_id.'
                  ';
        $result = $this->db->query($query);
        return $result;
    }

    public function email_notification($db_data)
    {
        $query = 'UPDATE email_notification_history 
                  SET email_template_id = '.$db_data['email_template_id'].',
                  email_title = '.$db_data['email_title'].',
                  ids = "'.$db_data['ids'].'"
                  WHERE id = '.$db_data['lead_id'].'
                  ';
        $result = $this->db->query($query);
        return $result;
    }
    public function getRawVideosByLeadId($id)
    {
        $query = 'SELECT *
                  FROM raw_video
                  WHERE lead_id = '.$id.'
                 ';
        $result = $this->db->query($query);

        return $result;
    }

    public function insert_video($data)
    {
        $string = array(
            'title' => $data['title'],
            'slug' => $data['slug'],
            'lead_id' => $data['lead_id'],
            'url' => NULL,
            'user_id' => $this->sess->userdata('clientId'),
            'is_wooglobe_video' => 1,
            'video_type_id' => 1,
            'status' => 0
        );
        $this->db->insert('videos',$string);
        return $this->db->insert_id();
    }

    public function insert_raw_video($data)
    {

        $string = array(
            'lead_id' => $data['lead_id'],
            'url' => $data['url'],
            'video_id' => $data['video_id']
        );
        $this->db->insert('raw_video',$string);
        return $this->db->insert_id();

    }
    public function update_raw_video($data)
    {

        $result_raw = $this->db->query('Select count(id) as num_rows, video_id, client_link, url from raw_video where lead_id="'.$data['lead_id'].'"');
        $client= $result_raw->result();


        if(isset($client[0])){
            $client_link=$client[0]->client_link;
            $video_id=$client[0]->video_id;
            $num_rows=$client[0]->num_rows;
            $url=$client[0]->url;
        }
        $string = array(
            'lead_id' => $data['lead_id'],
            'client_link' => $client_link,
            'url' => $data['url'],
            'video_id' => $video_id,
        );

        if($num_rows == 1 and empty($url)){
//update
            $this->db->where('lead_id', $data['lead_id']);
            $this->db->update('raw_video', $string);
        }else{
            $this->db->insert('raw_video',$string);
            return $this->db->insert_id();
        }

    }

    public function update_description_updated_status ($id, $description_updated)
    {
        $this->db->where('id', $id);
        $this->db->update('video_leads', ['description_updated' => $description_updated]);
    }
    public function getThirdpartyById($staffid){

        $query = '
			SELECT name
		    FROM third_party_staff tp
		    WHERE tp.id = '.$staffid.'
		    AND tp.status = 1 AND tp.deleted = 0
		';

        $result = $this->db->query($query);

        if($result->num_rows() > 0){

            return $result->row();

        }else{

            return false;

        }
    }
    public function updatedetails($id,$updatedeatils,$check)
    {
        if ($check == "title") {
            $query = '
			UPDATE video_leads
			SET video_title="' . $updatedeatils . '"
			WHERE id = ' . $id;
        } elseif ($check == "url") {
            $query = '
			UPDATE video_leads
			SET video_url="' . $updatedeatils . '"
			WHERE id = ' . $id;
        } elseif($check == "message"){
            $query = '
			UPDATE video_leads
			SET message="' . $updatedeatils . '"
			WHERE id = ' . $id;
        }

        $result = $this->db->query($query);

        return $result;
    }

    public function getCurencies(){
        $result = $this->db->query("
	        SELECT *
	        FROM currency
	        WHERE sort_no > 0
	        AND status = 1
	        ORDER BY sort_no ASC
	    ");
        return $result->result();
    }

}
