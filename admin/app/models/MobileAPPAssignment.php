<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MobileAPPAssignment extends APP_Model
{

    public function __construct()
    {
        parent::__construct();


    }

    public function getAllEvents($fields = '*')
    {
        if (is_array($fields)) {

            $fields = implode(',', $fields);

        }
        $whereFields = '';
        $condition = '';


        $query = '
			SELECT ' . $fields . '
			FROM ma_events e
			WHERE
			e.status=1
			
			AND e.deleted = 0
		
		';
        if (!empty($condition)) {

            $query .= $condition;

        }
        if (!empty($orderby)) {

            $query .= ' ORDER BY ' . $orderby;

        }


        $result = $this->db->query($query);

        return $result;

    }

    public function getAllAssignments($fields = '*', $search = '', $start = 0, $limit = 0, $orderby = 'e.id DESC', $colums = '')
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
               SELECT a.id,u.full_name,a.video,a.is_approved,e.name,a.status,a.created_at FROM `mobile_app_assignments` a INNER JOIN users u ON a.user_id = u.id INNER JOIN ma_events e ON a.event_id=e.id';
        if (!empty($condition)) {

            $query .= $condition;

        }
        if (!empty($orderby)) {

            $query .= ' ORDER BY ' . $orderby;

        }

        if ($limit > 0) {

            $query .= ' LIMIT ' . 10 . ',' . $limit;

        }

        $result = $this->db->query($query);

        return $result;

    }

    public function getAssignmentById($id, $fields = '*')
    {
        if (is_array($fields)) {

            $fields = implode(',', $fields);

        }

        $query = '
			SELECT a.id, a.status,a.is_approved
		    FROM mobile_app_assignments a
		   
		    WHERE a.id = ' . $id . '
		';

        $result = $this->db->query($query);


        if ($result->num_rows() > 0) {

            return $result->row();

        } else {

            return false;

        }


    }


}
