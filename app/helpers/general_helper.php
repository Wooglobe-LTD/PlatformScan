<?php


function auth (){
	
	$ci = &get_instance();

	
	$sess = $ci->sess->userdata('isClientLogin');
	
	if(!$sess){
		
		redirect('login');
		
	}
	
}

function auth_partner (){

    $ci = &get_instance();


    $sess = $ci->sess->userdata('isPartnerLogin');

    if(!$sess){

        redirect('login/feed');

    }

}


function auth_ajax (){
	
	$ci = &get_instance();
	
	$sess = $ci->sess->userdata('isClientLogin');
	
	if(!$sess){
		$response = array();
		
		$response['code'] = 204;
		$response['message'] = 'Invalid user session!';
		$response['error'] = 'Session expire.';
		$response['url'] = base_url().'login';
		
		return $response;
		
	}else{
		return false;
	}
	
}

function auth_verify (){
	
	$ci = &get_instance();
	
	$sess = $ci->sess->userdata('isClientLogin');
	
	if($sess){
		
		redirect('home');
		
	}
	
}

function db_result_to_array_map($res){
    $ret_arr = [];
    array_map(function ($value) use (&$ret_arr) {
        $result = array_values($value);
        return sizeof($result) == 2 ?
            $ret_arr[$result[0]] = $result[1]
            : $ret_arr[$result[0]] = array_slice($value, 1, sizeof($value));
    }, $res);

    return $ret_arr;
}

