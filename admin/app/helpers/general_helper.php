<?php


function auth()
{

    $ci = &get_instance();

    $sess = $ci->sess->userdata('isAdminLogin');

    if (!$sess) {

        redirect('auth');
    }
}


function auth_ajax()
{

    $ci = &get_instance();

    $sess = $ci->sess->userdata('isAdminLogin');

    if (!$sess) {
        $response = array();

        $response['code'] = 204;
        $response['message'] = 'Invalid user session!';
        $response['error'] = 'Session expire.';
        $response['url'] = base_url() . 'auth';

        return $response;
    } else {
        return false;
    }
}

function json($response)
{

    $ci = &get_instance();

    $response = $ci->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
    return $response;
}

function auth_verify()
{

    $ci = &get_instance();

    $sess = $ci->sess->userdata('isAdminLogin');

    if ($sess) {

        redirect('dashboard');
    }
}

function getVideoDuration($video_src)
{
    $ffprobe = FFMpeg\FFProbe::create();
    $duration = $ffprobe
        ->streams($video_src) // extracts streams informations
        ->videos()                      // filters video streams
        ->first()                       // returns the first video stream
        ->get('duration');

    return $duration;
}

function settings()
{

    $ci = &get_instance();

    $result = $ci->db->query('
		SELECT *
		FROM settings
		WHERE id = 1
	');

    return $result->row();
}

function slug($text, $table, $colum)
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
		FROM ' . $table . '
		WHERE ' . $colum . ' = "' . $text . '"
	');
    $result = $result->row();

    if ($result->tot > 0) {

        $num = ($result->tot + 1);

        $text = $text . '-' . $num;
    }

    return $text;
}

