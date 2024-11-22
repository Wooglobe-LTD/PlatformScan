<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cronjobs extends APP_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Auth_Model', 'auth');
        $this->load->model('User_Model', 'user');
        $this->load->model('Video_Lead_Model', 'lead');
        $this->load->helper('string');
        //echo $_SERVER['HTTP_REFFER'];exit;
    }
    public function download_published_video_thumbnail()
    {
        $query = "SELECT v.lead_id, v.youtube_id, vl.unique_key, v.id from video_publishing_scheduling vps 
        LEFT JOIN edited_video ev ON ev.video_id = vps.video_id
        LEFT JOIN videos v ON v.id = vps.video_id
        LEFT JOIN video_leads vl ON v.lead_id = vl.id
        WHERE (vps.publish_type = 'YouTube' AND vps.published = 1 AND vps.youtube_publish_status = 'public' ) 
        AND (ev.portal_thumb IS NULL OR ev.portal_thumb = '') 
        AND (v.youtube_id IS NOT NULL AND v.youtube_id != '') ";

        $res = $this->db->query($query)->result_array();
        foreach ($res as $vid) {
            $lead_id = $vid['lead_id'];
            $unique_key = $vid["unique_key"];
            $youtube_id = $vid["youtube_id"];
            $url = "https://img.youtube.com/vi/$youtube_id/0.jpg";
            $file_name = basename($url);
            $content = file_get_contents($url);
            $file_url = "./../uploads/$unique_key/edited_videos/mrss/thumbnail/$file_name";
            $source_file = $file_url;
            if ($content === false) {
                continue;
            }

            file_put_contents($source_file, $content);

            if (is_file($source_file)) {
                // Uploading to S3
                $file_extension = explode('.', $source_file);
                $file_extension = $file_extension[1];
                $target_file_key = S3_BASE_FOLDER . $file_url;
                $target_file_key = explode('/', $target_file_key);
                $target_file_key = 'uploads/' . $unique_key . '/edited_videos/thumbnail/' . $file_name;
                $url_s3 = $this->upload_file_s3_new('public-read', $source_file, $target_file_key, $file_extension, false);
                $data['portal_thumb'] = $url_s3;
                $this->db->where('video_id', $vid['id']);
                $query = $this->db->get('edited_video');

                if ($query->num_rows() > 0) {
                    $this->db->where('video_id', $vid['id']);
                    $result = $this->db->update('edited_video', $data);
                } else {
                    $data['video_id'] = $vid['id'];
                    $result = $this->db->insert('edited_video', $data);
                }
            }
        }
    }
    public function pop_mrss_queue()
    {
        $mrss_queue = $this->db->query('SELECT * FROM mrss_queue ORDER BY id ASC')->result_array();
        $res = $this->db->query('SELECT id, feed_delay, feed_time, pub_date FROM mrss_feeds')->result_array();
        $feed_delays = db_result_to_array_map($res);
        $res = $this->db->query('SELECT feed_id,mon,tue,wed,thu,fri,sat,sun FROM mrss_info')->result_array();
        $feed_days = db_result_to_array_map($res);
        // print_r($feed_days);
        $today = strtolower(
            date(
                'D',
                strtotime(
                    'now'
                )
            )
        );

        $done = array();
        foreach ($mrss_queue as $row) {
            $feed_id = $row['feed_id'];
            $feed_delay = intval($feed_delays[$feed_id]["feed_delay"]);
            $feed_time = $feed_delays[$feed_id]["feed_time"];
            $feed_time_from = '';
            $feed_time_to = '';

            if ($feed_days[$feed_id][$today] != 1) {
                continue;
            }

            if (strlen($feed_time) == 11) {
                $feed_time_from = explode("-", $feed_time)[0];
                $feed_time_to = explode("-", $feed_time)[1];
            } else {
                $feed_time = "00:00-00:00";
            }

            // Limiting to only one video per feed
            if (in_array($feed_id, $done)) {
                continue;
            }

            // Logging to mrss_queue_logs
            $log_file = "./app/logs/mrss_queue_logs.logs";
            $log_message .= "\n----- " . date('Y-m-d H:i:s') . "\n";
            $log_message .= "video_id: " . print_r($row['video_id'], true) . "\n";
            $log_message .= "feed_id: " . print_r($feed_id, true) . "\n";
            $log_message .= "today: " . print_r($today, true) . "\n";
            $log_message .= "feed_today: " . print_r($feed_days[$feed_id][$today], true) . "\n";
            $log_message .= "feed_time: " . print_r($feed_time, true) . "\n";
            file_put_contents($log_file, $log_message);

            $feed_time_from_h = explode(":", $feed_time_from)[0];
            $feed_time_from_m = explode(":", $feed_time_from)[1];

            // echo "feed_time_from_h::";print_r(intval($feed_time_from_h));


            $feed_time_to_h = explode(":", $feed_time_to)[0];
            $feed_time_to_m = explode(":", $feed_time_to)[1];

            // echo "feed_time_to_h::";print_r($feed_time_to_h);

            $to_date_time  = new DateTime();
            $from_date_time = new DateTime();
            // echo "from_date_time::Before::";print_r($from_date_time);

            // echo "to_date_time::Before::";print_r($to_date_time);

            $from_date_time->setTime(intval($feed_time_from_h), intval($feed_time_from_m));
            $to_date_time->setTime(intval($feed_time_to_h), intval($feed_time_to_m));

            $current_data_time = new DateTime();
            // echo "current_date_time::";print_r($current_data_time);

            // echo "from_date_time::";print_r($from_date_time);

            // echo "to_date_time::";print_r($to_date_time);


            if (($current_data_time >= $from_date_time && $current_data_time <= $to_date_time) || ($from_date_time == $to_date_time)) {

                $current_time = time();

                $past_time = strtotime($row['publication_date']);
                $difference = $current_time - $past_time;

                $difference_minute =  $difference / 60;

                // echo "Difference::".$difference_minute;

                if ($difference_minute >= $feed_delay) {
                    $queue_pop = $this->db->query('SELECT * from mrss_queue WHERE feed_id = ' . $feed_id . ' LIMIT 1')->result_array();
                    if ($queue_pop != NULL) {
                        // Limiting to only one video per feed
                        $done[] = $feed_id;
                        // echo "Done::";
                        // print_r($done);

                        $queue_pop = $queue_pop[0];

                        // 1 videos
                        $this->db->where('id', $queue_pop['video_id']);
                        $this->db->update('videos', array('mrss' => 1));

                        // 2 feed_video
                        $this->db->insert('feed_video', ['video_id' => $queue_pop['video_id'], 'feed_id' => $queue_pop['feed_id'], 'exclusive_to_partner' => $queue_pop['exclusive_to_partner']]);

                        // 3 mrss_publications
                        $dbQuery = $this->db->query(
                            "
                            SELECT * FROM mrss_publication
                            WHERE feed_id = " . $queue_pop['feed_id'] . "
                            AND video_id = " . $queue_pop['video_id']
                        );

                        if ($feed_delays[$feed_id]["pub_date"] == "queue_to_mrss") {
                            $db_data['publication_date'] = date('Y-m-d H:i:s');
                        } else {
                            $db_data['publication_date'] = $queue_pop['publication_date'];
                        }

                        if ($dbQuery->num_rows() > 0) {
                            $this->db->where('feed_id', $queue_pop['feed_id']);
                            $this->db->where('video_id', $queue_pop['video_id']);
                            $this->db->update('mrss_publication', $db_data);
                        } else {
                            $db_data['feed_id'] = $queue_pop['feed_id'];
                            $db_data['video_id'] = $queue_pop['video_id'];
                            $this->db->insert('mrss_publication', $db_data);
                        }

                        $this->db->query('DELETE FROM mrss_queue where video_id = ' . $queue_pop['video_id'] . ' AND feed_id = ' . $queue_pop['feed_id']);

                        // Update time to takle 1 video per feed after delay
                        $db_data = array();
                        $db_data['publication_date'] = date('Y-m-d H:i:s');

                        // echo "Update with date::";
                        // print_r($db_data['publication_date']);

                        $this->db->where('feed_id', $queue_pop['feed_id']);
                        $this->db->update('mrss_queue', $db_data);
                        // echo "Updated Queue with time\n";

                    }
                }
                // else print_r("Not in delay\n");
            }
            // else print_r("Not in allowed time range\n");
        }
    }


    public function pop_brands_mrss_queue()
    {
        $mrss_queue = $this->db->query('SELECT * FROM mrss_brand_feeds WHERE published = 0 ORDER BY id ASC')->result_array();
        $res = $this->db->query('SELECT id, feed_delay, feed_time FROM brands_mrss_feeds')->result_array();
        $feed_delays = db_result_to_array_map($res);
        $res = $this->db->query('SELECT brands_feed_id,mon,tue,wed,thu,fri,sat,sun FROM brands_mrss_info')->result_array();
        $feed_days = db_result_to_array_map($res);
        // print_r($feed_days);
        // print_r($mrss_queue);

        $today = strtolower(
            date(
                'D',
                strtotime(
                    (new DateTime('now'))->format('Y-m-d H:i:s')
                )
            )
        );

        $done = array();
        foreach ($mrss_queue as $row) {
            $feed_id = $row['brands_feed_id'];
            $feed_delay = intval($feed_delays[$feed_id]["feed_delay"]);
            $feed_time = $feed_delays[$feed_id]["feed_time"];
            $feed_time_from = '';
            $feed_time_to = '';
            // print_r($feed_days[$feed_id][$today]);

            if ($feed_days[$feed_id][$today] != 1) {
                continue;
            }

            if (strlen($feed_time) == 11) {
                $feed_time_from = explode("-", $feed_time)[0];
                $feed_time_to = explode("-", $feed_time)[1];
            } else {
                $feed_time = "00:00-00:00";
            }

            // Limiting to only one video per feed
            if (in_array($feed_id, $done)) {
                continue;
            }



            $feed_time_from_h = explode(":", $feed_time_from)[0];
            $feed_time_from_m = explode(":", $feed_time_from)[1];

            // echo "feed_time_from_h::";print_r(intval($feed_time_from_h));


            $feed_time_to_h = explode(":", $feed_time_to)[0];
            $feed_time_to_m = explode(":", $feed_time_to)[1];

            // echo "feed_time_to_h::";print_r($feed_time_to_h);

            $to_date_time  = new DateTime();
            $from_date_time = new DateTime();
            // echo "from_date_time::Before::";print_r($from_date_time);

            // echo "to_date_time::Before::";print_r($to_date_time);

            $from_date_time->setTime(intval($feed_time_from_h), intval($feed_time_from_m));
            $to_date_time->setTime(intval($feed_time_to_h), intval($feed_time_to_m));

            $current_data_time = new DateTime();
            // echo "current_date_time::";print_r($current_data_time);

            // echo "from_date_time::";print_r($from_date_time);

            // echo "to_date_time::";print_r($to_date_time);


            if (($current_data_time >= $from_date_time && $current_data_time <= $to_date_time) || ($from_date_time == $to_date_time)) {

                $current_time = time();

                $past_time = strtotime($row['publication_date']);
                $difference = $current_time - $past_time;

                $difference_minute =  $difference / 60;

                echo "Difference::" . $difference_minute;

                if ($difference_minute >= $feed_delay) {
                    $queue_pop = $this->db->query('SELECT * from mrss_brand_feeds WHERE brands_feed_id = ' . $feed_id . ' AND published = 0 LIMIT 1')->result_array();
                    if ($queue_pop != NULL) {
                        // Limiting to only one video per feed
                        $done[] = $feed_id;
                        // echo "Done::";
                        // print_r($done);

                        $queue_pop = $queue_pop[0];

                        // 1 videos
                        // $this->db->where('id',$queue_pop['video_id']);
                        // $this->db->update('videos',array('mrss'=>1));

                        // 2 feed_video
                        // $this->db->insert('feed_video', ['video_id' => $queue_pop['video_id'], 'feed_id' => $queue_pop['feed_id'], 'exclusive_to_partner' => $queue_pop['exclusive_to_partner']]);

                        // 3 mrss_publications
                        // $dbQuery = $this->db->query("
                        //     SELECT * FROM mrss_publication
                        //     WHERE brands_feed_id = ".$queue_pop['brands_feed_id']."
                        //     AND video_id = ".$queue_pop['video_id']
                        // );

                        // $db_data['publication_date'] = $queue_pop['publication_date'];

                        // $this->db->where('brands_feed_id', $queue_pop['brands_feed_id']);
                        // $this->db->where('partner_id',$queue_pop['partner_id']);
                        // $this->db->where('brand_id',$queue_pop['brand_id']);
                        // $this->db->where('lead_id',$queue_pop['lead_id']);


                        // $this->db->update('mrss_brand_feeds',$db_data);


                        // if($dbQuery->num_rows() > 0){
                        //     $this->db->where('feed_id',$queue_pop['feed_id']);
                        //     $this->db->where('video_id',$queue_pop['video_id']);
                        //     $this->db->update('mrss_publication',$db_data);
                        // }else{
                        //     $db_data['feed_id'] = $queue_pop['feed_id'];
                        //     $db_data['video_id'] = $queue_pop['video_id'];
                        //     $this->db->insert('mrss_publication',$db_data);
                        // }

                        // $this->db->query('DELETE FROM mrss_queue where video_id = '.$queue_pop['video_id'].' AND feed_id = '.$queue_pop['feed_id']);

                        // Update time to takle 1 video per feed after delay
                        echo "\n" . $queue_pop['id'] . "\n";
                        echo "\n";
                        var_dump($queue_pop);
                        echo "\n";

                        $this->db->where('id', $queue_pop['id']);
                        $db_data = array();
                        $db_data['published'] = 1;
                        $this->db->update('mrss_brand_feeds', $db_data);


                        $db_data = array();
                        $db_data['publication_date'] = date('Y-m-d H:i:s');
                        $this->db->where('brands_feed_id', $queue_pop['brands_feed_id']);
                        $this->db->where('published', 0);
                        $this->db->update('mrss_brand_feeds', $db_data);
                        echo "Updated Queue with time\n";
                    }
                }
                // else print_r("Not in delay\n");
            }
            // else print_r("Not in allowed time range\n");
        }
    }

    public function send_email_reminders()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $this->load->model('Communication_Model', 'email');
        $this->load->model('Video_Deal_Model', 'deal');
        $stage = array("welcome" => 10, "contract_sent" => 2, "pending" => 3);
        $short_code = array(10 => "reminder_welcome_intro_email", 2 => "reminder_sending_contract_email", 3 => "lead_information_missing");

        foreach ($stage as $name => $status) {
            $reminder_time = 18; //$this->config->item('reminder_'.$name);
            // get last activity for stage

            if ($status == 3) {
                //$reminder_time = 0.01;
                /*   $query = 'SELECT vl.*, MAX(act.created_at) as last_activity, act.action
                FROM video_leads vl
                INNER JOIN users u
                ON vl.client_id = u.id
                AND u.deleted = 0
                AND u.password IS NOT NULL
                LEFT JOIN videos v
                ON v.lead_id = vl.id
                AND v.deleted = 0
                AND v.status = 1
                AND v.lead_id = vl.id
                INNER JOIN action_taken act
                ON vl.id = act.lead_id
                WHERE vl.status = 3
                AND vl.deleted = 0
                AND vl.information_pending = 0
                #AND vl.id not in (1077, 1156, 1304, 1344, 1351, 1352, 1370, 1373, 1396)
                #AND vl.id not in (1156, 1344, 1351, 1352, 1396)
                GROUP BY act.lead_id';*/
                $query = 'SELECT vl.*, act.action, MAX(act.created_at) as last_activity
                FROM video_leads vl
                INNER JOIN action_taken act
                ON vl.id = act.lead_id
                WHERE vl.status = 3
                AND vl.deleted = 0
                and vl.reminder_sent=0
                AND vl.information_pending = 0 group by act.lead_id';
            } else {
                $query = 'SELECT vl.*, MAX(act.created_at) as last_activity, act.action
                FROM video_leads vl
                INNER JOIN lead_action_dates lad
                ON vl.id = lad.lead_id
                INNER JOIN action_taken act
                ON vl.id = act.lead_id
                WHERE vl.status = ' . $status . '
                AND vl.deleted = 0
                #AND vl.id!=16
                
                GROUP BY act.lead_id';
            }
            $result = $this->db->query($query);



            foreach ($result->result() as $row) {

                $reminder_sent = $row->reminder_sent;
                $first_reminder_email = strpos($row->action, 'first reminder');
                $email_from_client = strpos($row->action, 'Email from client');
                $reply_from_client = strpos($row->action, 'Email reply');
                //$reply_from_admin = strpos($row->action, 'Email from admin');

                // if automatic reminder not sent
                // and email with subject first reminder not found in db (just to make sure no repeated reminder is sent)
                // Received no email from client in this stage
                // Client has not replied to email in current stage after initial stage email was sent
                if ($reminder_sent == 0 and $first_reminder_email === false and $email_from_client === false and $reply_from_client === false) {

                    $last_activity = strtotime($row->last_activity);
                    $now = strtotime("now");
                    $diff = $now - $last_activity; // time difference from last activity
                    $reminder_in_sec = $reminder_time * 60 * 60;

                    if ($diff > $reminder_in_sec) { // if reminder time is greater time since last activity, reminder should be sent

                        $leadData = $this->email->getLeadById($row->id, 'vl.*');
                        $userData = $this->user->getUserByEmail($leadData->email)->result();


                        if (!empty($userData) && is_null($userData[0]->password) && $leadData->status == 3 && $leadData->information_pending == 0 && $leadData->reminder_sent == 0) {

                            $qry = 'SELECT * from email_templates WHERE short_code= "account_creation_remainder"';
                        } else {

                            $qry = 'SELECT * from email_templates WHERE short_code="' . $short_code[$status] . '"';
                        }
                        /*if (is_null($userData->password)) {
                            $qry = 'SELECT * from email_templates WHERE short_code= "account_creation_remainder"';

                        } else {
                            $qry = 'SELECT * from email_templates WHERE short_code="' . $short_code[$status] . '"';

                        }*/
                        $res = $this->db->query($qry);
                        $template = $res->row();

                        $unique_key = $this->lead->getUniqueKey($leadData->id);

                        if ($unique_key) {
                            $subject = $template->subject . '-' . $unique_key->unique_key;
                        } else {
                            $subject = $template->subject;
                        } //print "LEAD:".$leadData->id;
                        $email_template_id = 2;
                        if ($status == 3) {
                            $email_template_id = 5;
                        }

                        $templateId = $this->deal->getTemplateId($row->id, $email_template_id);
                        $ids = json_decode($templateId->ids, true);


                        $slug_url = $this->config->item('root_url');
                        if ($slug_url == '' or $slug_url == 'https://' or $slug_url == 'https:///') {
                            $slug_url = 'https://www.wooglobe.com/';
                        }
                        if ($status == 3) {
                            $url = $slug_url . 'login/';
                        } else {
                            $url = $slug_url . 'video-contract/' . $leadData->slug;
                        }

                        $message = dynStr($template->message, $ids);
                        $message = str_replace('@LINK', $url, $message);

                        $sent = $this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');

                        if ($sent) {
                            action_add($leadData->id, 0, 0, 0, 1, 'First ' . str_replace('_', ' ', $short_code[$status]));
                            $vleads['reminder_sent'] = 1;
                            $this->db->where('id', $row->id);
                            $this->db->update('video_leads', $vleads);

                            echo json_encode("email sent");
                            //exit;
                        } else {
                            $response['code'] = 201;
                            $response['message'] = 'Email not sent!';
                            echo json_encode($response);
                            //exit;
                        }
                    }
                }
            }
        }


        exit;
    }
    public function check_feeds()
    {
        $invalid_feed_values = array();
        $query = 'SELECT partner_id,url FROM `mrss_feeds` WHERE `deleted` = 0';
        $feeds = $this->db->query($query);
        foreach ($feeds->result() as $feed) {
            if ($feed->partner_id > 0) {
                $feeds_download_url = 'https://www.wooglobe.com/mrss/mrss_partner_download/' . $feed->url;
            } else {
                $feeds_download_url = 'https://www.wooglobe.com/mrss/download/' . $feed->url;
            }
            $check_validation = $this->check_func($feeds_download_url);
            print "<pre>";
            print_r($check_validation);

            if ($check_validation == 1) {
                continue;
            } else {
                print_r($feeds_download_url);
                $invalid_feed_values[] = $feeds_download_url;
            }
        }

        if (!empty($invalid_feed_values)) {
            $message = "Results: " . print_r($invalid_feed_values, true);
            $result =  $this->email('aliaakhan85@gmail.com', 'Admin', 'no-reply@wooglobe.com', $from_name = '', 'Invalid Feeds', $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');
        }
    }
    public function check_func($sFeedURL)
    {
        $sValidator = 'https://validator.w3.org/feed/check.cgi?url=';
        $sValidationRespons = '';
        if ($sValidationResponse = @file_get_contents($sValidator . urlencode($sFeedURL))) {
            if (stristr($sValidationResponse, 'This is a valid RSS feed') !== false) {
                return true;
            } else if (stristr($sValidationResponse, '1:0: syntax error') !== false) {
                return true;
            } else if (empty($sValidationRespons)) {
                /* $res=$this->check_func($sFeedURL);*/
                /*  if($res){*/
                return true;
                /*  }else{
                    return false;
                }*/
            } else {
                return $sValidationRespons;
            }
        } else {
            return false;
        }
    }
    public function import_leads()
    {
        $url = 'getRecords';

        $param = 'selectColumns=Leads(First Name,Last Name,Email,Video URL,Video Title,Description,Created Time)';
        $zoho = file_get_content($url, $param);
        header("Content-type: application/json");
        $xml = simplexml_load_string($zoho, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);

        $array = json_decode($json, TRUE);
        $rows = array();

        if (isset($array['result'])) {
            if (isset($array['result']['Leads'])) {
                if (isset($array['result']['Leads']['row'])) {
                    $rows = $array['result']['Leads']['row'];
                    foreach ($rows as $row) {
                        if (isset($row['FL']) && count($row['FL']) > 0) {
                            $result = $this->db->query('
                                SELECT id 
                                FROM video_leads
                                WHERE zoho_lead_id = ' . $row['FL'][0] . '
                            ');
                            if ($result->num_rows() == 0) {


                                $dbData['zoho_lead_id'] = $row['FL'][0];
                                $dbData['first_name'] = $row['FL'][3];
                                $dbData['last_name'] = $row['FL'][4];
                                $dbData['email'] = $row['FL'][5];
                                $dbData['created_at'] = $row['FL'][7];
                                $dbData['message'] = $row['FL'][10];
                                $dbData['video_url'] = $row['FL'][12];

                                if (isset($row['FL'][13])) {
                                    $dbData['video_title'] = $row['FL'][13];
                                } else {

                                    $dbData['video_title'] = '';
                                }
                                $slug = slug($dbData['video_title'], 'video_leads', 'slug');
                                $dbData['slug'] = $slug;
                                $dbData['status'] = 1;
                                $dbData['updated_at'] = date('Y-m-d H:i:s');
                                $this->db->insert('video_leads', $dbData);

                                $id = $this->db->insert_id();
                                $random = random_string('alnum', 8);
                                $random = strtoupper($random);
                                $random = $random . $id;

                                $key = hash("sha1", $random, FALSE);
                                $key = strtoupper($key);
                                $data = array(
                                    'unique_key' => $random,
                                    'encrypted_unique_key' => $key
                                );

                                $this->db->where('id', $id);
                                $this->db->update('video_leads', $data);
                            }
                        }
                    }
                }
            }
        }

        echo json_encode('Leads Import successfully!.');
        exit;
    }

    public function sr_callback()
    {

        /*$data1 = json_decode(file_get_contents('php://input'), true);*/
        //$string = '{"uuid":"51d9ced3-722f-4c26-b189-ea45e6f26bff","status":"ok","event_type":"converted","timestamp":"2018-04-11T06:24:52.314220Z","team":{"name":"WooGlobe LTD","subdomain":"wooglobepk","url":"https:\/\/wooglobepk.signrequest.com\/api\/v1\/teams\/wooglobepk\/"},"document":{"team":{"name":"WooGlobe LTD","subdomain":"wooglobepk","url":"https:\/\/wooglobepk.signrequest.com\/api\/v1\/teams\/wooglobepk\/"},"uuid":"9c2d590d-b2c8-40ee-a157-44e26719c723","user":null,"file_as_pdf":"https:\/\/signrequest-pro.s3.amazonaws.com\/docs\/2018\/04\/11\/685aa179a48dc40353863d97197d80e91654c956\/19_1523427859.pdf?Signature=%2FkUda%2Bm9ronmk5WboVngfjAqOg4%3D&Expires=1523428252&AWSAccessKeyId=AKIAIFC5SSMNRPLY3AMQ","name":"19_1523427859.pdf","external_id":"19","file":"https:\/\/signrequest-pro.s3.amazonaws.com\/docs\/2018\/04\/11\/685aa179a48dc40353863d97197d80e91654c956\/19_1523427859.pdf?Signature=%2FkUda%2Bm9ronmk5WboVngfjAqOg4%3D&Expires=1523428252&AWSAccessKeyId=AKIAIFC5SSMNRPLY3AMQ","file_from_url":null,"template":null,"prefill_tags":[],"integrations":[],"pdf":null,"status":"ne","signrequest":{"from_email":"sell@wooglobe.com","from_email_name":"WooGlobe","is_being_prepared":false,"prepare_url":null,"redirect_url":"http:\/\/localhost\/viralgreats\/admin\/","required_attachments":[],"disable_attachments":true,"disable_text_signatures":false,"disable_text":true,"disable_date":true,"disable_emails":false,"disable_upload_signatures":false,"subject":null,"message":"WooGlobe Content Agreement","who":"o","send_reminders":true,"signers":[{"email":"sell@wooglobe.com","display_name":"WooGlobe Licensing (sell@wooglobe.com)","first_name":"WooGlobe","last_name":"Licensing","email_viewed":false,"viewed":false,"signed":false,"downloaded":false,"signed_on":null,"needs_to_sign":false,"approve_only":false,"notify_only":false,"in_person":false,"order":0,"emailed":false,"language":"en","force_language":false,"verify_phone_number":null,"verify_bank_account":null,"declined":false,"declined_on":null,"forwarded":false,"forwarded_on":null,"forwarded_to_email":null,"forwarded_reason":null,"message":null,"inputs":[],"embed_url_user_id":null,"embed_url":null,"attachments":[],"redirect_url":"http:\/\/localhost\/viralgreats\/admin\/","after_document":null,"integrations":[]},{"email":"usman.ali.sarwar.wg@gmail.com","display_name":"Usman Ali Sarwar (usman.ali.sarwar.wg@gmail.com)","first_name":"Usman Ali","last_name":"Sarwar","email_viewed":true,"viewed":true,"signed":true,"downloaded":false,"signed_on":"2018-04-11T06:25:45.768716Z","needs_to_sign":true,"approve_only":false,"notify_only":false,"in_person":false,"order":0,"emailed":true,"language":"en","force_language":false,"verify_phone_number":null,"verify_bank_account":null,"declined":false,"declined_on":null,"forwarded":false,"forwarded_on":null,"forwarded_to_email":null,"forwarded_reason":null,"message":null,"inputs":[{"type":"s","page_index":0,"text":"","checkbox_value":null,"date_value":null,"external_id":null}],"embed_url_user_id":null,"embed_url":null,"attachments":[],"redirect_url":"http:\/\/localhost\/viralgreats\/admin\/","after_document":null,"integrations":[]}],"uuid":"7ce473d2-c576-4d24-b839-b23a8125229a"},"api_used":true,"signing_log":null,"security_hash":null,"attachments":[]},"signer":null,"token_name":"Real","event_time":"1523427892","event_hash":"23565018d07e91688934d9794fb172f19aaf8328e22bb4c3133409178c0d7349"}';
        /*   $data['data'] = json_encode($data1);
        $this->db->insert('callback',$data);*/
        //$data1 = json_decode($string);

        /*$leadId = $data1->document->external_id;
        $leadData = $this->lead->getLeadById($leadId,'vl.*');
        if($data1->event_type == 'declined'){

            $this->db->where('id',$leadId);
            $this->db->update('video_leads',array('status'=>4));

        }
        if($data1->event_type == 'signed'){

            $this->db->where('id',$leadId);
            $this->db->update('video_leads',array('status'=>3));
        }*/
    }


    public function test()
    {

        $data = json_decode('{"uuid":"2eecb86e-6ce7-4781-9502-14527331c482","status":"ok","event_type":"declined","timestamp":"2018-03-13T11:02:29.821939Z","team":{"name":"WoogGlobe","subdomain":"wooglobepk","url":"https:\/\/wooglobepk.signrequest.com\/api\/v1\/teams\/wooglobepk\/"},"document":{"team":{"name":"WoogGlobe","subdomain":"wooglobepk","url":"https:\/\/wooglobepk.signrequest.com\/api\/v1\/teams\/wooglobepk\/"},"uuid":"955b2be5-1b35-4798-9f2e-6a7c5651197a","user":null,"file_as_pdf":"https:\/\/signrequest-pro.s3.amazonaws.com\/docs\/2018\/03\/13\/4751f40c52207a2193891c6175e1019798715f6c\/21_1520938307.pdf?Signature=JviGpzZVApmy9rtNuIYeOaRuKcY%3D&Expires=1520939249&AWSAccessKeyId=AKIAIFC5SSMNRPLY3AMQ","name":"21_1520938307.pdf","external_id":"Send Sign Request","file":"https:\/\/signrequest-pro.s3.amazonaws.com\/docs\/2018\/03\/13\/4751f40c52207a2193891c6175e1019798715f6c\/21_1520938307.pdf?Signature=JviGpzZVApmy9rtNuIYeOaRuKcY%3D&Expires=1520939249&AWSAccessKeyId=AKIAIFC5SSMNRPLY3AMQ","file_from_url":null,"template":null,"prefill_tags":[],"integrations":[],"pdf":"https:\/\/signrequest-pro.s3.amazonaws.com\/pdfs\/2018\/03\/13\/8d4b59f1252ce603ec75d1ae1fcd3e6e38b38de7\/21_1520938307_declined.pdf?Signature=jdL%2FhtoDsQoydon7NjZHmdJML5Q%3D&Expires=1520939249&AWSAccessKeyId=AKIAIFC5SSMNRPLY3AMQ","status":"de","signrequest":{"from_email":"sell@wooglobe.com","from_email_name":"WooGlobe","is_being_prepared":false,"prepare_url":null,"redirect_url":"http:\/\/localhost\/viralgreats\/admin\/","required_attachments":[],"disable_attachments":true,"disable_text_signatures":false,"disable_text":true,"disable_date":true,"disable_emails":false,"disable_upload_signatures":false,"subject":null,"message":"Please sign this","who":"o","send_reminders":true,"signers":[{"email":"sell@wooglobe.com","display_name":"WooGlobe Licensing (sell@wooglobe.com)","first_name":"WooGlobe","last_name":"Licensing","email_viewed":false,"viewed":false,"signed":false,"downloaded":false,"signed_on":null,"needs_to_sign":false,"approve_only":false,"notify_only":false,"in_person":false,"order":0,"emailed":false,"language":"en","force_language":false,"verify_phone_number":null,"verify_bank_account":null,"declined":false,"declined_on":null,"forwarded":false,"forwarded_on":null,"forwarded_to_email":null,"forwarded_reason":null,"message":null,"inputs":[],"embed_url_user_id":null,"embed_url":null,"attachments":[],"redirect_url":"http:\/\/localhost\/viralgreats\/admin\/","after_document":null,"integrations":[]},{"email":"maliks_usman786@yahoo.com","display_name":"maliks_usman786@yahoo.com","first_name":"","last_name":"","email_viewed":true,"viewed":true,"signed":false,"downloaded":false,"signed_on":null,"needs_to_sign":true,"approve_only":false,"notify_only":false,"in_person":false,"order":0,"emailed":true,"language":"en","force_language":false,"verify_phone_number":null,"verify_bank_account":null,"declined":true,"declined_on":"2018-03-13T11:02:28.679599Z","forwarded":false,"forwarded_on":null,"forwarded_to_email":null,"forwarded_reason":null,"message":"No Way","inputs":[],"embed_url_user_id":null,"embed_url":null,"attachments":[],"redirect_url":"http:\/\/localhost\/viralgreats\/admin\/","after_document":null,"integrations":[]}],"uuid":"362fc2c1-5091-4c43-91db-c99940e4a3c4"},"api_used":true,"signing_log":{"pdf":"https:\/\/signrequest-pro.s3.amazonaws.com\/logs\/2018\/03\/13\/283363d9a4063ad0d1d44fe8461c13a3eead6ee2\/21_1520938307_signing_log.pdf?Signature=fCrpP2lELECDgEijJ0EFHtkX4vY%3D&Expires=1520939249&AWSAccessKeyId=AKIAIFC5SSMNRPLY3AMQ","security_hash":"b0caa7b0cef7c95aaf5ef94dc6e0975f11fbfec7a6e97ccc8c2c5ec6c9e8fcbf"},"security_hash":"15a63ec8341f14f716600b775566f98369d47f46b27cc36a986183d496abacc5","attachments":[]},"signer":null,"token_name":"WooGlobe","event_time":"1520938949","event_hash":"ba5666d93352928f78712cfdedc39e5ae683dd8323997c5fe2c687465491e2a0"}');
        echo '<pre>';
        print_r($data->event_type);
        exit;
    }

    public function import_deals()
    {
        $url = 'getRecords';
        $param = 'selectColumns=Potentials(First Name,Last Name,Email,Video URL,Video Title,Description,Created Time,Video Rating,Potential Stage,Potential Name,Potential Title,Closing Date)&Stage=Contract Sent&fromIndex=1&toIndex=1000&newFormat=1';
        $zoho = zoho_deals($url, $param);
        header("Content-type: application/json");
        $xml = simplexml_load_string($zoho, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);
        //echo '<pre>';
        //print_r($array);

        echo json_encode($array);
        exit;
        $rows = array();
        if (isset($array['result'])) {
            if (isset($array['result']['Leads'])) {
                if (isset($array['result']['Leads']['row'])) {
                    $rows = $array['result']['Leads']['row'];
                    foreach ($rows as $row) {
                        if (isset($row['FL']) && count($row['FL']) > 0) {
                            $result = $this->db->query('
                                SELECT id 
                                FROM video_leads
                                WHERE zoho_lead_id = ' . $row['FL'][0] . '
                            ');
                            if ($result->num_rows() == 0) {
                                $dbData['zoho_lead_id'] = $row['FL'][0];
                                $dbData['first_name'] = $row['FL'][1];
                                $dbData['last_name'] = $row['FL'][2];
                                $dbData['email'] = $row['FL'][3];
                                $dbData['created_at'] = $row['FL'][4];
                                $dbData['message'] = $row['FL'][5];
                                $dbData['video_url'] = $row['FL'][6];
                                $dbData['video_title'] = $row['FL'][7];
                                $dbData['status'] = 1;
                                $dbData['updated_at'] = date('Y-m-d H:i:s');
                                $this->db->insert('video_leads', $dbData);
                            }
                        }
                    }
                }
            }
        }

        echo json_encode('Leads Import successfully!.');
        exit;
    }
    public function gmail()
    {
        define('SCOPES', implode(
            ' ',
            array(
                Google\Service\Gmail::MAIL_GOOGLE_COM,
                Google\Service\Drive::DRIVE,
                Google\Service\YouTube::YOUTUBE,
                Google\Service\YouTube::YOUTUBE_READONLY,
                Google\Service\YouTube::YOUTUBE_UPLOAD,
                Google\Service\YouTube::YOUTUBEPARTNER,
                Google\Service\YouTube::YOUTUBEPARTNER_CHANNEL_AUDIT,

                Google_Service_Sheets::SPREADSHEETS,
                Google_Service_Sheets::SPREADSHEETS_READONLY,
                Google_Service_Sheets::DRIVE_READONLY,
                Google_Service_Sheets::DRIVE_FILE,
                Google_Service_Sheets::DRIVE,
            )
        ));

        date_default_timezone_set('America/New_York');
        $client = new Google_Client();
        $client->setApplicationName(APPLICATION_NAME_TUBE);
        $client->setScopes(SCOPES);
        $client->setAuthConfig(YOUTUBE_CLIENT_SECRET_PATH);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        // Load previously authorized credentials from a file.
        $credentialsPath = YOUTUBE_CREDENTIALS_PATH;
        $authUrl = base_url();
        if (file_exists($credentialsPath)) {
            redirect('cb_gmail?code=true');
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
        }

        redirect($authUrl);
    }


    public function cb_gmail()
    {

        define('SCOPES', implode(
            ' ',
            array(
                Google\Service\Gmail::MAIL_GOOGLE_COM,
                Google\Service\Drive::DRIVE,
                Google\Service\YouTube::YOUTUBE,
                Google\Service\YouTube::YOUTUBE_READONLY,
                Google\Service\YouTube::YOUTUBE_UPLOAD,
                Google\Service\YouTube::YOUTUBEPARTNER,
                Google\Service\YouTube::YOUTUBEPARTNER_CHANNEL_AUDIT,
                Google\Service\YouTube::YOUTUBE_FORCE_SSL,

                Google_Service_Sheets::SPREADSHEETS,
                Google_Service_Sheets::SPREADSHEETS_READONLY,
                Google_Service_Sheets::DRIVE_READONLY,
                Google_Service_Sheets::DRIVE_FILE,
                Google_Service_Sheets::DRIVE,

            )
        ));
        date_default_timezone_set('America/New_York');
        $client = new Google_Client();
        $client->setApplicationName(APPLICATION_NAME_TUBE);
        $client->setScopes(SCOPES);
        $client->setAuthConfig(YOUTUBE_CLIENT_SECRET_PATH);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $client->setIncludeGrantedScopes(true);
        if (!isset($_GET['code'])) {
            // $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] .'/wgplatform/admin/index.php/cb_gmail');
            $client->setRedirectUri('https://' . $_SERVER['HTTP_HOST'] . '/admin/index.php/cb_gmail');

            // echo 'https://' . $_SERVER['HTTP_HOST'] .'/admin/index.php/cb_gmail';
            // exit;
            redirect($client->createAuthUrl());
        }
        $authCode = trim($this->input->get('code'));
        // print_r($authCode);
        if (empty($authCode)) {
            redirect('gmail');
        }

        //$_SESSION['client_t'] = $client;


        $credentialsPath = YOUTUBE_CREDENTIALS_PATH;
        if (file_exists($credentialsPath)) {
            // print_r($credentialsPath);
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

            file_put_contents($credentialsPath, json_encode($accessToken));
            print_r($accessToken);
        } else {
            // Request authorization from the user.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            //$accessToken = $client->fetchAccessTokenWithRefreshToken($accessToken['refresh_token']);
            // mkdir(dirname('./app/config/gmail-auth-code.json'), 0700, true);
            //file_put_contents('./app/config/gmail-auth-code.json', json_encode($authCode));
            if (!file_exists(dirname($credentialsPath))) {
                mkdir(dirname($credentialsPath), 0700, true);
            }
            file_put_contents($credentialsPath, json_encode($accessToken));
            print_r($accessToken);
        }
    }

    public function cb_mgmt_channel()
    {

        define('SCOPES', implode(
            ' ',
            array(
                Google\Service\Gmail::MAIL_GOOGLE_COM,
                Google\Service\Drive::DRIVE,
                Google\Service\YouTube::YOUTUBE,
                Google\Service\YouTube::YOUTUBE_READONLY,
                Google\Service\YouTube::YOUTUBE_UPLOAD,
                Google\Service\YouTube::YOUTUBEPARTNER,
                Google\Service\YouTube::YOUTUBEPARTNER_CHANNEL_AUDIT,
                Google\Service\YouTube::YOUTUBE_FORCE_SSL,

                Google_Service_Sheets::SPREADSHEETS,
                Google_Service_Sheets::SPREADSHEETS_READONLY,
                Google_Service_Sheets::DRIVE_READONLY,
                Google_Service_Sheets::DRIVE_FILE,
                Google_Service_Sheets::DRIVE,

            )
        ));
        date_default_timezone_set('America/New_York');
        $client = new Google_Client();
        $client->setApplicationName(APPLICATION_NAME_TUBE);
        $client->setScopes(SCOPES);
        $client->setAuthConfig(YOUTUBE_MANAGMENT_CHANNEL_CLIENT_SECRET_PATH);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $client->setIncludeGrantedScopes(true);
        if (!isset($_GET['code'])) {
            $client->setRedirectUri('https://' . $_SERVER['HTTP_HOST'] . '/admin/index.php/cb_mgmt_channel');
            redirect($client->createAuthUrl());
        }
        $authCode = trim($this->input->get('code'));
        // print_r($authCode);
        if (empty($authCode)) {
            redirect('gmail');
        }

        //$_SESSION['client_t'] = $client;


        $credentialsPath = YOUTUBE_MANAGMENT_CHANNEL_CREDENTIALS_PATH;
        if (file_exists($credentialsPath)) {
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

            file_put_contents($credentialsPath, json_encode($accessToken));
            print_r($accessToken);
        } else {
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

            if (!file_exists(dirname($credentialsPath))) {
                mkdir(dirname($credentialsPath), 0700, true);
            }
            file_put_contents($credentialsPath, json_encode($accessToken));
            print_r($accessToken);
        }
    }
    /*public function gmail_ids(){
        $this->config->load('imap');
        $this->load->library('imap',$this->config->config['imap']);


        $messages = $this->imap->getAllMessages('[Gmail]/All Mail');
        $i = 0;
        $j = 0;
        foreach($messages as $message){

            $query = $this->db->query('
	            SELECT gc.id
	            FROM gmail_messages gm
	            WHERE gm.uid = "'.$message->uid.'"
	      
	        ');
            $dbData = array();
            foreach ($message as $i=>$v){
                $dbData[]
            }
            if($query->num_rows() == 0){
                $this->db->insert('gmail_messages',$message);
                $i++;
            }else{
                $j++;
            }

        }
        echo $i.' New emails found.<br>'.$j.' Existing emails.';
        exit;
    }*/
    public function gmail_inbox()
    {


        /*$this->load->library('gmail');
	    $messages = $this->gmail->getMessages();
	    $i = 0;
	    $j = 0;
	    foreach ($messages as $message){

	        $query = $this->db->query('
	            SELECT gc.id
	            FROM gmail_conversation gc
	            WHERE gc.message_id = "'.$message['message_id'].'"
	      
	        ');

	        if($query->num_rows() == 0){
                $message['converted_date_time'] = date('Y-m-d H:i:s',strtotime($message['message_date']));
	            $this->db->insert('gmail_conversation',$message);
	            $i++;

            }else{
	            $j++;
            }
        }*/
        ini_set('memory_limit', '2048M');
        $this->config->load('imap');
        $this->load->library('imap', $this->config->config['imap']);


        $messages = $this->imap->getAllMessages('[Gmail]/All Mail');

        /*echo '<pre>';
        print_r($messages);
        exit;*/
        $total = count($messages);
        $i = 0;
        $j = 0;
        $k = 1;
        $query_batch = array();
        foreach ($messages as $message) {

            /*echo '<pre>';
            print_r($megs);
            exit;*/
            $query = $this->db->query('
	            SELECT gc.id
	            FROM gmail_conversation gc
	            WHERE gc.uid = "' . $message->uid . '"
	      
	        ');
            $ignor = array(591, 592, 597, 598, 606, 913, 915, 2212, 4233, 5197, 5244, 5497, 5740, 5815);
            if ($query->num_rows() == 0 && !in_array($message->uid, $ignor)) {
                $megs = $this->imap->get_message($message->uid);
                $dbData = array();
                $dbData['converted_date_time'] = date('Y-m-d H:i:s', strtotime($megs['date']));
                $dbData['uid'] = $megs['uid'];
                $dbData['message_id'] = $megs['message_id'];
                $dbData['subject'] = $megs['subject'];
                $dbData['message'] = $megs['body']['html'];
                $dbData['message_date'] = $megs['date'];

                if (isset($megs['from']['email'])) {
                    $dbData['from_email'] = $megs['from']['email'];
                } else {
                    $dbData['from_email'] = '';
                }

                if (isset($megs['from']['name'])) {
                    $dbData['from_name'] = $megs['from']['name'];
                } else {
                    $dbData['from_name'] = '';
                }

                if (isset($megs['to'][0]['email'])) {
                    $dbData['to_email'] = $megs['to'][0]['email'];
                } else {
                    $dbData['to_email'] = '';
                }

                if (isset($megs['to'][0]['name'])) {
                    $dbData['to_name'] = $megs['to'][0]['name'];
                } else {
                    $dbData['to_name'] = '';
                }


                $query_batch[] = $dbData;
                $this->db->insert('gmail_conversation', $dbData);
                /*if(($k % 1000) == 0 || $k == $total){
                    $this->db->insert_batch('gmail_conversation',$query_batch);
                    $query_batch = array();

                }*/
                $i++;
            } else {
                $j++;
            }
        }
        /*echo '<pre>';
        print_r($query_batch);
        exit;*/
        //$this->db->insert_batch('gmail_conversation',$query_batch);


        echo $i . ' New emails found.<br>' . $j . ' Existing emails.';
        exit;
    }

    public function gmail_today_inbox()
    {
        ini_set('memory_limit', '2048M');

        $this->config->load('imap');
        $this->load->library('imap', $this->config->config['imap']);
        $this->imap->search_on_date(date('Y-m-d'));
        //$messages =  $this->imap->search('UNSEEN');
        $messages =  $this->imap->search();
        /*echo '<pre>';
        print_r($messages);
        exit;*/

        //$messages = $this->imap->count_messages('[Gmail]/All Mail');

        $i = 0;
        $j = 0;
        $query_batch = array();
        foreach ($messages as $message) {
            //$megs = $this->imap->get_message($message);
            $query = $this->db->query('
	            SELECT gc.id
	            FROM gmail_conversation gc
	            WHERE gc.uid = "' . $message . '"
	      
	        ');

            if ($query->num_rows() == 0) {
                $megs = $this->imap->get_message($message);
                $dbData = array();
                $dbData['converted_date_time'] = date('Y-m-d H:i:s', strtotime($megs['date']));
                $dbData['uid'] = $megs['uid'];
                $dbData['message_id'] = $megs['message_id'];
                $dbData['subject'] = $megs['subject'];
                $dbData['message'] = $megs['body']['html'];
                $dbData['message_date'] = $megs['date'];
                if (isset($megs['from']['email'])) {
                    $dbData['from_email'] = $megs['from']['email'];
                } else {
                    $dbData['from_email'] = '';
                }

                if (isset($megs['from']['name'])) {
                    $dbData['from_name'] = $megs['from']['name'];
                } else {
                    $dbData['from_name'] = '';
                }

                $dbData['to_email'] = $megs['to'][0]['email'];
                $dbData['to_name'] = $megs['to'][0]['name'];
                $query_batch[] = $dbData;
                //$this->db->insert('gmail_conversation',$dbData);
                $i++;
            } else {
                $j++;
            }
        }

        if (count($query_batch) > 0) {
            $this->db->insert_batch('gmail_conversation', $query_batch);
        }

        $msg = $i . ' New emails found.<br>' . $j . ' Existing emails.';
        $date = date('Y-m-d H:i:s') . '<br>' . $msg;
        //$this->email('client.wooglobe@gmail.com','Client Portal','viral@wooglobe.com','Cronjobs','Today Gmail Messages',$date);
        echo $msg;
        exit;
    }
    public function gmail_push()
    {

        $this->load->library('gmail');
        $messages = $this->gmail->getPushNotification();
        // print_r("fbehjefbhjer");exit();
        //Get History ID Store latest history ID in the variable
        $historyidnew = $messages->historyId;
        $getJson = file_get_contents("./app/config/history_id.json", false);
        $historyidold = json_encode($getJson);
        //Get OLD HISTORY ID
        $historyidold = stripslashes($historyidold);
        $historyidold = str_replace(array('[', ']'), '', $historyidold);
        $historyidold = str_replace('"', '', $historyidold);
        if ($historyidold == $historyidnew) {
            //if OLD HISTORY ID AND LATEST HISTORY ID THEN EXIT FROM FUNCTION

            redirect('/');
        } else {
            print 'else';
            $arr = array($historyidnew);
            $json = json_encode($arr);
            //Store Latest History id in file
            $setJson = file_put_contents("./app/config/history_id.json", $json);
            $gmailmessages = $this->gmail->listHistory('me', $historyidold);
            //Get latest message from given Starting history id
            $gmail_to = '';
            $gmail_from = '';
            $gmail_sub = '';
            $msg_threadId = '';
            $gmail_date = '';
            $i = 0;
            $msg_data = '';
            //Loop
            foreach ($gmailmessages as $gmailid) {
                $getgmailid = $gmailid['messages'][0]->id;

                $res = $this->db->query('SELECT COUNT(id) as msg_found FROM gmail_conversation WHERE message_id="' . $getgmailid . '"')->result();
                $row = $res[0];
                // check if msg id already in db, skip
                if ($row->msg_found > 0) {
                    continue;
                }
                // check if msg id is empty then exit
                if ($getgmailid == '') {
                    break;
                } else {
                    // fetch message data for each msessage id
                    $getgmailnewmessages = $this->gmail->getGmailMessage('me', $getgmailid);
                    if (gettype($getgmailnewmessages) == 'NULL') {
                        continue;
                    } else if (array_intersect($getgmailnewmessages['labelIds'], array('CATEGORY_SOCIAL'))) {
                        continue;
                    } else { // message we want to save
                        $gmail_msg_id = $getgmailnewmessages['id'];
                        $msg_threadId = $getgmailnewmessages['threadId'];
                        if (array_intersect($getgmailnewmessages['labelIds'], array('INBOX'))) {
                            $labels = 'INBOX';
                        } else if (array_intersect($getgmailnewmessages['labelIds'], array('SENT'))) {
                            $labels = 'SENT';
                        } else if (array_intersect($getgmailnewmessages['labelIds'], array('DELETED'))) {
                            $labels = 'DELETED';
                        }
                        // if payload has parts attribute
                        $payload = (array)$getgmailnewmessages['payload'];
                        if (isset($payload['parts'])) {
                            if (isset($payload['parts'][1])) {
                                $gmail_msg_key = $payload['parts'][1];
                                $msg_body = (array)$gmail_msg_key['body'];
                                if (isset($msg_body['data'])) {
                                    $msg_data = $this->gmailBodyDecode($msg_body['data']);
                                } else {
                                    $msg_data_attachment_data = base64_decode(str_replace(array('-', '_'), array('+', '/'), $msg_body['attachmentId']));
                                    $msgfile = fopen("./uploads/pdf/" . $gmail_msg_id . ".pdf", "wb");
                                    fwrite($msgfile, $msg_data_attachment_data);
                                    fclose($msgfile);
                                    $msgfile_name = "./uploads/pdf/" . $gmail_msg_id . ".pdf";
                                    $data_size_zero = $payload['parts'][0]['body']['size'];
                                    if ($data_size_zero == 0) {
                                        if (isset($payload['parts'][0]['parts'][1]['body']['data'])) {
                                            $msg_inner_data = $this->gmailBodyDecode($payload['parts'][0]['parts'][1]['body']['data']);
                                            $msg_data = $msg_inner_data . 'Attach file path' . '' . $msgfile_name;
                                        } else {
                                            $msg_inner_data = $this->gmailBodyDecode($payload['parts'][0]['parts'][0]['body']['data']);
                                            $msg_data = $msg_inner_data . 'Attach file path' . '' . $msgfile_name;
                                        }
                                    }
                                }
                            } else {
                                $gmail_msg_key = $payload['parts'][0];
                                $msg_body = (array)$gmail_msg_key['body'];
                                $msg_data = $this->gmailBodyDecode($msg_body['data']);
                            }
                        } else {
                            $gmail_msg_key = $payload['body'];
                            $msg_data = $this->gmailBodyDecode($gmail_msg_key['data']);
                        }
                        $gmail_headers = $payload['headers'];

                        foreach ($gmail_headers as $gmail_header) {
                            if ($gmail_header['name'] == 'To') {

                                $gmail_to = $gmail_header['value'];
                                $gmail_toarray = explode(" <", $gmail_to);
                                $gmail_to_name = $gmail_toarray[0];
                                $gmail_to_name = str_replace('>', '', $gmail_to_name);
                                if (isset($gmail_toarray[1])) {
                                    $gmail_to = $gmail_toarray[1];
                                }
                                $gmail_to = str_replace('>', '', $gmail_to);
                                if ($gmail_to == 'NULL') {
                                    $gmail_to = $gmail_to_name;
                                }
                            }
                            if ($gmail_header['name'] == 'From') {
                                $gmail_from = $gmail_header['value'];
                                $gmail_fromarray = explode(" <", $gmail_from);
                                $gmail_from_name = $gmail_fromarray[0];
                                $gmail_from_name = str_replace('>', '', $gmail_from_name);
                                if (isset($gmail_fromarray[1])) {
                                    $gmail_from = $gmail_fromarray[1];
                                }
                                $gmail_from = str_replace('>', '', $gmail_from);
                                if ($gmail_from == 'NULL') {
                                    $gmail_from = $gmail_from_name;
                                }
                            }
                            if ($gmail_header['name'] == 'Subject') {
                                $gmail_sub = $gmail_header['value'];
                            }
                            if ($gmail_header['name'] == 'Date') {
                                $gmail_date = $gmail_header['value'];
                            }
                            $gmail_converted_datetime = date('Y-m-d H:i:s', strtotime($gmail_date));
                        }
                        $gmail_data_array = array(
                            $msg_threadId,
                            $gmail_date,
                            $gmail_converted_datetime,
                            $gmail_to,
                            $gmail_from,
                            $gmail_sub,
                            $msg_data
                        );
                        $msg_array = array(
                            "uid" => $gmail_msg_id,
                            "message_id" => $gmail_msg_id,
                            "thread_id" => $msg_threadId,
                            "from_email" => $gmail_from,
                            "from_name" => $gmail_from_name,
                            "to_email" => $gmail_to,
                            "to_name" => $gmail_to_name,
                            "subject" => $gmail_sub,
                            "message_date" => $gmail_date,
                            "message" => $msg_data,
                            "converted_date_time" => $gmail_converted_datetime,
                            "labels" => $labels
                        );
                    }
                }
                $result = $this->db->insert('gmail_conversation', $msg_array);

                // Insert into action_taken table to keep track of actions against a video lead
                // get lead id from unique key
                $gmail_subj_array = explode('-', $gmail_sub);
                $unique_key = $gmail_subj_array[count($gmail_subj_array) - 1];
                $lead_query = $this->db->query('SELECT id FROM video_leads WHERE unique_key="' . $unique_key . '"')->row();
                if ($lead_query) {
                    $lead_id = $lead_query->id;
                    $action = "Email Received ";

                    if (strpos($gmail_from, 'wooglobe.com') < 0) { // email by client
                        if (strpos($gmail_sub, 'RE:') > -1) { // if email is a reply to an email thread
                            $action = "Email reply by client";
                        } else { // new email
                            $action = "Email from client";
                        }
                    } else { //email by admin
                        if (strpos($gmail_sub, 'RE:') > -1) { // if email is a reply to an email thread
                            $action = "Email reply by admin";
                        } else { // new email
                            $action = "Email from admin";
                        }
                    }
                } else {
                    // email corresponding lead not found
                    continue;
                }

                $action_taken['lead_id'] = $lead_id;
                $action_taken['video_id'] = 0;
                $action_taken['user_id'] = 0;
                $action_taken['admin_id'] = 0;
                $action_taken['is_admin'] = 0;
                $action_taken['action'] = $action;
                $action_taken['created_at'] = date("Y-m-d H:i:s");
                $this->db->insert('action_taken', $action_taken);
            }
        }
        redirect('/');
    }

    public function fullSyncGmail($before_date, $after_date)
    {

        print 'started at::' . date('Y-m-d H:i:s');
        ini_set('max_execution_time', 5000);
        $before_date = 'before:' . $before_date . ' and after:' . $after_date;
        //truncate table  gmail_conversation;
        //$this->db->query("Truncate Table  gmail_conversation");
        $this->load->library('gmail');
        // fetch all messages from gmail api. It returns message id and thread id
        $gmailmessages = $this->gmail->listGmailMessages('me', array(), false, $before_date);
        $a = 0;
        $skipped = 0;
        foreach ($gmailmessages as $item) {
            print "Row #" . $a;
            $a++;
            $labels = '';
            $gmail_to = '';
            $gmail_from = '';
            $gmail_sub = '';
            $msg_threadId = '';
            $gmail_date = '';
            $gmail_from_name = '';
            $gmail_to_name = '';
            $gmail_converted_datetime = '';
            $msg_id = $item['id'];
            // check if msg id already in db, skip
            $res = $this->db->query('SELECT COUNT(id) as msg_found FROM gmail_conversation WHERE message_id="' . $msg_id . '"')->result();
            $row = $res[0];
            if ($row->msg_found > 0) {
                $skipped++;
                continue;
            }
            // fetch message data for each msessage id
            $msg_data = $this->gmail->getGmailMessage('me', $msg_id);
            print '<br>msg detail at::' . date('Y-m-d H:i:s.u');
            if (gettype($msg_data) == 'NULL') { // If message id not found - may be deleted
                $skipped++;
                continue;
            } else if (array_intersect($msg_data['labelIds'], array('CATEGORY_SOCIAL'))) { // skip social messages
                $skipped++;
                continue;
            } else { // message we want to save
                $gmail_msg_id = $msg_id;
                $msg_threadId = $msg_data['threadId'];
                if (array_intersect($msg_data['labelIds'], array('INBOX'))) {
                    $labels = 'INBOX';
                } else if (array_intersect($msg_data['labelIds'], array('SENT'))) {
                    $labels = 'SENT';
                } else if (array_intersect($msg_data['labelIds'], array('DELETED'))) {
                    $labels = 'DELETED';
                }
                $payload = (array) $msg_data['payload'];

                // if payload has parts attribute
                if (isset($payload['parts'])) {
                    // Array element 0 in parts has message bofy in plain text
                    // elemnt 1 has same message in html format
                    // if 1 is found we use html other wise plain message content
                    if (isset($payload['parts'][1])) {
                        $gmail_msg_key = $payload['parts'][1];
                        $msg_body = (array)$gmail_msg_key['body'];
                        if (isset($msg_body['data'])) {
                            $msg_data = $this->gmailBodyDecode($msg_body['data']);
                        } else {
                            $msg_data_attachment_data = base64_decode(str_replace(array('-', '_'), array('+', '/'), $msg_body['attachmentId']));
                            $msgfile = fopen("./uploads/pdf/" . $gmail_msg_id . ".pdf", "wb");
                            fwrite($msgfile, $msg_data_attachment_data);
                            fclose($msgfile);
                            $msgfile_name = "./uploads/pdf/" . $gmail_msg_id . ".pdf";
                            $msg_data_parts = $payload['parts'][0];
                            $msg_data_parts_zero = $payload['parts'][0]['parts']['0'];
                            $msg_body = (array)$gmail_msg_key['body'];
                            if ($msg_body['size'] == 0) {
                                if (isset($msg_data_parts_zero['header'][1])) {
                                    $msg_data_html = $this->gmailBodyDecode($msg_data_parts_zero['header'][1]['body']['data']);
                                } else {
                                    $msg_data_html = $this->gmailBodyDecode($msg_data_parts_zero['header'][0]['body']['data']);
                                }
                            }
                            $msg_data = $msg_data_html . '<br>Attachment file path:<br>' . '' . $msgfile_name;
                        }
                    } else { // plain text
                        $gmail_msg_key = $payload['parts'][0];
                        $msg_body = (array)$gmail_msg_key['body'];
                        $msg_data = $this->gmailBodyDecode($msg_body['data']);
                    }
                } else { // if 'parts' not returned. find message content in 'body'
                    $gmail_msg_key = $payload['body'];
                    $msg_data = $this->gmailBodyDecode($gmail_msg_key['data']);
                }
                // Get headers info here. To, From, Subject, Date
                // Date is converted to Timestamp format for converted_date fields in table
                $gmail_headers = $payload['headers'];
                foreach ($gmail_headers as $gmail_header) {
                    if ($gmail_header['name'] == 'To') {

                        $gmail_to = $gmail_header['value'];
                        $gmail_toarray = explode(" <", $gmail_to);
                        $gmail_to_name = $gmail_toarray[0];
                        $gmail_to_name = str_replace('>', '', $gmail_to_name);
                        if (isset($gmail_toarray[1])) {
                            $gmail_to = $gmail_toarray[1];
                        }
                        $gmail_to = str_replace('>', '', $gmail_to);
                        if ($gmail_to == 'NULL') {
                            $gmail_to = $gmail_to_name;
                        }
                    }
                    if ($gmail_header['name'] == 'From') {
                        $gmail_from = $gmail_header['value'];
                        $gmail_fromarray = explode(" <", $gmail_from);
                        $gmail_from_name = $gmail_fromarray[0];
                        $gmail_from_name = str_replace('>', '', $gmail_from_name);
                        if (isset($gmail_fromarray[1])) {
                            $gmail_from = $gmail_fromarray[1];
                        }
                        $gmail_from = str_replace('>', '', $gmail_from);
                        if ($gmail_from == 'NULL') {
                            $gmail_from = $gmail_from_name;
                        }
                    }
                    if ($gmail_header['name'] == 'Subject') {
                        $gmail_sub = $gmail_header['value'];
                    }
                    if ($gmail_header['name'] == 'Date') {
                        $gmail_date = $gmail_header['value'];
                    }
                    $gmail_converted_datetime = date('Y-m-d H:i:s', strtotime($gmail_date));
                }

                // Complete data for an item is ready here and pushed into array to save in db
                $gmail_data_array[] = array(
                    $msg_threadId,
                    $gmail_date,
                    $gmail_converted_datetime,
                    $gmail_to,
                    $gmail_from,
                    $gmail_sub,
                    $msg_data
                );
                $msg_array = array(
                    "uid" => $gmail_msg_id,
                    "message_id" => $gmail_msg_id,
                    "thread_id" => $msg_threadId,
                    "from_email" => $gmail_from,
                    "from_name" => $gmail_from_name,
                    "to_email" => $gmail_to,
                    "to_name" => $gmail_to_name,
                    "subject" => $gmail_sub,
                    "message_date" => $gmail_date,
                    "message" => $msg_data,
                    "converted_date_time" => $gmail_converted_datetime,
                    "labels" => $labels
                );
            }
            $dataPoints[] = array("message_id" => $gmail_msg_id, "gmail_data" => $gmail_data_array);
            $this->db->insert('gmail_conversation', $msg_array);
        }
        print "Process Complete";
    }

    function gmailBodyDecode($data)
    {
        $data = base64_decode(str_replace(array('-', '_'), array('+', '/'), $data));
        //from php.net/manual/es/function.base64-decode.php#118244

        $data = imap_qprint(quoted_printable_encode($data));

        return ($data);
    }
    public function youtube()
    {

        $this->load->library('youtube');
        $this->youtube->getCategories();
    }

    public function fb()
    {
        $fb = new Facebook\Facebook([
            'app_id' => $this->config->config['fb_id'], // Replace {app-id} with your app id
            'app_secret' => $this->config->config['fb_secret'],
            'default_graph_version' => 'v3.1',
        ]);
        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email', 'publish_video', 'user_posts', 'manage_pages', 'user_videos', 'pages_show_list', 'publish_pages', 'read_page_mailboxes']; // Optional permissions
        //$permissions = ['email']; // Optional permissions
        $rediectUrl = $this->data['url'] . 'cb_fb';
        $loginUrl = $helper->getLoginUrl($rediectUrl, $permissions);

        redirect($loginUrl);
    }


    public function cb_fb()
    {



        $fb = new Facebook\Facebook([
            'app_id' => $this->config->config['fb_id'], // Replace {app-id} with your app id
            'app_secret' => $this->config->config['fb_secret'],
            'default_graph_version' => 'v3.1',
        ]);
        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email', 'publish_video', 'user_posts', 'manage_pages', 'user_videos', 'pages_show_list', 'publish_pages', 'read_page_mailboxes']; // Optional permissions
        //$permissions = ['email'];
        $rediectUrl = $this->data['url'] . 'cb_fb';
        $loginUrl = $helper->getLoginUrl($rediectUrl, $permissions);

        try {
            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }



        $oAuth2Client = $fb->getOAuth2Client();

        $tokenMetadata = $oAuth2Client->debugToken($accessToken);


        $tokenMetadata->validateAppId($this->config->config['fb_id']);
        $tokenMetadata->validateExpiration();

        if (!$accessToken->isLongLived()) {
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
                exit;
            }
        }

        $dbData['token'] = $accessToken->getValue();
        $this->db->where('id', 1);
        $this->db->update('fb_token', $dbData);
        $password = sha1('WooGlobe@78600');
        $username = 'admin';
        $result = $this->auth->login($username, $password);
        $user = $result->row();
        $sess = array(
            'isAdminLogin' => TRUE,
            'adminId' => $user->id,
            'adminName' => $user->name,
            'adminUsername' => $user->username,
            'adminEmail' => $user->email,
            'adminRoleId' => $user->admin_role_id,
            'adminRole' => $user->admin_role,
            'adminTypeId' => $user->role_type_id,
            'adminType' => $user->admin_type,
        );
        $this->sess->set_userdata($sess);
        redirect('Dashboard');
    }

    public function test_fb()
    {

        $this->load->model('Video_Deal_Model', 'deal');
        $this->load->model('Video_Model', 'video');
        $video_data = $this->deal->getPortalVideo(14);
        $video = $this->video->getVideoById(14);

        $this->load->library('fb');
        $data = array(
            'title' => $video->title,
            'description' => $video->description
        );
        $videoPath = './../' . $video_data->fb_url;
        $result = $this->fb->uplaodVideo($data, $videoPath);


        echo '<pre>';
        print_r($result);
        exit;
    }

    public function distribution()
    {
        $this->load->model('Video_Deal_Model', 'deal');
        $this->load->model('Video_Model', 'video');
        $datetime = date('Y-m-d H:i:s');
        $this->load->library('youtube');
        //$this->load->library('sheet');
        //$this->load->library('fb');
        $result = $this->db->query('
                SELECT *
                FROM video_publishing_scheduling
                WHERE publish_datetime <= "' . $datetime . '"
                AND published = 0
        
        ');
        //         echo '
        //         SELECT *
        //         FROM video_publishing_scheduling
        //         WHERE publish_datetime <= "'.$datetime.'"
        //         AND published = 0

        // ';
        //         var_dump($result->result());
        //         exit();
        foreach ($result->result() as $row) {
            $video_data = $this->deal->getPortalVideo($row->video_id);
            $video = $this->video->getVideoById($row->video_id);
            $dealData = $this->video->getLeadDetailByVideoId($row->video_id);
            if ($row->publish_type == 'YouTube') {
                $youtube = $this->youtube->publishVideo($video_data, $row);
                if ($youtube['error'] == false) {

                    $dbData1['youtube_id'] = $youtube['id'];
                    $this->db->where('id', $row->video_id);
                    $this->db->update('videos', $dbData1);
                    $dbData2['published'] = 1;
                    $this->db->where('id', $row->id);
                    $this->db->update('video_publishing_scheduling', $dbData2);
                    //$this->sheet->addRowToSpreadsheet(array($dbData1['youtube_id'],'https://www.youtube.com/watch?v='.$result['id'],$row->video_title,'',$dealData->unique_key));
                    if ($video) {
                        $status = $this->deal->updateYoutubeStatus($video->lead_id);
                        $this->deal->dealStatusChangeFromDistributeToWon($video->lead_id);
                    }
                }
            } else if ($row->publish_type == 'FaceBook') {


                //Create a new DateTime object using the date string above.
                $dateTime = new DateTime($row->publish_datetime);

                //Format it into a Unix timestamp.
                $timestamp = $dateTime->format('U');

                $data = array(
                    'title' => $video->title,
                    'description' => $video->description,
                    'content_tags' => explode(',', $video->tags),
                    'published' => false,
                    'scheduled_publish_time' => strtotime($timestamp),
                );
                $videoPath = './../' . $video_data->fb_url;

                $result = $this->fb->uplaodVideo($data, $videoPath);

                if ($result) {

                    $dbData1['facebook_id'] = $result['id'];
                    $this->db->where('id', $row->video_id);
                    $this->db->update('videos', $dbData1);
                    $dbData2['published'] = 1;
                    $this->db->where('video_id', $row->video_id);
                    $this->db->where('publish_type', 'FaceBook');
                    $this->db->update('video_publishing_scheduling', $dbData2);
                    if ($video_data) {
                        $status = $this->deal->updateFacebookStatus($video->lead_id);
                        $this->deal->dealStatusChangeFromDistributeToWon($video->lead_id);
                    }
                }
            }
        }
    }

    public function videos_bulk_upload()
    {
        //echo getcwd();exit;
        $this->load->helper('file');
        $file = getcwd() . '\bulk_files\test.csv';
        $file = str_replace("\\", '/', $file);
        //echo $file;exit;
        $this->db->query('TRUNCATE bulk_upload');
        $query = $this->db->query("
	            LOAD DATA INFILE '" . $file . "'
                INTO TABLE bulk_upload
                COLUMNS TERMINATED BY ','
                OPTIONALLY ENCLOSED BY '\"'
                ESCAPED BY '\"'
                LINES TERMINATED BY '\n'
                IGNORE 1 LINES
                (video_title,description , tags,thumb,channel,status,url,first_name,last_name,email,message,rating_point,rating_comment,closeing_date,revenue_share);
	    ");

        $videos = $this->db->query('
	            SELECT *
	            FROM bulk_upload
	    ');
        exit;
        foreach ($videos->result() as $video) {
            $currnt_date = date('Y-m-d H:i:s');
            if (!empty($video->email)) {
                $client_id = 0;
                $client_query = $this->db->query('
	                SELECT *
	                FROM users 
	                WHERE email = "' . $video->email . '"
	                AND role_id = 1
	            ');
                if ($client_query->num_rows() > 0) {
                    $client_id = $client_query->row()->id;
                } else {
                    $client_db_data['full_name'] = $video->first_name . ' ' . $video->last_name;
                    $client_db_data['email'] = $video->email;
                    $client_db_data['created_at'] = $currnt_date;
                    $client_db_data['deleted_at'] = $currnt_date;
                    $client_db_data['created_by'] = 1;
                    $client_db_data['updated_by'] = 1;
                    $client_db_data['role_id'] = 1;
                    $client_db_data['status'] = 1;
                    $this->db->insert('users', $client_db_data);
                    $client_id = $this->db->insert_id();
                }

                $lead_data = array();
                $lead_data['created_at'] = $currnt_date;
                $lead_data['deleted_at'] = $currnt_date;
                $lead_data['created_by'] = 1;
                $lead_data['updated_by'] = 1;
                $lead_data['status'] = 8;
                $lead_data['client_id'] = $client_id;
                $lead_data['first_name'] = $video->first_name;
                $lead_data['last_name'] = $video->last_name;
                $lead_data['email'] = $video->email;
                $lead_data['video_title'] = $video->video_title;
                $lead_data['video_url'] = $video->video_url;
                $lead_data['message'] = $video->message;
                $lead_data['published_yt'] = 1;
                $lead_data['published_fb'] = 1;
                $lead_data['published_portal'] = 1;
                $lead_data['uploaded_edited_videos'] = 1;
                $lead_data['terms'] = 1;

                $lead_data['rating_point'] = $video->rating_point;
                $lead_data['rating_comments'] = $video->rating_comments;
                $lead_data['closing_date'] = date('Y-m-d H:i:s', strtotime($video->closing_date));
                $lead_data['revenue_share'] = $video->revenue_share;
                $lead_data['slug'] = slug($lead_data['video_title'], 'video_leads', 'slug');
                $lead_data['load_view'] = 4;
                $lead_data['shotVideo'] = 1;
                $lead_data['haveOrignalVideo'] = $video->is_orgininal_video;
                $lead_data['information_pending'] = 1;
                $this->db->insert('video_leads', $lead_data);
                $lead_id = $this->db->insert_id();
                $random = random_string('alnum', 8);
                $random = strtoupper($random);
                $random = $random . $lead_id;

                $key = hash("sha1", $random, FALSE);
                $key = strtoupper($key);
                $data = array(
                    'unique_key' => $random,
                    'encrypted_unique_key' => $key
                );

                $this->db->where('id', $lead_id);
                $this->db->update('video_leads', $data);

                $video_data = array();
                $video_data['created_at'] = $currnt_date;
                $video_data['deleted_at'] = $currnt_date;
                $video_data['created_by'] = 1;
                $video_data['updated_by'] = 1;
                $video_data['status'] = 1;
                $video_data['embed'] = 0;
                $video_data['is_wooglobe_video'] = 1;
                $video_data['real_deciption_updated'] = 1;
                $video_data['is_category_verified'] = 1;
                $video_data['is_tags_verified'] = 1;
                $video_data['is_title_verified'] = 1;
                $video_data['is_description_verified'] = 1;
                $video_data['is_orignal_video_verified'] = 1;
                $video_data['video_verified'] = 1;
                $video_data['is_high_quality'] = 1;
                $video_data['question_video_taken'] = $video->where_taken;
                $video_data['question_video_context'] = $video->context;
                $video_data['question_when_video_taken'] = date('Y-m-d H:i:s', strtotime($video->when_taken));
                $video_data['question_video_information'] = $video->other_info;
                $video_data['title'] = $video->video_title;
                $video_data['description'] = $video->description;
                $video_data['tags'] = $video->tags;
                $video_data['url'] = '';
                $video_data['thumbnail'] = '';
                $video_data['user_id'] = $client_id;
                $video_data['lead_id'] = $lead_id;
                $video_data['category_id'] = 8;
                $video_data['video_type_id'] = 1;
                $video_data['slug'] = slug($lead_data['video_title'], 'videos', 'slug');
                $this->db->insert('videos', $video_data);
                $video_id = $this->db->insert_id();
                $file_directory = strtolower(trim($video->first_name) . '_' . trim($video->last_name));
                $rawfile_path = './wooglobe/raw_files/' . $file_directory . '/';
                $upload_path =  './../uploads/videos/';
                $all_raw_files = get_filenames($rawfile_path);
                $edited_videos = array();
                foreach ($all_raw_files as $raw_file) {
                    $file_info = pathinfo($rawfile_path . $raw_file);
                    $currentFilePath = $rawfile_path . $raw_file;
                    $new_file_name = md5($file_info['filename']) . '.' . $file_info['extension'];
                    $newFilePath = $upload_path . 'raw_videos/' . $new_file_name;
                    $fileMoved = rename($currentFilePath, $newFilePath);
                    $raw_video_dbdata['video_id'] = $video_id;
                    $raw_video_dbdata['lead_id'] = $lead_id;
                    $raw_video_dbdata['url'] = 'uploads/videos/' . $new_file_name;
                    $this->db->insert('raw_video', $raw_video_dbdata);
                }
                $portalfile_path = './wooglobe/portal/' . $file_directory . '/';
                $portalthumb_path = './wooglobe/portal/thumbnail/' . $file_directory . '/';
                $all_portal_files = get_filenames($portalfile_path);
                $all_portal_thumb = get_filenames($portalthumb_path);
                foreach ($all_portal_files as $portal_file) {
                    $file_info = pathinfo($portalfile_path . $portal_file);
                    $currentFilePath = $portalfile_path . $portal_file;
                    $new_file_name = md5($file_info['filename']) . '.' . $file_info['extension'];
                    $newFilePath = $upload_path . 'edited/' . $new_file_name;
                    $fileMoved = rename($currentFilePath, $newFilePath);
                    $edited_videos['portal_url'] = 'uploads/videos/edited/' . $new_file_name;
                }
                foreach ($all_portal_thumb as $portal_thumb) {
                    $file_info = pathinfo($portalthumb_path . $portal_thumb);
                    $currentFilePath = $portalthumb_path . $portal_thumb;
                    $new_file_name = md5($file_info['filename']) . '.' . $file_info['extension'];
                    $newFilePath = $upload_path . 'edited/thumbnail/' . $new_file_name;
                    $fileMoved = rename($currentFilePath, $newFilePath);
                    $edited_videos['portal_thumb'] = 'uploads/videos/thumbnail/' . $new_file_name;
                }
                $fbfile_path = './wooglobe/facebook/' . $file_directory . '/';
                $fbthumb_path = './wooglobe/facebook/thumbnail/' . $file_directory . '/';
                $all_fb_files = get_filenames($fbfile_path);
                $all_fb_thumb = get_filenames($fbthumb_path);
                foreach ($all_fb_files as $fb_file) {
                    $file_info = pathinfo($fbfile_path . $fb_file);
                    $currentFilePath = $fbfile_path . $fb_file;
                    $new_file_name = md5($file_info['filename']) . '.' . $file_info['extension'];
                    $newFilePath = $upload_path . 'edited/' . $new_file_name;
                    $fileMoved = rename($currentFilePath, $newFilePath);
                    $edited_videos['fb_url'] = 'uploads/videos/edited/' . $new_file_name;
                }
                foreach ($all_fb_thumb as $fb_thumb) {
                    $file_info = pathinfo($fbthumb_path . $fb_thumb);
                    $currentFilePath = $fbthumb_path . $fb_thumb;
                    $new_file_name = md5($file_info['filename']) . '.' . $file_info['extension'];
                    $newFilePath = $upload_path . 'edited/thumbnail/' . $new_file_name;
                    $fileMoved = rename($currentFilePath, $newFilePath);
                    $edited_videos['fb_thumb'] = 'uploads/videos/thumbnail/' . $new_file_name;
                }
                $ytfile_path = './wooglobe/youtube/' . $file_directory . '/';
                $ytthumb_path = './wooglobe/youtube/thumbnail/' . $file_directory . '/';
                $all_yt_files = get_filenames($ytfile_path);
                $all_yt_thumb = get_filenames($ytthumb_path);
                foreach ($all_yt_files as $yt_file) {
                    $file_info = pathinfo($ytfile_path . $yt_file);
                    $currentFilePath = $ytfile_path . $yt_file;
                    $new_file_name = md5($file_info['filename']) . '.' . $file_info['extension'];
                    $newFilePath = $upload_path . 'edited/' . $new_file_name;
                    $fileMoved = rename($currentFilePath, $newFilePath);
                    $edited_videos['yt_url'] = 'uploads/videos/edited/' . $new_file_name;
                }
                foreach ($all_yt_thumb as $yt_thumb) {
                    $file_info = pathinfo($ytthumb_path . $yt_thumb);
                    $currentFilePath = $ytthumb_path . $yt_thumb;
                    $new_file_name = md5($file_info['filename']) . '.' . $file_info['extension'];
                    $newFilePath = $upload_path . 'edited/thumbnail/' . $new_file_name;
                    $fileMoved = rename($currentFilePath, $newFilePath);
                    $edited_videos['yt_thumb'] = 'uploads/videos/thumbnail/' . $new_file_name;
                }
                $edited_videos['video_id'] = $video_id;
                $this->db->insert('edited_video', $edited_videos);
            }
        }
    }

    public function export_videos()
    {
        $this->load->library('youtube');
        $channels = $this->youtube->getChannels();
        echo '<pre>';
        print_r($channels);
        exit;
    }

    public function import_categories()
    {
        set_time_limit(0);
        ini_set('memory_limit', '5012M');
        require_once APPPATH . "third_party/reader/XLSXReader.php";
        $xlsx = new XLSXReader('./bulk_files/Keywords.xlsx');
        $this->db->query('TRUNCATE TABLE `categories` ');
        $data = $xlsx->getSheetData('Sheet1');
        $array_index = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);
        $parent_categories = array();
        foreach ($data as $i => $row) {
            if ($i > 1) {
                if ($i == 2) {
                    foreach ($array_index as $col) {
                        if (!empty($row[$col])) {
                            $dbData['title'] = $row[$col];
                            $dbData['status'] = 1;
                            $dbData['parent_id'] = 0;
                            $dbData['slug'] = slug($dbData['title'], 'categories', 'slug');
                            $dbData['created_at'] = date('Y-m-d H:i:s');
                            $dbData['updated_at'] = date('Y-m-d H:i:s');
                            $dbData['created_by'] = 1;
                            $dbData['updated_by'] = 1;
                            $this->db->insert('categories', $dbData);
                            $parent_id = $this->db->insert_id();
                            $parent_categories[$col] = $parent_id;
                        }
                    }
                } else {
                    foreach ($array_index as $col) {
                        if (!empty($row[$col])) {
                            $dbData['title'] = $row[$col];
                            $dbData['status'] = 1;
                            $dbData['parent_id'] = $parent_categories[$col];
                            $dbData['slug'] = slug($dbData['title'], 'categories', 'slug');
                            $dbData['created_at'] = date('Y-m-d H:i:s');
                            $dbData['updated_at'] = date('Y-m-d H:i:s');
                            $dbData['created_by'] = 1;
                            $dbData['updated_by'] = 1;
                            $this->db->insert('categories', $dbData);
                            //$parent_id = $this->db->insert_id();
                            //$parent_categories[$col] = $parent_id;
                        }
                    }
                }
            }
        }
        echo '<pre>';
        print_r($data);
        exit;
    }

    public function test_reply()
    {

        $this->email_reply('usman.ali.sarwar.wg@gmail.com', 'Usman', 'viral@wooglobe.com', 'This test Reply Mail', '<CAGBt0ifNm91o0txHFzvCgsCgFWgvg9wLf1-XWOgwuW6DivZ8ig@mail.gmail.com>');
        exit;
    }

    public function test_email()
    {

        $this->email('usman.ali.sarwar.wg@gmail.com', 'Usman', 'viral@wooglobe.com', 'Usman Wooglobe', 'Test', 'test');
        exit;
    }

    public function mrss()
    {
        include_once('./app/third_party/getid3/getid3/getid3.php');
        $getID3 = new getID3;
        $query = 'SELECT v.*,vl.unique_key
            FROM videos v
            INNER JOIN video_leads vl
            ON v.lead_id = vl.id
            WHERE v.status = 1
             AND v.deleted = 0
             AND v.is_wooglobe_video = 1
             ORDER BY v.created_at DESC
             ';


        $videos = $this->db->query($query);

        $title = "WooGlobe Video Feed";

        $link = $this->data['root'];
        // This is a description of this iTunes Feed.
        $description  = "Description of the WooGlobe Video Feed";
        // This is the language you display for this podcast.
        $lang = "en-us";
        // This is the copyright information.
        $copyright = "Copyright 2018 WooGlobe";
        // This is the build date.
        $builddate = date(DATE_RFC2822);

        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                <rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/" xmlns:bc="' . $this->data['root'] . '" xmlns:dcterms="' . $this->data['root'] . 'terms">
                <channel>
                    <title>' . $title . '</title>
                    <link>' . $link . '</link>
                    <description><![CDATA[' . $description . ']]></description>
                    <language>' . $lang . '</language>
                    <copyright>' . $copyright . '</copyright>
                    <lastBuildDate>' . $builddate . '</lastBuildDate>';
        if ($videos->num_rows() > 0) {
            foreach ($videos->result() as $video) {
                $file_data = $getID3->analyze('./../' . $video->url);
                $xml .= '<item>
                            <title>' . $video->title . '</title>
                            <link>' . $this->data['root'] . '?video=' . $video->slug . '</link>
                            <description>' . $video->description . '</description>
                            <pubDate>' . date('d M, Y', strtotime($video->created_at)) . '</pubDate>
                            <enclosure url="https://www.youtube.com/watch?v=' . $video->youtube_id . '" type="video/mp4"></enclosure>
                            <media:content type="video/mp4" url="https://www.youtube.com/watch?v=' . $video->youtube_id . '">
                                <media:tags>' . $video->tags . '</media:tags>
                                <media:keywords>' . $video->tags . '</media:keywords>
                                <media:thumbnail>' . $video->thumbnail . '</media:thumbnail>
                                <media:credit role="producer" scheme="urn:ebu"><![CDATA[ WooGlobe ]]></media:credit>
                            </media:content>
                            </item>';
            }
        }
        /*<bc:videoid>'.$video->unique_key.'</bc:videoid>
                                <bc:duration>'.@$file_data['playtime_string'].'</bc:duration>*/
        $xml .= '</channel></rss>';
        echo $xml;
    }

    public function sr_template()
    {
        include('./vendor/signrequest/src/AtaneNL/SignRequest/Client.php');
        include('./vendor/signrequest/src/AtaneNL/SignRequest/CreateDocumentResponse.php');
        $client = new \AtaneNL\SignRequest\Client($this->config->config['sr_token']);


        $contractName = 'test';



        //$additinaolParams = array('from_email_name' => 'WooGlobe', 'redirect_url' => root_url('contract_signed/'.$leadData->slug),'subject'=>'Wooglobe Contract - '.$leadData->unique_key,'from_email'=>'sell@wooglobe.com');
        /*$prifills = array(
            '{"external_id":"contract_video_title", "text":"Test Video"}',
            '{"external_id":"contract_video_url", "text":"http://www.google.com"}',
            '{"external_id":"contract_revenue_share", "text":"50"}',
            '{"external_id":"contract_date", "text":"08/24/2018"}');*/
        $prifills = array();
        $title = array("external_id" => "contract_video_title", "text" => "Test Video 1");
        $url = array("external_id" => "contract_video_url", "text" => "http://www.google.com");
        $share = array("external_id" => "contract_revenue_share", "text" => "500");
        //$date = array("external_id"=>"contract_date", "text"=>date('m/d/Y'));
        $prifills[] = $title;
        $prifills[] = $url;
        $prifills[] = $share;
        //$prifills[] = $date;
        $prifills = json_encode($prifills);

        //echo json_encode(array(array('email' => 'usman.ali.sarwar.wg@gmail.com'),array('email' => 'sell@wooglobe.com')));exit;
        /*$cdr = $client->sendSignRequestFromTemplate('https://wooglobe.signrequest.com/api/v1/templates/67ade125-2177-4602-a724-a6f0f271d7df/',
            '5',$prifills,base_url(),'test_pdf_14.pdf');*/

        $prifills = array();
        $title = array("external_id" => "contract_video_title", "text" => "Test Video 1");
        $url = array("external_id" => "contract_video_url", "text" => "http://www.google.com");
        $share = array("external_id" => "contract_revenue_share", "text" => "500");
        //$date = array("external_id"=>"contract_date", "text"=>date('m/d/Y'));
        $prifills[] = $title;
        $prifills[] = $url;
        $prifills[] = $share;
        //$prifills[] = $date;
        //$prifills = '[{"external_id":"contract_video_title","text":"Test Video 1"},{"external_id":"contract_video_url","text":"http://www.google.com"},{"external_id":"contract_revenue_share","text":"50"}]';
        //echo $prifills;exit;
        $result = $client->sendSignRequestFromTemplate('67ade125-2177-4602-a724-a6f0f271d7df', 'sell@wooglobe.com', array(array('email' => 'usman.ali.sarwar.wg@gmail.com')), "WooGlobe Content Agreement", true, array('from_email_name' => 'WooGlobe', 'redirect_url' => base_url('contract_signed/asdasdasdas'), 'subject' => 'Wooglobe Contract - asdasdasdas', 'from_email' => 'sell@wooglobe.com', 'prefill_tags' => $prifills));
        echo '<pre>';
        print_r($result);
        $uuid = $result->uuid;
        $dbData['sr_uuid'] = $uuid;
        echo 1;
        exit;
    }

    public function backup_db()
    {

        ini_set('memory_limit', '-1');
        $this->load->library('gmail');

        $path = './../../../www/db_backups';

        $dailyDriveId = '1fJb06LYle4vVj1xPRrJbkxSRSXQyups4';

        $dir = $path;

        //$dir = './../../../lib/automysqlbackup';
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    //echo 'FileName : '.$file.'<br>';
                    //echo $dir.'/'.$file;
                    //echo file_exists($dir.'/'.$file);exit;
                    if (strlen($file) > 2) {
                        $this->gmail->uploadOnDrive($file, $dir . '/' . $file, 'application/gzip', $dailyDriveId);
                        unlink($dir . '/' . $file);
                    }
                }
                closedir($dh);
            }
        }


        $path = './../../../www/code_backups';
        $dailyDriveId = '1g0Ejgg1mhDUV9Cd9NWDLZFZMJ7RInTCu';

        $dir = $path;

        //$dir = './../../../lib/automysqlbackup';
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    //echo 'FileName : '.$file.'<br>';
                    //echo file_exists($dir.'/'.$file);exit;
                    if (strlen($file) > 2) {
                        $this->gmail->uploadOnDrive($file, $dir . '/' . $file, 'application/zip', $dailyDriveId);
                        unlink($dir . '/' . $file);
                    }
                }
                closedir($dh);
            }
        }
        exit;
    }

    public function video_categorie()
    {

        $result = $this->db->query('
            SELECT * 
            FROM videos
        ');

        if ($result->num_rows() > 0) {
            foreach ($result->result() as $row) {
                $categories_ids = explode(',', $row->category_id);
                foreach ($categories_ids as $category_id) {
                    $categories = $this->db->query('
                        SELECT *
                        FROM video_categories
                        WHERE video_id = ' . $row->id . '
                        AND category_id = ' . $category_id . '
                    ');

                    if ($categories->num_rows() == 0) {
                        $dbData['video_id'] = $row->id;
                        $dbData['category_id'] = $category_id;
                        $this->db->insert('video_categories', $dbData);
                    }
                }
            }
        }
    }

    public function gmail_api()
    {
        $this->load->library('gmail');
        $messages = $this->gmail->getRawMessages();

        foreach ($messages as $message) {


            $query = $this->db->query('
	            SELECT gc.id
	            FROM gmail_conversation gc
	            WHERE gc.uid = "' . $message->id . '"
	      
	        ');
            $i = 0;
            $j = 0;
            if ($query->num_rows() == 0) {
                $megs = $this->gmail->getMessageDetail($message->id);

                $this->db->insert('gmail_conversation', $megs);

                $i++;
            } else {
                $j++;
            }
        }
        echo $i . ' New emails found.<br>' . $j . ' Existing emails.';
        exit;
    }
    public function getVideos()
    {
        $this->load->library('youtube');
        $array_videos = array();
        $start = 0;
        for ($i = 1; $i <= 16; $i++) {

            $reusult = $this->db->query('SELECT video_id FROM video_ids GROUP BY video_id LIMIT ' . $start . ',50');
            //echo $reusult->num_rows().'<pre>';
            $ids = array();
            foreach ($reusult->result() as $row) {
                $ids[] = $row->video_id;
            }
            $ids = implode(',', $ids);

            $videos = $this->youtube->getVideos($ids);
            $array_videos = array_merge($array_videos, $videos);
            $start = ($start + 50);
        }
        $response['rows'] = $array_videos;
        $this->load->model('MyExcelModel', 'excel');
        $download_link = $this->excel->generate_excel_file_common($response, 'videos', TRUE);
        redirect($download_link);
    }

    public function getTilteList()
    {
        $videos = $this->db->query('
	            SELECT *
	            FROM bulk_upload
	    ');
        $array_videos = array();
        if ($videos->num_rows() > 0) {
            foreach ($videos->result_array() as $row) {
                if (!preg_match('/^[A-Z0-9 ]+$/i', trim($row['video_title']))) {
                    $row['filter'] = $row['video_title'];
                } else {
                    $row['filter'] = '';
                }
                $array_videos[] = $row;
            }
            $response['rows'] = $array_videos;
            $this->load->model('MyExcelModel', 'excel');
            $download_link = $this->excel->generate_excel_file_common($response, 'string', TRUE);
            redirect($download_link);
        }
    }

    public function yt_videos_id()
    {
        $ids = 'GsrW34OnSAI,V71IoExjjiE,vlGLcgC0LrM,_o5Ef5n9Au8,PJIGWS3HmzA,5ZLBBdoG2F0,RMSFHhy-wHk,kMEv6BgjIh0,SNVeeOcd4XE,sGlIsJTLN3Y,r3d3KXzYZcU,qF5xkpF6Bb4,daoz4hPEEKs,XUOnr9cc-wo,R9M_AEc79NI,Nx5fFPuPF08,IdQM5siYIyc,mdzTlRPYCPc,lww74j4he4k,Nv3XZU6DFS4,iWbhhUpZvRg,-9rASQp9B9c,wsoWjml5uxw,mXmzQM2uG28,By_og612XE0,W8zz6Inv7ww,E5Lh5b8Gu5o,vKa72bx0GAw,_PBQFlBccoA,R6qQbsIy050';
        $ids .= ',idOxoxapdXc,iPWWZQclEQY,ohjPzLLaPgo,d_087Wp17nk,HbY22_1vr6w,-eH2i9ZrUCI,dcGRg19GlcE,zJlvQBDimZQ,RufdMxpU-OI,4T6YVxGecjM,r2abKIngv5A,VWYGRnFqIiY,xfXLbB3I6s0,sdqlo1kw3FY,jZxXrXNwauU,IfI8fB-B3Hk,1AGOfAWjwfM,fdHokKVyo78,-DiugZIVVUM,IhalAXCclHc,tIVdOvH3VFI,CRoHjBb30Hw,1NpR9wenD6k,_dgqB5yWP0o,lRerhdIZlqw,f-PrlnCg8YI,iOFNWVtTses,Pd8M1UkE7z0,T6rZQTme57g,UGOrTg6UG60';
        $ids .= ',B-zmcHfhdR4,xqYMDDFicRc,W5oDx-J3w44,KSQakA57SsM,-0d6XWWUz64,s_B3ymSfVaE,jonwXfCK9Q4,f9CV0XBXPMw,4U4WD-PGLWY,-J_G_6MFKgc,TmEzbh1p6xk,-CNucm_hlPU,GF3Ap3IudAw,8pUFmXo_py4,pcHJJx1OQRY,bPicdi1ZAgo,xZ-IHL7GcDs,MgBAS5BSSFY,zAf920BCxxw,yEP1clt0d_c,duyeYNmEz24,1R6sRh4XwqE,V5tRCiblbhU,KhmKZimN-_k,o6PhI4qwMHg,-5e7EocbQV4,wfIMA9fwIBE,VlAHZijm24k,7yrnh4P8Tgs,S6rjbsSeeJo';
        $ids .= ',Y1tGREIszlA,oGoRjQFamIM,rU-US035jho,NSTkU9qfyI8,Merk_UFqI6U,unLXxIF9osA,1eETWqmjI7g,yDgbBigrCYU,E2dfDtVaqpM,D2ySFiKIXTM,8QuzXejSkYY,VmhMB9eRYuI,JAp9JiDDopw,EWRnvyV2ZTw,bu6iBvG_5VY,jBpYVQVEQgY,GLQiak0n81Y,mshURPWSNo0,pYvr1mlvipw,BkOTmFJkkEY,0j2qT_ySOnE,RuKb-GPDFxA,jdl3utKZUww,qkLOZ2mCiv4,7FReieI1F8g,o4jKhVEKNXg,BGnhPMQ37iw,D6q776DXunE,CxWqXCTaKqc,65PH5FfC3gA';
        $ids .= ',yXr1NzlzFzY,GtcKzx62qgE,5dS6img-mgU,UdNzYMDeD0c,jLYwjAtQcxU,6eVnu_yqhOo,rY3_f2LgSeU,Nz9YmGF7qmE,_Prb-06BAEI,V_GoLjEZT00,ur8K5UYP0dA,nc2DSMaB72Y,hwSkWRQoRDU,rDd2IMXInEs,gon3O0LMe0E,Ogz970Y-iRo,wdDfQUlpk_U,ZFoFdThNsvE,YisHECPurwg,XQlZJW8ON7k,4wVW2TnCX7o,PtlkXGsXeoM,5dttSBB5n2g,pp2tF9vr9gM,MZjjt13pn_0,1p0TuCYvBzM,NqQst2JOVnE,mEBsCirAH2s,mJbDM0tcEao,awk_R0JArA8';
        $ids .= ',AWOBuHCmWtQ,vHm8OJZmx6Y,RfWYxrTJNv8,5Ex00h-KBW0,ZBo1ZIoWzRM,vZMB44qq3x0,FJP5u2yM0aQ,tCT016TZ6NU,uxca0AFUAeE,v1nn5PqHTTE,ww3bCBoqLOo,WBQ5MIsWTsc,WyguKJ1ru6A,wZo3_C9Z1Gs,QI_41vSfUTs,Srcz7aFLSfk,x03RpKUh3Q0,Fzd_-957xR0,W5jxap32oJg,3OcBFghbiPw,0TW-iHa4bNc,uNUbJfZ5n-4,VmiqqxDMFqM,VYUNZbhaAPU,kntwzk1QTJk,G34qyTE6XBo,U2UvPhPBQs0,8FR-NgOUmE8,AZXH5soV_Qs,nggSOe4eNxc';
        $ids .= ',A3E1AReAfag,aBhUuq_r2v0,2wiI3siyxY0,JpKtcNLnbeM,U3xK5H5VEmQ,HBbMAhxedbw,Qc4DZN1uwD0,mudSnL6RqFw,bqaZSd6peag,pVlEkmHErek,rTtVcOx_Njg,y9lpzJLFmRw,il9B30wpWCU,nQP_SwL_bbw,CJaEc58frmo,pRuBkSLDmqQ,ZB9LFr4HlqQ,IX48xkzQfCg,eN9RH6v52jE,JySssF00dMc,5R0sKBYUqMQ,Qzpkc6Z_lWM,l7h29BVXFkI,xOIeBexGobY,gAt7JSAi1Bc,mqK3JcoWq-w,cwWKRU5hDJc,rSANZMGbFDE,A8ggnp-YN3s,Uv1A13lZyag';
        $ids .= ',FNfjJZmwTUo,gwwcosgR1EI,SpZxXCL4rM0,EhcDbIatqwI,MTVsn5lOYLU,7_pDcscpXmk,ZE_ly0kTqVU,GzWDIof9pao,osXQmIcZTFw,FLweyGBLzEk,yvAHqXoC-AM,mFYqMi13dVA,Vhy43mLwadY,h80eSLHR_F0,cg2qLRPU1Vk,R7MJV76h28o,lRIVR1a4lCo,qP6rXnGRUh0,22GdriVQu38,rp19j3JT17E,oiKwEqjuUJo,m4lIWdLGvIg,Lxw01vbiGes,KpH7ZvVaZTw,sP-hfIVYFoI,Stv1tJ2Gq2g,3KhqQGZa0T8,pM-ogOg5xdc,E26xHoOjIYo,tlphpHd6X90';
        $ids .= ',DEVCItlP8Gk,9NWucRd-hjQ,BVQ4zRa6uVk,lC5zoTv1zFU,_5jB-WEKPXU,BVYq6629AUo,vm5mcj1flMI,LDvBipMdjD8,24QJxnbkApw,lOMsVgPX8Z4,keLxlgU5F54,Eo-WKjDSuBc,p2vCfvvdVdY,zZ-nZy6jFsE,TZlOWGFhvPc,nwBtr6HZmR4,9jS6EL9D0IE,dCevH3pAns8,TMOuOfJS-Pw,9fbCZIAV1GI,fo3BCc40mEE,Bls48Unc0DY,3M0JTsesFcc,fBEfaDEF8zE,NR-FL3_vuSk,c8U_0Cnb8TI,Wx79dDDeGxc,0ESUwvPtXD0,hXa4oZ2vIQ0,lXKQHnlYgy8';
        $ids .= ',ojVrjQvnqkc,qFKd7rBpaFg,bxarNwWYgpk,l3eQX-zalAk,WOGmEqr7hBQ,QkwGGridbAk,1CLwVxFv4tU,3wV3UG1TWJE,TDKc5pOYctQ,Jg8Um3i9mxA,Z9IoAw_TJ64,sNfkVcXeIr0,GGPuKXZz4Mc,TKprA3bfIIQ,fZ4L7Y8rQWs,mjwczc3d0f8,cNJ3vOVW-NY,OOgY_hFmgpo,ejS5MK8pl1g,QhbE3V9akHY,zkcbBT9ZxQQ,StpLv8eGbBA,RZjSPlQ0om4,qMA2-Kjd3AA,1fvzz0cIbks,FZoF6BZZ2bU,BAwH_i0ITHg,lfWYFaE3N4o,wJoHJjKQnVQ,A3mhkvuL1f4';
        $ids .= ',CobOH053gp4,o_jyLAOcICo,Q2RmB2ItYnU,oOmO6Whb1pI,I1FeO4k_Trw,sxpel2mr12E,r9mCciFnWpE,CTgIoLKd-8Q,aU43xeZenu4,8UowB9-yVpI,qe43vpiNd08,PD_AMffXGTs,b5DBDiaTJuw,BwFeZysL23w,EgpfBwDEths,KfPwreYvK_g,yhdTzR6aT3I,6NCDEDJ2C38,A6UIuIwxIW0,7-IiAR0-lsQ,_27zvzzIisQ,Q3ErvD9dGlQ,WWoZVWl1_Ww,qKpkbSKo340,Gsiu-NEhhc0,khrn82pegRE,HYKKXRcw9lA,umGP3XOpSPU,SU3dsNZJSAY,dGtZyHwwj3o';
        $ids .= ',73884uRL0vM,JYbNrW0BTq0,SUs8C15C0SE,pNRMhnD7p60,zkRkZ8aYoo0,bTylvTZgidY,WroDLyULXjQ,qI18yGqN0oA,AtIYrt20Ki8,hqonFdsyqzs,FGZ-BVotSWs,NLkge76ro6c,0wOWtNdBMKI,IcNjdhqOFbw,ACKKJiiUzVk,x6Mv6hIqr1E,R6DkSGG4Vhk,zJbsxxnnRhU,vfA5LEKmmgg,91XrW1JsjCo,FBjD8emWBaQ,BsqW3y6A9d4,2cJjAVVd6pk,9EH1sSX2kAs,2pHyyP_jK60,FQnkrtTwAMA,U1LuDyBxppU,Y6cvkpa7rL0,VyBhaWaQiwU,ahzvR-3FlWc';
        $ids .= ',zD14BZ5v-KI,SPaoKxgZcpg,FVi6tXLyu0I,g41dB8s7JIw,XwffTh03iCg,FgW7ciwI2iY,6pT-Sj8s_B4,MvVwHSqmKQ8,ij17csxo0sc,hjay3Tl7kzc,mf58Neu7ays,jnfGj7eHo60,P3Ll0nZK1Ns,Xry3DFYT-3o,77kag3u4xEA,KJOU_s8N6Tw,f1cy__e3KB4,vKKIM6irWog,mZmnR-lQXCE,RNbUbEeG8pI,pxeei1XODng,W5hJsDrJg90,fkymE-qsCFA,_cqPmJP2_ao,Yp3-VLJCQT8,B4VJWYQMGYk,yZDuqgVjUIw,WeYtfqdR-tE,yk9kGj5YT_s,dCzQ1I23oA0';
        $ids .= ',WM21rZYwiOs,LfjY0UV-6FY,F00e2vBx7Gc,I0GXqR7FrzA,6WV5qSs8Rk8,esnybFgPqT8,z3FnhFvhVz0,5tSUL_ArYcg,Kv_kHgS3AmI,-mUidtzyRjs,TUYlwOk5AIc,xZb_5QkfsDk,rUpEeH6B-A0,hb2YSy4HKvo,tLn-jDlepTw,Cm9D4p0G3O0,Df1gxEyhfqY,wJmuXLYFkNY,QKjf1YbDUWk,E6BXCYgesh8,JwCyMwhFY-w,K7LjHjO65ac,dBBtqELwE80,9mHurxPkaDQ,kjc_3hCN7lI,Ikhh2MdaJ54,OH0FiI3y6vQ,G8zTqQ7jpGU,koa_DeavEic,IJNQ5eoEXzA';
        $ids .= ',BwCeFuJ6SP8,MI6PePi6lpg,escJ6B-e7fk,ssNGoFw7Wfs,3GmxHIrhuUs,IDSD4yTImG8,WBeKCrw9nuI,9kyRVzZCChs,M7xxp3qua78,HoCIEzN1RQY,ky8Y0YiHutw,fj42ZAK8FnE,1Lm11b5lf2o,EoY652fyaC8,F7GlixLSAuQ,5JQRxK6MrZA,knfSq2Dt344,ceJfO5k5J_o,Gs_K17JfOWY,LhMlpyIkOeY,FnOomyrayso,52hckcd0yoM,rDI19AhRyhU,owFuf_FT9Z4,OPVcW2qd-WA,MM5xT0AsCrM,z83OI67DMiY,Y9WmnXxeY3U,0vIwSnqC-w0,8L46tnwyW3w';
        $ids .= ',7XZ6luItC-U,aH5ecOEFp-Y,31MRMeCB6nY,lU4bvGG41gc,sEpm9Y3kI94,n0rrujS8xD8,0uNcx1a825M,bdlElJ9Gt3I,AJoTilY9-7U,fReNBf-oFKY,h3hKip0yaU0,8mykudRWPpw,5bFSzb2T7Uc,pxbndFFgzko,HHnCtYnYeAE,BpXozQTRclE,h-Ln6srLRLk,763kk-2AhFw,QXrYnOAKm6Y,1pU-9rM99wQ,diAxWFwjVQM,a_DIwg9OjAw,7eZ6y63Eap0,axcTNqjVCiE,c56CTx3Y5k8,WQ5upycIUPY,lhtbG6sP-gA,IXPKA3kfcLU,JD3CuFacmRA,h9IS6Wopp6s';
        $ids .= ',lNEWgpsk94M,6HFh63nGQvQ,R5a1aGL2JJ0,IvAlhN1mbTs,VuC4yu2kQY0,KOIJzg8CRzs,O1ZL9kKDo_4,zgsaRdMGcOE,qvLluFmN-VM,5YKA1V2yD48,5zl1W_Sw8U4,OFEBukG_KE0,2I1vrLtD9Qk,EslgxY3tPeA,HyOXnHIIw3c,o9qrVryNplc,0tmmMScFXtU,vjmBMD8d5mY,X2XUdeETfO0,_H9gHFgGQHs,yFIIxNu8La0,ZU32k3zaKCI,47rYIEn_li4,UhqhoMp-R1A,IObfJNT6kLM,X66SqR6_G64,KTqGSBOvtfA,FlmcBZnaK1Y,1NRNuHYYCVQ,4Ktq8XkIVfY';
        $ids .= ',hg_VW8ikDOQ,ndAqjn8xSwo,mOmxdL6LJ3g,6pMRu50SEuM,en1R13thKXg,IDHBk8mAvf8,JQ1TR3qBF58,iETVUsFfBgU,wCGapPv2j6k,Xez13IdwM0A,ZdoS6RPZBNA,bW1b0RvlM-Q,IjMCGoOMHkc,sDCJEEd6V7c,hGMJoxsPR-4,JkQjP_SFEDc,Hl-kf35QeyE,KrrqBt2sBTU,OJCXo7YojyU,bS6lqezlre4,1BDUiCVmRGE,VZIXwIHvHnU,wQ8mp808qPE,Nzsp0dCIfA8,V2Hr3IDV-rg,tmbkvnZuAU0,uTMQ6ldJ7c0,dMqIAIQ87Zk,uQXdgaAUVsc,uWHbbRkaHTE';
        $ids .= ',UOJTlQRjKLM,99bSZJDrKFg,QyzyhWSm96E,SZpwew5vfMc,_d9ZRHRHFzE,VGNSSZNVRtQ,omqEG09QkyI,mgtD8txjgEA,285HONldIbY,zvMd60gXG5Y,pddlmlgvAco,PjOmM4lNWuY,_TnYKsupOUI,TCB2NP9QLdk,E0Q80s0Tlts,mlgM0Poeg9o,JXqHXqgRCWk,t0lJtI1C5sQ,rZofT8Lgd-w,95hNNWzC8as,SemdtibZt4E,iup8mLZ1mN8,I0_X9JQbjjg,w2HOSBK2N8U,Y5t0dchpBh4,EJhYeCx2MpQ,TUesRs9tXnQ,msIZ-F_ociU,eN3EJa_Yzc8,q3EnlbJHWqU';
        $ids .= ',PqWTAqG_mvw,IoI49hNiJI8,Oxr5I0wx0tU,bNF9bEuJ0n0,v35ttobB1Ao,wzw0GFWLpJw,aInzW0yn124,5U6ElRUnao8,EjM5fHhipFo,YES9oc0r5rw,_GrkbqvZFLQ,FoExYnyacNg,7FpTW9085no,jmghW4oXhLs,ugw3hNiQG9g,SSgRt5vmJ7A,Wr6N4wZFo8g,GmNLHqT9fUk,FV7fVjBG2Wk,j2g4F09hCiI,GxxyOkkk_e8,FJJN2h3IopY,igl8oRGUf0w,tOHnxmBzHqM,87C53FRwaHE,c4exDe7eqHU,JuT0xUj5-uU,167TwDjbnCo,b8EZ5FTKC-c,tUjClXGUAsA';
        $ids .= ',Tqq3D2lgwO8,akVaTmXXglk,iMLcZC30ncw,uhD2wjNa0fY,A_b7yxYDDXo,6eUYiC6v5iY,dmCaIch4Clg,ugH26olz57w,gG_Ec1RjV0A,9_6VIB3tnjw,1ub84162Jp8,CVknsfZkGp0,vVf6nELFcRE,kfvDn81SIKo,Q7FLoRqzZ4s,X3e8LRwR-aE,pyznnO7mVqc,pFVTJszYOpo,2dkW2ZPz8Ig,HQCw-JGxXCE,Gw6RLbkd024,rNlk16VYG_4,mR_qRdv9N1s,gY-Qdx05-_Y,mgSoxh3UxzQ,ehQzBol91v0,soWL6byn6SU,KhpDD0pqhEo,wyQhw2fgyMk,7_dzgqjRlSo';
        $ids .= ',LEoXgn-2IcE,GLah2sQ3-sM,qEcPAR068wk,k4m1NLI6grY,pf2agNRVJbI,AwZW9HK2Ozs,QUtGEoKBMR0,5aOVVyeUHRI,mGGbd8zmRtg,Oz8M_sBIedg,UMrIZZHyfjs,DENflKHUUak,sALKVVvdLog,WZrElMYQVeE,XKUtGYlKYvs,PyZaCFLwVvw,lmX3mgx5Goc,Ih4QU1uXMaI,Q5kwKqob84U,fsabgmYRXII,x6CSYmClX78,GudtuNRV0iU,6bMFcQ1QspM,f0nIvMXQrO4,kvkWRe0NR0k,eVrKSxx0RE0,CYqU3FHlBsY,KJ2kYnUSnvY,ImbfydAhV1Y,AdH1H1umWJU';
        $ids .= ',Hv_3erk_Vmg,kc0V2NJSGjA,yNwmMIQ8-qM,65UjYy7FR28,hQ6NsQhT6ck,T9a4rn69_bE,fH9Q68ZBewA,PREavn2IMLc,vHX61do0euQ,Sujqc0XtVZ0,Eb77Zuc-5xs,gVAV09wKK4U,7pjWFxINceI,SbLUvGvh9II,MfaT-R8gb0U,fFo8AM4QqnU,kKCNoC2xt6g,Lc7lEHKclp8,PDMo5lXwtgM,5RlKHLLjIT4,xP_ydY_TMhw,7fKfxpE1BQ0,PqS0vr-Ro80,x_eAMI2trMI,HnTClo1hlto,p2Y0mxZ7Pso,nPNAEpiF7Ok,FdjSee4UHPc,-kESNfAskq8,aJ4275oBqig';
        $ids .= ',MFSykgDXdAk,7qv67zKsagI,TE6y0iXXL8o,Kld8qB_ZG0g,cOu-2JD9sNU,Wj0LKeuhZdg,uT0aWUXoszE,eP52iTmX38Y,4O5l8O935LQ,L5uTU-ZpPvQ,v_zlZAIqqqc,OJLoklW_ATA,8nghSW1I5y4,0WUVuaxXHIA,4PwGORq89gY,Qar5yZuSNgE,W70AFczI9Zw,6nHKiGIkevA,ikGwbm4jYdI,78owg_Rapgs,DU_OZgAhIXQ,esWPWJ4w6XQ,7eEe0xPc02Y,Iyfmw0QUVSc,2yB5jLZjnJc,vgHKLtdn_is,JdJnwwJ_wAc,-Zd5BbJtVRw,e43Ui3KTVJU,1vaPvE79UO4';
        $ids .= ',r5hH2ZotIZs,tZHCKRp__qw,krtWhPqHwyQ,w0-GsQ2Vikk,1XxKPHmwczA,C7bihD33D1I,OwU9vRwpLbY,RyYxufHdUZE,4dFjJg_iQJA,r75ibxFYZBM,TKKEAVoAWlI,S2ViQahaEkU,4CnwAp7jUpE,EcwZO1s0Dyk,CkiWVG7X_wg,r75bTK2E3lQ,0_hlZBowaVw,LnayqepcKUg,JphoPhCQ0yU,7M9AKlkZP3A,QnDOKK39K3M,I47Q_VVPZSA,mB51-3TWX6I,s575kstb2Qc,H6pkx1HR2BI,PF5I57ySe1Y,KVE8HbiMryY,ZU_Gv7BzEjc,Eje4zfDZe8c,aUwg7Yi8j_I';
        $ids .= ',yMKgaOUjD38,f1lWvdViZTg,Bib_PGzQYJ4,3k9mEln7G68,U_K0tTqu4A0,wA_4FN6DDS0,NVt28F--yxY,ieA84CBCW7k,4BgUBk_vqBA,lq7c5LBMP1U,wAUCEzcvcnA,aizdHUNAxGM,WYNiJn_3U5U,a-vjb7tNGlQ,UDaapKPWtOU,GyohLXnKGlE,2CrkL8IWyGo,C5jm3rJ0H4g,Er5KS_mTujg,1S0HD3RnEfc,UHiM-2fW47U,1QWy2geoCmw,vb9cmdYZB5Y,Aeylad_bkXE,54idYUaEuKk,p5GOSSBfSCI,Jzc2qgnArSc,IcVTDqO8row,QObnqd2fmP0,qBrOEwJ42AE';
        $ids .= ',rUIuJcArjuE,wjrdDZBp4gA,sXgronfBar8,4yzK7xyp3ec,adNfnASGXjA,kiGrrXMRu3U,QmmeTONYCeQ,DgH1ZEOhDsc,ojmed-Ozkj4,9IYMDf-CQ60,W1Gg2XW_M9c,GMrcmkjkH44,8cOsg87QyHY,LevoDccdSms,UlEUnprQr-Y,yRNd6lUdyoY,dpFAkkg6bU4,VmjLaxu7LtA,rWvnZG2z7AU,so7tvuA63DY,bzAq-mMdkag,eZfvOHJmSXY';

        $ids = explode(',', $ids);
        foreach ($ids as $id) {
            $data['video_id'] = $id;
            $data['data_get'] = 0;
            $this->db->insert('video_ids', $data);
        }
        echo '<pre>';
        print_r($ids);
        exit;
    }

    public function awsMediaJobsStatus()
    {
        $query = "SELECT * FROM `watermark_videos` WHERE aws_job_id != '' AND unique_key NOT IN('WGA932075','WGA370621')";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {

            $id = $row->aws_job_id;
            $name = explode('/', $row->local_url);
            $name = end($name);
            $orignalNameArray = explode('.', $name);
            $orignalName = $orignalNameArray[0] . '.mp4';
            $name_new = str_replace('.', "_$row->unique_key.", $orignalName);
            $url = str_replace('raw_videos', 'watermark_video', $row->local_url);
            $url = str_replace($name, $name_new, $url);

            $job = $this->awsMediaConvertGetJob2($url);
            if ($job) {
                $jobData['aws_job_id'] = NULL;
                $jobData['s3_url'] = $job;
                $this->db->where('id', $row->id);
                $this->db->update('watermark_videos', $jobData);
            } else {
                // $uid = explode('/', $row->local_url);
                // //$assignFiles[] = $uid[count($uid) - 1];
                // $uid = $uid[1];

                // $jobId = $this->awsMediaConvert('s3://wooglobe/'.$row->local_url,'./../'.$row->local_url,$uid);
                // // $jobData['video_id'] = $row->video_id; // FIX THIS
                // $jobData['aws_job_id'] = $jobId;
                // // $jobData['local_url'] = $flist['file_key'];
                // // $jobData['unique_key'] = $uid;
                // $this->db->where('id', $row->id);
                // $this->db->update('watermark_videos', $jobData);

            }
        }

        $query = "SELECT * FROM `social_videos` WHERE aws_job_id != '' AND unique_key NOT IN('WGA932075','WGA370621')";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {

            $id = $row->aws_job_id;
            $name = explode('/', $row->local_url);
            $name = end($name);
            $orignalNameArray = explode('.', $name);
            $orignalName = $orignalNameArray[0] . '.mp4';
            $name_new = str_replace('.', "_$row->unique_key.", $orignalName);
            $url = str_replace('raw_videos', 'social_video', $row->local_url);
            $url = str_replace($name, $name_new, $url);

            $job = $this->awsMediaConvertGetJob2($url);
            if ($job) {
                $jobData['aws_job_id'] = NULL;
                $jobData['s3_url'] = $job;
                $this->db->where('id', $row->id);
                $this->db->update('social_videos', $jobData);
            }
        }
    }

    public function tests3()
    {
        $source_file = $_SERVER['DOCUMENT_ROOT'] . '/' . 'uploads/WGA492807/documents/WGA492807_signed.pdf';
        $file_extension = explode('.', $source_file);
        $file_extension = $file_extension[1];
        $target_file_key = 'uploads/WGA492807/documents/WGA492807_signed.pdf';
        //$url = $this->s3_change_permission_file($target_file_key,'private');
        //$url = $this->get_file_s3_pri($target_file_key);
        $url = $this->upload_file_s3_pri($source_file, $target_file_key, $file_extension, FALSE);
        echo '<pre>';
        print_r($url);
        exit;
    }
    public function report()
    {
        $allVideos = $this->db->query('
            SELECT v.id,vl.unique_key FROM videos v LEFT JOIN video_leads vl ON vl.id = v.lead_id
        ');
        $raw_videos_array = array();
        $s3_videos_array = array();
        $documents = array();
        $s3_documents = array();
        $portal_videos = array();
        $s3_portal_videos = array();
        $portal_thumnails = array();
        $s3_portal_thumnails = array();
        $yt_videos = array();
        $s3_yt_videos = array();
        $yt_thumnails = array();
        $s3_yt_thumnails = array();
        $fb_thumnails = array();
        $s3_fb_thumnails = array();
        $fb_videos = array();
        $s3_fb_videos = array();
        $str = 'https://wooglobe.s3.us-west-2.amazonaws.com/';
        $root = $_SERVER['DOCUMENT_ROOT'];
        foreach ($allVideos->result() as $video) {
            $raw_videos = $this->db->query('
                SELECT * FROM raw_video WHERE video_id = ' . $video->id . '
            ');

            $edited_videos = $this->db->query('
                SELECT * FROM edited_video WHERE video_id = ' . $video->id . '
            ');
            $i = 0;
            foreach ($raw_videos->result() as $raw_video) {
                /*if(!empty($raw_video->url) && strlen(str_replace($str,'',$raw_video->url)) > 0){
                    if(file_exists($root.str_replace($str,'',$raw_video->url))){
                        if(!$this->get_file_info(str_replace($str,'',$raw_video->url))){
                            if(!in_array($video->unique_key,$raw_videos_array)){
                                $raw_videos_array[] = $video->unique_key;
                            }
                        }
                        if(!in_array($video->unique_key,$raw_videos_array)){
                            $raw_videos_array[] = $video->unique_key;
                        }
                    }*/

                /*if($this->get_file_info(str_replace($str,'',$raw_video->url))){
                        if(!in_array($video->unique_key,$raw_videos_array)){
                            $s3_raw_videos_array[] = $video->unique_key;
                        }
                    }

                }*/

                if (!empty($raw_video->s3_document_url) && strlen(str_replace($str, '', $raw_video->s3_document_url)) > 0) {
                    $this->s3_change_permission_file_2(str_replace($str, '', $raw_video->s3_document_url), 'private');
                    $i++;
                    /*if(file_exists($root.str_replace($str,'',$raw_video->s3_document_url))){
                        if(!$this->get_file_info(str_replace($str,'',$raw_video->s3_document_url))) {
                            if (!in_array($video->unique_key, $documents)) {
                                $documents[] = $video->unique_key;
                            }
                        }
                    }*/
                }
            }

            /*foreach ($edited_videos->result() as $edited_video){
                if(!empty($edited_video->portal_url) && strlen(str_replace($str,'',$edited_video->portal_url)) > 0){
                    if(file_exists($root.str_replace($str,'',$edited_video->portal_url))){
                        if(!$this->get_file_info(str_replace($str,'',$edited_video->portal_url))) {
                            if (!in_array($video->unique_key, $portal_videos)) {
                                $portal_videos[] = $video->unique_key;
                            }
                        }
                    }
                }*/

            /*if(!empty($edited_video->portal_thumb) && strlen(str_replace($str,'',$edited_video->portal_thumb)) > 0){
                    if(file_exists($root.str_replace($str,'',$edited_video->portal_thumb))){
                        if(!$this->get_file_info(str_replace($str,'',$edited_video->portal_thumb))) {
                            if (!in_array($video->unique_key, $portal_thumnails)) {
                                $portal_thumnails[] = $video->unique_key;
                            }
                        }
                    }
                }*/

            /*if(!empty($edited_video->fb_url) && strlen(str_replace($str,'',$edited_video->fb_url)) > 0){
                    if(file_exists($root.str_replace($str,'',$edited_video->fb_url))){
                        if(!$this->get_file_info(str_replace($str,'',$edited_video->fb_url))) {
                            if (!in_array($video->unique_key, $fb_videos)) {
                                $fb_videos[] = $video->unique_key;
                            }
                        }
                    }
                }*/

            /*if(!empty($edited_video->fb_thumb) && strlen(str_replace($str,'',$edited_video->fb_thumb)) > 0){
                    if(file_exists($root.str_replace($str,'',$edited_video->fb_thumb))){
                        if(!$this->get_file_info(str_replace($str,'',$edited_video->fb_thumb))) {
                            if (!in_array($video->unique_key, $fb_thumnails)) {
                                $fb_thumnails[] = $video->unique_key;
                            }
                        }
                    }
                }*/

            /*if(!empty($edited_video->yt_url) && strlen(str_replace($str,'',$edited_video->yt_url)) > 0){
                    if(file_exists($root.str_replace($str,'',$edited_video->yt_url))){
                        if(!$this->get_file_info(str_replace($str,'',$edited_video->yt_url))) {
                            if (!in_array($video->unique_key, $yt_videos)) {
                                $yt_videos[] = $video->unique_key;
                            }
                        }

                    }
                }*/

            /*if(!empty($edited_video->yt_thumb) && strlen(str_replace($str,'',$edited_video->yt_thumb)) > 0){
                    if(file_exists($root.str_replace($str,'',$edited_video->yt_thumb))){
                        if(!$this->get_file_info(str_replace($str,'',$edited_video->yt_thumb))) {
                            if (!in_array($video->unique_key, $yt_thumnails)) {
                                $yt_thumnails[] = $video->unique_key;
                            }
                        }

                    }
                }*/
            //}
        }
        echo 'Raw files  : ' . count($allVideos->result()) . '</br>';
        echo 'Raw files  : ' . $i . '</br>';
        /* echo 'Raw files  : '.count($raw_videos_array).'</br>';
        echo 'Documents  : '.count($documents).'</br>';
        echo 'Edited MRSS : '.count($portal_videos).'</br>';
        echo 'Edited MRSS Thumb : '.count($portal_thumnails).'</br>';
        echo 'Edited Youtube Video  : '.count($yt_videos).'</br>';
        echo 'Edited Youtube Thumb : '.count($yt_thumnails).'</br>';
        echo 'Edited Facebook Video  : '.count($fb_videos).'</br>';
        echo 'Edited Facebook Thumb  : '.count($fb_thumnails).'</br>';*/

        /*echo '<pre>';
        print_r($fb_thumnails);
        echo '</pre>';
        echo '<pre>';
        print_r($yt_videos);
        echo '</pre>';
        echo '<pre>';
        print_r($yt_thumnails);
        echo '</pre>';*/
        exit;
    }

    public function deleteLeads()
    {
        $query = "SELECT * FROM `video_leads` WHERE `email` IN('savanah.david20@gmail.com','waleed4hassan@gmail.com','hannah.ethan20@gmail.com','fahad2023@gmail.com','fahad@woolgobe.com','wooglobe@gmail.com','fahad.munawar@tekfocal.com','tekfocal@gmail.com','sheikh.fazalkareem@gmail.com','ali03224520136@gmail.com','xyz@gmail.com','abc@gmail.com','umar@wooglobe.com','umar.wooglobe@gmail.com','sherazsunny681@gmail.com','sheraz.wooglobe@gmail.com','rizwan@wooglobe.com','developers136@gmail.com','usman.ali.sarwar.wg@gmail.com','usman.janjapan@gmail.com','mmmobitech@gmail.com','maliks_usman786@yahoo.com','mahmood43514@gmail.com','bilal.zahid@powersoft19.com','farooq.akbar@powersoft19.com','abdulr@powersoft19.com','abdulrr@powersoft19.com','ali.ahma@powersoft19.com','farooq12@powersoft19.com','muhammad.waqas1@powersoft19.com','khurram.ansar@powersoft19.com','ahsan.riaz@powersoft19.com','ibrar.saeed@powersoft19.com','muhammad.waqas@powersoft19.com','farooq.akbar@powersoft19.com','moin.akhtar@powersoft19.com','moin.akhtar@powersoft19.com','ali.ahmad@powersoft19.com')";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            @rmdir("./../uploads/$row->unique_key");
            $this->delete_file("uploads/$row->unique_key");
            $this->db->where('lead_id', $row->id);
            $this->db->delete('videos');
            $this->db->where('id', $row->client_id);
            $this->db->delete('users');
            $this->db->where('id', $row->id);
            $this->db->delete('video_leads');
        }
    }

    public function sheet_test()
    {
        $this->load->library('sheet');
        $this->sheet->addVideosToSheet();
    }

    public function sheet()
    {
        define('SCOPES', implode(
            ' ',
            array(
                Google_Service_Sheets::SPREADSHEETS,
                Google_Service_Sheets::SPREADSHEETS_READONLY,
                Google_Service_Sheets::DRIVE_READONLY,
                Google_Service_Sheets::DRIVE_FILE,
                Google_Service_Sheets::DRIVE,

            )
        ));

        date_default_timezone_set('America/New_York');
        $client = new Google_Client();
        $client->setApplicationName(SHEET_APPLICATION_NAME);
        $client->setScopes(SCOPES);
        $client->setAuthConfig(SHEET_CLIENT_SECRET_PATH);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        // Load previously authorized credentials from a file.
        $credentialsPath = SHEET_CREDENTIALS_PATH;
        $authUrl = base_url();
        if (file_exists($credentialsPath)) {
            redirect('cb_sheet?code=true');
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
        }

        redirect($authUrl);
    }


    public function cb_sheet()
    {

        define('SCOPES', implode(
            ' ',
            array(
                Google_Service_Sheets::SPREADSHEETS,
                Google_Service_Sheets::SPREADSHEETS_READONLY,
                Google_Service_Sheets::DRIVE_READONLY,
                Google_Service_Sheets::DRIVE_FILE,
                Google_Service_Sheets::DRIVE,

            )
        ));
        date_default_timezone_set('America/New_York');
        $client = new Google_Client();
        $client->setApplicationName(SHEET_APPLICATION_NAME);
        $client->setScopes(SCOPES);
        $client->setAuthConfig(SHEET_CLIENT_SECRET_PATH);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('consent');
        $authCode = trim($this->input->get('code'));
        print_r($authCode);
        if (empty($authCode)) {
            redirect('gmail');
        }


        $credentialsPath = SHEET_CREDENTIALS_PATH;
        if (file_exists($credentialsPath)) {
            print_r($credentialsPath);
            $accessToken = json_decode(file_get_contents($credentialsPath), true);
            print_r($accessToken);
        } else {
            // Request authorization from the user.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            // $accessToken = $client->fetchAccessTokenWithRefreshToken($accessToken['refresh_token']);
            // mkdir(dirname('./app/config/gmail-auth-code.json'), 0700, true);
            //file_put_contents('./app/config/gmail-auth-code.json', json_encode($authCode));
            if (!file_exists(dirname($credentialsPath))) {
                mkdir(dirname($credentialsPath), 0700, true);
            }
            file_put_contents($credentialsPath, json_encode($accessToken));
        }

        redirect('Dashboard');
    }

    public function compilationsVideoRights()
    {
        $leadsQuery = $this->db->query('
            SELECT id,social_video_id
            FROM video_leads
            WHERE status in(6,7,8)
            AND deleted = 0
            AND compilation = 0
        ');

        foreach ($leadsQuery->result() as $row) {
            if (!empty($row->social_video_id)) {
                $infoQuery = $this->db->query('
                SELECT *
                FROM compilation_leads_info
                WHERE tt_id = "' . $row->social_video_id . '"
            ');
                if ($infoQuery->num_rows() > 0) {

                    $info = $infoQuery->row();
                    $this->db->where('id', $row->id);
                    $this->db->update('video_leads', array('compilation' => 1, 'ytc_id' => $info->lead_id, 'ytc_group_id' => $info->lead_group_id, 'cli_id' => $info->id));
                    $this->db->where('id', $info->id);
                    $this->db->update('compilation_leads_info', array('status' => 'Acquired'));
                }
            }
        }
    }

    public function paypal()
    {
        $this->load->library('PayPalClient');
        $this->paypalclient->payout();
    }

    public function paypal_webhook()
    {
        $this->load->model('Payment_Model', 'payment');
        $postData = json_decode(file_get_contents('php://input'), true);
        //$this->db->insert('paypal_response',array('response'=>json_encode($postData)));
        /*ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $query = $this->db->query('SELECT * FROM paypal_response WHERE response IS NOT NULL');
        foreach ($query->result() as $row){
            $postData = json_decode($row->response,true);

            if(isset($postData['event_type']) && $postData['event_type'] == 'PAYMENT.PAYOUTSBATCH.SUCCESS'){
                if (isset($postData['resource']['batch_header']['payout_batch_id'])) {
                    $invoice = $this->db->query("
                SELECT * 
                FROM invoice
                WHERE paypal_id = '".$postData['resource']['batch_header']['payout_batch_id']."'
                AND status = 0
            ")->row();
                    if(!empty($invoice)){
                        $this->db->where('id',$invoice->id);
                        $this->db->update('invoice',array('status'=>1));
                        $result = $this->payment->getAllPaymentsByEarningsIdsHistory($invoice->earning_ids, 'e.client_net_earning payment,c.symbol,u.full_name,u.email,u.paypal_email,u.address,u.id user_id,e.id,e.video_id,v.lead_id,vl.unique_key,u.state_id,u.zip_code,u.city_id,c.code,u.country_id');
                        $inv_id = $invoice->invoice_id;

                        $name = '';
                        $address = '';
                        $address2 = '';
                        $country = '';
                        $email = '';
                        $paypal_email = '';
                        $amount = 0;
                        $currency = '';
                        $currency_code = '';
                        $method = 'PayPal';
                        $methodEmail = 'PayPal';
                        $data_log_query = '';
                        if ($result->num_rows() > 0) {

                            foreach ($result->result() as $earning) {

                                $name = $earning->full_name;
                                $address = $earning->address;
                                $address2 = $earning->city_id . ', ' . $earning->state_id . ', ' . $earning->zip_code;
                                $country = $earning->country_id;
                                $email = $earning->email;
                                $paypal_email = $earning->paypal_email;
                                $amount = ($amount + $earning->payment);
                                $currency = $earning->symbol;
                                $currency_code = $earning->code;
                                $methodEmail = $earning->paypal_email;

                                $data_log_query = $this->db->query('SELECT user_browser,user_ip_address,contract_signed_datetime,contract_view_datetime FROM `contract_logs` WHERE lead_id="' . $earning->lead_id . '"');

                                $dbData['paid'] = 1;
                                $this->db->where('id', $earning->id);
                                $this->db->update('earnings',$dbData);
                                $insertData['video_id'] = $earning->video_id;
                                $insertData['lead_id'] = $earning->lead_id;
                                $insertData['user_id'] = $earning->user_id;
                                $insertData['created_at'] = date('Y-m-d H:i:s');
                                $insertData['inv_id'] = $invoice->invoice_id;
                                $insertData['log_message'] = 'Payout Successfully!';
                                $insertData['created_by'] = 1;
                                $this->db->insert('payment_log',$insertData);
                            }


                            if (!empty($data_log_query)) {
                                $logs_query = $data_log_query->row();
                                $access_key = '1d092358b81f0bfd0755bb3b19ac3bbf';

// Initialize CURL:
                                $ch = curl_init('http://api.ipstack.com/' . $logs_query->user_ip_address . '?access_key=' . $access_key . '');
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Store the data:
                                $json = curl_exec($ch);
                                curl_close($ch);

// Decode JSON response:
                                $api_result = json_decode($json, true);
                                if (isset($api_result['country_name'])) {
                                    $country = $api_result['country_name'];
                                }
                            }

                            $pdfData['transaction_id'] = $postData['resource']['batch_header']['payout_batch_id'];
                            $pdfData['date'] = date('Y-m-d');
                            $pdfData['name'] = $name;
                            $pdfData['address'] = $address;
                            $pdfData['address2'] = $address2;
                            $pdfData['amount'] = $amount;
                            $pdfData['country'] = $country;

                            $pdfData['email'] = $email;
                            $pdfData['currency'] = $currency;
                            $pdfData['method'] = $method;
                            $pdfData['methodEmail'] = $methodEmail;
                            $pdfData['result'] = $result;
                            $pdfData['inv_id'] = $invoice->invoice_id;
                            $html = $this->load->view('payments/pdf/invoice', $pdfData, true);
                            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                            $certificate = 'file://' . root_path() . '/admin/app/libraries/TCPDF-master/examples/data/cert/signature.crt';
                            $info = array(
                                'Name' => 'WooGlobe Ltd',
                                'Location' => '16 Weir Road<br>Bexley, DA5 1BJ<br>United Kingdom<br>E: Accounts@WooGlobe.com',
                                'Reason' => 'WooGlobe Contract Signing',
                                'ContactInfo' => 'https://wooglobe.com/',
                            );
                            $pdf->setSignature($certificate, $certificate, 'tcpdfdemo', '', 2, $info);
                            $pdf->setSignature($certificate, $certificate, 'tcpdfdemo', '', 2, $info);

// set document information
                            $pdf->SetCreator(PDF_CREATOR);
                            $pdf->SetAuthor('WooGlobe');
                            $pdf->SetTitle('WooGlobe');
                            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                            $pdf->SetPrintHeader(false);
                            $pdf->SetPrintFooter(false);
// add a page
                            $pdf->AddPage();
                            $pdf->writeHTML($html);
// reset pointer to the last page
                            $pdf->lastPage();
//Close and output PDF document
                            $output_file = root_path() . '/finance/remittance_advice/' . $invoice->invoice_id . '-invoice.pdf';
                            if (!file_exists(root_path() . '/finance/remittance_advice')) {
                                mkdir(root_path() . '/finance/remittance_advice', 0777, true);
                                $output_file = root_path() . '/finance/remittance_advice/' . $invoice->invoice_id . '-invoice.pdf';
                            }

                            $pdf->Output($output_file, 'F');
                            $pdf_link = '/finance/remittance_advice/' . $invoice->invoice_id . '-invoice.pdf';
                            $source_file = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . $pdf_link;
                            $file_extension = explode('.', $source_file);
                            $file_extension = $file_extension[1];
                            $target_file_key = 'finance/remittance_advice/' . $invoice->invoice_id . '-invoice.pdf';
                            $url = $this->upload_file_s3_new('private', $source_file, $target_file_key, $file_extension, FALSE);
                            $response['s3_url'] = $url;
                        } else {
                            echo 'Invalid response from paypal <br>';
                            //exit;
                        }
                    }else {
                        echo 'Invalid response from paypal <br>';
                        //exit;
                    }

                }else {
                    echo 'Invalid response from paypal <br>';
                    //exit;
                }
            }else {
                echo 'Invalid response from paypal <br>';
                //exit;
            }

        }*/

        if (isset($postData['event_type']) && $postData['event_type'] == 'PAYMENT.PAYOUTSBATCH.SUCCESS') {
            if (isset($postData['resource']['batch_header']['payout_batch_id'])) {
                $invoice = $this->db->query("
                SELECT * 
                FROM invoice
                WHERE paypal_id = '" . $postData['resource']['batch_header']['payout_batch_id'] . "'
                AND status = 0
            ")->row();
                if (!empty($invoice)) {
                    $this->db->where('id', $invoice->id);
                    $this->db->update('invoice', array('status' => 1));
                    $result = $this->payment->getAllPaymentsByEarningsIdsHistory($invoice->earning_ids, 'e.client_net_earning payment,c.symbol,u.full_name,u.email,u.paypal_email,u.address,u.id user_id,e.id,e.video_id,v.lead_id,vl.unique_key,u.state_id,u.zip_code,u.city_id,c.code,u.country_id');
                    $inv_id = $invoice->invoice_id;

                    $name = '';
                    $address = '';
                    $address2 = '';
                    $country = '';
                    $email = '';
                    $paypal_email = '';
                    $amount = 0;
                    $currency = '';
                    $currency_code = '';
                    $method = 'PayPal';
                    $methodEmail = 'PayPal';
                    $data_log_query = '';
                    if ($result->num_rows() > 0) {

                        foreach ($result->result() as $earning) {

                            $name = $earning->full_name;
                            $address = $earning->address;
                            $address2 = $earning->city_id . ', ' . $earning->state_id . ', ' . $earning->zip_code;
                            $country = $earning->country_id;
                            $email = $earning->email;
                            $paypal_email = $earning->paypal_email;
                            $amount = ($amount + $earning->payment);
                            $currency = $earning->symbol;
                            $currency_code = $earning->code;
                            $methodEmail = $earning->paypal_email;

                            $data_log_query = $this->db->query('SELECT user_browser,user_ip_address,contract_signed_datetime,contract_view_datetime FROM `contract_logs` WHERE lead_id="' . $earning->lead_id . '"');

                            $dbData['paid'] = 1;
                            $this->db->where('id', $earning->id);
                            $this->db->update('earnings', $dbData);
                            $insertData['video_id'] = $earning->video_id;
                            $insertData['lead_id'] = $earning->lead_id;
                            $insertData['user_id'] = $earning->user_id;
                            $insertData['created_at'] = date('Y-m-d H:i:s');
                            $insertData['inv_id'] = $invoice->invoice_id;
                            $insertData['log_message'] = 'Payout Successfully!';
                            $insertData['created_by'] = 1;
                            $this->db->insert('payment_log', $insertData);
                        }


                        if (!empty($data_log_query)) {
                            $logs_query = $data_log_query->row();
                            $access_key = '1d092358b81f0bfd0755bb3b19ac3bbf';

                            // Initialize CURL:
                            $ch = curl_init('http://api.ipstack.com/' . $logs_query->user_ip_address . '?access_key=' . $access_key . '');
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                            // Store the data:
                            $json = curl_exec($ch);
                            curl_close($ch);

                            // Decode JSON response:
                            $api_result = json_decode($json, true);
                            if (isset($api_result['country_name'])) {
                                $country = $api_result['country_name'];
                            }
                        }

                        $pdfData['transaction_id'] = $postData['resource']['batch_header']['payout_batch_id'];
                        $pdfData['date'] = date('Y-m-d');
                        $pdfData['name'] = $name;
                        $pdfData['address'] = $address;
                        $pdfData['address2'] = $address2;
                        $pdfData['amount'] = $amount;
                        $pdfData['country'] = $country;

                        $pdfData['email'] = $email;
                        $pdfData['currency'] = $currency;
                        $pdfData['method'] = $method;
                        $pdfData['methodEmail'] = $methodEmail;
                        $pdfData['result'] = $result;
                        $pdfData['inv_id'] = $invoice->invoice_id;
                        $html = $this->load->view('payments/pdf/invoice', $pdfData, true);
                        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                        $certificate = 'file://' . root_path() . '/admin/app/libraries/TCPDF-master/examples/data/cert/signature.crt';
                        $info = array(
                            'Name' => 'WooGlobe Ltd',
                            'Location' => '16 Weir Road<br>Bexley, DA5 1BJ<br>United Kingdom<br>E: Accounts@WooGlobe.com',
                            'Reason' => 'WooGlobe Contract Signing',
                            'ContactInfo' => 'https://wooglobe.com/',
                        );
                        $pdf->setSignature($certificate, $certificate, 'tcpdfdemo', '', 2, $info);
                        $pdf->setSignature($certificate, $certificate, 'tcpdfdemo', '', 2, $info);

                        // set document information
                        $pdf->SetCreator(PDF_CREATOR);
                        $pdf->SetAuthor('WooGlobe');
                        $pdf->SetTitle('WooGlobe');
                        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                        $pdf->SetPrintHeader(false);
                        $pdf->SetPrintFooter(false);
                        // add a page
                        $pdf->AddPage();
                        $pdf->writeHTML($html);
                        // reset pointer to the last page
                        $pdf->lastPage();
                        //Close and output PDF document
                        $output_file = root_path() . '/finance/remittance_advice/' . $invoice->invoice_id . '-invoice.pdf';
                        if (!file_exists(root_path() . '/finance/remittance_advice')) {
                            mkdir(root_path() . '/finance/remittance_advice', 0777, true);
                            $output_file = root_path() . '/finance/remittance_advice/' . $invoice->invoice_id . '-invoice.pdf';
                        }

                        $pdf->Output($output_file, 'F');
                        $pdf_link = '/finance/remittance_advice/' . $invoice->invoice_id . '-invoice.pdf';
                        $source_file = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . $pdf_link;
                        $file_extension = explode('.', $source_file);
                        $file_extension = $file_extension[1];
                        $target_file_key = 'finance/remittance_advice/' . $invoice->invoice_id . '-invoice.pdf';
                        $url = $this->upload_file_s3_new('private', $source_file, $target_file_key, $file_extension, FALSE);
                        $response['s3_url'] = $url;
                    } else {
                        echo 'Invalid response from paypal <br>';
                        //exit;
                    }
                } else {
                    echo 'Invalid response from paypal <br>';
                    //exit;
                }
            } else {
                echo 'Invalid response from paypal <br>';
                //exit;
            }
        } else {
            echo 'Invalid response from paypal <br>';
            //exit;
        }
    }

    public function time_check()
    {
        echo date('Y-m-d H:i:s T');
        exit;
    }

    public function send_email_contarct()
    {

        $leads = "
            SELECT vl.* 
            FROM video_leads vl
            WHERE vl.unique_key IN(
                'WGA987595'
            )
        ";

        $leads = $this->db->query($leads);

        foreach ($leads->result() as $lead) {
            $emailData = getEmailTemplateByCode('welcome_email');
            $already_accountemailData = getEmailTemplateByCode('deal_information_received');
            $token_results = $this->db->query('SELECT verify_token,password FROM `users`WHERE `id`= "' . $lead->client_id . '"')->result();
            $token = $token_results[0]->verify_token;
            if (empty($token)) {
                $this->load->helper('string');
                $token = random_string('alnum', 20);
                $dbDatatoken['verify_token'] = $token;
                $dbDatatoken['token_expiry_time'] = date('Y-m-d H:i:s', strtotime('+20 days', strtotime(date('Y-m-d H:i:s'))));
                $this->db->where('id', $lead->client_id);
                $this->db->update('users', $dbDatatoken);
                $token_results = $this->db->query('SELECT verify_token,password FROM `users`WHERE `id`= "' . $lead->client_id . '"')->result();
                $token = $token_results[0]->verify_token;
                action_add($result->lead_id, $result->id, 0, $lead->client_id, 1, 'Token Added');
            }
            if ($emailData) {
                $str = $emailData->message;
                if ($lead->unique_key) {
                    $subject = $emailData->subject . '(Important)-' . $lead->unique_key;
                } else {
                    $subject = $emailData->subject;
                }
                $ids = array(
                    'users' => $lead->client_id
                );
                $this->data['root'] = "wgv.wooglobe.com/";
                $message = dynStr($str, $ids);
                // $url = $this->data['root'].'new-login/'.$token; ->waqas
                $url = "www.wooglobe.com" . '/new-login/' . $token;

                $message = str_replace('@LINK', $url, $message);
                // if($_SERVER['HTTP_HOST'] == 'localhost') {
                //     $file_to_attach = $_SERVER['DOCUMENT_ROOT'].'/uat/uploads/'.$lead->unique_key.'/documents/'.$lead->unique_key. '_signed.pdf';

                // }else{
                //     $file_to_attach = $_SERVER['DOCUMENT_ROOT'].'/var/www/html/uploads/'.$lead->unique_key.'/documents/'.$lead->unique_key. '_signed.pdf';

                // }
                //echo $file_to_attach.'<br>';
                //if($lead->contract_signed==0 && $lead->video_verified ==1) {
                $result = $this->email($lead->email, $lead->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '', $file_to_attach, $lead->unique_key);

                $datacontract['contract_signed'] = 1;
                $this->db->where('unique_key', $lead->unique_key);
                $this->db->update('video_leads', $datacontract);
                //}

                //$insert = $this->user->email_notification($notification);
            }
            //$result = $this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '', $file_to_attach, $unique_key);
        }
    }

    public function landscape_conversion()
    {
        $query = '
        SELECT rv.*, vl.unique_key, ev.portal_url, wv.s3_url watermark_url, vl.created_at
        FROM raw_video rv
        INNER JOIN video_leads vl
        ON rv.lead_id = vl.id
        INNER JOIN edited_video ev
        ON rv.video_id = ev.video_id
        LEFT JOIN watermark_videos wv
        ON rv.video_id = wv.video_id
        WHERE vl.deleted = 0
        AND vl.status >= 6
        AND vl.status <= 8
        AND  vl.created_at > "2023-6-20"
        AND vl.is_ai_based = 0
        AND rv.landscape_converted_url IS NULL
        GROUP By vl.unique_key
        ORDER BY rv.video_id DESC';

        $videos = $this->db->query($query);
        $videos_result = $videos->result();

        if (!is_dir("../temp_videos")) {
            mkdir("../temp_videos", 0777, true);
        }

        foreach ($videos_result as $video) {
            $video_url = '';
            if (strlen($video->portal_url) > 0 && $video->portal_url != 'http://manualupload.com' && $video->portal_url != 'Manual Upload' && $video->portal_url != 'hoa94aFvAyQ' && $video->portal_url != '0') {
                $video_url = $video->portal_url;
            } else if (strlen($video->watermark_url) > 0) {
                $video_url = $video->watermark_url;
            } else if (strlen($video->s3_url) > 0 && $video->portal_url != '0') {
                $video_url = $video->s3_url;
            }

            if (strlen($video_url) > 0) {
                $output_file = "uploads/" . $video->unique_key . "/landscape_converted/" . $video->unique_key . "-landscape";

                // Safely run ffprobe to get video dimensions
                $video_data = exec("ffprobe -v error -select_streams v:0 -show_entries stream=width,height -of csv=p=0 '$video_url'");
                $video_data = explode(',', $video_data);

                if (count($video_data) == 2) {
                    $video_dimensions = array(
                        'width' => (int) $video_data[0],
                        'height' => (int) $video_data[1]
                    );

                    // Check for valid dimensions
                    if ($video_dimensions['width'] > 0 && $video_dimensions['height'] > 0) {
                        // Check aspect ratio
                        if ($video_dimensions['width'] / $video_dimensions['height'] != 16 / 9) {
                            // Use the generic uploadToS3 function
                            $s3_path = $output_file;
                            print(date("Y-m-d H:i:s") . " - Uploading to S3 -> " . $s3_path . "\n");
                            try {
                                $jobId = LandscapeConversion_AWS($video_url, $s3_path);

                                if ($jobId) {
                                    print("Uploaded to s3 completed -> " . $jobId);
                                    $this->db->where('video_id', $video->video_id);
                                    $this->db->update('raw_video', array('landscape_converted_url' => $jobId));
                                    print_r("\nDB Updated\n");
                                }
                            } catch (Exception $e) {
                                print("ERROR in S3 upload: " . $e->getMessage() . "\n");
                                continue;
                            }
                        } else {
                            continue;
                        }
                    } else {
                        print("Invalid video dimensions for video ID: {$video->video_id}\n");
                        continue;
                    }
                } else {
                    print("Unable to retrieve video dimensions for video ID: {$video->video_id}\n");
                    continue;
                }
            }
        }
    }

    public function custom_landscape_conversion()
    {
        $query = '
            SELECT rv.*, vl.unique_key, ev.portal_url, wv.s3_url watermark_url, vl.created_at
            FROM raw_video rv
            LEFT JOIN video_leads vl
            ON rv.lead_id = vl.id
            LEFT JOIN edited_video ev
            ON rv.video_id = ev.video_id
            LEFT JOIN watermark_videos wv
            ON rv.video_id = wv.video_id
            WHERE vl.deleted = 0
            AND vl.status >= 6
            AND vl.status <= 8
            AND vl.unique_key IN ("WGA299252","WGA210768")
            ORDER BY rv.video_id';

        $videos  = $this->db->query($query);
        $videos_result = $videos->result();
        if (!is_dir("../temp_videos")) {
            mkdir("../temp_videos", 0777, true);
        }

        foreach ($videos_result as $video) {
            if (strlen($video->landscape_converted_url) == 0 && date('Y-m-d', strtotime($video->created_at)) > date('Y-m-d', strtotime('2023-6-20'))) {
                $video_url = '';
                if (strlen($video->portal_url) > 0 && $video->portal_url != 'http://manualupload.com' && $video->portal_url != 'Manual Upload' && $video->portal_url != 'hoa94aFvAyQ' && $video->portal_url != '0') {
                    $video_url = $video->portal_url;
                } else if (strlen($video->watermark_url) > 0) {
                    $video_url = $video->watermark_url;
                } else if (strlen($video->s3_url) > 0 && $video->portal_url != '0') {
                    $video_url = $video->s3_url;
                }

                if (strlen($video_url) > 0) {
                    $filename = basename($video_url);
                    $local_temp_url = "../temp_videos/" . $filename;
                    $file_extension = explode('.', $filename);
                    $file_extension = $file_extension[1];
                    $output_file = "uploads/" . $video->unique_key . "/landscape_converted/" . $filename;

                    $video_data = exec("ffprobe -v error -select_streams v:0 -show_entries stream=width,height -of csv=p=0 $video_url");
                    $video_data = explode(',', $video_data);
                    $video_dimensions = array(
                        'width' => (int) $video_data[0],
                        'height' => (int) $video_data[1]
                    );

                    if ($video_dimensions['width'] / $video_dimensions['height'] != 16 / 9) {
                        exec("ffmpeg -i $video_url -vf \"scale=-1:1080,pad=1920:1080:(ow-iw)/2:(oh-ih)/2\" -c:v libx264 -crf 5 $local_temp_url");
                    }
                    $s3_url = $this->upload_file_s3_new('public-read', $local_temp_url, $output_file, $file_extension, FALSE);
                    if (strlen($s3_url) > 0) {
                        $this->db->where('video_id', $video->video_id);
                        $this->db->update('raw_video', array('landscape_converted_url' => $s3_url));
                        unlink($local_temp_url);
                    }
                }
            }
        }
    }
}