function settings(){
	
	$ci = &get_instance();
	
	$result = $ci->db->query('
		SELECT *
		FROM settings
		WHERE id = 1
	');
	
	return $result->row();
}

function parent_categories(){

    $ci = &get_instance();

    $result = $ci->db->query('
		SELECT *
		FROM categories
		WHERE parent_id = 0
		AND status = 1
		AND deleted = 0
		ORDER BY title ASC
	');

    return $result;
}

function child_categories($parent_id){

    $ci = &get_instance();

    $result = $ci->db->query('
		SELECT *
		FROM categories
		WHERE parent_id = '.$parent_id.'
		AND status = 1
		AND deleted = 0
		ORDER BY title ASC
	');

    return $result;
}
    function replace_variables($query,$id)
    {

        if (!empty($query)) {
            $query_parts = explode('.', $query);
            $table = trim($query_parts[0]);
            $column = trim($query_parts[1]);
            $ci = &get_instance();

            $table_query = $ci->db->query('
                   SELECT * 
                    FROM information_schema.COLUMNS 
                    WHERE TABLE_NAME = "' . $table . '" 
                    AND COLUMN_NAME = "' . $column . '" 
                ');
            if ($table_query->num_rows() > 0) {

                $result = $ci->db->query('
                        SELECT ' . $column . '
                        FROM ' . $table . '
                        WHERE id = ' . $id . '
                    ');
                if ($result->num_rows() > 0) {
                    $result = $result->row();

                    return $result->$column;
                } else {
                    return '';
                }
            } else {
                return '';
            }
        } else {

            return '';

        }
    }

    function dynStr($str,$ids) {
        preg_match_all("/\{[a-zA-Z0-9._ ]+\}+/", $str, $matches);

        foreach($matches as $match_group) {
            foreach($match_group as $match) {


                $match = str_replace("}", "", $match);
                $match = str_replace("{", "", $match);
                $match = str_replace(" ", "_", $match);
                $match = strtolower($match);

                $match_up = strtoupper($match);
                $query_parts = explode('.', $match);
                $table = trim($query_parts[0]);
                $column = trim($query_parts[1]);

                $str = str_replace("{".$match_up."}", replace_variables($match,$ids[$table]), $str);
            }
        }

        return $str;
    }

    /*function zoho($apiUrl,$apiParam){
        $ci = &get_instance();
        header("Content-type: application/xml");
        $token= $ci->config->config['zoho'];
        $url = "https://crm.zoho.com/crm/private/xml/Leads/".$apiUrl;

        $param= "authtoken=".$token."&scope=crmapi&".$apiParam;

        //echo $url.$param;exit;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $result = curl_exec($ch);



        curl_close($ch);



        return $result;
    }*/

    function getEarnings($date,$id){

        $ci = &get_instance();
        $query = 'SELECT 
                      (SELECT SUM(ee.earning_amount) 
                        FROM earnings ee 
                        WHERE DATE_FORMAT(ee.earning_date,\'%M, %Y\') = "'.$date.'"
                        AND ee.earning_type_id = 1
                        AND ee.status = 1
                        AND ee.video_id = v.id
                        ) AS this_month_social_earning,
                        (SELECT SUM(eee.earning_amount) 
                        FROM earnings eee 
                        WHERE DATE_FORMAT(eee.earning_date,\'%M, %Y\') = "'.$date.'"
                        AND eee.earning_type_id = 2
                        AND eee.status = 1
                        AND eee.video_id = v.id
                        ) AS this_month_licensing_earning
                  FROM videos v 
                  INNER JOIN earnings e 
                  ON v.id = e.video_id 
                  AND v.user_id = '.$id.'
                  GROUP BY v.user_id';
        $result = $ci->db->query($query)->row_array();

        return $result;
    }
    function getVideos($date,$id){
        $ci = &get_instance();
        $query = 'SELECT (SELECT SUM(ee.earning_amount) 
                        FROM earnings ee 
                        WHERE DATE_FORMAT(ee.earning_date,\'%M, %Y\') = "'.$date.'"
                        AND ee.earning_type_id = 1
       					AND ee.video_id = v.id
       					AND ee.status = 1
       					) AS socail,
                        (SELECT SUM(eee.earning_amount) 
                        FROM earnings eee
                        WHERE DATE_FORMAT(eee.earning_date,\'%M, %Y\') = "'.$date.'"
                        AND eee.earning_type_id = 2
                        AND eee.video_id = v.id
                        AND eee.status = 1
                        ) AS license,v.id,v.title
                  FROM earnings e
                  INNER JOIN videos v
                  ON e.video_id = v.id
                  WHERE v.user_id =  '.$id.'
                  AND DATE_FORMAT(e.earning_date,\'%M, %Y\') = "'.$date.'"
                  ORDER By v.id
                  ';
        $result = $ci->db->query($query)->result_array();

        return $result;
    }
    function getSocialDetail($date,$video_id){
            $ci = &get_instance();
            $query1 = 'SELECT e.*,ss.sources
                        FROM earnings e
                        INNER JOIN videos v 
                        ON e.video_id = v.id
                        AND v.status = 1
                        AND v.deleted = 0
                        INNER JOIN users u 
                        ON v.user_id = u.id
                        AND u.status = 1
                        AND u.deleted = 0
                        INNER JOIN social_sources ss
                        ON e.social_source_id = ss.id
                        AND ss.status = 1
                        AND ss.deleted = 0
                        WHERE e.status = 1
                        AND e.deleted = 0
                        AND e.video_id = '.$video_id.'
                        AND DATE_FORMAT(e.earning_date,\'%M, %Y\') = "'.$date.'"
                        AND e.earning_type_id = 1';
            $result1 = $ci->db->query($query1)->result_array();
            return $result1;

}

    function getLicensingDetail($date,$video_id){
            $ci = &get_instance();
            $query1 = 'SELECT e.*,p.full_name
                        FROM earnings e
                        INNER JOIN videos v 
                        ON e.video_id = v.id
                        AND v.status = 1
                        AND v.deleted = 0
                        INNER JOIN users u 
                        ON v.user_id = u.id
                        AND u.status = 1
                        AND u.deleted = 0
                        INNER JOIN users p
                        ON e.partner_id = p.id
                        AND p.status = 1
                        AND p.deleted = 0
                        WHERE e.status = 1
                        AND e.deleted = 0
                        AND e.video_id = '.$video_id.'
                        AND DATE_FORMAT(e.earning_date,\'%M, %Y\') = "'.$date.'"
                        AND e.earning_type_id = 2';
            $result1 = $ci->db->query($query1)->result_array();

            return $result1;
    }
    function getEarningsMonthly($id){
        $ci = &get_instance();
        $date = date('F, Y');
        $query = 'SELECT 
                      (SELECT SUM(ee.earning_amount) 
                        FROM earnings ee 
                        WHERE DATE_FORMAT(ee.earning_date,\'%M, %Y\') = "'.$date.'"
                        AND ee.earning_type_id = 1
                        AND ee.status = 1
                        AND ee.video_id = v.id
                        ) AS this_month_social_earning,
                        (SELECT SUM(eee.earning_amount) 
                        FROM earnings eee 
                        WHERE DATE_FORMAT(eee.earning_date,\'%M, %Y\') = "'.$date.'"
                        AND eee.earning_type_id = 2
                        AND eee.status = 1
                        AND eee.video_id = v.id
                        ) AS this_month_licensing_earning
                  FROM videos v 
                  INNER JOIN earnings e 
                  ON v.id = e.video_id 
                  AND v.user_id = '.$id.'
                  GROUP BY v.user_id
                  ';
        $result = $ci->db->query($query)->row_array();

        return $result;
    }

function slug($text,$table,$colum)
{
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, '-');

    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return false;
    }

    $ci = &get_instance();

    $result = $ci->db->query('
		SELECT COUNT(id) as tot
		FROM '.$table.'
		WHERE '.$colum.' = ".$text."
	');
    $result = $result->row();

    if($result->tot > 0){

        $num = ($result->tot+1);

        $text = $text.'-'.$num;

    }

    return $text;
}


function action_add($lead_id = 0,$video_id = 0,$user_id = 0,$admin_id = 0,$is_admin = 1,$message){
    $current_datetime = date('Y-m-d H:i:s');
    $dbData['lead_id'] = $lead_id;
    $dbData['video_id'] = $video_id;
    $dbData['user_id'] = $user_id;
    $dbData['admin_id'] = $admin_id;
    $dbData['action'] = $message;
    $dbData['is_admin'] = $is_admin;
    $dbData['created_at'] = $current_datetime;
    $ci = &get_instance();
    $ci->db->insert('action_taken',$dbData);
    return true;

}

function getPageById($id){

	$ci = &get_instance();

	$result = $ci->db->query('
		SELECT *
		FROM content
		WHERE id = '.$id.'
	');

	return $result->row();
}
function getContentById($id){

    $ci = &get_instance();

    $result = $ci->db->query('
		SELECT *
		FROM content_thanks_page
		WHERE id = '.$id.'
		AND status = 1
		AND deleted = 0
	');
    if($result->num_rows() > 0){
        return $result->row();
    }else{
        return false;
    }


}

function yt_video_id ($video_url) {

    $case_links = array('ybe'  => 'youtu.be',
						'yem'  => 'youtube.com/embed',
						'ycm'  => 'youtube.com');

    foreach ($case_links as $url_type => $link_test) {

        if (strpos($video_url, $link_test) !== false) {
            if ($url_type == 'ycm') {
                $url_parts = parse_url($video_url);
                if (isset($url_parts['query'])) {
                    parse_str($url_parts['query'], $query_params);
                    return $query_params['v'];
                }
            }
            else {
                $video_url_components = explode('/', rtrim(str_replace('https://', '', $video_url), '/'));
				
				$video_id = $video_url_components[('yem' == $url_type)?(2):(1)];
				
				$get_params = explode('?', $video_id);	
				
				return $get_params[0];
            }
        }
    }

    return false;
}
function extrect_video_id($url){
    $videoId = '';
    if (strpos($url, 'facebook.com/') !== false) {
        //it is FB video

    } else if (strpos($url, 'vimeo.com/') !== false) {
        //it is Vimeo video
        $videoId = explode("vimeo.com/", $url)[1];
        if (strpos($videoId, '&') !== false) {
            $videoId = explode("&", $videoId)[0];
        }

    } else if (strpos($url, 'youtube.com/') !== false) {
        //it is Youtube video
        $videoId = explode("v=", $url)[1];
        if (strpos($videoId, '&') !== false) {
            $videoId = explode("&", $videoId)[0];
        }

    } else if (strpos($url, 'youtu.be/') !== false) {
        //it is Youtube video
        $videoId = explode("youtu.be/", $url)[1];
        if (strpos($videoId, '&') !== false) {
            $videoId = explode("&", $videoId)[0];
        }


    } /*twitter start*/
    else if (strpos($url, 'twitter.com') !== false) {

    }else if (strpos($url, 'tiktok.com/') !== false) {
        if(strpos($url, 'vm.tiktok.com/') !== false){


            $url = $url;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Must be set to true so that PHP follows any "Location:" header
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $a = curl_exec($ch); // $a will contain all headers

            $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL); // This is what you need, it will return you the last effective URL
            $url_parts = explode('/',$url);
            $url_parts = explode('.html',end($url_parts));
            $videoId = trim($url_parts[0]);


        }else{
            $url = 'https://www.tiktok.com/oembed?url='.$url;
            $data = json_decode(file_get_contents($url));
            if(isset($data->html)){
                $videoId = extract_tpl($data->html);
                if(isset($videoId['id'])){
                    $videoId = $videoId['id'];
                }
            }
        }
        /*$ci = &get_instance();
        $ci->load->library('tiktok');
        $videoId = $ci->tiktok->getVideoByUrl($url);*/
    }
    else {
        //echo $finalUrl;
    }
    ///echo $videoId;exit;
    return $videoId;
}

function extract_tpl($string,$prefix="data-video-") {
    $start = 0;
    $end = 0;

    while(strpos($string,$prefix,$end))
    {
        $start = strpos($string,$prefix,$start)+strlen($prefix);
        $end = strpos($string,'"',$start)-1;
        $end2 = strpos($string,'"',$end+2);
        $array[substr($string,$start,$end-$start)] = substr($string,$end+2,$end2-$end-2);
    }

    return $array;
}

function getUserCurrencySymbolById($id){

    $ci = &get_instance();

    $result = $ci->db->query("
		SELECT c.symbol
		FROM users u
		LEFT JOIN currency c 
		ON u.currency_id = c.id
		WHERE u.id = $id
	");

    $symbol = '$';
    if(!empty($result->row()->symbol)){
        $symbol = $result->row()->symbol;
    }
    return $symbol;
}

function getDefaultCurrency(){
    $ci = &get_instance();

    $result = $ci->db->query("
		SELECT *
		FROM currency c
		WHERE c.default_currency = 1 LIMIT 1
	")->result_array();

    if(count($result) > 0){
        return $result[0];
    }
    return NULL;
}

function convertPaymentCurrency($from, $to, $amount, $rate, $default_currency_id){
    if($rate == 0){
        return array($amount, $rate);
    }

    $ci = &get_instance();
    $query = "SELECT * FROM currency WHERE id = ".$default_currency_id;
    $result = $ci->db->query($query)->result_array();
    $default_currency = $result[0];

    // USD -> USD
    if ( $to == $default_currency["id"] && $from == $default_currency["id"]){
        return array($amount, 1);
    }
    // GBP -> GBP
    else if($to == $from && $to != $default_currency["id"]){
        $query = "SELECT conversion_rate FROM conversion_rates WHERE 
        from_currency = ".$to." AND 
        to_currency = ".$default_currency["id"]." LIMIT 1";
        $result = $ci->db->query($query)->result_array();
        $conversion_rate = $result[0];

        return array($amount * $conversion_rate["conversion_rate"], $conversion_rate["conversion_rate"]);
    }
    // GBP -> USD
    else if($to == $default_currency["id"]){
        return array($amount, $rate);    
    }
    // USD -> GBP
    else if($from == $default_currency["id"]){
        return array($amount / $rate, $rate);
    }
}

?>
