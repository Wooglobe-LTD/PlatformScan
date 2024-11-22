<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_Model extends APP_Model {

	public function __construct() {
        parent::__construct();

        
    }
	
	public function getAllPayments($user_id = 0,$fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'e.id DESC',$colums = '')
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
                if(!empty($field['name'])){
                    if($field['name'] != 'action' && $field['name'] != 'c.action'){
                        $condition .= ' '.$field['name'].' LIKE "%'.$search.'%" OR';
                    }
                }
			}

			$condition = rtrim($condition,'OR').')';
		}

		if(!empty($user_id) && $user_id > 0){

            $user_id = ' AND u.id = '.$user_id.'';
        }else{

            $user_id = '';

        }

		$query = '
			SELECT '.$fields.'
			FROM `earnings` `e` 
            LEFT JOIN `currency` `c` ON e.currency_id = c.id
            INNER JOIN `videos` `v` ON e.video_id = v.id
            INNER JOIN `video_leads` `vl` ON vl.id = v.lead_id
            AND v.deleted = 0
            INNER JOIN `users` `u` ON v.user_id = u.id
            AND u.deleted = 0
            WHERE e.deleted = 0
            AND e.paid IN (0,2)
            AND e.status = 1
            GROUP BY u.id
		';
		$query .= $user_id;
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

    public function getAllPaymentsByUser($user_id = 0,$fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'e.id DESC',$colums = '')
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
                if(!empty($field['name'])){
                    if($field['name'] != 'action' && $field['name'] != 'c.action'){
                        $condition .= ' '.$field['name'].' LIKE "%'.$search.'%" OR';
                    }
                }
            }

            $condition = rtrim($condition,'OR').')';
        }

        if(!empty($user_id) && $user_id > 0){

            $user_id = ' AND u.id = '.$user_id.'';
        }else{

            $user_id = '';

        }

        $query = '
			SELECT '.$fields.'
			FROM `earnings` `e` 
            LEFT JOIN `currency` `c` ON e.currency_id = c.id
            INNER JOIN `videos` `v` ON e.video_id = v.id
            AND v.deleted = 0
            INNER JOIN `video_leads` `vl` ON v.lead_id = vl.id
            AND vl.deleted = 0
            INNER JOIN `users` `u` ON v.user_id = u.id
            AND u.deleted = 0
            WHERE e.deleted = 0
            AND e.paid = 0
            AND e.status = 1
            
		';
        $query .= $user_id;
        if(!empty($condition)){

            $query .= $condition;

        }
        //$query .= ' GROUP BY e.video_id ';
        if(!empty($orderby)){

            $query .= ' ORDER BY '.$orderby;

        }

        if($limit > 0){

            $query .= ' LIMIT '.$start.','.$limit;

        }
        $result = $this->db->query($query);

        return $result;

    }

    public function getAllEarningRequests($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'e.id DESC',$colums = '')
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
                if(!empty($field['name'])){
                    if($field['name'] != 'action' && $field['name'] != 'c.action'){
                        $condition .= ' '.$field['name'].' LIKE "%'.$search.'%" OR';
                    }
                }
            }

            $condition = rtrim($condition,'OR').')';
        }



        $query = '
			SELECT '.$fields.'
			FROM earnings e
			LEFT JOIN earning_types et
			ON e.earning_type_id = et.id
			AND et.status = 1
			AND et.deleted = 0
			LEFT JOIN videos v
			ON e.video_id = v.id
			AND v.status = 1
			AND v.deleted = 0
			LEFT JOIN social_sources ss
			ON e.social_source_id = ss.id
			AND ss.status = 1
			AND ss.deleted = 0
			LEFT JOIN users u
			ON e.partner_id = u.id
			AND u.status = 1
			AND u.deleted = 0
			LEFT JOIN currency c
			ON e.currency_id = c.id
			WHERE e.deleted = 0
			AND e.status = 0
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
	
	public function getEarningById($id,$fields='*')
	{
		if(is_array($fields)){

			$fields = implode(',',$fields);

		}

		$query = '
			SELECT '.$fields.'
		    FROM earnings e
			LEFT JOIN earning_types et
			ON e.earning_type_id = et.id
			AND et.status = 1
			AND et.deleted = 0
			LEFT JOIN videos v
			ON e.video_id = v.id
			AND v.status = 1
			AND v.deleted = 0
			LEFT JOIN social_sources ss
			ON e.social_source_id = ss.id
			AND ss.status = 1
			AND ss.deleted = 0
			LEFT JOIN users u
			ON e.partner_id = u.id
			AND u.status = 1
			AND u.deleted = 0
		    WHERE e.id = '.$id.'
		';
		
		$result = $this->db->query($query);
		
		if($result->num_rows() > 0){

			return $result->row();

		}else{

			return false;

		}

		
	}

    public function getAllPaymentsByEarningsIds($ids,$fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'e.id DESC',$colums = '')
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
                if(!empty($field['name'])){
                    if($field['name'] != 'action' && $field['name'] != 'c.action'){
                        $condition .= ' '.$field['name'].' LIKE "%'.$search.'%" OR';
                    }
                }
            }

            $condition = rtrim($condition,'OR').')';
        }

        if(!empty($ids)){

            $ids = ' AND e.id in ( '.$ids.' )';
        }else{

            $ids = '';

        }

        $query = '
			SELECT '.$fields.'
			FROM `earnings` `e` 
            LEFT JOIN `currency` `c` ON e.currency_id = c.id
            INNER JOIN `videos` `v` ON e.video_id = v.id
            AND v.deleted = 0
            INNER JOIN `video_leads` `vl` ON v.lead_id = vl.id
            AND vl.deleted = 0
            INNER JOIN `users` `u` ON v.user_id = u.id
            AND u.deleted = 0
            WHERE e.deleted = 0
            AND e.paid = 0
            AND e.status = 1
            
		';
        $query .= $ids;
        if(!empty($condition)){

            $query .= $condition;

        }
        //$query .= ' GROUP BY e.video_id ';
        if(!empty($orderby)){

            $query .= ' ORDER BY '.$orderby;

        }

        if($limit > 0){

            //$query .= ' LIMIT '.$start.','.$limit;

        }
        $result = $this->db->query($query);

        return $result;

    }

    public function getAllPaymentsByEarningsIdsHistory($ids,$fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'e.id DESC',$colums = '')
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
                if(!empty($field['name'])){
                    if($field['name'] != 'action' && $field['name'] != 'c.action'){
                        $condition .= ' '.$field['name'].' LIKE "%'.$search.'%" OR';
                    }
                }
            }

            $condition = rtrim($condition,'OR').')';
        }

        if(!empty($ids)){

            $ids = ' AND e.id in ( '.$ids.' )';
        }else{

            $ids = '';

        }

        $query = '
			SELECT '.$fields.'
			FROM `earnings` `e` 
            LEFT JOIN `currency` `c` ON e.currency_id = c.id
            INNER JOIN `videos` `v` ON e.video_id = v.id
            AND v.deleted = 0
            INNER JOIN `video_leads` `vl` ON v.lead_id = vl.id
            AND vl.deleted = 0
            INNER JOIN `users` `u` ON v.user_id = u.id
            AND u.deleted = 0
            WHERE e.deleted = 0
            AND e.paid = 2
            AND e.status = 1
            
		';
        $query .= $ids;
        if(!empty($condition)){

            $query .= $condition;

        }
        //$query .= ' GROUP BY e.video_id ';
        if(!empty($orderby)){

            $query .= ' ORDER BY '.$orderby;

        }

        if($limit > 0){

            //$query .= ' LIMIT '.$start.','.$limit;

        }
        $result = $this->db->query($query);

        return $result;

    }

    public function getAllPaymentsHistory($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'i.id DESC',$colums = '')
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
                if(!empty($field['name'])){
                    if($field['name'] != 'action' && $field['name'] != 'c.action'){
                        $condition .= ' '.$field['name'].' LIKE "%'.$search.'%" OR';
                    }
                }
            }

            $condition = rtrim($condition,'OR').')';
        }



        $query = '
			SELECT '.$fields.'
			FROM `invoice` `i` 
            WHERE 1 = 1
            
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

    public function getTaxRateByUser($user_id)
    {
		$query = '
            SELECT c.tax_rate, c.name AS country
            FROM users u
            LEFT JOIN countries c
            ON (c.id = u.country_id
            OR c.phonecode = CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(u.country_code, "-", 1), "+", -1) AS UNSIGNED))
            WHERE u.id = '.$user_id.'
            AND c.name COLLATE utf8mb4_unicode_ci LIKE CONCAT(SUBSTRING(SUBSTRING_INDEX(u.country_code, "-", -1), 1, 1), "%")
        ';
		$result = $this->db->query($query);
		
		if($result->num_rows() > 0){
			return $result->row();
		}
        else{
			return false;
		}
    }


}
