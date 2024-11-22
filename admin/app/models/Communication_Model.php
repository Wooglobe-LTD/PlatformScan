<?php

/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 4/25/2018
 * Time: 4:17 PM
 */
class Communication_Model extends APP_Model
{
    public function __construct() {
        parent::__construct();


    }

    public function getTemplates($id)
    {

        $query = 'SELECT * FROM email_notification_history
                  WHERE lead_id = '.$id.'
                  AND id > 0
                  GROUP BY email_template_id
                  ';

        $result = $this->db->query($query)->result_array();
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
		    WHERE vl.id = '.$id.'
		';

        $result = $this->db->query($query);

        if($result->num_rows() > 0){

            return $result->row();

        }else{

            return false;

        }


    }

    public function getTemplateId($id,$fields)
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }
        $query = 'SELECT '.$fields.'
                  FROM email_notification_history enh
                  WHERE enh.id = '.$id.'
                  ';

        $result = $this->db->query($query);

        if($result->num_rows() > 0){

            return $result->row();

        }else{

            return false;

        }
    }
    public function getTemplate($id,$fields)
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }
        $query = 'SELECT '.$fields.'
                  FROM email_templates et
                  WHERE et.id = '.$id.'
                  ';

        $result = $this->db->query($query);

        if($result->num_rows() > 0){

            return $result->row();

        }else{

            return false;

        }
    }

    public function update_status($lead_id, $status=10)
    {
        $query = 'UPDATE video_leads 
                  SET status = '.$status.',
                  reminder_sent = 0
                  WHERE id = '.$lead_id.'
                  ';
        $result = $this->db->query($query);
        return $result;
    }

    public function getPartnersByLeadId($lead_id)
    {
        $query = 'SELECT u.*
                  FROM users u
                  LEFT JOIN mrss_feeds mf
                  ON mf.partner_id = u.id
                  LEFT JOIN mrss_publication mp
                  ON mp.feed_id = mf.id
                  LEFT JOIN videos v
                  ON v.id = mp.video_id
                  WHERE v.lead_id = '.$lead_id.'
                  AND u.deleted = 0
        ';
        
        $result = $this->db->query($query);
        return $result;
    }
    
    public function getLeadDataById($id,$fields='*')
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }

        $query = '
			SELECT '.$fields.'
		    FROM video_leads vl
            LEFT JOIN videos v
            ON v.lead_id = vl.id
		    WHERE vl.id = '.$id.'
		';

        $result = $this->db->query($query);

        if($result->num_rows() > 0){

            return $result->row();

        }else{

            return false;

        }


    }
}
