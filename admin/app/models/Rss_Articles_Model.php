<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rss_Articles_Model extends APP_Model {

	public function __construct() {
        parent::__construct();
    }

	public function getAllCategories()
	{
		$query = '
			SELECT *
		    FROM categories c
			WHERE deleted = 0
        ';

		$result = $this->db->query($query);

		return $result;
	}
	
	public function getAllArticleFeeds($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'raf.id DESC',$colums = '')
	{
		if(is_array($fields)){
			$fields = implode(',',$fields);
		}
		$query = '
			SELECT '.$fields.'
			FROM rss_article_feeds raf
			WHERE raf.deleted = 0
		';

		if($limit > 0){
			$query .= ' LIMIT '.$start.','.$limit;
		}

		$result = $this->db->query($query);

		return $result;

	}
	
	public function getAllArticles($fields = '*',$search = '',$start = 0,$limit = 0,$orderby = 'ra.id DESC',$colums = '')
	{
		if(is_array($fields)){
			$fields = implode(',',$fields);
		}
		$query = '
			SELECT '.$fields.'
			FROM rss_articles ra
			LEFT JOIN rss_article_slides ras
			ON ras.article_id = ra.id
			LEFT JOIN rss_article_feeds raf
			ON raf.id = ra.feed_id
			WHERE ra.deleted = 0
			GROUP BY ra.id
		';

		if($limit > 0){
			$query .= ' LIMIT '.$start.','.$limit;
		}

		$result = $this->db->query($query);

		return $result;

	}

	public function getNextArticleId ()
	{
		$query = '
			SELECT MAX(id) as id
			FROM rss_articles
		';

		$result = $this->db->query($query)->row();
		$next_id = $result->id + 1;

		return $next_id;
	}
	
	public function getArticleFeedByTitle($title)
	{

		$query = '
			SELECT *
		    FROM rss_article_feeds raf
			WHERE raf.feed_title = "'.$title.'"
		';

		$result = $this->db->query($query);

		return $result;

	}
	
	public function getArticleFeedByURL($url)
	{
		$query = '
			SELECT *
		    FROM rss_article_feeds raf
			WHERE raf.feed_url = "'.$url.'"
		';

		$result = $this->db->query($query);

		return $result;

	}

	public function getArticlesByFeedId($feed_id)
	{
		$query = '
			SELECT *
			FROM rss_articles ra
			WHERE ra.feed_id = '. $feed_id . '
			AND ra.deleted = 0
		';

		$result = $this->db->query($query);

		return $result;
	}

	
	public function getAllPartners()
	{
		$query = '
			SELECT u.id, u.full_name 
			FROM users u
			WHERE u.role_id = 2
			AND u.deleted = 0
		';

		$result = $this->db->query($query);

		return $result;
	}

	public function getNameByPartnerId($id)
	{
		$query = '
			SELECT u.full_name
			FROM users u
			WHERE u.id = '. $id
		;

		$result = $this->db->query($query);

		return $result->row();
	}

	public function getArticleDataById($id)
	{
		$query = '
			SELECT ra.*, COUNT(ras.id) num_of_slides
			FROM rss_articles ra
			LEFT JOIN rss_article_slides ras
			ON ras.article_id = ra.id
			WHERE ra.id = '.$id.'
			AND ra.deleted = 0
		';

		$result = $this->db->query($query);

		return $result->row_array();
	}

	public function getArticleSlidesByID($id)
	{
		$query = '
			SELECT ras.*
			FROM rss_article_slides ras
			WHERE ras.article_id = '.$id
		;

		$result = $this->db->query($query);

		return $result->result_array();
	}

	public function getSlideImageURLsBySlideID($id)
	{
		$query = '
			SELECT ras.image_url, ras.image_s3_url
			FROM rss_article_slides ras
			WHERE ras.id = '.$id
		;

		$result = $this->db->query($query);

		return $result->row_array();
	}

}
