<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MobileAPPEvent extends APP_Model
{

    public function __construct()
    {
        parent::__construct();


    }

    public function getAllCategories($fields = '*')
    {
        if (is_array($fields)) {

            $fields = implode(',', $fields);

        }
        $whereFields = '';
        $condition = '';


        $query = '
			SELECT ' . $fields . '
			FROM mobile_app_categories c
			WHERE c.parent_id =0
			AND
			c.status=1
			
			AND c.deleted = 0
		
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

    public function getAllEvents($fields = '*', $search = '', $start = 0, $limit = 0, $orderby = 'e.id DESC', $colums = '')
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
                SELECT *,e.id FROM ma_events e INNER JOIN mobile_app_categories mc ON e.category_id=mc.id where e.status=1';
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

    public function getEventById($id, $fields = '*')
    {
        if (is_array($fields)) {

            $fields = implode(',', $fields);

        }

        $query = '
			SELECT ' . $fields . '
		    FROM ma_events e
		    WHERE e.id = ' . $id . '
		';

        $result = $this->db->query($query);

        if ($result->num_rows() > 0) {

            return $result->row();

        } else {

            return false;

        }


    }


}
