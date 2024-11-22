<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MrssReports extends APP_Controller {

    public function __construct() {
        parent::__construct();
        $this->data['active'] = 'MrssReports';
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
            'assets/js/jquery.mousewheel.min.js',
            'assets/js/mrssreports.js'
        );
        $this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
        $this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
        $this->data['assess'] =  array(
            'list'=>role_permitted_html(false,'reports'),
        );
        $this->load->model('MrssReport_Model','report');
        $this->load->model('Categories_Model', 'mrss');

    }
    public function index()
    {
        auth();
        role_permitted(false,'reports');
        $this->data['title'] = 'Mrss Report';
        $this->data['mrss_categories'] = $this->mrss->getGeneralCategories();
        //$this->data['selected_mrss_categories'] = $this->mrss->getVideoSelectedCategoriesByVideoId($id);
        $this->data['mrss_partners'] = $this->mrss->getMrssPartners();
        //$this->data['mrss_feed_data'] = $this->mrss->getFeedDataByVideoId($id);
        $this->data['non_exclusive_partner_data']=$this->mrss->nonExclusivePartnerdata();
        $this->data['content'] = $this->load->view('mrssreports/listing',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }

    public function reports_listing(){
        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'reports');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $params = $this->security->xss_clean($this->input->get());
        // echo ($params);
        // exit();
        $search = '';
        $orderby = 'mp.publication_date DESC';
        $start = 0;
        $limit = 0;
        $where = NULL;
        if(isset($params['search'])){
            $search = $params['search']['value'];
        }
        if(isset($params['start'])){
            $start = $params['start'];
        }
        if(isset($params['length'])){
            $limit = $params['length'];
        }
        if (isset($params['order'])) {
            $orderby = $params['columns'][$params['order'][0]['column']]['name'] . ' ' . $params['order'][0]['dir'];
        }
        $group=' GROUP BY (fv.video_id) ';

        if((isset($params['date_from']) && !empty($params['date_from'])) && (isset($params['date_to']) && !empty($params['date_to']))){
            $date_from = date('Y-m-d',strtotime($params['date_from']));
            $date_to = date('Y-m-d',strtotime($params['date_to']));
            $where .= " AND DATE(mp.publication_date) ";
            if(isset($params['publish_status']) && $params['publish_status'] == 2) {
                $where .= "NOT ";
            }
            $where .= "BETWEEN '$date_from' AND '$date_to' ";
        }

        if(isset($params['cat']) && !empty($params['cat'])  && isset($params['partner']) && !empty($params['partner']) ){
            $cateory=array();
            $partner_cat=array();
            foreach ($params['cat'] as $cat) {
                array_push($cateory,$cat);
            }
            foreach ($params['partner'] as $partner) {
                array_push($partner_cat,$partner);

            }
            $gen_count=count($cateory);
            $partner_count=count($partner_cat);
            $feed_count = $gen_count + $partner_count-1;
            $gen_list = implode(', ', $cateory);
            $partner_list = implode(', ', $partner_cat);

            if(isset($params['publish_status']) && $params['publish_status'] == 2) {
                $where .= 'AND mp.feed_id NOT IN ('.$gen_list.','.$partner_list.')';
            }
            else {
                $where .= 'AND mp.feed_id IN ('.$gen_list.','.$partner_list.')';
            }
            $group=' GROUP BY (fv.video_id) HAVING COUNT(*) > '.$feed_count ;

        }
        else {
            if(isset($params['cat']) && !empty($params['cat'])) {
               // print "cat one";
                $cateory=array();
                $partner_cat=array();
                foreach ($params['cat'] as $cat) {
                    array_push($cateory,$cat);
                }
                if (!empty($params['partner'])) {
                    foreach ($params['partner'] as $partner) {
                        array_push($partner_cat,$partner->id);
                    }
                }
                $gen_count=count($cateory);
                $partner_count=count($partner_cat);
                $feed_count = $gen_count + $partner_count-1;
                $gen_list = implode(', ', $cateory);
                $partner_list = implode(', ', $partner_cat);

                if(isset($params['publish_status']) && $params['publish_status'] == 2) {
                    $where .= 'AND mp.feed_id NOT IN ('.$gen_list;
                }
                else {
                    $where .= 'AND mp.feed_id IN ('.$gen_list;
                }
                if(!empty($partner_list)) {
                    $where .= ",".$partner_list.')';
                }
                
                $group=' GROUP BY (fv.video_id) HAVING COUNT(*) > '.$feed_count ;
            }
            if(isset($params['partner']) && !empty($params['partner'])) {
                $k = 0;
                $partner_where = '';
                if(count($params['partner']) > 0){
                    $partner_where=' AND (';
                }
                foreach ($params['partner'] as $part) {

                    if ($k == 0) {
                        // first
                        $partner_where .= " mp.feed_id ";
                        if(isset($params['publish_status']) && $params['publish_status'] == 2) {
                            $partner_where .= "!";
                        }
                        $partner_where .= "= $part ";
                    } else {
                        if(isset($params['publish_status']) && $params['publish_status'] == 2) {
                            $partner_where .= " AND ";
                        }
                        else{
                            $partner_where .= " OR ";
                        }
                        $partner_where .= "mp.feed_id ";
                        if(isset($params['publish_status']) && $params['publish_status'] == 2) {
                            $partner_where .= "!";
                        }
                        $partner_where .= "= $part ";
                    }
                    $k++;
                }
                if($partner_where!=''){
                    $partner_where.=')';
                }
                $where .= $partner_where;
            }
        }
        if(isset($params['cat']) && empty($params['cat'])  && isset($params['partner']) && empty($params['partner']) && isset($params['date_from']) && empty($params['date_from']) && isset($params['publish_status']) && $params['publish_status'] == 2) {
            $where .= "AND mp.publication_date IS NULL";
        }

        $result = $this->report->getReports(
            '
            group_concat(mp.feed_id) as feed_id,
            mp.video_id,
            mf.partner_id,
			vl.unique_key,
			ed.portal_thumb,
			vl.video_title,
			vl.video_url,
			vl.status,
			vl.information_pending,
			v.video_verified,
			v.description,
			vl.uploaded_edited_videos,
			v.tags,
			vl.confidence_level,
			vl.rating_point,
			vl.revenue_share,
			vl.video_comment,
			v.title,
			vl.id as lead_id,
			v.id as video_id,
			v.mrss,
			DATE_FORMAT(mp.publication_date,"%m/%d/%Y %H:%i %p") AS pub_date
			',
            $start,$limit,$where,$group,$orderby);
        $result2 = $this->report->getReports(
            '
            group_concat(mp.feed_id) as feed_id,
            mp.video_id,
            mf.partner_id,
            vl.unique_key,
            ed.portal_thumb,
            vl.video_title,
            vl.video_url,
            vl.status,
            vl.information_pending,
            v.video_verified,
            v.description,
            vl.uploaded_edited_videos,
            v.tags,
            vl.confidence_level,
            vl.rating_point,
            vl.revenue_share,
            vl.video_comment,
            v.title,
            vl.id as lead_id,
            v.id as video_id,
            v.mrss,
			DATE_FORMAT(mp.publication_date,"%m/%d/%Y %H:%i %p") AS pub_date
            ',0,0,$where,$group);
         //echo $this->db->last_query();exit;
        /*      $resultCount = $this->report->getReports('*','',0,0,$where,$group,'ORDER BY vl.id DESC');

           print_r( count($result->result()));
              exit();*/
        $response = array();
        $data = array();

        foreach($result->result() as $row){
            // $stage = 'Pending Lead';
            // if($row->status == 10){
            //     $stage = 'Lead Rated';
            // }else if($row->status == 5){
            //     $stage = 'Poor Rating Video';
            // }else if($row->status == 2){
            //     $stage = 'Contract Sent';
            // }else if($row->status == 3 ){
            //     $stage = 'Information Received';
            // }else if($row->status == 6 && $row->information_pending == 1 && $row->video_verified == 1 && $row->uploaded_edited_videos == 0){
            //     $stage = 'Upload Edited Videos';
            // }
            // else if($row->status == 6 && $row->video_verified == 1 && $row->uploaded_edited_videos == 1){
            //     $stage = 'Distribute Edited Videos';
            // }else if($row->status == 8){
            //     $stage = 'Closed Won';
            // }else if($row->status == 9){
            //     $stage = 'Closed Lost';
            // }else if($row->status == 11){
            //     $stage = 'Not Interested';
            // }
            $res_feed_id='';
            $unique_link =  '<a href=deal-detail/'.$row->lead_id.'>'.$row->unique_key.'</a>';
            $potal_thumb =$row->portal_thumb;
            $potal_thumb=wordwrap($potal_thumb, 40, "<wbr>", true);
            $video_title =$row->title;
            $video_title=wordwrap($video_title, 40, "<wbr>", true);
            $video_des =$row->description;
            $video_des=wordwrap($video_des, 40, "<wbr>", true);
            $video_tags =$row->tags;
            if(!empty($video_tags)){
                $video_tags=preg_replace("/(.{30})/", "$1\n\r", $video_tags);;
            }
            $video_url =$row->video_url;
            $video_url=wordwrap($video_url, 40, "<wbr>", true);
            $gerenal_cat_title ='';
            $partner_cat_title ='';

            if(!empty($params['cat']) && !empty($params['partner'])){

                if(!empty($row->feed_id)){
                    $searchForValue = ',';
                    $feed_id_gen='';
                    if( strpos($row->feed_id, $searchForValue) !== false ) {
                        $farr=explode(",",$row->feed_id);
                        foreach ($farr as $far){
                            $verify_gerenal_id = $this->report->getGeneralMRSSCatNameby($far);
                            $verify_gerenal_res =$verify_gerenal_id->result();


                            if(isset($verify_gerenal_res[0])){
                                $feed_id_gen =$verify_gerenal_res[0]->id;
                            }
                        }
                        if(!empty($feed_id_gen)){
                            $res_partner_feed_id = $this->report->getPartnerMRSSCatNamebyFeedidandVideoid($feed_id_gen,$row->video_id);
                            $partner_cat_res =$res_partner_feed_id->result();


                            foreach ($partner_cat_res as $partner_cat ){

                                $partner_cat_title .= $partner_cat->title .',';
                            }
                        }
                    }else{
                        $res_partner_feed_id = $this->report->getPartnerMRSSCatNameby($row->feed_id);
                        $partner_cat_res =$res_partner_feed_id->result();
                        $z = 0;
                        foreach ($partner_cat_res as $partner_cat ){
                            if($z = 0){
                                $partner_cat_title .= $partner_cat->title;
                            }else{
                                $partner_cat_title .= $partner_cat->title .',';
                            }
                            $z++;
                        }
                    }
                }
                $searchForValue = ',';
                $feed_id_partner='';
                if( strpos($row->feed_id, $searchForValue) !== false ) {
                    $farr=explode(",",$row->feed_id);
                    foreach ($farr as $far){
                        $verify_partner_id = $this->report->getPartnerMRSSCatNameby($far);
                        $verify_partner_res =$verify_partner_id->result();
                        if(isset($verify_partner_res[0])){
                            $feed_id_partner =$verify_partner_res[0]->id;
                        }
                    }
                    if(!empty($feed_id_partner)){
                        $res_feed_id = $this->report->getGeneralMRSSCatNamebyFeedidandVdieoid($feed_id_partner,$row->video_id);

                        $gerenal_cat_res =$res_feed_id->result();
                        foreach ($gerenal_cat_res as $gerenal_cat ){

                            $gerenal_cat_title .= $gerenal_cat->title .',';

                        }
                    }


                }else{
                    $res_feed_id = $this->report->getGeneralMRSSCatNameby($row->feed_id);
                    $gerenal_cat_res =$res_feed_id->result();
                    $l = 0;
                    foreach ($gerenal_cat_res as $gerenal_cat ){
                        if($l = 0){
                            $gerenal_cat_title .= $gerenal_cat->title;
                        }else{
                            $gerenal_cat_title .= $gerenal_cat->title .',';
                        }
                        $l++;
                    }
                }

            }else if(empty($params['cat']) && !empty($params['partner'])){

                $searchForValue = ',';
                $feed_id_gen='';
                if( strpos($row->feed_id, $searchForValue) !== false ) {
                    $farr=explode(",",$row->feed_id);
                    foreach ($farr as $far){
                        $verify_gerenal_id = $this->report->getGeneralMRSSCatNameby($far);
                        $verify_gerenal_res =$verify_gerenal_id->result();
                        if(isset($verify_gerenal_res[0])){
                            $feed_id_gen =$verify_gerenal_res[0]->id;
                        }
                    }
                    if (!empty($feed_id_gen)) {
                        $res_partner_feed_id = $this->report->getPartnerMRSSCatNamebyFeedidandVideoid($feed_id_gen,$row->video_id);
                        $partner_cat_res =$res_partner_feed_id->result();

                        foreach ($partner_cat_res as $partner_cat ){

                            $partner_cat_title .= $partner_cat->title .',';
                        }
                    }
                }else{
                    $res_partner_feed_id = $this->report->getPartnerMRSSCatNameby($row->feed_id);
                    $partner_cat_res =$res_partner_feed_id->result();
                    $z = 0;
                    foreach ($partner_cat_res as $partner_cat ){
                        if($z = 0){
                            $partner_cat_title .= $partner_cat->title;
                        }else{
                            $partner_cat_title .= $partner_cat->title .',';
                        }
                        $z++;
                    }
                }

            }else if(!empty($params['cat']) && empty($params['partner'])){

                $searchForValue = ',';
                $feed_id_partner='';
                if( strpos($row->feed_id, $searchForValue) !== false ) {
                    $farr=explode(",",$row->feed_id);
                    foreach ($farr as $far){
                        $verify_partner_id = $this->report->getPartnerMRSSCatNameby($far);
                        $verify_partner_res =$verify_partner_id->result();
                        if(isset($verify_partner_res[0])){
                            $feed_id_partner =$verify_partner_res[0]->id;
                        }
                    }
                    if (!empty($feed_id_partner)) {
                        $res_feed_id = $this->report->getGeneralMRSSCatNamebyFeedidandVdieoid($feed_id_partner,$row->video_id);

                        $gerenal_cat_res =$res_feed_id->result();
                        foreach ($gerenal_cat_res as $gerenal_cat ){

                            $gerenal_cat_title .= $gerenal_cat->title .',';

                        }
                    }

                }else{
                    $res_feed_id = $this->report->getGeneralMRSSCatNameby($row->feed_id);
                    $gerenal_cat_res =$res_feed_id->result();
                    $l = 0;
                    foreach ($gerenal_cat_res as $gerenal_cat ){
                        if($l = 0){
                            $gerenal_cat_title .= $gerenal_cat->title;
                        }else{
                            $gerenal_cat_title .= $gerenal_cat->title .',';
                        }
                        $l++;
                    }
                }
            }

            $res_parnter_id = $this->mrss->getExclusivePartnerByVideoId($row->video_id);
            if(!empty($res_parnter_id)){
                $exclusive = 'Yes';
            }else{
                $exclusive = 'No';
            }

            $r = array();
            $r[] = $unique_link;
            $r[] = $potal_thumb;
            $r[] = $row->rating_point;
            $r[] = $video_title;
            $r[] = $row->description;
            $r[] = htmlspecialchars($video_tags);
            $r[] = $video_url;
            $r[] = $row->revenue_share;
            $r[] = $gerenal_cat_title;
            $r[] = $partner_cat_title;
            $r[] = $exclusive;
            $r[] = $row->confidence_level;
            $r[] = $row->video_comment;
            $r[] = $row->created_at;
            $r[] = $row->publication_date;

            $data[] = $r;
        }
        $response['code'] = 200;
        $response['message'] = 'Listing';
        $response['error'] = '';
        $response['data'] = $data;
        $response['recordsTotal'] = count($result2->result());
        $response['recordsFiltered'] = count($result2->result());
        echo json_encode($response);
        exit;
    }
}
