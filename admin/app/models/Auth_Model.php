<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_Model extends APP_Model {

	public function __construct() {
        parent::__construct();

        
    }
	
	public function login($username,$password)
	{
		
		$query = '
			SELECT a.*,art.title AS admin_type,ar.title AS admin_role,ar.role_type_id
		    FROM admin a
		    INNER JOIN admin_roles ar
            ON a.admin_role_id = ar.id
            AND ar.deleted = 0
            LEFT JOIN admin_role_types art
            ON ar.role_type_id = art.id
		    WHERE a.username = "'.$username.'"
		    AND a.password = "'.$password.'"
		    AND a.status = 1
		    AND a.deleted = 0
		';
		
		$result = $this->db->query($query);
		
		return $result;
		
	}
	
	public function getUserByEmail($email)
	{
		
		$query = '
			SELECT a.*,art.title AS admin_type,ar.title AS admin_role,ar.role_type_id
		    FROM admin a
		    INNER JOIN admin_roles ar
            ON a.admin_role_id = ar.id
            AND ar.deleted = 0
            LEFT JOIN admin_role_types art
            ON ar.role_type_id = art.id
		    WHERE a.email = "'.$email.'"
		    AND a.status = 1
		    AND a.deleted = 0
		';
		
		$result = $this->db->query($query);
		
		return $result;
		
	}

    public function getUserByUsername($username)
    {

        $query = '
			SELECT a.*,art.title AS admin_type,ar.title AS admin_role,ar.role_type_id
		    FROM admin a
		    INNER JOIN admin_roles ar
            ON a.admin_role_id = ar.id
            AND ar.deleted = 0
            LEFT JOIN admin_role_types art
            ON ar.role_type_id = art.id
		    WHERE a.username = "'.$username.'"
		    AND a.status = 1
		    AND a.deleted = 0
		';

        $result = $this->db->query($query);

        return $result;

    }
	
	public function getUserByPassword($password)
	{
		
		$query = '
			SELECT a.*,art.title AS admin_type,ar.title AS admin_role,ar.role_type_id
		    FROM admin a
		    INNER JOIN admin_roles ar
            ON a.admin_role_id = ar.id
            AND ar.deleted = 0
            LEFT JOIN admin_role_types art
            ON ar.role_type_id = art.id
		    WHERE a.password = "'.$password.'"
			AND a.id = '.$this->sess->userdata('adminId').' 
		    AND a.status = 1
		    AND a.deleted = 0
		';
		
		$result = $this->db->query($query);
		
		return $result;
		
	}


	/*
	 * unblockAccountByUsername, checkTempAccountBlockStatus, blockAccountByUsername will manage temporarily account block due to failed login attempts
	 * */

    /*
     * unblockAccountByUsername, checkTempAccountBlockStatus, blockAccountByUsername will manage temporarily account block due to failed login attempts
     * */

    public function blockAccountByUsername($username)
    {
        $this->load->helper('string');
        $data = array(
            'temp_unlock_time' => date('Y-m-d H:i', strtotime($this->config->item('account_block_time'), strtotime(date('Y-m-d H:i')))),
            'temp_unlock_code' => random_string('alnum', 7)
        );

        $this->db->where('username', $username);
        $this->db->update('admin', $data);

        return $data;
    }

    public function unblockAccountByUsername($username)
    {
        $data = array(
            'temp_unlock_time' => NULL,
            'temp_unlock_code' => NULL
        );

        $this->db->where('username', $username);
        $this->db->update('admin', $data);
    }

    // returns 1 if account is temporarily blocked
    public function checkTempAccountBlockStatus($username)
    {
        $query = $this->db->get_where('admin', array('username' => $username), 1);

        if ($query->num_rows()) {
            $admin_info = $query->row();

            if (strtotime(date('Y-m-d H:i')) <= strtotime($admin_info->temp_unlock_time)) {
                return 1;
            }
            else {
                if (!empty($admin_info->temp_unlock_time)) {
                    $this->unblockAccountByUsername($username);
                }
            }
        }

        return 0;
    }


}
