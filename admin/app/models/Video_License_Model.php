<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video_License_Model extends APP_Model {

	public function __construct() {
        parent::__construct();


    }

	public function getAllLicenseLeads($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'vl.id DESC',$colums = '')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}
		$whereFields = '';
		$condition = '';
		if(!empty($search) && is_array($colums)){

			$count = count($colums);

			unset($colums[$count-1]);

			$condition = ' AND (';

			$whereFields = $colums;

			foreach($whereFields as $field){
                if($field['name'] != 'action' && $field['name'] != 'vl.action') {
                    $condition .= ' ' . $field['name'] . ' LIKE "%' . $search . '%" OR';
                }
			}

			$condition = rtrim($condition,'OR').')';
		}


		$query = '
			SELECT '.$fields.'
			FROM video_license vl
			LEFT JOIN videos v
			ON vl.video_id = v.id
			AND v.deleted = 0
			AND v.status = 1
			LEFT JOIN users u
			ON vl.partner_id = u.id
			AND u.deleted = 0
			AND u.status = 1
			LEFT JOIN countries c
			ON vl.country_id = c.id
			LEFT JOIN license_type lt
			ON vl.license_type_id = lt.id
		    WHERE vl.deleted = 0
		    AND vl.status = 1
		';
		if(!empty($condition)){

			$query .= $condition;

		}
		if(!empty($orderby)){

			$query .= ' ORDER BY '.$orderby;

		}

		if($limit > 0){

			$query .= ' LIMIT '.$start.','.$limit;

		}

		$result = $this->db->query($query);

		return $result;

	}



	public function getLicenseLeadById($id,$fields='*')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}

		$query = '
			SELECT '.$fields.'
		    FROM video_license vl
			LEFT JOIN videos v
			ON vl.video_id = v.id
			AND v.deleted = 0
			AND v.status = 1
			LEFT JOIN users u
			ON vl.partner_id = u.id
			AND u.deleted = 0
			AND u.status = 1
			LEFT JOIN countries c
			ON vl.country_id = c.id
			LEFT JOIN license_type lt
			ON vl.license_type_id = lt.id
		    WHERE vl.id = '.$id.'
		';

		$result = $this->db->query($query);

		if($result->num_rows() > 0){

			return $result->row();

		}else{

			return false;

		}


	}


}
