<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_Finance_Model extends APP_Model {

	public function __construct() {
        parent::__construct();

        
    }
	
    public function getAllSums(){


	    $query = "
	        SELECT SUM(e.client_net_earning) payment,c.symbol,u.full_name,u.email,u.paypal_email,u.address,u.id user_id,vl.created_at,vl.unique_key wid,v.lead_id,e.currency_id
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
            GROUP BY e.video_id
	    ";
	    return $this->db->query($query);
    }

    public function getAllSumsByDates($from,$to){


        $query = "
	        SELECT e.paid,e.earning_amount,e.wooglobe_net_earning,e.client_net_earning,e.expense_amount,c.symbol,u.full_name,u.email,u.paypal_email,u.address,u.id user_id,vl.created_at,vl.unique_key wid,v.lead_id,e.currency_id
			FROM `earnings` `e` 
            LEFT JOIN `currency` `c` ON e.currency_id = c.id
            INNER JOIN `videos` `v` ON e.video_id = v.id
            AND v.deleted = 0
			INNER JOIN `video_leads` `vl` ON v.lead_id = vl.id
            AND vl.deleted = 0
            INNER JOIN `users` `u` ON v.user_id = u.id
            AND u.deleted = 0
            WHERE e.deleted = 0
            AND e.status = 1
	        AND DATE(e.earning_date) BETWEEN '$from' AND '$to' 
	    ";

        return $this->db->query($query);
    }
}
