<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class APP_Model extends CI_Model {

	public function __construct() {
        parent::__construct();

		       
    }
	
	
	public function getAll($table,$fields = '*',$where = '',$order = 'id ASC',$start = 0,$limit = 0){
		
		$result = false;
		
		$condition = '';
		
		if(is_array($where)){
			
			foreach($where as $i=>$v){
				
				$condition .= 'AND '.$i.' = '.'"'.$v.'" ';
				
			}
			
		}else{
			
			$condition = $where;
			
		}
		
		if(!empty($table)){
			if($fields == '*'){
				$fields =array('*');
			}
			$query = '
				SELECT '.implode(',',$fields).'
				FROM '.$table.'
				WHERE 1=1
				'.$condition.'
				ORDER BY '.$order.'
			';
			
			if($start > 0 && $limit > 0){
				
				$condition .= 'LIMIT '.$start.','.$limit;
				
			}else if($start > 0 && $limit > 0){
				
				$condition .= 'LIMIT '.$limit;
				
			}
			
			$result = $this->db->query($query);
			
		}
		
		return $result;
	}
	
	
	public function getById($table,$id,$fields = '*'){
		
		$result = false;
		
		
		if(!empty($table)){
			
			$query = '
				SELECT '.implode(',',$fields).'
				FROM '.$table.'
				WHERE id = '.$id.'
				
			';
			
			$result = $this->db->query($query);
			
			if($result->num_rows() > 0){
				
				$result = $result->row();
			}else{
				
				$result = false;
				
			}
			
		}
		
		return $result;
	}
	
	
	public function updateById($table,$fields,$id){
		
		$result = false;
		
		$condition = '';
		
		if(is_array($fields)){
			
			foreach($fields as $i=>$v){
				
				$condition .= $i.' = '.'"'.$v.'",';
				
			}
			
			$condition = rtrim($condition,',');
			
		}else{
			
			return false;
			
		}
		
		if(!empty($table)){
			
			$query = '
				UPDATE '.$table.'
				SET '.$condition.'
				WHERE id = '.$id.'
			';
			
			
			
			$result = $this->db->query($query);
			
		}
		
		return $result;
	}
	
	public function delete($table,$id){
		
		$result = false;
		
		
		if(!empty($table)){
			
			$query = '
				DELETE 
				FROM '.$table.'
				WHERE id = '.$id.'
				
			';
			
			
			
			$result = $this->db->query($query);
			
			
		}
		
		return $result;
	}
	
	public function softDelete($table,$id){
		
		$result = false;
		
		
		if(!empty($table)){
			
			$query = '
				UPDATE '.$table.'
				SET deleted = 1
				WHERE id = '.$id.'
				
			';
			
			
			
			$result = $this->db->query($query);
			
			
		}
		
		return $result;
	}
    public function getVideos()
    {
        $query = "SELECT * FROM videos WHERE is_wooglobe_video = '1' AND status = '1' AND deleted = '0'";
        $results = $this->db->query($query)->result_array();
        //return $result->result_array();
        $videos = array();
        foreach($results as $result){
        	$title = array('label' => $result['title'],'value'=>$result['title']);
            $videos[] = $title;
        }
        return $videos;
    }

    public function getTemplateByShortCode($code)
    {
        $query = "Select * FROM email_templates where short_code = '".$code."'";
        $result = $this->db->query($query);
        if($result->num_rows() > 0){
            return $result->row();
        }else{
            return false;
        }

    }

    public function getSuperAdmin()
    {
        $query = "Select * FROM admin where id = 1";
        $result = $this->db->query($query);
        if($result->num_rows() > 0){
            return $result->row();
        }else{
            return false;
        }

    }

    public function getStatesByCountryId($country_id){
        $query = "Select * FROM states where country_id = $country_id";
        $result = $this->db->query($query);
        if($result->num_rows() > 0){
            return $result->result();
        }else{
            return array();
        }

    }
}
