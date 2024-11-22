<?php

/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 2/13/2018
 * Time: 4:03 PM
 */
class Upload extends CI_Model
{
    public function upload_video($data)
    {

        $this->db->insert('video_leads',$data);
        return $this->db->insert_id();

    }

    public function getTemplate()
    {
        $query = "Select * FROM email_templates where short_code = 'video_submission'";
        $result = $this->db->query($query)->row_array();
        return $result;
    }

    public function getTemplateByShortCode()
    {
        $query = "Select * FROM email_templates where short_code = 'video_submission'";
        $result = $this->db->query($query)->row_array();
        return $result;
    }

    public function upload_videos($result)
    {
        $this->db->insert('videos',$result);
        return $this->db->insert_id();
    }

    public function getDetailsBySlug($slug)
    {
        $query = 'SELECT * FROM video_leads WHERE slug = "'.$slug.'"';
        $result = $this->db->query($query)->row_array();
        return $result;
    }

    public function update_status($lead_id,$uuid)
    {
        $query = 'UPDATE video_leads 
                  SET status =   2,
                  reminder_sent = 0,
                  sr_uuid = "'.$uuid.'"
                  WHERE id = '.$lead_id.'
                  ';
        $result = $this->db->query($query);
        return $result;
    }

    public function update_contract_time($date_time,$lead_id)
    {
        $query = 'UPDATE lead_action_dates 
                  SET contract_sent_date =   "'.$date_time.'"
                  WHERE lead_id = '.$lead_id.'
                  ';
        $result = $this->db->query($query);
        return $result;
    }

    public function email_notification($db_data)
    {
        $this->db->insert('email_notification_history',$db_data);
        return $this->db->insert_id();

    }

}