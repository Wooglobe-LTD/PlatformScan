<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MobileAPPPage extends APP_Model
{

    public function __construct()
    {
        parent::__construct();


    }

   

    public function getAllPages($fields = '*', $search = '', $start = 0, $limit = 0, $orderby = 'id DESC', $colums = '')
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
                if ($field['name'] != 'action' && $field['name'] != 'action') {
                    $condition .= ' ' . $field['name'] . ' LIKE "%' . $search . '%" OR';
                }
            }

            $condition = rtrim($condition, 'OR') . ')';
        }


        // $query = '
        //         SELECT *,e.id,e.name as name ,(case when (e.status = 1) THEN "Active" ELSE "Inactive" END) as status ,mac.name as country_name FROM ma_events e INNER JOIN mobile_app_categories mc ON e.category_id=mc.id INNER JOIN ma_countries as mac ON mac.id=e.country_id
        $query = '
            SELECT '.$fields.'
            FROM ma_pages            
            where deleted IS NULL
             
        ';        if (!empty($condition)) {

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

    public function getPageById($id, $fields = '*')
    {
        if (is_array($fields)) {

            $fields = implode(',', $fields);

        }

        $query = '
			SELECT ' . $fields . '
		    FROM ma_pages
		    WHERE id = ' . $id . '
		';

        $result = $this->db->query($query);

        if ($result->num_rows() > 0) {

            return $result->row();

        } else {

            return false;

        }


    }


}
