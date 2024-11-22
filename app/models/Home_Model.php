<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_Model extends APP_Model {

	public function __construct() {
        parent::__construct();

        
    }
	

	public function getPartnerName($partner){
	    $query = 'SELECT full_name from users where id='.$partner;
        $result = $this->db->query($query);
        return $result->row()->full_name;
    }
    public function getBrandName($brand_id){
        $query = 'SELECT brand_name from mrss_brands where id='.$brand_id;
        $result = $this->db->query($query);
        return $result->row()->brand_name;
    }
    public function getLeadByLeadId($id){

        $query = 'SELECT vl.*,v.question_video_taken FROM video_leads vl LEFT JOIN videos v ON v.lead_id = vl.id where vl.id= "'.$id.'" LIMIT 1';
        $result = $this->db->query($query);
        return $result->row();
    }
	public function getVideosByCategorySlug($categorySlug,$search,$start,$limit,$by='v.id',$sort='DESC'){


	    $category = $this->getCategoryBySlug($categorySlug);
        $categoriesIds =array();
	    if($category){
            $categoriesIds = $this->getAllCategoriesIds($category->id);
            if(!$categoriesIds){
                $categoriesIds[] = $category->id;
            }
        }else{

	        return false;
        }
        $categoriesIds = implode(',',$categoriesIds);
	    $from = ' FROM videos v
	             INNER JOIN edited_video ev
	             ON v.id = ev.video_id
	             INNER JOIN categories c
	             ON v.category_id = c.id
	             AND c.status = 1
	             AND c.deleted = 0
	             LEFT JOIN categories pc
	             ON c.parent_id = pc.id
	             AND pc.status = 1
	             AND pc.deleted = 0
	             INNER JOIN videos_types vt
	             ON v.video_type_id = vt.id 
	             AND vt.status = 1
	             AND vt.deleted = 0
	             INNER JOIN users u
	             ON v.user_id = u.id 
	             AND u.status = 1
	             AND u.deleted = 0
	             WHERE v.status = 1
	             AND v.deleted = 0
	             AND v.is_wooglobe_video = 1
	             AND (v.youtube_id IS NOT NULL AND v.youtube_id != "")
	             AND v.category_id in ('.$categoriesIds.')
	             ';
	    $select = 'SELECT c.title AS category_title,
	                      pc.title AS parent_category_title,
	                      v.title,
	                      v.description,
	                      v.tags,
	                      ev.portal_url AS url,
	                      v.category_id,
	                      ev.portal_thumb AS thumbnail,
	                      v.slug,
	                      v.embed,
	                      v.video_type_id,
	                      v.user_id,
	                      vt.title AS video_type';
	    $selectCount = 'SELECT COUNT(v.id) AS total';
	    $conditions = '';

	    $conditions .= 'ORDER BY '.$by.' '.$sort;

	    if($limit > 0){

            $conditions .= 'LIMIT '.$start.','.$limit;

        }

        $query = $select.$from.$conditions;
        $queryCount = $selectCount.$from.$conditions;

        $data['videos'] = $this->db->query($query)->result();
        //echo $this->db->last_query();exit;
        $data['videosCount'] = $this->db->query($queryCount)->row()->total;
        $data['category'] = $category->title;
        return $data;


    }

    public function getCategoryBySlug($slug){

        $result = $this->db->query('
                SELECT *
                FROM categories 
                WHERE slug = "'.$slug.'"
                AND status = 1
                AND deleted = 0
        ');

        if($result->num_rows() > 0){

            return $result->row();

        }else{

            return false;

        }

    }

    public function getLandscapeConvertedUrl($id){
        $query = '
            SELECT landscape_converted_url
            FROM raw_video 
            WHERE lead_id = '.$id;

        $result = $this->db->query($query);

        if($result->num_rows() > 0){
            return $result->row()->landscape_converted_url;
        }else{
            return null;
        }

    }

    public function getAllCategoriesIds($id){

        $result = $this->db->query('
                SELECT id
                FROM categories 
                WHERE (id = '.$id.' OR parent_id = '.$id.')
                AND status = 1
                AND deleted = 0
        ');

        //echo $this->db->last_query();exit;
        if($result->num_rows() > 0){

            $ids = array();
            foreach($result->result() as $row){
                $ids[] = $row->id;
            }


            return $ids;

        }else{

            return false;

        }

    }

    public function getPageContentById($id){

        $result = $this->db->query('
                SELECT *
                FROM content 
                WHERE id = '.$id.'
                AND status = 1
                AND deleted = 0
        ');
        //echo $this->db->last_query();exit;
        if($result->num_rows() > 0){

            return $result->row();

        }else{

            return false;

        }

    }
    public function video_details($slug)
    {
        $query = 'SELECT v.youtube_id,v.title as vtitle,v.category_id,v.tags,v.user_id,v.description,ev.portal_url AS url,ev.portal_thumb AS thumbnail,v.created_at,v.question_when_video_taken,v.slug,v.embed,v.id,u.full_name,u.picture,c.title as ctitle,vl.unique_key
                  FROM `videos` v
                  INNER JOIN video_leads vl
                  ON v.lead_id = vl.id
                  LEFT JOIN edited_video ev
                  ON v.id = ev.video_id
                  INNER JOIN categories c
                  ON v.category_id = c.id
                  AND c.status = 1
                  AND c.deleted = 0
                  INNER JOIN users u
                  ON v.user_id = u.id
                  AND u.status = 1
                  AND u.deleted = 0
                  WHERE v.slug = "'.$slug.'"
                  AND v.status = 1
                  AND v.deleted = 0';
        $result =  $this->db->query($query)->row_array();
        return $result;
    }

    public function list_videos($user_id = 0)
    {
    	if(empty($user_id)){
			$user_id = 0;
		}
        $query = "SELECT * FROM videos WHERE user_id = ".$user_id." AND (youtube_id IS NOT NULL AND youtube_id != '') ORDER BY  created_at DESC LIMIT 0,3";
        $res = $this->db->query($query)->result_array();

        return $res;
    }



    public function videosSearch($search,$start,$limit,$by='v.id',$sort='DESC'){
        $search = preg_replace('/\s+/', ' ', $search);
        //echo $search;exit;
        $from = ' FROM videos v
	             INNER JOIN categories c
	             ON v.category_id = c.id
	             AND c.status = 1
	             AND c.deleted = 0
	             LEFT JOIN categories pc
	             ON c.parent_id = pc.id
	             AND pc.status = 1
	             AND pc.deleted = 0
	             INNER JOIN videos_types vt
	             ON v.video_type_id = vt.id 
	             AND vt.status = 1
	             AND vt.deleted = 0
	             INNER JOIN users u
	             ON v.user_id = u.id 
	             AND u.status = 1
	             AND u.deleted = 0
	             WHERE v.status = 1
	             AND v.deleted = 0
	             AND v.is_wooglobe_video = 1
	             AND (v.youtube_id IS NOT NULL AND v.youtube_id != "")
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
	                      vt.title AS video_type';
        $selectCount = 'SELECT COUNT(v.id) AS total';
        $conditions = '';
        $conditions2 = '';
        $limitPart = '';
        if(!empty($search)){
            $keywords = explode(' ',$search);
            foreach ($keywords as $i=> $key){
                $keywords[$i] = '+'.$key.'*';
            }
            $keywords = trim(implode(' ',$keywords));

            $conditions .= ' AND MATCH(v.title,v.description,v.tags)
                   AGAINST( "' . $keywords . '" IN NATURAL LANGUAGE MODE) ';
            $conditions2 .= ' AND (v.title = "'.$search.'" OR v.description = "'.$search.'" OR v.tags = "'.$search.'") ';


        }
        $limitPart .= 'ORDER BY '.$by.' '.$sort;

        if($limit > 0){

            $limitPart .= ' LIMIT '.$start.','.$limit;

        }

        $query = $select.$from.$conditions.$limitPart;
        $query2 = $select.$from.$conditions2.$limitPart;
        $queryCount = $selectCount.$from.$conditions;

        $query2 = $this->db->query($query2)->result();
        $data['videos'] = array_merge($query2,$this->db->query($query)->result());
        /*echo '<pre>';
        print_r($this->db->query($query)->result());
        exit;*/
        //echo $this->db->last_query();exit;
        $data['videosCount'] = ($this->db->query($queryCount)->row()->total + count($query2));
        //$data['category'] = $category->title;
        return $data;


    }

    public function getVideosData($categorySlug){

        $categories = array();
        $limit = 1;
        if(empty($categorySlug)){
                $categories = $this->db->query('
                        SELECT *
                        FROM categories 
                        WHERE parent_id = 0
                        AND status = 1
                        AND deleted = 0
                        ORDER BY title ASC
                
                ');
                if($categories->num_rows() > 0){
                    $categories = $categories->result();
                }else{
                    return array();
                }
        }else{
            $limit = 0;
            $category = $this->db->query('
                        SELECT *
                        FROM categories 
                        WHERE slug = "'.$categorySlug.'"
                        AND status = 1
                        AND deleted = 0
                        ORDER BY title ASC
                
                ');
            if($category->num_rows() > 0){

                $category = $category->row();
                $categories = array($category);
                /*$categories = $this->db->query('
                        SELECT *
                        FROM categories 
                        WHERE parent_id = '.$category->id.'
                        AND status = 1
                        AND deleted = 0
                        ORDER BY title ASC
                
                ');
                if($categories->num_rows() > 0){
                    $categories = $categories->result();
                }else{
                    $categories = array($category);
                }*/

            }else{
                return array();
            }


        }

        foreach ($categories as $category){

            //$categoriesIds = $this->getAllCategoriesIds($category->id);
            //$category->videos = $this->getFourVideoByCategoryId(implode(',',$categoriesIds));
            $category->videos = $this->getFourVideoByCategoryId($category->id,$limit);
            //echo '<pre>';
            //print_r($category->videos);

        }
        //exit;
       return $categories;
    }

    public function getVideosDataPagination($categorySlug,$start){

        $categories = array();
        if(empty($categorySlug)){
            $categories = $this->db->query('
                        SELECT *
                        FROM categories 
                        WHERE parent_id = 0
                        AND status = 1
                        AND deleted = 0
                        ORDER BY title ASC
                
                ');
            if($categories->num_rows() > 0){
                $categories = $categories->result();
            }else{
                return array();
            }
        }else{
            $category = $this->db->query('
                        SELECT *
                        FROM categories 
                        WHERE slug = "'.$categorySlug.'"
                        AND status = 1
                        AND deleted = 0
                        ORDER BY title ASC
                
                ');
            if($category->num_rows() > 0){

                $category = $category->row();
                $categories = array($category);
                /*$categories = $this->db->query('
                        SELECT *
                        FROM categories
                        WHERE parent_id = '.$category->id.'
                        AND status = 1
                        AND deleted = 0
                        ORDER BY title ASC

                ');
                if($categories->num_rows() > 0){
                    $categories = $categories->result();
                }else{
                    $categories = array($category);
                }*/

            }else{
                return array();
            }


        }

        foreach ($categories as $category){

            //$categoriesIds = $this->getAllCategoriesIds($category->id);
            //$category->videos = $this->getFourVideoByCategoryId(implode(',',$categoriesIds));
            $category->videos = $this->getTenVideoByCategoryId($category->id,$start);
            //echo '<pre>';
            //print_r($category->videos);

        }
        //exit;
        return $categories;
    }

    public function getFourVideoByCategoryId($category_id,$limit){

	    $query = 'SELECT *
            FROM videos v
            INNER JOIN video_leads vl
            ON v.lead_id = vl.id
            AND vl.published_portal = 1
            WHERE v.status = 1
             AND v.deleted = 0
             AND v.is_wooglobe_video = 1
             AND (v.youtube_id IS NOT NULL AND v.youtube_id != "")
             AND find_in_set("'.$category_id.'", v.category_id)
             ORDER BY v.id DESC';
	    if($limit == 1){
            $query .= ' LIMIT 0,3';
        }

    



        $videos = $this->db->query($query);

        return $videos->result();
    }

    public function getTenVideoByCategoryId($category_id,$start,$limit = 9){


        $query = 'SELECT *
            FROM videos v
            INNER JOIN video_leads vl
            ON v.lead_id = vl.id
            AND vl.published_portal = 1
            WHERE v.status = 1
             AND v.deleted = 0
             AND v.is_wooglobe_video = 1
             AND (v.youtube_id IS NOT NULL AND v.youtube_id != "")
             AND find_in_set("'.$category_id.'", v.category_id)
             ORDER BY v.id DESC';
        if($limit > 0){
            $query .= " LIMIT $start,$limit";
        }





        $videos = $this->db->query($query);

        return $videos->result();
    }

    public function getLatestVideo($start = 0,$limit = 3){
        $query = 'SELECT *
            FROM videos v
            WHERE v.status = 1
             AND v.deleted = 0
             AND v.is_wooglobe_video = 1
             AND (v.youtube_id IS NOT NULL AND v.youtube_id != "")
             ORDER BY v.created_at DESC';
        if($limit > 0){
            $query .=" LIMIT $start,$limit; ";
        }



        $videos = $this->db->query($query);

        return $videos;
    }

    public function getTrendingVideo($start = 0,$limit = 3){

        $query = 'SELECT v.*,(SELECT COUNT(vv.id) FROM videos_views vv WHERE vv.video_id = v.id) as vcoun
            FROM videos v
            INNER JOIN video_leads vl
            ON v.lead_id = vl.id
            WHERE v.status = 1
             AND v.deleted = 0
             AND v.is_wooglobe_video = 1
             AND (v.youtube_id IS NOT NULL AND v.youtube_id != "")
             ORDER BY vcoun DESC';
        if($limit > 0){
            $query .=" LIMIT $start,$limit; ";
        }


        $videos = $this->db->query($query);

        return $videos;
    }

    public function getVideoTypes(){

        $result = $this->db->query('
	            SELECT * 
	            FROM videos_types
	            ORDER BY id ASC 
	    ');

        return $result;
    }

    public function getCountries()
    {
        $result = $this->db->query('
            SELECT *
            FROM countries
            ORDER BY name ASC
        ');
        $result = $result->result_array();
        return $result;
    }

    public function license_video($data)
    {
        $query = $this->db->insert_string('video_license',$data);
        $this->db->query($query);
        $id = $this->db->insert_id();
        return $id;
    }

    public function getVideoId($slug)
    {

        $query = "SELECT id From videos WHERE slug = '$slug'";


        $result = $this->db->query($query);
        $result = $result->row_array();
        return $result;
    }

    public function getLicenseType()
    {
        $query = "SELECT * From license_type ";
        $result = $this->db->query($query);
        $result = $result->result_array();
        return $result;
    }

    public function get_ids($id)
    {
        $query = "SELECT * From video_license WHERE id = '$id'";
        $result = $this->db->query($query);
        $result = $result->row_array();
        return $result;
    }

    public function getVideoCategories($ids){

        $category = $this->db->query('
                        SELECT *
                        FROM categories 
                        WHERE id in ('.$ids.')
                        AND status = 1
                        AND deleted = 0
                        ORDER BY title ASC
                
                ');


        //$category = $this->db->query($category);

        return $category->result();
    }

    public function view_count_video($video_id){
        $user_id = $this->sess->userdata('clientId');
        if(empty($user_id)){
            $user_id = 0;
        }

        $ip = $this->input->ip_address();

        $result = $this->db->query('
            SELECT *
            FROM videos_views
            WHERE (user_id != 0 AND user_id = '.$user_id.') 
            OR ip_address = "'.$ip.'"
        ');

        if($result->num_rows() == 0){
            $dbData['video_id'] = $video_id;
            $dbData['ip_address'] = $ip;
            $dbData['user_id'] = $user_id;
            $this->db->insert('videos_views',$dbData);
        }

        $result = $this->db->query('
            SELECT COUNT(id) as video_count
            FROM videos_views
            WHERE video_id = '.$video_id.'
        ');

        return $result->row()->video_count;

    }

    public function getSuggestedVideos($categories,$title,$video_id){

        if(!empty($title)){
            $title = preg_replace("/[^A-Za-z0-9 ]/", '', $title);
            $title = preg_replace('/\s+/', ' ', $title);
            $keywords = explode(' ',$title);
           $a=0;
            foreach ($keywords as $i=> $key){

               // $keywords[$i] = '+'.$key.'*';
                $keywords[$i] = strtolower($key);
            }
            $keywords = trim(implode(' ',$keywords));
        }

         $query = 'SELECT v.*,(SELECT COUNT(vv.id) FROM videos_views vv WHERE vv.video_id = v.id) as vcoun
            FROM video_categories vc
            INNER JOIN videos v 
            ON vc.video_id = v.id
            INNER JOIN video_leads vl
            ON v.lead_id = vl.id
            WHERE v.status = 1
            AND v.deleted = 0
            AND v.is_wooglobe_video = 1
            AND v.id != '.$video_id.'
            AND (v.youtube_id IS NOT NULL AND v.youtube_id != "")
            AND vc.category_id IN ('.$categories.') 
            AND MATCH(v.title,v.description,v.tags) AGAINST( "'.$keywords.'" IN BOOLEAN MODE)
            GROUP BY v.id
            ORDER BY vcoun DESC
            LIMIT 0,5
             ';
        $videos = $this->db->query($query);
        //echo $this ->db->last_query();exit;

        return $videos;
    }
    public function getRelatedVideos($title,$video_id)
    {
        if(!empty($title)){
            $title = preg_replace("/[^A-Za-z0-9 ]/", '', $title);
            $title = preg_replace('/\s+/', ' ', $title);
            $keywords = explode(' ',$title);
            foreach ($keywords as $i=> $key){
                $keywords[$i] = '+'.$key.'*';
            }
            $keywords = trim(implode(' ',$keywords));
        }
        $tags= 'SELECT `tags` FROM `videos` WHERE `id` = '.$video_id.' LIMIT 0,5';
        $extags=$this->db->query($tags)->result()[0]->tags;
        $tags=explode(',', $extags);
       // $a = 0;
        $tagkeywords=array();
        foreach($tags as $tag){
            if(strtolower($tag) == 'wooglobe'){
                continue;
            }
           // $tagkeywords[] = '+'.str_replace(' ', '', $tag).'*';
            if(!empty($tagkeywords) and !empty($tag)){
                $tagkeywords[] = $tag;
            }else if(!empty($tag)){
                $tagkeywords[] = '+'. $tag;
            }
            /*if($a == 0 ){
                $tagkeywords[] = '+'. $tag;
            }else{
                $tagkeywords[] = $tag;
            }$a++;*/

        }//print_r($tagkeywords);echo "<br>";
        $tags_results = trim(implode(' ',$tagkeywords));
        $tags_results =str_replace(array( '(', ')' ), '', $tags_results );

        $query = 'SELECT v.*,(SELECT COUNT(vv.id) FROM videos_views vv WHERE vv.video_id = v.id) as vcoun
            FROM video_categories vc
            INNER JOIN videos v 
            ON vc.video_id = v.id
            INNER JOIN video_leads vl
            ON v.lead_id = vl.id
            WHERE v.status = 1
            AND v.deleted = 0
            AND v.is_wooglobe_video = 1
            AND (v.youtube_id IS NOT NULL AND v.youtube_id != "")
            AND v.id != '.$video_id.'
            AND MATCH(v.title,v.description,v.tags) AGAINST( "'.$tags_results.'" IN BOOLEAN MODE)
            GROUP BY v.id
            ORDER BY vcoun DESC
            LIMIT 0,5
             ';

        $videos = $this->db->query($query);
        return $videos;
    }

    public function  getGeneralMRSSCatNameby($feed_id){
        $query = '
			SELECT id,title FROM `mrss_feeds` WHERE `id` in ('.$feed_id.') AND partner_id = 0';
        $result = $this->db->query($query);

        return $result;
    }
    public function  getReutersCatNameby($feed_id){
        $query = '
			SELECT id,slug as title FROM `categories` WHERE `id` in ('.$feed_id.') AND parent_id = 1';
        $result = $this->db->query($query);

        return $result;
    }
	
	public function getArticleFeed($partner, $url)
	{
		$query = '
			SELECT *
		    FROM rss_article_feeds raf
			WHERE raf.feed_url = "'.$partner.'/'.$url.'"
		';

		$result = $this->db->query($query);

		return $result;

	}

	public function getArticlesByFeedId($feed_id)
	{
		$query = '
			SELECT ra.*, c.title category_name
			FROM rss_articles ra
            LEFT JOIN categories c
            ON c.id = ra.category
			WHERE ra.feed_id = '. $feed_id .'
            ORDER BY ra.updated_at DESC
		';
        
		$result = $this->db->query($query);

		return $result;
	}

	public function getSlidesByArticleId($article_id)
	{
		$query = '
			SELECT *
			FROM rss_article_slides ras
			WHERE ras.article_id = '. $article_id
		;
        
		$result = $this->db->query($query);

		return $result;
	}
}
