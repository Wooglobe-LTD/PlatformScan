<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_Model extends APP_Model {

    public function __construct() {
        parent::__construct();


    }

    public function login($email,$password)
    {

        $query = '
			SELECT *
		    FROM users u
		    WHERE u.email = "'.$email.'"
		    AND u.password = "'.$password.'"
		    AND u.status = 1
		    AND u.deleted = 0
		    AND u.role_id = 1
		';

        $result = $this->db->query($query);

        return $result;

    }

    public function ghost($id)
    {

        $query = '
			SELECT *
		    FROM users u
		    WHERE u.id = '.$id.'
		    AND u.deleted = 0
		    AND u.role_id = 1
		';

        $result = $this->db->query($query);

        return $result;

    }

    public function login_partner($email,$password)
    {

        $query = '
			SELECT *
		    FROM users u
		    WHERE u.email = "'.$email.'"
		    AND u.password = "'.$password.'"
		    AND u.status = 1
		    AND u.deleted = 0
		    AND u.role_id = 2
		';

        $result = $this->db->query($query);

        return $result;

    }

    public function getUserByEmail($email)
    {

        $query = '
			SELECT *
		    FROM users u
		    WHERE u.email = "'.$email.'"
		    AND u.status = 1
		    AND u.deleted = 0
		   /* AND u.bulk_user  = 0*/
		';

        $result = $this->db->query($query);

        return $result;

    }
    public function getUserByEmailWithNoPassword($email)
    {

        $query = '
			SELECT *
		    FROM users u
		    WHERE u.email = "'.$email.'"
		    AND u.status = 1
		    AND u.deleted = 0
		    AND u.password  = 0
		';

        $result = $this->db->query($query);

        return $result;

    }
    public function getBlockUserByEmail($email)
    {

        $query = '
			SELECT *
		    FROM users u
		    WHERE u.email = "'.$email.'" AND deleted = 1
		';

        $result = $this->db->query($query);

        return $result;

    }
    public function getUserByPayPalEmail($email)
    {

        $query = '
			SELECT *
		    FROM users u
		    WHERE u.paypal_email = "'.$email.'"
		    AND u.status = 1
		    AND u.deleted = 0
		';

        $result = $this->db->query($query);

        return $result;

    }


    public function getUserByVerifyToken($token)
    {

        $query = '
			SELECT *
		    FROM users u
		    WHERE u.verify_token = "'.$token.'"
		    AND u.deleted = 0
		';
        $result = $this->db->query($query);
        return $result;
    }

    public function getUserByVerifyTokenAndOTP($token,$otp)
    {

        $query = '
			SELECT *
		    FROM users u
		    WHERE u.verify_token = "'.$token.'"
		    AND u.otp = '.$otp.'
		    AND u.deleted = 0
		';

        $result = $this->db->query($query);
        return $result;

    }

    public function getUserByVerifyTokenAndOTPAndTime($token,$otp,$time)
    {

        $query = '
			SELECT *
		    FROM users u
		    WHERE u.verify_token = "'.$token.'"
		    AND u.otp = '.$otp.'
		    AND u.deleted = 0
		';

        $result = $this->db->query($query)->row_array();

        if(isset($result['token_expiry_time']) && !empty($result['token_expiry_time'])) {
            $t1 = strtotime($result['token_expiry_time']);
            $t2 = strtotime($time);
            $diff = ($t2 - $t1) / 900;
            if($diff <= 1){
                return true;
            }
            else{
                return false;
            }
        }
        return false;

    }
    public function getUserByPassword($password)
    {

        $query = '
			SELECT *
		    FROM users u
		    WHERE u.password = "'.$password.'"
			AND id = '.$this->sess->userdata('adminId').'
		    AND a.status = 1
		';

        $result = $this->db->query($query);

        return $result;

    }

    public function update_password($post)
    {
        $data = array(
            'password' => $post['password'],
            'is_first_time_login' => 1
        );

        $password = md5($post['password']);
        $is_first = 0;
        $id = $post['id'];

        $query = "UPDATE users SET password ='$password', is_first_time_login = $is_first WHERE id = $id";
        $result = $this->db->query($query);

        $query1 =      '
                        SELECT *
                        FROM users u
                        WHERE u.id = "'.$id.'"
                        AND u.status = 1
                        AND u.deleted = 0
                        AND u.role_id = 1
                        AND u.is_first_time_login = 0
		                ';


        $result1 = $this->db->query($query1);
        return $result1;
    }

    public function update_partner_password($post)
    {
        $data = array(
            'password' => $post['password'],
            'is_first_time_login' => 1
        );

        $password = md5($post['password']);
        $is_first = 0;
        $id = $post['id'];

        $query = "UPDATE users SET password ='$password', is_first_time_login = $is_first WHERE id = $id";
        $result = $this->db->query($query);

        $query1 =      '
                        SELECT *
                        FROM users u
                        WHERE u.id = "'.$id.'"
                        AND u.status = 1
                        AND u.deleted = 0
                        AND u.role_id = 2
                        AND u.is_first_time_login = 0
		                ';


        $result1 = $this->db->query($query1);
        return $result1;
    }

    public function getUserByToken($token)
    {
        $query = '
			SELECT *
		    FROM users u
		    WHERE u.verify_token = "'.$token.'"
		    AND u.status = 1
		    AND u.deleted = 0
		';

        $result = $this->db->query($query);

        return $result;
    }

    /*
     * unblockAccountByUsername, checkTempAccountBlockStatus, blockAccountByUsername will manage temporarily account block due to failed login attempts
     * */

    public function blockAccountByEmail($email)
    {
        $this->load->helper('string');
        $data = array(
            'temp_unlock_time' => date('Y-m-d H:i', strtotime($this->config->item('account_block_time'), strtotime(date('Y-m-d H:i')))),
            'temp_unlock_code' => random_string('alnum', 7)
        );

        $this->db->where('email', $email);
        $this->db->where('role_id','1');
        $this->db->update('users', $data);

        return $data;
    }

    public function blockPartnerAccountByEmail($email)
    {
        $this->load->helper('string');
        $data = array(
            'temp_unlock_time' => date('Y-m-d H:i', strtotime($this->config->item('account_block_time'), strtotime(date('Y-m-d H:i')))),
            'temp_unlock_code' => random_string('alnum', 7)
        );

        $this->db->where('email', $email);
        $this->db->where('role_id', '2');
        $this->db->update('users', $data);

        return $data;
    }

    public function unblockAccountByEmail($email)
    {
        $data = array(
            'temp_unlock_time' => NULL,
            'temp_unlock_code' => NULL
        );

        $this->db->where('email', $email);
        $this->db->where('role_id','1');
        $this->db->update('users', $data);
    }

    public function unblockPartnerAccountByEmail($email)
    {
        $data = array(
            'temp_unlock_time' => NULL,
            'temp_unlock_code' => NULL
        );

        $this->db->where('email', $email);
        $this->db->where('role_id', '2');
        $this->db->update('users', $data);
    }

    // returns 1 if account is temporarily blocked
    public function checkTempAccountBlockStatus($email)
    {
        $query = $this->db->get_where('users', array('email' => $email, 'role_id' => '1'), 1);

        if ($query->num_rows()) {
            $user_info = $query->row();

            if (strtotime(date('Y-m-d H:i')) <= strtotime($user_info->temp_unlock_time)) {
                return 1;
            }
            else {
                if (!empty($user_info->temp_unlock_time)) {
                    $this->unblockAccountByEmail($email);
                }
            }
        }

        return 0;
    }

    public function checkTempPartnerAccountBlockStatus($email)
    {
        $query = $this->db->get_where('users', array('email' => $email, 'role_id' => '2'), 1);

        if ($query->num_rows()) {
            $user_info = $query->row();

            if (strtotime(date('Y-m-d H:i')) <= strtotime($user_info->temp_unlock_time)) {
                return 1;
            }
            else {
                if (!empty($user_info->temp_unlock_time)) {
                    $this->unblockPartnerAccountByEmail($email);
                }
            }
        }

        return 0;
    }
}
