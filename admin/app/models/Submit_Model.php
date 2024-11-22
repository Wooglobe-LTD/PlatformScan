<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Submit_Model extends APP_Model {

	public function __construct() {
        parent::__construct();

        
    }
    public function getUserByEmail($email)
    {

        $query = '
			SELECT *
		    FROM users u
		    WHERE u.email = "'.$email.'"
            AND u.password  = 0
		    AND u.status = 1
		    AND u.deleted = 0
		    AND u.bulk_user  = 0
		';

        $result = $this->db->query($query);

        return $result;

    }

    public function getTemplate()
    {
        $query = "Select * FROM email_templates where short_code = 'video_submission'";
        $result = $this->db->query($query)->row_array();
        return $result;
    }




}
