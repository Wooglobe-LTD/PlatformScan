<?php

use phpDocumentor\Reflection\PseudoTypes\True_;

defined('BASEPATH') or exit('No direct script access allowed');

class User_Model extends APP_Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function getMrssBrandsUsersFromBrands($brand_id)
    {
        $query = '
            SELECT u.id,u.full_name
            FROM users u
            JOIN brands_mrss_feeds b ON u.id = b.partner_id
            WHERE u.deleted = 0 
            AND u.role_id = 2 
            AND b.brand_id = '.$brand_id.' ORDER BY u.id DESC';
        // $query = '
        //     SELECT u.id,u.full_name
        //     FROM users u
        //     JOIN mrss_brand_feeds b ON u.id = b.partner_id
        //     WHERE u.deleted = 0 
        //     AND u.role_id = 2 
        //     ORDER BY u.id DESC
		// ';
        $result = $this->db->query($query);

        return $result;
    }
    public function getAllUsers($type = 0, $fields = '*', $search = '', $start = 0, $limit = 0, $orderby = 'u.id DESC', $colums = '')
    {
        if (is_array($fields)) {

            $fields = implode(',', $fields);
        }
        $whereFields = '';
        $condition = '';
        if (!empty($search) && is_array($colums)) {

            $count = count($colums);

            unset($colums[$count - 1]);

            $condition = ' AND (';

            $whereFields = $colums;

            foreach ($whereFields as $field) {
                if ($field['name'] != 'action' && $field['name'] != 'c.action') {
                    $condition .= ' ' . $field['name'] . ' LIKE "%' . $search . '%" OR';
                }
            }

            $condition = rtrim($condition, 'OR') . ')';
        }


        $query = '
			SELECT ' . $fields . '
		    FROM users u
		    WHERE u.deleted = 0
		';
        if ($type > 0) {
            $query .=  ' AND u.role_id = ' . $type;
        }
        if (!empty($condition)) {

            $query .= $condition;
        }
        if (!empty($orderby)) {

            $query .= ' ORDER BY ' . $orderby;
        }

        if ($limit > 0) {

            $query .= ' LIMIT ' . $start . ',' . $limit;
        }
        $result = $this->db->query($query);

        return $result;
    }
    public function updateunBlockUsers($id)
    {
        $query = '
			UPDATE users u
		    SET u.deleted = 0
		    WHERE u.id = ' . $id . '
		';
        $result = $this->db->query($query);

        return $result;
    }
    public function getBlockUsers()
    {


        $query = '
			SELECT *
		    FROM users u
		    WHERE u.deleted = 1
		';


        $result = $this->db->query($query);

        return $result;
    }
    public function getNotInterested()
    {


        $query = '
			SELECT *  
			FROM `video_leads` 
			WHERE `status` = 11
		';


        $result = $this->db->query($query);

        return $result;
    }

    public function getAllUsersActive($type = 0, $fields = '*', $search = '', $start = 0, $limit = 0, $orderby = 'u.id DESC', $colums = '')
    {
        if (is_array($fields)) {

            $fields = implode(',', $fields);
        }
        $whereFields = '';
        $condition = '';
        if (!empty($search) && is_array($colums)) {

            $count = count($colums);

            unset($colums[$count - 1]);

            $condition = ' AND (';

            $whereFields = $colums;

            foreach ($whereFields as $field) {

                $condition .= ' ' . $field['name'] . ' LIKE "%' . $search . '%" OR';
            }

            $condition = rtrim($condition, 'OR') . ')';
        }


        $query = '
			SELECT ' . $fields . '
		    FROM users u
		    WHERE u.deleted = 0
		    AND u.status = 1
		';
        if ($type > 0) {
            $query .=  ' AND u.role_id = ' . $type;
        }
        if (!empty($condition)) {

            $query .= $condition;
        }
        if (!empty($orderby)) {

            $query .= ' ORDER BY ' . $orderby;
        }

        if ($limit > 0) {

            $query .= ' LIMIT ' . $start . ',' . $limit;
        }

        $result = $this->db->query($query);

        return $result;
    }

    public function getUserByEmail($email, $role = '')
    {

        $query = '
			SELECT *
		    FROM users u
		    WHERE u.email = "' . $email . '"';
        if (!empty($role) and is_numeric($role)) {
            $query .= 'AND u.deleted = 0 AND role_id=' . $role;
        }

        $result = $this->db->query($query);

        return $result;
    }
    public function getPartnerByEmail($email, $role = '', $deleted)
    {

        $query = '
			SELECT *
		    FROM users u
		    WHERE u.email = "' . $email . '"';
        if ($deleted == 0) {
            $query .= ' AND deleted=' . $deleted;
        }
        if (!empty($role) and is_numeric($role)) {
            $query .= ' AND role_id=' . $role;
        }

        $result = $this->db->query($query);

        return $result;
    }

    public function getUserById($id, $fields = '*')
    {
        if (is_array($fields)) {

            $fields = implode(',', $fields);
        }

        $query = '
			SELECT ' . $fields . '
		    FROM users u
		    WHERE u.id = ' . $id . '
		';

        $result = $this->db->query($query);

        if ($result->num_rows() > 0) {

            return $result->row();
        } else {

            return false;
        }
    }

    public function email_notification($db_data)
    {
        $query = 'UPDATE email_notification_history 
                  SET email_template_id = ' . $db_data['email_template_id'] . ',
                  email_title = ' . $db_data['email_title'] . ',
                  ids = "' . $db_data['ids'] . '"
                  WHERE id = ' . $db_data['lead_id'] . '
                  ';
        $result = $this->db->query($query);
        return $result;
    }
    
    public function getPartnerEmailsById($id)
    {
        $query = '
			SELECT email
		    FROM partner_emails pe
		    WHERE pe.partner_id = ' . $id
        ;

        $result = $this->db->query($query);

        return $result;
    }
}
