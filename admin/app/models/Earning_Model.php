<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Earning_Model extends APP_Model {

	public function __construct() {
        parent::__construct();

        
    }
	
	public function getAllEarnings($video_id = 0,$fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'e.id DESC',$colums = '')
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

		if(!empty($video_id) && $video_id > 0){

            $video_id = ' AND e.video_id = '.$video_id.'';
        }else{

            $video_id = '';

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
			LEFT JOIN payment_log pl
			ON v.id = pl.video_id
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
			LEFT JOIN video_leads vl ON vl.id = v.lead_id
			WHERE e.deleted = 0
			
		';
		$query .= $video_id;
		if(!empty($condition)){

			$query .= $condition;

		}
        $query .= ' GROUP BY e.id';
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
			LEFT JOIN currency c1
			ON c1.id = u.currency_id
			LEFT JOIN video_leads vl
			ON vl.id = v.lead_id
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


}
