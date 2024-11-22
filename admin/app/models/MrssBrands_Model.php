<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MrssBrands_Model extends APP_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function getMrssPartners()
	{
		$query = '
			SELECT c.id, title, partner_id, slug, url, u.full_name
			FROM mrss_feeds c, users u
		    WHERE c.deleted = 0
		    AND c.type = 0
		    AND c.status = 1
		    AND u.role_id=2
		    AND c.partner_id = u.id
		';
		return $this->db->query($query);
	}

	public function getMrssBrands($mrss_brands_only = false)
	{
		// $query = '
		// 	SELECT mpb.partner_id, mpb.brand_id, mb.brand_name
		// 	FROM mrss_partner_brands mpb
		//     INNER JOIN mrss_brands mb
		//     ON mpb.brand_id = mb.id
		//     AND mpb.partner_id = '.$id
		// ;
		if ($mrss_brands_only) {
			$query = '
			SELECT b.id, b.brand_name FROM mrss_brands b JOIN brands_mrss_feeds f ON b.id = f.brand_id
			';
		} else {
			$query = '
			SELECT id, brand_name FROM mrss_brands
			';
		}
		return $this->db->query($query);
	}

	public function getMrssBrandFeedsByLeadId($id)
	{
		$query = '
			SELECT mbf.*, u.full_name, mb.brand_name
			FROM mrss_brand_feeds mbf
			INNER JOIN users u
			ON mbf.partner_id = u.id
			AND u.role_id = 2
			INNER JOIN mrss_brands mb
			ON mbf.brand_id = mb.id
            WHERE mbf.lead_id = ' . $id;
		return $this->db->query($query);
	}

	public function getMrssBrandFeedById($id)
	{
		$query = '
			SELECT *
			FROM mrss_brand_feeds mbf
			WHERE mbf.id = ' . $id;
		$result = $this->db->query($query);

		return $result->row();
	}

	public function existsPartnerBrandLead($partner_id, $brand_id, $lead_id)
	{
		$_partner_id = implode(',', $partner_id);

		// $query = '
		//     SELECT EXISTS (
		//         SELECT 1
		//         FROM mrss_brand_feeds mbf
		//         WHERE mbf.partner_id = "' . $partner_id . '"
		//         AND mbf.brand_id = "' . $brand_id . '"
		// 		AND mbf.lead_id = "' . $lead_id . '"
		//     ) as exist LIMIT 1
		// ';
		$query = 'SELECT EXISTS (
			SELECT 1
			FROM mrss_brand_feeds mbf
			WHERE mbf.partner_id IN (' . $_partner_id . ')
			AND mbf.brand_id = ' . $brand_id . '
			AND mbf.lead_id = "' . $lead_id . '"
		) as exist LIMIT 1';
	
		$result = $this->db->query($query)->result()[0];

		return $result->exist === "1" ? TRUE : FALSE;
	}

	public function remove_mrss_brand_feed($id)
	{
		$query = '
		DELETE FROM mrss_brand_feeds
		WHERE id = '.$id;

		$result = $this->db->query($query);

		return $result;
	}

}
