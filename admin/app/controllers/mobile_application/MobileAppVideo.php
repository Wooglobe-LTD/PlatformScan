<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MobileAppVideo extends APP_Model
{

    public function __construct()
    {
        parent::__construct();


    }

    public function getAllVideos($type = 2, $fields = '*', $search = '', $start = 0, $limit = 0, $orderby = 'v.id DESC', $colums = '')
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
                if ($field['name'] != 'v.action' && !empty($field['name'])) {

                    $condition .= ' ' . $field['name'] . ' LIKE "%' . $search . '%" OR';
                }
            }

            $condition = rtrim($condition, 'OR') . ')';
        }

        /*    if(!empty($type) && $type != 2){

                $type = ' AND v.is_wooglobe_video = '.$type.'';
            }else if($type == 0){*/

        // $type = ' AND v.is_wooglobe_video = 0';

        /*}else{
            $type = '';
        }*/

        $query = '
			SELECT ' . $fields . '
			FROM ma_videos v
			LEFT JOIN admin a
			ON v.created_by = a.id
			AND a.deleted = 0
			LEFT JOIN mobile_app_categories c
			ON v.category_id = c.id
			AND c.deleted = 0
			
		    WHERE v.deleted = 0
		';
      //  $query .= $type;
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


    public function getAllVideosActive($type = 2, $fields = '*', $search = '', $start = 0, $limit = 0, $orderby = 'v.id DESC', $colums = '')
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

        if (!empty($type) && $type != 2) {

            $type = ' AND v.is_wooglobe_video = ' . $type . '';
        } else if ($type == 0) {

            $type = ' AND v.is_wooglobe_video = 0';

        } else {
            $type = '';
        }

        $query = '
			SELECT ' . $fields . '
			FROM ma_videos v
			LEFT JOIN users u
			ON v.user_id = u.id
			AND u.deleted = 0
			LEFT JOIN mobile_app_categories c
			ON v.category_id = c.id
			AND c.deleted = 0
			LEFT JOIN videos_types t
			ON v.video_type_id = t.id
			AND t.deleted = 0
		    WHERE v.deleted = 0
		    AND v.status = 1
		';
        $query .= $type;
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

    public function getVideoByTitle($title, $parent_id)
    {

        $query = '
			SELECT *
		    FROM ma_videos v
			WHERE v.title = "' . $title . '"
		';

        $result = $this->db->query($query);

        return $result;
    }

    public function getRawVideoById($id)
    {
        $query = '
			SELECT *
		    FROM raw_video rv
			WHERE rv.video_id = "' . $id . '"
		';
        $result = $this->db->query($query);

        return $result->result_array();
    }

    public function getRawVideoById1($id)
    {
        $query = '
			SELECT *
		    FROM raw_video rv
			WHERE rv.video_id = "' . $id . '"
		';
        $result = $this->db->query($query);

        return $result->result();
    }

    public function getVideoById($id, $fields = '*')
    {
        if (is_array($fields)) {

            $fields = implode(',', $fields);

        }

        $query = '
			SELECT ' . $fields . '
		    FROM ma_videos v
		    WHERE v.id = ' . $id . '
		';

        $result = $this->db->query($query);

        if ($result->num_rows() > 0) {

            return $result->row();

        } else {

            return false;

        }


    }

    public function getLeadByVideoId($id)
    {
        $query = '
	        SELECT v.lead_id
	        FROM ma_videos v
	        WHERE v.id = ' . $id . '
	    ';

        $result = $this->db->query($query);

        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }

    }


    public function getParentCategories($fields = '*')
    {
        if (is_array($fields)) {

            $fields = implode(',', $fields);

        }

        $query = '
			SELECT ' . $fields . '
		    FROM mobile_app_categories c
			WHERE c.parent_id = 0
			AND c.status = 1
			AND c.deleted = 0
			ORDER BY c.title ASC
		';

        $result = $this->db->query($query);

        return $result;

    }

    public function getKeywords($fields = '*')
    {
        if (is_array($fields)) {

            $fields = implode(',', $fields);

        }

        $query = '
			SELECT ' . $fields . '
		    FROM mobile_application_keywords k			
			Where k.status = 1
			AND k.deleted = 0
			ORDER BY k.keyword ASC
		';

        $result = $this->db->query($query);

        return $result;

    }

    public function getSubCategories($fields = '*', $id)
    {
        if (is_array($fields)) {

            $fields = implode(',', $fields);

        }

        $query = '
			SELECT ' . $fields . '
		    FROM categories c
			WHERE c.parent_id = ' . $id . '
			AND c.status = 1
			AND c.deleted = 0
			ORDER BY c.title ASC
		';

        $result = $this->db->query($query);

        return $result;

    }

    public function getAllUsers($fields = '*')
    {
        if (is_array($fields)) {

            $fields = implode(',', $fields);

        }

        $query = '
			SELECT ' . $fields . '
		    FROM users u
			WHERE u.status = 1
			AND u.deleted = 0
			ORDER BY u.full_name ASC
		';

        $result = $this->db->query($query);

        return $result;

    }

    public function getAllVideoTypes($fields = '*')
    {
        if (is_array($fields)) {

            $fields = implode(',', $fields);

        }

        $query = '
			SELECT ' . $fields . '
		    FROM videos_types vt
			WHERE vt.status = 1
			AND vt.deleted = 0
			ORDER BY vt.id ASC
		';

        $result = $this->db->query($query);

        return $result;

    }


    public function getVideoDetailById($id, $fields = '*')
    {
        if (is_array($fields)) {

            $fields = implode(',', $fields);

        }

        $query = '
			SELECT ' . $fields . '
			FROM ma_videos v
			LEFT JOIN mobile_app_categories c
			ON c.id = v.category_id
		    WHERE v.id = ' . $id . '
		';

        $result = $this->db->query($query);

        if ($result->num_rows() > 0) {

            return $result->row();

        } else {

            return false;

        }


    }

    public function getAllVideosOriginalFiles($id = 0, $fields = '*', $search = '', $start = 0, $limit = 0, $orderby = 'rv.id DESC', $colums = '')
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

        if (!empty($id) && $id > 0) {

            $type = ' AND rv.video_id = ' . $id . '';
        } else {
            $type = '';
        }

        $query = '
			SELECT ' . $fields . '
			FROM raw_video rv
			LEFT JOIN ma_videos v
			ON rv.video_id = v.id
		    WHERE 1 = 1
		';
        $query .= $type;
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

    public function getFileById($id, $fields = '*')
    {
        if (is_array($fields)) {

            $fields = implode(',', $fields);

        }

        $query = '
			SELECT ' . $fields . '
		    FROM raw_video rv
			LEFT JOIN ma_videos v
			ON rv.video_id = v.id
		    WHERE rv.id = ' . $id . '
		';

        $result = $this->db->query($query);

        if ($result->num_rows() > 0) {

            return $result->row();

        } else {

            return false;

        }


    }

    public function getLeadDetailByVideoId($id)
    {
        $query = '
	        SELECT vl.*
	        FROM ma_videos v
	        INNER JOIN video_leads vl
	        ON v.lead_id = vl.id
	        WHERE v.id = ' . $id . '
	    ';

        $result = $this->db->query($query);

        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }

    }


}
