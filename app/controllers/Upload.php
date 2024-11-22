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

}