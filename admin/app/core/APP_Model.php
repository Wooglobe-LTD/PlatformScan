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

	public function getEmailTemplateByCode($code){


        $query = '
			SELECT *
		    FROM email_templates et
		    WHERE et.short_code = "'.$code.'"
		';
        $result = $this->db->query($query);
        if($result->num_rows() > 0){

            return $result->row();

        }else{
            return false;
        }
    }

    public function getEmailTemplateById($id)
    {

        $query = '
			SELECT *
		    FROM email_templates et
		    WHERE et.id = "'.$id.'"
		';
        $result = $this->db->query($query);
        if($result->num_rows() > 0){

            return $result->row();

        }else{
            return false;
        }

    }

    public function videosSearch($search,$start,$limit,$by='v.id',$sort='DESC'){
        $search = preg_replace('/\s+/', ' ', $search);
        $user_id = 0;
        //$pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
        //if(preg_match($pattern, $search)){
         //   $search1 = str_replace('@', '_', $search);
            // search term is email address
            //$q = 'SELECT id FROM users u WHERE email="'.$search.'"';
            //$r = $this->db->query($q)->result()[0];

            //$user_id = $r->id;

       // }
       // $q = 'SELECT * FROM video_leads vl WHERE MATCH(vl.email,vl.video_title,v.slug) AGAINST( "\' . $keywords . \'" IN NATURAL LANGUAGE MODE) \';';
        //echo $search;exit;
        $from = ' FROM videos v
	             INNER JOIN video_leads vl
	             ON vl.id = v.lead_id
	             AND vl.deleted = 0
	             INNER JOIN categories c
	             ON v.category_id = c.id
	             AND c.deleted = 0
	             LEFT JOIN categories pc
	             ON c.parent_id = pc.id
	             AND pc.deleted = 0
	             INNER JOIN videos_types vt
	             ON v.video_type_id = vt.id 
	             AND vt.deleted = 0
	             INNER JOIN users u
	             ON v.user_id = u.id 
	             AND u.deleted = 0
	             WHERE v.is_wooglobe_video = 1
	             ';
        $select = 'SELECT c.title AS category_title,
	                      pc.title AS parent_category_title,
	                      v.title,
	                      v.description,
	                      v.tags,
	                      v.url,
	                      v.category_id,
	                      v.slug,
	                      v.thumbnail,
	                      v.embed,
	                      v.video_type_id,
	                      v.user_id,
	                      v.bulk,
	                      v.youtube_id,
	                      vl.id AS lead_id,
	                      vt.title AS video_type';
        $selectCount = 'SELECT COUNT(v.id) AS total';
        $conditions = '';
        $conditions2 = '';
        $limitPart = '';
        if(!empty($search)){
            //if($user_id == 0){
                $keywords = explode(' ',str_replace('@', '', $search));
                foreach ($keywords as $i=> $key){
                    $keywords[$i] = '+'.$key.'*';
                }
                $keywords = trim(implode(' ',$keywords));

                $conditions .= ' AND MATCH(v.title,v.description,v.tags)
                   AGAINST( "' . $keywords . '" IN NATURAL LANGUAGE MODE) ';
                $conditions2 .= ' WHERE (vl.unique_key = "'.$search.'" OR vl.video_url = "'.$search.'" OR vl.email = "'.$search.'" OR vl.video_title = "'.$search.'") ';// OR v.title = "'.$search.'" OR v.description = "'.$search.'" OR v.tags = "'.$search.'") ';

            /*}else{
                $conditions .= ' AND v.user_id='.$user_id.' ';
                $conditions2 .= ' AND v.user_id='.$user_id.' ';
            }*/
			// $limitPart .= ' ORDER BY MATCH(v.title,v.description,v.tags) AGAINST( "' . $keywords . '" IN NATURAL LANGUAGE MODE) DESC';


        }
        $limitPart .= 'ORDER BY '.$by.' '.$sort;

        if($limit > 0){

            $limitPart .= 'LIMIT '.$start.','.$limit;

        }

        $query = $select.$from.$conditions.$limitPart;
        $select2 = 'SELECT vl.id as lead_id, vl.rating_comments as bulk, vl.email, video_title as title, video_url as url FROM video_leads vl ';
        $query2 = $select2.$conditions2. 'ORDER BY vl.id '.$sort;
        $queryCount = $selectCount.$from.$conditions;

        $query2 = $this->db->query($query2)->result();
        if(count($query2) == 1){
            $data['detail'] = true;
            $data['single'] = $query2[0];
			$data['videos'] = array_merge($query2, array());
			$data['videosCount'] = 1;
			return $data;
        }
		// else if(count($query2) > 1){
		// 	$data['videos'] = $query2;
		// 	$data['videosCount'] = ($this->db->query($queryCount)->row()->total + count($query2));
		// 	return $data;
        // }
        $data['videos'] = array_merge($query2,$this->db->query($query)->result());
        /*echo '<pre>';
        print_r($this->db->query($query)->result());
        exit;*/
        //echo $this->db->last_query();exit;
        $data['videosCount'] = ($this->db->query($queryCount)->row()->total + count($query2));
        //$data['category'] = $category->title;
        return $data;


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
}