function role_permitted($only_super_admin = true, $menu = false, $action = false)
{

    $ci = &get_instance();

    $isLogin = $ci->sess->userdata('isAdminLogin');
    $roleType = $ci->sess->userdata('adminTypeId');
    $adminRoleId = $ci->sess->userdata('adminRoleId');

    if ($only_super_admin === true) {

        if (!$isLogin) {

            redirect('auth');
        } else {

            return true;
        }
    } else {
        if ($isLogin && $roleType == 1) {
            return true;
        } else {
            if ($menu === false && $action === false) {

                redirect('dashboard');
            } else if ($menu !== false && $action !== false) {

                $result = $ci->db->query('
                    SELECT arp.id
                      FROM admin_role_permissions arp
                      INNER JOIN menus m
                      ON arp.menu_id = m.id
                      AND m.status = 1
                      AND m.deleted = 0
                      INNER JOIN menu_actions ma
                      ON arp.menu_action_id = ma.id
                      AND ma.status = 1
                      AND ma.deleted = 0
                      WHERE arp.admin_role_id IN (' . $adminRoleId . ')
                      AND m.controller_uri = "' . $menu . '"
                      AND ma.action_uri = "' . $action . '"
                ');

                if ($result->num_rows() > 0) {

                    return true;
                } else {

                    redirect('dashboard');
                }
            } else if ($menu !== false && $action === false) {

                $result = $ci->db->query('
                    SELECT arp.id
                      FROM admin_role_permissions arp
                      INNER JOIN menus m
                      ON arp.menu_id = m.id
                      AND m.status = 1
                      AND m.deleted = 0
                      WHERE arp.admin_role_id IN (' . $adminRoleId . ')
                      AND m.controller_uri = "' . $menu . '"
                      AND arp.menu_action_id = 0
                ');
                //echo $ci->db->last_query();exit;
                if ($result->num_rows() > 0) {

                    return true;
                } else {

                    redirect('dashboard');
                }
            }
        }
    }
}

function role_permitted_html($only_super_admin = true, $menu = false, $action = false)
{

    $ci = &get_instance();

    $isLogin = $ci->sess->userdata('isAdminLogin');
    $roleType = $ci->sess->userdata('adminTypeId');
    $adminRoleId = $ci->sess->userdata('adminRoleId');
    if ($only_super_admin === true) {

        if (!$isLogin) {

            return false;
        } else {

            return true;
        }
    } else {

        if (!$isLogin) {
            return false;
        } else if ($roleType == 1) {
            return true;
        } else {
            if ($menu === false && $action === false) {

                return false;
            } else if ($menu !== false && $action !== false) {

                $result = $ci->db->query('
                    SELECT arp.id
                      FROM admin_role_permissions arp
                      INNER JOIN menus m
                      ON arp.menu_id = m.id
                      AND m.status = 1
                      AND m.deleted = 0
                      INNER JOIN menu_actions ma
                      ON arp.menu_action_id = ma.id
                      AND ma.status = 1
                      AND ma.deleted = 0
                      WHERE arp.admin_role_id IN (' . $adminRoleId . ')
                      AND m.controller_uri = "' . $menu . '"
                      AND ma.action_uri = "' . $action . '"
                ');

                if ($result->num_rows() > 0) {

                    return true;
                } else {

                    return false;
                }
            } else if ($menu !== false && $action === false) {

                $result = $ci->db->query('
                    SELECT arp.id
                      FROM admin_role_permissions arp
                      INNER JOIN menus m
                      ON arp.menu_id = m.id
                      AND m.status = 1
                      AND m.deleted = 0
                      WHERE arp.admin_role_id IN (' . $adminRoleId . ')
                      AND m.controller_uri = "' . $menu . '"
                      AND arp.menu_action_id = 0
                ');
                //echo $ci->db->last_quey();exit;
                if ($result->num_rows() > 0) {

                    return true;
                } else {

                    return false;
                }
            }
        }
    }
}

function role_permitted_ajax($only_super_admin = true, $menu = false, $action = false)
{

    $ci = &get_instance();

    $isLogin = $ci->sess->userdata('isAdminLogin');
    $roleType = $ci->sess->userdata('adminTypeId');
    $adminRoleId = $ci->sess->userdata('adminRoleId');
    $response = array();

    $response['code'] = 204;
    $response['message'] = 'Invalid user session!';
    $response['error'] = 'Session expire.';
    $response['url'] = base_url() . 'auth';


    if ($only_super_admin === true) {

        if (!$isLogin) {

            $response['url'] = base_url() . 'auth';
            return $response;
        } else {

            return false;
        }
    } else {
        if ($isLogin && $roleType == 1) {
            return false;
        } else {
            if ($menu === false && $action === false) {

                $response['url'] = base_url() . 'dashboard';
                return $response;
            } else if ($menu !== false && $action !== false) {

                $result = $ci->db->query('
                    SELECT arp.id
                      FROM admin_role_permissions arp
                      INNER JOIN menus m
                      ON arp.menu_id = m.id
                      AND m.status = 1
                      AND m.deleted = 0
                      INNER JOIN menu_actions ma
                      ON arp.menu_action_id = ma.id
                      AND ma.status = 1
                      AND ma.deleted = 0
                      WHERE arp.admin_role_id IN (' . $adminRoleId . ')
                      AND m.controller_uri = "' . $menu . '"
                      AND ma.action_uri = "' . $action . '"
                ');

                if ($result->num_rows() > 0) {

                    return false;
                } else {

                    $response['url'] = base_url() . 'dashboard';
                    return $response;
                }
            } else if ($menu !== false && $action === false) {

                $result = $ci->db->query('
                    SELECT arp.id
                      FROM admin_role_permissions arp
                      INNER JOIN menus m
                      ON arp.menu_id = m.id
                      AND m.status = 1
                      AND m.deleted = 0
                      WHERE arp.admin_role_id IN (' . $adminRoleId . ')
                      AND m.controller_uri = "' . $menu . '"
                      AND arp.menu_action_id = 0
                ');

                if ($result->num_rows() > 0) {

                    return false;
                } else {

                    $response['url'] = base_url() . 'dashboard';
                    return $response;
                }
            }
        }
    }
}

function getActionsByMenuId($menu_id)
{

    $ci = &get_instance();

    $result = $ci->db->query('
		SELECT ma.*
		FROM menu_actions ma
		WHERE ma.menu_id = ' . $menu_id . '
		AND ma.status = 1
		AND ma.deleted =0
	');

    return $result;
}

function get_user_menu()
{
    $ci = &get_instance();
    $isLogin = $ci->sess->userdata('isAdminLogin');
    $roleType = $ci->sess->userdata('adminTypeId');
    $adminRoleId = $ci->sess->userdata('adminRoleId');
    if ($isLogin) {
        if ($roleType == 1) {
            $menus = $ci->db->query('
                    SELECT m.*
                    FROM menus m
                    WHERE m.status = 1
                    AND m.is_sub_menu = 0
                    AND m.deleted = 0
                    GROUP BY m.id
                    ORDER BY m.sort_no ASC 
            ');
        } else {
            $menus = $ci->db->query('
                    SELECT arp.*,m.*
                    FROM admin_role_permissions arp
                    INNER JOIN menus m 
                    ON arp.menu_id = m.id
                    AND m.status = 1
                    AND m.deleted = 0
                    WHERE arp.admin_role_id = ' . $adminRoleId . '
                    AND m.is_sub_menu = 0
                    AND arp.menu_action_id = 0
                    GROUP BY arp.menu_id
                    ORDER BY m.sort_no ASC
            ');
        }
        return $menus->result();
    } else {
        return [];
    }
}

function get_sub_menu($parent_id = 0)
{
    $ci = &get_instance();
    $isLogin = $ci->sess->userdata('isAdminLogin');
    $roleType = $ci->sess->userdata('adminTypeId');
    $adminRoleId = $ci->sess->userdata('adminRoleId');
    if ($isLogin) {
        if ($roleType == 1) {
            $menus = $ci->db->query('
                    SELECT m.*
                    FROM menus m
                    WHERE m.status = 1
                    AND m.is_sub_menu = 1
                    AND m.deleted = 0
                    AND m.parent_id = "' . $parent_id . '"
                    GROUP BY m.id
                    ORDER BY m.sort_no ASC 
            ');
        } else {
            $menus = $ci->db->query('
                    SELECT arp.*,m.*
                    FROM admin_role_permissions arp
                    INNER JOIN menus m 
                    ON arp.menu_id = m.id
                    AND m.status = 1
                    AND m.deleted = 0
                    WHERE arp.admin_role_id = ' . $adminRoleId . '
                    AND m.is_sub_menu = 1
                    AND m.parent_id = "' . $parent_id . '"
                    AND arp.menu_action_id = 0
                    GROUP BY arp.menu_id
                    ORDER BY m.sort_no ASC
            ');
        }
        return $menus->result();
    } else {
        return [];
    }
}

function replace_variables($query, $id)
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
        if ($table_query->num_rows() > 0 && (!empty($table) && !empty($column) && !empty($id))) {

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

function dynStr($str, $ids)
{
    preg_match_all("/\{[a-zA-Z0-9._ ]+\}+/", $str, $matches);

    foreach ($matches as $match_group) {
        foreach ($match_group as $match) {


            $match = str_replace("}", "", $match);
            $match = str_replace("{", "", $match);
            $match = str_replace(" ", "_", $match);
            $match = strtolower($match);

            $match_up = strtoupper($match);
            $query_parts = explode('.', $match);
            $table = trim($query_parts[0]);
            $column = trim($query_parts[1]);

            $str = str_replace("{" . $match_up . "}", replace_variables($match, $ids[$table]), $str);
        }
    }

    return $str;
}

function zoho($apiUrl, $apiParam)
{
    $ci = &get_instance();
    header("Content-type: application/xml");
    $token = $ci->config->config['zoho'];
    //$token= 'sdasdasd';
    $url = "https://crm.zoho.com/crm/private/xml/Leads/" . $apiUrl;

    $param = "authtoken=" . $token . "&scope=crmapi&" . $apiParam;


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
}

function file_get_content($apiUrl, $apiParam)
{
    $ci = &get_instance();
    // header("Content-type: application/xml");
    $token = $ci->config->config['zoho'];
    $url = "http://crm.zoho.com/crm/private/xml/Leads/" . $apiUrl;

    $str = urlencode($apiParam);

    $param = "?authtoken=" . $token . "&scope=crmapi&" . $str;

    $com_url = $url . $param;


    $content = file_get_contents($com_url);

    return $content;
}

function zoho_deals($apiUrl, $apiParam)
{
    $ci = &get_instance();
    header("Content-type: application/xml");
    $token = $ci->config->config['zoho'];
    $url = "https://crm.zoho.com/crm/private/xml/Deals/" . $apiUrl;
    //$url = 'https://crm.zoho.com/crm/private/xml/Deals/getRecords?';
    //$param= 'authtoken=7ebb775248c74bf6bcf624c24d15caab&scope=crmapi&selectColumns=Potentials(First Name,Last Name,Email,Video URL,Video Title,Description,Created Time)&version=1';
    //'https://crm.zoho.com/crm/private/xml/Deals/getRecords?authtoken=7ebb775248c74bf6bcf624c24d15caab&scope=crmapi&selectColumns=Potentials(First Name,Last Name,Email,Video URL,Video Title,Description,Created Time)&version=1';
    $param = "authtoken=" . $token . "&scope=crmapi&" . $apiParam;
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
}

function zoho_json($apiUrl, $apiParam)
{
    $ci = &get_instance();
    header("Content-type: application/xml");
    $token = $ci->config->config['zoho'];
    $url = "https://crm.zoho.com/crm/private/json/Leads/" . $apiUrl;

    $param = "authtoken=" . $token . "&scope=crmapi&" . $apiParam;

    // echo $url.$param;exit;

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
}

function zoho_campaigns($apiUrl, $apiParam)
{
    $ci = &get_instance();
    header("Content-type: application/xml");
    $token = $ci->config->config['zoho'];
    $url = "https://crm.zoho.com/crm/private/xml/Campaigns/" . $apiUrl;

    $param = "authtoken=" . $token . "&scope=crmapi&" . $apiParam;

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
}

function zoho_potentials($apiUrl, $apiParam)
{
    $ci = &get_instance();
    header("Content-type: application/xml");
    $token = $ci->config->config['zoho'];
    $url = "https://crm.zoho.com/crm/private/xml/PotStageHistory/" . $apiUrl;

    $param = "authtoken=" . $token . "&scope=crmapi&" . $apiParam;

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
}

function signrequest($apiUrl, $apiParam)
{
    $ci = &get_instance();
    header("Content-type: application/xml");
    $token = $ci->config->config['zoho'];
    $url = "https://crm.zoho.com/crm/private/xml/Leads/" . $apiUrl;

    $param = "authtoken=" . $token . "&scope=crmapi&" . $apiParam;

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
}

function getEmailTemplateByCode($code)
{

    $ci = &get_instance();

    $result = $ci->db->query('
		SELECT *
		FROM email_templates
		WHERE short_code = "' . $code . '"
		AND status = 1
		AND deleted = 0
	');

    if ($result->num_rows() > 0) {
        return $result->row();
    } else {
        return false;
    }
}

function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('', 'K', 'M', 'G', 'T');

    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}

function action_add($lead_id = 0, $video_id = 0, $user_id = 0, $admin_id = 0, $is_admin = 1, $message)
{
    $current_datetime = date('Y-m-d H:i:s');
    $dbData['lead_id'] = $lead_id;
    $dbData['video_id'] = $video_id;
    $dbData['user_id'] = $user_id;
    $dbData['admin_id'] = $admin_id;
    $dbData['action'] = $message;
    $dbData['is_admin'] = $is_admin;
    $dbData['created_at'] = $current_datetime;
    $ci = &get_instance();
    $ci->db->insert('action_taken', $dbData);
    return true;
}

// Get time interval function
function getTimeInterval($date)
{

    // Set date as DateTime object
    $date = new DateTime($date);

    // Set now as DateTime object
    $now = date('Y-m-d H:i:s', time()); // If you want to compare two dates you both provide just delete this line and add a $now to the function parameter (ie. function getTimeInterval($date, $now))
    $now = new DateTime($now);

    // Check if date is in the past or future and calculate timedifference and set tense accordingly
    if ($now >= $date) {
        $timeDifference = date_diff($date, $now);
        $tense = " ago";
    } else {
        $timeDifference = date_diff($now, $date);
        $tense = " until";
    }

    // Set posible periods (lowest first as to end result with the highest value that isn't 0)
    $period = array(" second", " minute", " hour", " day", " month", " year");

    // Set values of the periods using the DateTime formats (matching the periods above)
    $periodValue = array($timeDifference->format('%s'), $timeDifference->format('%i'), $timeDifference->format('%h'), $timeDifference->format('%d'), $timeDifference->format('%m'), $timeDifference->format('%y'));

    // Loop through the periods (ie. seconds to years)
    for ($i = 0; $i < count($periodValue); $i++) {
        // if difference period is in days and hours are more than 12 then increase by a day i.e.: 1 day 19 hours shows 2 days ago instead of 1 day ago
        if ($i == 3 and $periodValue[$i - 1] > 12) {
            $periodValue[$i] = $periodValue[$i] + 1;
        }
        // If current value is different from 1 add 's' to the end of current period (ie. seconds)
        if ($periodValue[$i] != 1) {
            $period[$i] .= "s";
        }

        // If current value is larger than 0 set new interval overwriting the lower value that came before ensuring the result shows only the highest value that isn't 0
        if ($periodValue[$i] > 0) {
            $interval = $periodValue[$i] . $period[$i] . $tense; // ie.: 3 months ago
        }
    }

    // If any values were larger than 0 (ie. timedifference is not 0 years, 0 months, 0 days, 0 hours, 0 minutes, 0 seconds ago) return interval
    if (isset($interval)) {
        return $interval;
        // Else if no values were larger than 0 (ie. timedifference is 0 years, 0 months, 0 days, 0 hours, 0 minites, 0 seconds ago) return 0 seconds ago
    } else {
        return "0 seconds" . $tense;
    }
}

function getStageTime($lead_status, $lead_id)
{
    $ci = &get_instance();
    // fetch lead data if status is 3
    $lead_status_action = 'Contract signed';

    if ($lead_status == 3) {
        $qry = 'SELECT * FROM video_leads where id=' . $lead_id;
        $res = $ci->db->query($qry);
        $lead_data = $res->row();
        if ($lead_data->client_id != 0 and $lead_data->information_pending == 1) { // raw file uploaded
            $lead_status_action = 'Orginal video files uploaded';
        } else if ($lead_data->client_id != 0 and $lead_data->information_pending == 0) { // information pending or account created
            $lead_status_action = 'Account created';
        }
    }
    //Edited files uploaded
    //$status_action = array( 2=>"Contract send", 3=>"Contract signed", 4=>"Orginal video files uploaded", 5=>"", 6=>"", 7=>"", 8=>"", 9=>"", 10=>"Lead converted to deal", 11=>"");
    $status_action = array(2 => "Contract send", 3 => $lead_status_action, 5 => "Poor rating video", 6 => "Video verified", 8 => "sas", 10 => "Lead converted to deal", 11 => "Move to Not Interested");
    if ($lead_status_action == 'Account created') {
        $q = 'action IN ("Contract signed", "Account created") and lead_id=' . $lead_id . ' order by id DESC';
    } else {
        $q = 'action="' . $status_action[$lead_status] . '" and lead_id=' . $lead_id;
    }
    $query = 'SELECT created_at FROM action_taken where ' . $q;
    $result = $ci->db->query($query);
    $status_date = '';
    if ($result->num_rows() > 0) {
        $status_date = $result->row()->created_at;
    } else {
        $query = 'SELECT MAX(created_at) as created_at FROM action_taken where action="Lead converted to deal" AND lead_id=' . $lead_id;
        $result = $ci->db->query($query);
        $status_date = $result->row()->created_at;
    }

    if ($lead_status == 11 and $status_date == '') {
        $query = 'SELECT MAX(created_at) as created_at FROM action_taken where lead_id=' . $lead_id;
        $result = $ci->db->query($query);
        $status_date = $result->row()->created_at;
    }
    //return $status_date;
    return getTimeInterval($status_date);
}
function getUserCurrencySymbolById($id)
{

    $ci = &get_instance();

    $result = $ci->db->query("
		SELECT c.symbol
		FROM users u
		LEFT JOIN currency c 
		ON u.currency_id = c.id
		WHERE u.id = $id
	");

    $symbol = '$';
    if (!empty($result->row()->symbol)) {
        $symbol = $result->row()->symbol;
    }
    return $symbol;
}
function convertPaymentCurrency($from, $to, $amount, $rate, $default_currency_id)
{
    if ($rate == 0) {
        return array($amount, $rate);
    }
    $ci = &get_instance();
    $query = "SELECT * FROM currency WHERE id = " . $default_currency_id;
    $result = $ci->db->query($query)->result_array();
    $default_currency = $result[0];


    // USD -> USD
    if ($to == $default_currency["id"] && $from == $default_currency["id"]) {
        return array($amount, 1);
    }
    // GBP -> GBP
    else if ($to == $from && $to != $default_currency["id"]) {
        $query = "SELECT conversion_rate FROM conversion_rates WHERE 
        from_currency = " . $to . " AND 
        to_currency = " . $default_currency["id"] . " LIMIT 1";
        $result = $ci->db->query($query)->result_array();
        $conversion_rate = $result[0];

        return array($amount * $conversion_rate["conversion_rate"], $conversion_rate["conversion_rate"]);
    }
    // GBP -> USD
    else if ($to == $default_currency["id"]) {
        return array($amount, $rate);
    }
    // USD -> GBP
    else if ($from == $default_currency["id"]) {
        return array($amount / $rate, $rate);
    }
}
function getCurrencyByCode($code)
{
    $ci = &get_instance();

    $result = $ci->db->query("
		SELECT *
		FROM currency c
		WHERE c.code = '$code' LIMIT 1
	")->result_array();

    if (count($result) > 0) {
        return $result[0];
    }
    return NULL;
}

function getDefaultCurrency()
{
    $ci = &get_instance();

    $result = $ci->db->query("
		SELECT *
		FROM currency c
		WHERE c.default_currency = 1 LIMIT 1
	")->result_array();

    if (count($result) > 0) {
        return $result[0];
    }
    return NULL;
}

function getDefaultCurrencySymbol()
{
    $ci = &get_instance();

    $result = $ci->db->query("
		SELECT c.symbol
		FROM currency c
		WHERE c.default_currency = 1
	");

    $symbol = '$';
    if (!empty($result->row()->symbol)) {
        $symbol = $result->row()->symbol;
    }
    return $symbol;
}

function getCurrencySymbolById($id)
{
    $ci = &get_instance();

    $result = $ci->db->query("
		SELECT c.symbol
		FROM currency c
		WHERE c.id = $id
	");

    $symbol = '$';
    if (!empty($result->row()->symbol)) {
        $symbol = $result->row()->symbol;
    }
    return $symbol;
}

function getConversionRate($from, $to)
{
    $ci = &get_instance();

    $query = "SELECT conversion_rate FROM conversion_rates WHERE 
        from_currency = " . $from . " AND 
        to_currency = " . $to . " LIMIT 1";
    $result = $ci->db->query($query)->result_array();
    $conversion_rate = $result[0]["conversion_rate"];
    return $conversion_rate;
}

function db_result_to_array_map($res, $key_column = 0)
{
    $ret_arr = [];
    array_map(function ($value) use (&$ret_arr) {
        $result = array_values($value);
        return sizeof($result) == 2 ?
            $ret_arr[$result[0]] = $result[1]
            : $ret_arr[$result[0]] = array_slice($value, 1, sizeof($value));
    }, $res);

    return $ret_arr;
}

function getRolesByGroupId($group_id)
{

    $ci = &get_instance();

    $result = $ci->db->query('
		SELECT r.*
		FROM role_groups rp
		INNER JOIN admin_roles r 
		ON r.id = rp.role_id AND  r.deleted =0
		WHERE rp.group_id = ' . $group_id . '
	');

    return $result;
}

function getStaffByGroupId($group_id)
{

    $ci = &get_instance();

    $result = $ci->db->query('
		SELECT a.*
		FROM staff_groups sp
		INNER JOIN admin a 
		ON a.id = sp.admin_id AND  a.deleted =0
		WHERE sp.group_id = ' . $group_id . '
	');

    return $result;
}

function getRolesIds($role_ids)
{

    $ci = &get_instance();

    $result = $ci->db->query("
		SELECT r.*
		FROM admin_roles r 
		WHERE  r.deleted = 0 AND id in($role_ids)
		
	");

    return $result;
}
function extrect_video_id($url)
{
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
    } /*twitter start*/ else if (strpos($url, 'twitter.com') !== false) {
    } else if (strpos($url, 'tiktok.com/') !== false) {
        if (strpos($url, 'vm.tiktok.com/') !== false) {


            $url = $url;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Must be set to true so that PHP follows any "Location:" header
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $a = curl_exec($ch); // $a will contain all headers

            $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL); // This is what you need, it will return you the last effective URL
            $url_parts = explode('/', $url);
            $url_parts = explode('.html', end($url_parts));
            $videoId = trim($url_parts[0]);
        } else {
            $url = 'https://www.tiktok.com/oembed?url=' . $url;
            $data = json_decode(file_get_contents($url));
            if (isset($data->html)) {
                $videoId = extract_tpl($data->html);
                if (isset($videoId['id'])) {
                    $videoId = $videoId['id'];
                }
            }
        }
        /*$ci = &get_instance();
        $ci->load->library('tiktok');
        $videoId = $ci->tiktok->getVideoByUrl($url);*/
    } else {
        //echo $finalUrl;
    }
    ///echo $videoId;exit;
    return $videoId;
}

function extract_tpl($string, $prefix = "data-video-")
{
    $start = 0;
    $end = 0;

    while (strpos($string, $prefix, $end)) {
        $start = strpos($string, $prefix, $start) + strlen($prefix);
        $end = strpos($string, '"', $start) - 1;
        $end2 = strpos($string, '"', $end + 2);
        $array[substr($string, $start, $end - $start)] = substr($string, $end + 2, $end2 - $end - 2);
    }

    return $array;
}

function nb_mois($date1, $date2)
{
    $begin = new DateTime($date1);
    $end = new DateTime($date2);
    $end = $end->modify('+1 month');

    $interval = DateInterval::createFromDateString('1 month');

    $period = new DatePeriod($begin, $interval, $end);
    $counter = 0;
    foreach ($period as $dt) {
        $counter++;
    }

    return $counter;
}

function LandscapeConversion_AWS($fileUrl, $s3path)
{
    try {

        $client = new Aws\MediaConvert\MediaConvertClient([
            'credentials' => [
                'key'    => S3_KEY,
                'secret' => S3_SECRET,
            ],
            'version' => '2017-08-29',
            'region'  => 'us-west-2'
        ]);
        $result = $client->describeEndpoints([]);
        $single_endpoint_url = $result['Endpoints'][0]['Url'];

        // Initialize MediaConvert client with the custom endpoint URL
        $mediaConvertClient = new Aws\MediaConvert\MediaConvertClient([
            'credentials' => [
                'key'    => S3_KEY,
                'secret' => S3_SECRET,
            ],
            'version' => '2017-08-29',
            'region'  => 'us-west-2',
            'endpoint' => $single_endpoint_url
        ]);

        // Step 2: Define the job settings for conversion
        $jobSettings = [
            'Role' => "arn:aws:iam::819785585059:role/VideoEditor",
            'Settings' => [
                'Inputs' => [
                    [
                        'FileInput' => $fileUrl,
                    ],
                ],
                'OutputGroups' => [
                    [
                        'Name' => 'File Group',
                        'OutputGroupSettings' => [
                            'Type' => 'FILE_GROUP_SETTINGS',
                            'FileGroupSettings' => [
                                'Destination' =>  's3://'.S3_BUCKET."/".$s3path,
                            ],
                        ],
                        'Outputs' => [
                            [
                                'ContainerSettings' => [
                                    'Container' => 'MP4',
                                ],
                                'VideoDescription' => [
                                    'CodecSettings' => [
                                        'Codec' => 'H_264',
                                        'H264Settings' => [
                                            'RateControlMode' => 'CBR',
                                            'Bitrate' => 5000000,
                                        ],
                                    ],
                                    'Width' => 1920,
                                    'Height' => 1080,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        // Step 3: Submit the job
        $result = $mediaConvertClient->createJob($jobSettings);
        $jobId = $result->get('Job')['Id'];

        // Step 4: Poll for job completion
        $jobStatus = 'SUBMITTED';
        while ($jobStatus != 'COMPLETE' && $jobStatus != 'ERROR') {
            sleep(5);
            $jobResult = $mediaConvertClient->getJob(['Id' => $jobId]);
            $jobStatus = $jobResult->get('Job')['Status'];

            if ($jobStatus == 'ERROR') {
                $errorMessage = $jobResult->get('Job')["ErrorMessage"] ?? 'Unknown error';
                echo json_encode(['status' => 'error', 'message' => 'Job Failed', 'details' => $errorMessage]);
                return false;
            }
        }

        if ($jobStatus == 'COMPLETE') {
            // Initialize the S3 client to set the ACL for public-read
            $s3Client = new Aws\S3\S3Client([
                'credentials' => [
                    'key'    => S3_KEY,
                    'secret' => S3_SECRET,
                ],
                'version' => 'latest',
                'region'  => 'us-west-2'
            ]);
            // Make the object public by setting the ACL
            $s3Client->putObjectAcl([
                'Bucket' => S3_BUCKET,
                'Key'    =>  $s3path.".mp4",
                'ACL'    => 'public-read',
            ]);

            // Step 8: Construct the public object URL
            $publicUrl = "https://".S3_BUCKET.".s3.".S3_REGION.".amazonaws.com/".$s3path.".mp4";

            echo json_encode(['status' => 'success', 'message' => 'File uploaded and made public', 'publicUrl' => $publicUrl]);
            return $publicUrl;
        }
    } catch (AwsException $e) {
        // Output error message
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        return false;
    }
}