<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_Model extends APP_Model {

	public function __construct() {
        parent::__construct();
        $this->load->model('User_Model','user');
        
    }


    public function getVideoTypes(){

	    $result = $this->db->query('
	            SELECT * 
	            FROM videos_types
	            ORDER BY id ASC 
	    ');

        return $result;
    }

    public function getReuestsByEmail($email)
    {
        $query = 'SELECT * FROM video_leads where email= "'.$email.'" AND status = 1';
        $result = $this->db->query($query);
        return $result;
	}

    public function getDealsByEmail($email)
    {
        $query = 'SELECT * FROM video_leads where client_id= '.$email.' AND status = 3 AND information_pending = 0';
        $result = $this->db->query($query);
        return $result;
	}
    public function getRejectedByEmail($email)
    {
        $query = 'SELECT * FROM video_leads where email= "'.$email.'" AND status = 0';
        $result = $this->db->query($query);
        return $result;
    }
    public function getAllByEmail($email)
    {
        $query = 'SELECT * FROM video_leads where email= "'.$email.'"';
        $result = $this->db->query($query);
        return $result;
    }

    public function getAcquiredVideos($id)
    {
        $query = 'SELECT * 
                  FROM videos v 
                  INNER JOIN video_leads vl
                  ON vl.id = v.lead_id
                  WHERE vl.client_id = '.$id.'
                  AND v.is_wooglobe_video =1'
                  ;
        $result = $this->db->query($query);
        return $result;
	}
    public function videosLicense($id)
    {
        $query = 'SELECT
                      SUM(
                        (SELECT COUNT(vl.id)
                          FROM video_license vl
                          WHERE vl.video_id = v.id
                          AND vl.status = 0 And vl.deleted = 0)
                          ) AS sold_videos,
                      COUNT(v.id) AS uploaded_videos
                           FROM videos v 
                  INNER JOIN video_leads vl
                  ON vl.id = v.lead_id
                  WHERE vl.client_id = '.$id.'
                  AND vl.information_pending = 1
                  AND v.is_wooglobe_video = 1
                  AND v.deleted = 0';
        $result = $this->db->query($query)->row();

        return $result;
	}

    public function getLeadByEmail($email){

        $query = 'SELECT * FROM video_leads where email= "'.$email.'" AND status = 2';
        $result = $this->db->query($query);
        return $result;
	}

    public function getLeadById($id){

        $query = 'SELECT * FROM video_leads where client_id= "'.$id.'" ';
        $result = $this->db->query($query);
        return $result;
    }

    public function getLeadById1($id){

        $query = 'SELECT * FROM video_leads where id= "'.$id.'" ';
        $result = $this->db->query($query);
        if($result->num_rows() > 0){
            return $result->row();
        }
        return false;
    }

    public function getLeadBySlug($slug)
    {
        $query = 'SELECT * FROM video_leads where slug= "'.$slug.'"';
        $result = $this->db->query($query);
        return $result;

    }
    public function checkView($id,$lead_id)
    {
        $query = 'SELECT * FROM videos WHERE user_id = '.$id.' AND lead_id = '.$lead_id.' ';
        $result = $this->db->query($query);
        return $result;
	}

    public function insert_video($data)
    {
        unset($data['view']);
        $string = array(
            'title' => $data['video_title'],
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
    public function insert_video1($data)
    {
        unset($data['view']);
        $string = array(
            'question_video_taken' => $data['question1'],
            //'question_video_information' => $data['question2'],
            'question_when_video_taken' => date('Y-m-d',strtotime($data['question3'])),
            'question_video_context' => $data['question4'],
            'description' => $data['question4'],
            'title' => $data['title'],

        );
        $this->db->where('id', $data['id']);
        $this->db->update('videos', $string);
        return $this->db->affected_rows();
    }

    public function update_view($view_no,$lead_id)
    {
        $query = 'UPDATE video_leads 
                  SET load_view =   '.$view_no.'
                  WHERE id = '.$lead_id.'
                  ';
        $result = $this->db->query($query);
        return $result;

    }
    public function getNextPayment($id)
    {
        $first =  date('Y-m-01 00:00:00');
        $last =  date('Y-m-t 00:00:00');

        $query = 'SELECT SUM(e.earning_amount) AS next_payment
                  FROM videos v
                  INNER JOIN earnings e
                  ON v.id = e.video_id
                  AND v.user_id = '.$id.'
                  WHERE e.paid = 0
                  AND e.status = 1
                  AND e.video_id = v.id';

        $result = $this->db->query($query);
        return $result;
    }

    public function paid($id)
    {
        $query = 'SELECT SUM(e.earning_amount) AS paid
                  FROM videos v
                  INNER JOIN earnings e
                  ON v.id = e.video_id
                  AND v.user_id = '.$id.'
                  WHERE e.paid = 1
                  AND e.status = 1
                  AND e.video_id = v.id';

        $result = $this->db->query($query);
        return $result;
    }
    public function getNextPaymentMonthly($id)
    {
        $date = date('F, Y');
        $query = 'SELECT SUM(e.earning_amount) AS next_payment
                  FROM videos v
                  INNER JOIN earnings e
                  ON v.id = e.video_id
                  AND v.user_id = '.$id.'
                  WHERE DATE_FORMAT(e.earning_date,\'%M, %Y\') = "'.$date.'"
                  AND e.status = 1
                  AND e.video_id = v.id';

        $result = $this->db->query($query);
        return $result;
    }
    public function paidMonthly($id)
    {
        $date = date('F, Y');
        $query = 'SELECT SUM(e.earning_amount) AS paid
                  FROM videos v
                  INNER JOIN earnings e
                  ON v.id = e.video_id
                  AND v.user_id = '.$id.'
                  WHERE DATE_FORMAT(e.earning_date,\'%M, %Y\') = "'.$date.'"
                  AND e.paid = 1
                  AND e.status = 1
                  AND e.video_id = v.id';

        $result = $this->db->query($query);
        return $result;
    }



    public function getMonths($created_at)
    {
       $current = date('Y-m-d H:i:s');
        $query = 'SELECT
                    DATE_FORMAT(m1, \'%M, %Y\') AS month_year,
                    DATE_FORMAT(m1, \'%Y-%m-%d %H:%i:%s\') AS month_date
                    
                    FROM
                    (
                    SELECT 
                    ("'.$created_at.'" - INTERVAL DAYOFMONTH("'.$created_at.'")-1 DAY) 
                    +INTERVAL m MONTH as m1
                    FROM 
                    (
                    SELECT @rownum:=@rownum+1 as m from
                    (SELECT 1 union select 2 union select 3 union select 4) t1,
                    (SELECT 1 union select 2 union select 3 union select 4) t2,
                    (SELECT 1 union select 2 union select 3 union select 4) t3,
                    (SELECT 1 union select 2 union select 3 union select 4) t4,
                    (SELECT @rownum:=-1) t0
                    ) d1
                    ) d2 
                    where m1<="'.$current.'"
                    ORDER BY m1 DESC';
        $result = $this->db->query($query)->result_array();
        return $result;
    }

    public function account_created($id)
    {
        $query = 'SELECT created_at FROM users where id ='.$id.'';
        $result = $this->db->query($query)->row_array();
        return $result;
    }

    public function insert_info($data)
    {

        unset($data['view']);
        $string = array(
            'paypal_email' => $data['email'],
            'country_code' => $data['country_code'],
            'mobile' => $data['mobile'],
            //'gender' => $data['gender'],
            'address' => $data['address'],
            'address2' => $data['address2'],
            'city_id' => $data['city_id'],
            'state_id' => $data['state_id'],
            'zip_code' => $data['zip_code'],
            'country_id' => $data['country_id'],
        );
        $this->db->where('id', $data['id']);
        $this->db->update('users', $string);


    }

    public function update_pending($id)
    {
        $query = 'UPDATE video_leads 
                  SET information_pending = 1,
                  status = 3
                  WHERE id = '.$id.'
                  ';
        $result = $this->db->query($query);
        return $result;
    }
    public function checkUsers($id)
    {
        $query = 'SELECT paypal_email,email,country_code,mobile,gender,address,address2,city_id,state_id,zip_code,country_id FROM users WHERE id ='.$id.'';
        $result = $this->db->query($query)->row_array();
        return $result;
    }

    public function getAdRevenue($id,$slug = NULL)
    {
        $query = 'SELECT e.*,ss.sources,v.title,SUM(e.earning_amount) AS earn  
                   FROM videos v 
                   INNER JOIN earnings e 
                   ON v.id = e.video_id 
                   AND e.status = 1
                   AND e.deleted = 0
                   INNER JOIN social_sources ss
                   ON e.social_source_id = ss.id
                   AND ss.status = 1
                   AND ss.deleted = 0
                   WHERE v.user_id = '.$id.'
                   AND v.status = 1
                   AND v.deleted = 0';
        if(!empty($slug)){
            $query .= ' AND v.slug = "'.$slug.'"';
        }
        $result = $this->db->query($query);
        return $result;
    }

    public function getUserEarnings($id,$slug = null,$orderBy = 'e.created_at DESC',$groupBy = null)
    {
        $query = "
                SELECT e.*,et.earning_type,ss.sources,u.full_name,u.email,u.unique_key AS ukey,vl.unique_key AS lkey,v.title,vl.revenue_share,v.slug
                FROM videos v
                INNER JOIN video_leads vl
                ON v.lead_id = vl.id
                AND vl.deleted = 0
                INNER JOIN earnings e 
                ON e.video_id = v.id
                AND e.deleted = 0
                INNER JOIN earning_types et
                ON et.id = e.earning_type_id
                AND et.status = 1
                AND et.deleted = 0
                LEFT JOIN social_sources ss
                ON ss.id = e.social_source_id
                AND ss.status = 1
                AND ss.deleted = 0
                LEFT JOIN users u
                ON u.id = e.partner_id
                AND u.status = 1
                AND u.deleted = 0
                WHERE v.user_id = $id
                AND v.video_verified = 1
                AND v.status = 1
                AND v.deleted = 0
        ";
        if(!empty($slug)){
            $query .= " AND v.slug = '$slug'";
        }

        if(!empty($groupBy)){
            $query .= " GROUP BY $groupBy";
        }

        $query .= " ORDER BY $orderBy";
        $result = $this->db->query($query);
        return $result;
    }

    public function getAdVideosTitle($id)
    {
        $query = 'SELECT v.title,v.slug
                   FROM videos v 
                   INNER JOIN earnings e 
                   ON v.id = e.video_id 
                   AND e.status = 1
                   AND e.deleted = 0
                   INNER JOIN social_sources ss
                   ON e.social_source_id = ss.id
                   AND ss.status = 1
                   AND ss.deleted = 0
                   WHERE v.user_id = '.$id.'
                   AND v.status = 1
                   AND v.deleted = 0
                   GROUP BY v.title';

        $result = $this->db->query($query);
        return $result;
    }

    public function getLicenseRevenue($id,$slug = NULL)
    {
        $query = 'SELECT e.*,u.full_name,v.title,SUM(e.earning_amount) AS earn
                   FROM videos v 
                   INNER JOIN earnings e 
                   ON v.id = e.video_id 
                   AND e.status = 1
                   AND e.deleted = 0
                   INNER JOIN users u
                   ON e.partner_id = u.id
                   AND u.status = 1
                   AND u.deleted = 0
                   WHERE v.user_id = '.$id.'
                   AND v.status = 1
                   AND v.deleted = 0';
        if(!empty($slug)){
            $query .= ' AND v.slug = "'.$slug.'"';
        }

        $result = $this->db->query($query);
        return $result;
    }
    public function getLicenseVideoTitle($id)
    {
        $query = 'SELECT v.title,v.slug
                   FROM videos v 
                   INNER JOIN earnings e 
                   ON v.id = e.video_id 
                   AND e.status = 1
                   AND e.deleted = 0
                   INNER JOIN users u
                   ON e.partner_id = u.id
                   AND u.status = 1
                   AND u.deleted = 0
                   WHERE v.user_id = '.$id.'
                   AND v.status = 1
                   AND v.deleted = 0
                   GROUP BY v.title';
        $result = $this->db->query($query);
        return $result;
    }

    public function getRevenue($id,$slug = NULL)
    {
        $query = 'SELECT e.*,v.title
                   FROM videos v 
                   INNER JOIN earnings e 
                   ON v.id = e.video_id 
                   AND e.status = 1
                   AND e.deleted = 0
                   WHERE v.user_id = '.$id.'
                   AND v.status = 1
                   AND v.deleted = 0';
        if(!empty($slug)){
            $query .= ' AND v.slug = "'.$slug.'"';
        }
        $result = $this->db->query($query);
        return $result;

    }

    public function getVideosTitle($id)
    {
        $query = 'SELECT v.title,v.slug
                   FROM videos v 
                   INNER JOIN earnings e 
                   ON v.id = e.video_id 
                   AND e.status = 1
                   AND e.deleted = 0
                   WHERE v.user_id = '.$id.'
                   AND v.status = 1
                   AND v.deleted = 0
                   GROUP BY v.title';

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

    public function getAllDealsByEmail($email)
    {
        $query = 'SELECT * FROM video_leads where email= "'.$email.'" ORDER BY created_at DESC';
        $result = $this->db->query($query);
        return $result;
    }

}
