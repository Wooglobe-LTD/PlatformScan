<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class VideoContentManagementController extends APP_Controller
{
    public function __construct()
    {
        parent::__construct();
        $css = array(
            'bower_components/uikit/css/uikit.almost-flat.min.css',
            'assets/skins/dropify/css/dropify.css'
        );
        $js = array(
            'bower_components/datatables/media/js/jquery.dataTables.min.js',
            'bower_components/datatables-buttons/js/dataTables.buttons.js',
            'assets/js/custom/datatables/buttons.uikit.js',
            'bower_components/jszip/dist/jszip.min.js',
            'bower_components/pdfmake/build/pdfmake.min.js',
            'bower_components/pdfmake/build/vfs_fonts.js',
            'bower_components/datatables-buttons/js/buttons.colVis.js',
            'bower_components/datatables-buttons/js/buttons.html5.js',
            'bower_components/datatables-buttons/js/buttons.print.js',
            'assets/js/custom/datatables/datatables.uikit.min.js',
            'assets/js/custom/dropify/dist/js/dropify.min.js',
            'assets/js/pages/forms_file_input.min.js',
            'assets/js/ma_categories.js'
        );
        $this->data['commomCss'] = array_merge($css, $this->data['commomCss']);
        $this->data['commonJs'] = array_merge($this->data['commonJs'], $js);


    }
public function mrss_xml_feed($url)
    {
  
        //include_once('./app/third_party/getid3/getid3/getid3.php');

        $query_category = "SELECT cm.*
                FROM mrss_feeds cm
                WHERE cm.status = 1
                AND cm.deleted = 0
                AND cm.url = '$url'
        ";
        $category = $this->db->query($query_category);

        if ($category->num_rows() > 0) {
            $category = $category->row();

            //$getID3 = new getID3;
            $query = 'SELECT v.*,vl.slug as lead_slug,vl.unique_key,vl.created_at,fd.feed_id
            FROM videos v
            INNER JOIN video_leads vl
            ON v.lead_id = vl.id
            INNER JOIN feed_video fd ON v.id = fd.video_id
            WHERE v.status = 1
             AND v.deleted = 0
             AND v.is_wooglobe_video = 1
             AND v.mrss = 1
             AND fd.feed_id = ' . $category->id . '
             group by v.id
             ORDER BY v.created_at DESC
             ';


            $videos = $this->db->query($query);

            $title = "WooGlobe Video Feed";

            $link = $this->data['url'];

            $description = "Description of the WooGlobe Video Feed";
            // This is the language you display for this podcast.
            $lang = "en-us";
            // This is the copyright information.
            $copyright = "Copyright 2019 WooGlobe";

            $builddate = date(DATE_RFC2822);
            /*<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/" xmlns:bc="' . $this->data['url'] . '" xmlns:dcterms="' . $this->data['url'] . 'terms">*/

            $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                <rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/" xmlns:atom="http://www.w3.org/2005/Atom">
                <channel>
                <atom:link href="https://wooglobe.com/rss.xml" rel="self" type="application/rss+xml" />
                    <title>' . $title . '</title>
                    <link>' . $link . '</link>
                    <description><![CDATA[' . $description . ']]></description>
                    <language>' . $lang . '</language>
                    <copyright>' . $copyright . '</copyright>
                    <lastBuildDate>' . $builddate . '</lastBuildDate>';

            if ($videos->num_rows() > 0) {
                /*print"<pre>";
                print_r($videos->result());
                                print"</pre>";
                exit();*/

                //$file_data = $getID3->analyze($video->url);
                foreach ($videos->result() as $video) {

                    $ext='';
                    $query_raw = 'SELECT rv.s3_url
                            FROM videos v
                            INNER JOIN raw_video rv
                            ON rv.video_id = v.id
                            AND rv.video_id = ' . $video->id;

                    $videos_raw = $this->db->query($query_raw);
                    $raw_vid = $videos_raw->result();
                    $raw_total = count($raw_vid);

                    $query_edited = 'SELECT ev.portal_url,ev.portal_thumb
                            FROM videos v
                            INNER JOIN edited_video ev
                            ON ev.video_id = v.id
                            AND ev.video_id = ' . $video->id;

                    $videos_edited = $this->db->query($query_edited);
                    $vid = $videos_edited->result();


                    $videothumb = trim($video->youtube_id);
                    $thumb = '';
                    $videourl = 'https://WooGlobe.com/';

                    if (isset($vid[0])) {
                        $thumb = $vid[0]->portal_thumb;
                    }
                    if ($thumb) {
                        $thumb = $thumb;
                    } else {
                        $thumb = 'https://img.youtube.com/vi/' . $videothumb . '/hqdefault.jpg';
                    }
                    if ($raw_total > 1) {
                        if (isset($vid[0])) {
                            $videourl = $vid[0]->portal_url;
                            $ext = explode('.', $vid[0]->portal_url);
                        }
                    } else {
                        if (isset($raw_vid[0])) {
                            $videourl = $raw_vid[0]->s3_url;
                            $ext = explode('.', $raw_vid[0]->s3_url);
                        }
                    }
                    $tags=$video->tags;
                    $des=$video->description;
                    $des  = str_replace("&", "&amp;", $des);
                    $tags  = str_replace("&", "&amp;", $tags);
                    $videourl  = str_replace(" ", "%20", $videourl);
                    $slug  = $video->lead_slug;
                    $videourl = preg_replace('/\s+/', ' ',$videourl);
                    $xml .= '<item>
                            <title>' . $video->title . '</title>
                            <link>' . $videourl . '</link>
                            <guid>' . $this->data['url'] . '?video=' . $slug . '</guid>
                            <description>' . $des  . '</description>
                            <thumbnail>' . $thumb  . '</thumbnail>
                            <pubDate>' . date(DATE_RFC2822, strtotime($video->created_at)) . '</pubDate>
                            <enclosure url="' . $videourl . '" length="' . strlen($videourl) . '" type="video/mp4"></enclosure>
                            <media:content type="video/mp4" url="' . $videourl . '">
                                <media:keywords>' . $tags . '</media:keywords>
                                <media:thumbnail url="' . $thumb . '" width="300" height="169" />
                                <media:credit role="producer" scheme="urn:ebu"><![CDATA[ WooGlobe ]]></media:credit>
                            </media:content>
                            </item>';
                }


                $xml .= '</channel></rss>';
               /* $name = 'mrss.xml';
                $this->load->helper('download');
                force_download($name, $xml);*/
                /*<bc:videoid>'.$video->unique_key.'</bc:videoid>
                 <bc:duration>'.@$file_data['playtime_string'].'</bc:duration>*/
               // $this->load->helper('xml');

                echo $xml;
                exit;


            } else {
                echo 'Invalid URL';
                exit;
            }

        }
    }
    public function mrss()
    {

        // $this->db->select('mrss_link');
        // $this->db->where('status', 1);
        // $this->db->where('deleted', 0);
        // $query = $this->db->get('ma_mrss_feeds');
        // foreach ($query->result() as $row) {
           
            $mrss_link = $this->input->get('mrss_link', TRUE);
            $ch = curl_init($mrss_link);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            $data = curl_exec($ch);

            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
            }
            curl_close($ch);

            if (isset($error_msg)) {
                print_r($error_msg);
            }
            
                libxml_use_internal_errors(true);
                $doc = new SimpleXmlElement($data, LIBXML_NOCDATA);
            


            

            if (isset($doc->channel)) {

                $this->parseRSS($doc, $mrss_link);
            }
            if (isset($doc->entry)) {

                $this->parseAtom($doc);
            }

        // }
        exit;


    }

    function parseRSS($xml, $source)
    {
        // echo "<strong>" . $xml->channel->title . "</strong><br>";
        $cnt = count($xml->channel->item);
        $source_type = "mrss";
        for ($i = 0; $i < $cnt; $i++) {
            $url = $xml->channel->item[$i]->link;


            //  echo '<a href="' . $url . '">' . $title . '</a>' . $desc . '';
            $this->db->where('url', $url);
            $query = $this->db->get('ma_videos');
            if ($query->num_rows() == 0) {
                //just to save some execution extract values in if condition
                $title = $xml->channel->item[$i]->title;
                $desc = $xml->channel->item[$i]->description;
                $thumbnail = $xml->channel->item[$i]->thumbnail;
                $data = array(
                    "source" => $source,
                    "source_type" => $source_type,
                    "url" => $url,
                    "title" => $title,
                    "description" => $desc,
                    "thumbnail" => $thumbnail,
                    "status" => 2,
                    "created_at" => date('Y-m-d H:i:s'),
                );
                $this->db->insert("ma_videos", $data);
                echo "Video has been inserted";

            }
            else{
                echo "Duplicated Video Found in this MRSS Link ".$url;
                echo "</br>";
            }

        }

    }

    function parseAtom($xml)
    {
        // echo "<strong>" . $xml->author->name . "</strong>";
        $cnt = count($xml->entry);
        for ($i = 0; $i < $cnt; $i++) {
            $urlAtt = $xml->entry->link[$i]->attributes();
            $url = $urlAtt['href'];
            $title = $xml->entry->title;
            $desc = strip_tags($xml->entry->content);

            /*echo '<a href="' . $url . '">' . $title . '</a>' . $desc . '';
            echo "<br>";*/


        }
    }

    function check_fb_statistics($video)
    {
        if (isset($video["comments"]) || isset($video["likes"])) {
            $facebook_video_likes_limit = $this->config->item('FACEBOOK_VIDEO_LIKES_LIMIT');
            $facebook_video_comments_limit = $this->config->item('FACEBOOK_VIDEO_COMMENTS_LIMIT');
            if ((isset($video["comments"]) && count($video["comments"]) >= $facebook_video_comments_limit) || (isset($video["likes"]) && count($video["likes"]) >= $facebook_video_likes_limit)) {
                return TRUE;
            }

        } else {
            return FALSE;
        }


    }

    public function facebook_page()
    {
        $facebook_page_access_token = $this->config->item("FACEBOOK_PAGE_ACCESS_TOKEN");
        $facebook_page_id = $this->config->item("FACEBOOK_PAGE_ID");
        $url = "https://graph.facebook.com/v6.0/" . $facebook_page_id . "/videos?fields=picture,permalink_url,title,comments{comment_count},likes,description,source,thumbnails{uri,is_preferred}&access_token=" . $facebook_page_access_token;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $return = curl_exec($curl);
        curl_close($curl);
        $fb_video = json_decode($return, true);
        $source_type = "facebook page";
        foreach ($fb_video["data"] as $video) {

            $status = $this->check_fb_statistics($video);
            if ($status) {

                foreach ($video["thumbnails"]["data"] as $thumbnail) {
                    if ($thumbnail["is_preferred"]) {
                        $thumbnail = $thumbnail["uri"];
                    }

                }


                $data = array(
                    "source" => $facebook_page_id,
                    "source_type" => $source_type,
                    "title" => $video["title"],
                    "description" => $video["description"] ? $video["description"] : "",
                    "url" => $video["source"],
                    "thumbnail" => $thumbnail,
                    "status" => 2,
                    "created_at" => date('Y-m-d H:i:s'),
                );
                $this->db->where('url', $video["source"]);
                $query = $this->db->get('ma_videos');
                if ($query->num_rows() == 0) {
                    $inserted = $this->db->insert('ma_videos', $data);

                    /* echo "<pre>";
                     print_r($video);
                     echo "</pre>";*/

                }
            } else {
                echo "false";
            }

        }

        exit;
    }

    public function instagram_token()
    {
        echo "token";

    }

    public function instagram_account()
    {


        $redirect_uri = base_url() . "instagram-redirect-uri-for-token";

        $client_id = "176431030441323";


        $url = 'https://api.instagram.com/oauth/authorize/?client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&scope=user_profile,user_media&response_type=code';

        echo $url;
        exit;


    }

    function get_youtube_video_statistics($video_id, $API_key)
    {
        $url = "https://www.googleapis.com/youtube/v3/videos?part=statistics&id=" . $video_id . "&key=" . $API_key;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $return = curl_exec($curl);
        curl_close($curl);
        $video = json_decode($return, true);
         // print_r($video);
         // exit;
        $youtube_video_views_limit = $this->config->item('YOUTUBE_VIDEO_VIEWS_LIMIT');
        $youtube_video_comments_limit = $this->config->item('YOUTUBE_VIDEO_COMMENTS_LIMIT');
        $youtube_video_likes_limit = $this->config->item('YOUTUBE_VIDEO_LIKES_LIMIT');
        if ($video["items"][0]["statistics"]["viewCount"] >= $youtube_video_views_limit || $video["items"][0]["statistics"]["commentCount"] >= $youtube_video_comments_limit || $video["items"][0]["statistics"]["likeCount"] >= $youtube_video_likes_limit) {
            return TRUE;
        } else {
            return FALSE;
        }

    }

    public function youtube_channel()
    {
        // $auth_ajax = auth_ajax();
        // if ($auth_ajax) {
        //     echo json_encode($auth_ajax);
        //     exit;
        // }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Job Created Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        // $dbData = $this->security->xss_clean($this->input->post());
        // $run_limit=(round(24/$dbData["run_limit"],1))*60;
      
        // $payload=array('run_limit'=>$run_limit,'total_runs'=>$dbData["run_limit"],'remaining_runs'=>$dbData["run_limit"],'last_run'=>"",'channel_id'=>$dbData["channel_id"]);
        //     $data = array(
        //                 'name' => 'youtube_channel',
        //                 'status'=>1,
        //                 'payload'=>json_encode($payload),

        //               );

        //     $this->db->insert('jobs', $data);
        //     echo json_encode($response);
        //     exit;

        $channel_id = $this->input->get('channel_id', TRUE);

        if (!isset($channel_id)) {
            $this->db->select("channel_id");
            $query=$this->db->get('ma_youtube_channels');
            $channels=$query->result();
            foreach ($channels as $channel) {
               $channel_id=$channel->channel_id;
               $this->db->select_max("publishedAt");
         $this->db->where("source_type","youtube");
         $this->db->where("youtube_channel_id",$channel_id);
        $latestpublishedAt = $this->db->get('ma_videos');
        $latestpublishedAt = $latestpublishedAt->row();
        $source_type = "youtube";

        /*$date = new DateTime($latestpublishedAt->publishedAt);
       echo $latestpublishedAt->publishedAt ."<br>";
        $date->add(new DateInterval('PT1S')); // adds 1 sec

        echo ($date->format('Y-m-d\TH:i:s.P'));
        exit;*/
        if (!empty($latestpublishedAt->publishedAt)) {
            $url = "https://www.googleapis.com/youtube/v3/search?key=" . $API_key . "&channelId=" . $channelID . "&publishedAfter=" . $latestpublishedAt->publishedAt . "&part=snippet&type=video&order=" . $order . "&maxResults=" . $maxResults . "&pageToken=" . $NextPageToken . "&format=json";

        } else {
            $url = "https://www.googleapis.com/youtube/v3/search?key=" . $API_key . "&channelId=" . $channelID . "&part=snippet&type=video&order=" . $order . "&maxResults=" . $maxResults . "&pageToken=" . $NextPageToken . "&format=json";


        }


        do {

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $return = curl_exec($curl);
            curl_close($curl);
            $videoList = json_decode($return, true);


            if (!isset($videoList["error"])) {


                $total_results = $videoList["pageInfo"]["totalResults"];

                foreach ($videoList["items"] as $video) {
                    // echo "<pre>";
                    // print_r($video);
                    // echo "</pre>";
                    // exit;
                    $status = $this->get_youtube_video_statistics($video["id"]["videoId"], $API_key);
                    
                    if ($status) {

            $url="https://www.googleapis.com/youtube/v3/videos?key=".$API_key."&fields=items(snippet(tags))&part=snippet&id=".$video["id"]["videoId"];
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $return = curl_exec($curl);
        curl_close($curl);
        $video_tags_data = json_decode($return, true);
        $video_tags=array();
        foreach ($video_tags_data ["items"] as $item) {
            
            $tags=array();
            foreach ($item["snippet"]["tags"] as $tag) {
                
                array_push($tags, $tag);

            }
            $video_tags["tags"]=implode(",", $tags);
            
            
        }
            // print_r($video_tags["tags"]);
            // exit;
            
            
            
        
                        $data = array(
                            "source" => $channelID,
                            "source_type" => $source_type,
                            "youtube_channel_id" => $video["snippet"]["channelId"],
                            "youtube_channel_title" => $video["snippet"]["channelTitle"],
                            "title" => $video["snippet"]["title"],
                            "description" => $video["snippet"]["description"],
                            "thumbnail" => $video["snippet"]["thumbnails"]["high"]["url"],
                            "tags"=>$video_tags["tags"],
                            "status" => 2,
                            "created_at" => date('Y-m-d H:i:s'),
                            "publishedAt" => $video["snippet"]["publishedAt"],
                            "youtube_id" => $video["id"]["videoId"]
                        );
                        $this->db->where('youtube_id', $video["id"]["videoId"]);
                        $query = $this->db->get('ma_videos');
                        if ($query->num_rows() == 0) {
                            $inserted = $this->db->insert('ma_videos', $data);
                            echo "video inserted in db";
                            // echo "<pre>";
                            // print_r($video);
                            // echo "</pre>";
                        }
                    } else {
                        print "not eligible";
                    }


                }


                if (isset($videoList->NextPageToken)) {
                    $NextPageToken = $videoList->NextPageToken;
                }


                $start = $maxResults + 50;

            } else {
                echo "<pre>";
                print_r($videoList['error']);
                echo "</pre>";
            }


        } while ($start <= 20);
        exit;

            } //foreach end here
        } //if end here for channel id
        else{



            


        $API_key = "AIzaSyBb4D4cuC0BN_wBiesPUo6IXjdicLVFt4s";//$this->config->item('YOUTUBE_API_KEY');
        $channelID = $channel_id;
        $maxResults = 50;
        $start = 1;
        $total_results = "";
        $NextPageToken = "";
        $order = "date"; ////allowed order : date,rating,relevance,title,videocount,viewcount


       
         
       
         $this->db->select_min('publishedAt');
        $latestpublishedAt = $this->db->get('yt_exports');
        $latestpublishedAt = $latestpublishedAt->row();
        $source_type = "youtube";

        /*$date = new DateTime($latestpublishedAt->publishedAt);
       echo $latestpublishedAt->publishedAt ."<br>";
        $date->add(new DateInterval('PT1S')); // adds 1 sec

        echo ($date->format('Y-m-d\TH:i:s.P'));
        exit;*/
         if (!empty($latestpublishedAt->publishedAt)) {
            $url = "https://www.googleapis.com/youtube/v3/search?key=" . $API_key . "&channelId=" . $channelID . "&publishedBefore=" . $latestpublishedAt->publishedAt . "&part=snippet&type=video&order=" . $order . "&maxResults=" . $maxResults . "&pageToken=" . $NextPageToken . "&format=json";

        } else {
            $url = "https://www.googleapis.com/youtube/v3/search?key=" . $API_key . "&channelId=" . $channelID . "&part=snippet&type=video&order=" . $order . "&maxResults=" . $maxResults . "&pageToken=" . $NextPageToken . "&format=json";


        }

        do {

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $return = curl_exec($curl);
            curl_close($curl);
            $videoList = json_decode($return, true);


            if (!isset($videoList["error"])) {


                $total_results = $videoList["pageInfo"]["totalResults"];

                foreach ($videoList["items"] as $video) {
                    // echo "<pre>";
                    // print_r($video);
                    // echo "</pre>";
                    // exit;
                   // $status = $this->get_youtube_video_statistics($video["id"]["videoId"], $API_key);
                    
                    if (true) {

            $url="https://www.googleapis.com/youtube/v3/videos?key=".$API_key."&fields=items(snippet(tags))&part=snippet&id=".$video["id"]["videoId"];
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $return = curl_exec($curl);
        curl_close($curl);
        $video_tags_data = json_decode($return, true);
        $video_tags=array();
        foreach ($video_tags_data ["items"] as $item) {
            
            $tags=array();
            foreach ($item["snippet"]["tags"] as $tag) {
                
                array_push($tags, $tag);

            }
            $video_tags["tags"]=implode(",", $tags);
            
            
        }
            // print_r($video_tags["tags"]);
            // exit;
            
            
            
        
                        $data = array(
                            
                            "title" => $video["snippet"]["title"],
                            "description" => $video["snippet"]["description"],
                            "thumbnail" => $video["snippet"]["thumbnails"]["high"]["url"],
                            "tags"=>$video_tags["tags"],
                             "publishedAt" => $video["snippet"]["publishedAt"],
                            "youtube_id" => $video["id"]["videoId"]
                        );
                        $this->db->where('youtube_id', $video["id"]["videoId"]);
                        $query = $this->db->get('yt_exports');
                        if ($query->num_rows() == 0) {
                            $inserted = $this->db->insert('yt_exports', $data);
                            echo "video inserted in db";
                            // echo "<pre>";
                            // print_r($video);
                            // echo "</pre>";
                        }
                        else{
                             echo "duplicate video id ". $video["id"]["videoId"];
                        }
                    } else {
                        print "not eligible";
                    }


                }


                if (isset($videoList->NextPageToken)) {
                    $NextPageToken = $videoList->NextPageToken;
                }

                //$start ++; //$maxResults + 50;

            } else {
                echo "<pre>";
                print_r($videoList['error']);
                echo "</pre>";
            }

            exit;
        } while ($start == 1);
        exit;
        }

    }

}

