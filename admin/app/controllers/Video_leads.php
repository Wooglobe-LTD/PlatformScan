<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video_Leads extends APP_Controller {

	public function __construct() {
        parent::__construct();
		$this->data['active'] = 'video_leads';
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
			'assets/js/vleads2.js'
		);
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
		$this->data['assess'] =  array(
            'list'=>role_permitted_html(false,'video_leads'),
            'can_rate'=>role_permitted_html(false,'video_leads','can_rate'),
        );
		$this->load->model('Video_Lead_Model','lead');
		$this->load->helper('string');

    }
	public function index()
	{
		auth();
        role_permitted(false,'video_leads');
        $this->data['title'] = 'Video Leads Management';
        $this->data['content'] = $this->load->view('video_leads/listing',$this->data,true);
        $this->load->view('common_files/template',$this->data);
	}


    public function video_leads_listing(){
        $auth_ajax = auth_ajax();
        $party_name='';
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'video_leads');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $params = $this->security->xss_clean($this->input->get());
        $search = '';
        $orderby = 'vl.id DESC';
        $start = 0;
        $limit = 0;
        if(isset($params['search'])){
            $search = $params['search']['value'];
        }
        if(isset($params['start'])){
            $start = $params['start'];
        }
        if(isset($params['length'])){
            $limit = $params['length'];
        }
        if(isset($params['order'])){
            $orderby = $params['columns'][$params['order'][0]['column']]['name'].' '.$params['order'][0]['dir'];
        }

        $result = $this->lead->getAllLeads('CONCAT(vl.first_name," ",vl.last_name) as name,vl.id,vl.first_name,vl.last_name,vl.email,vl.video_title,vl.video_url,vl.staff_id,vl.third_party_staff_id, case when (vl.status = 1) THEN "Active" ELSE "Inactive" END as status,DATE_FORMAT(vl.created_at,"%m/%d/%Y %H:%i %p") AS created_at,vl.unique_key,vl.simple_video',$search,$start,$limit,$orderby,$params['columns']);
        $resultCount = $this->lead->getAllLeads();
        $response = array();
        $data = array();

        foreach($result->result() as $row){
            $finalUrl = '';
            if(strpos($row->video_url, 'facebook.com/') !== false) {
                //it is FB video
                $finalUrl = 'https://www.facebook.com/plugins/video.php?href='.rawurlencode($row->video_url).'&show_text=1&width=200';
            }else if(strpos($row->video_url, 'vimeo.com/') !== false) {
                //it is Vimeo video
                $videoId = explode("vimeo.com/", $row->video_url);
                if(isset($videoId[1])) {
                    $videoId = $videoId[1];
                    if (strpos($videoId, '&') !== false) {
                        $videoId = explode("&", $videoId);
                        $videoId = $videoId[0];
                    }
                }else{

                    $videoId = '';

                }
                $finalUrl = 'https://player.vimeo.com/video/'.$videoId;
            }else if(strpos($row->video_url, 'youtube.com/') !== false) {
                //it is Youtube video
                $videoId = explode("v=",$row->video_url);
                if(isset($videoId[1])){
                    $videoId = $videoId[1];
                    if(strpos($videoId, '&') !== false){
                        $videoId = explode("&",$videoId);
                        $videoId = $videoId[0];
                    }
                }else{

                    $videoId = '';

                }

                $finalUrl = 'https://www.youtube.com/embed/'.$videoId;



            }else if(strpos($row->video_url, 'youtu.be/') !== false) {
                //it is Youtube video
                $videoId = explode("youtu.be/", $row->video_url);
                if (isset($videoId[1])) {
                    $videoId = $videoId[1];
                    if (strpos($videoId, '&') !== false) {
                        $videoId = explode("&", $videoId);
                        $videoId = $videoId[0];
                    }
                } else {

                    $videoId = '';

                }
                $finalUrl = 'https://www.youtube.com/embed/' . $videoId;


            }else if(strpos($row->video_url, 'instagram.com/') !== false){
                $finalUrl = "https://api.instagram.com/oembed/?url=".$row->video_url;
            }else{
                $finalUrl = $row->video_url;
            }
            $staff_id=$row->staff_id;
            $third_party_staff_id = $row->third_party_staff_id;
            if(!empty($staff_id)){
                $party_query = $this->lead->getPartyById('admin',$staff_id);
                $party_result =$party_query->result();
                if(count($party_result)){
                    $party_name = $party_result[0]->name;
                }else{
                    $party_name = "WooGlobe";
                }

            } elseif(!empty($third_party_staff_id)){
                $party_query = $this->lead->getPartyById('third_party_staff',$third_party_staff_id);
                $party_result =$party_query->result();
                if(count($party_result)){
                    $party_name = $party_result[0]->name;
                }else{
                    $party_name = "WooGlobe";
                }
            }else{

                $party_name = 'WooGlobe';
            }
            $r = array();

            if(!empty($staff_id) || !empty($third_party_staff_id)){

                $links = '<a href="javascript:void(0);" class="play-video" data-id="'.$row->id.'" data-url="'.$finalUrl.'" data-title="'.$row->video_title.'"><i class="material-icons">&#xE04A;</i></a> ';
                //if(role_permitted_html(false,'video_leads','can_rate')){
                    $links .= '| <a href="javascript:void(0);" class="lead_detail" data-id="'.$row->id.'"><i class="material-icons">&#xE873;</i></a> ';
                //}

                $links .= '| <a title="Delete Lead" href="javascript:void(0);" class="delete-lead" data-id="'.$row->id.'"><i class="material-icons">delete_outline</i></a>';
                $links .= '| <a title="Delete Lead forever" href="javascript:void(0);" class="delete-lead-per" data-id="'.$row->id.'"><i class="material-icons">delete_forever</i></a>';


                $r[] = $links;
                if($row->simple_video == 1){
                    $r[] = '<div class="staff_div_blue">'.$row->created_at.'</div>';
                    $r[] = '<div class="staff_div_blue">'.$row->name.'</div>';
                    $r[] = $party_name;
                    $r[] = '<div class="staff_div_blue">'.$row->email.'</div>';
                    $r[] = '<a href="javascript:void(0);" class="play-video" data-id="'.$row->id.'" data-url="'.$finalUrl.'" data-title="'.$row->video_title.'">'.$row->video_title.'</a>';
                    $r[] = '<div class="staff_div_blue">'.$row->unique_key.'</div>';
                }else{
                    $r[] = '<div class="staff_div">'.$row->created_at.'</div>';
                    $r[] = '<div class="staff_div">'.$row->name.'</div>';
                    $r[] = $party_name;
                    $r[] = '<div class="staff_div">'.$row->email.'</div>';
                    $r[] = '<a href="javascript:void(0);" class="play-video" data-id="'.$row->id.'" data-url="'.$finalUrl.'" data-title="'.$row->video_title.'">'.$row->video_title.'</a>';
                    $r[] = '<div class="staff_div">'.$row->unique_key.'</div>';
                }

            }else{
                $links = '<a href="javascript:void(0);" class="play-video" data-id="'.$row->id.'" data-url="'.$finalUrl.'" data-title="'.$row->video_title.'"><i class="material-icons">&#xE04A;</i></a> ';
                $links .= '| <a href="javascript:void(0);" class="lead_detail" data-id="'.$row->id.'"><i class="material-icons">&#xE873;</i></a> ';
                $links .= '| <a title="Delete Lead" href="javascript:void(0);" class="delete-lead" data-id="'.$row->id.'"><i class="material-icons">delete_outline</i></a>';
                $links .= '| <a title="Delete Lead forever" href="javascript:void(0);" class="delete-lead-per" data-id="'.$row->id.'"><i class="material-icons">delete_forever</i></a>';
                /*if($this->data['assess']['can_edit']) {
                    $links .= '<a title="Edit Categeory" href="javascript:void(0);" class="edit-category" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> ';

                }*/

                $r[] = $links;
                $r[] = $row->created_at;
                $r[] = $row->name;
                $r[] =$party_name;
                $r[] = $row->email;
                $r[] = '<a href="javascript:void(0);" class="play-video" data-id="'.$row->id.'" data-url="'.$finalUrl.'" data-title="'.$row->video_title.'">'.$row->video_title.'</a>';
                $r[] = $row->unique_key;
                $r[]= $row->staff_id;

            }
            $data[] = $r;
        }
        $response['code'] = 200;
        $response['message'] = 'Listing';
        $response['error'] = '';
        $response['data'] = $data;
        $response['recordsTotal'] = $resultCount->num_rows();
        $response['recordsFiltered'] = $resultCount->num_rows();
        echo json_encode($response);
        exit;
    }





	public function get_lead(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}

        $role_permitted_ajax = role_permitted_ajax(false,'video_leads');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Record found!';
		$response['error'] = '';
		$response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->lead->getLeadById($id,'vl.id,vl.first_name,vl.last_name,vl.email,vl.video_title,vl.video_url,vl.message,vl.rating_point,vl.rating_comments,vl.shotVideo,vl.haveOrignalVideo, COALESCE(rv.url,"emptyurl") as url');
        $raw_urls = $this->lead->getRawUrls($id);
        if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No Category found!';
			$response['error'] = 'No category found!';
			$response['url'] = '';

		}else{
            $result->url = implode(', ', array_column($raw_urls, 'url'));
            $response['root_url'] = $this->data['root'];
			$response['data'] = $result;
            /*if($result->shotVideo == 1){
                $result->shotVideo = "Yes";
            }else{
                $result->shotVideo = "No";
            }

            if($result->haveOrignalVideo == 1){
                $result->haveOrignalVideo = "Yes";
            }else{
                $result->haveOrignalVideo = "No";
            }*/
		}
		
		
		echo json_encode($response);
		exit;

	}
    public  function delete_lead(){
        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'videos','delete_lead');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Video lead deleted Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));

        $result = $this->lead->getLeadByIdAllStatus($id);
        if(!$result){

            $response['code'] = 201;
            $response['message'] = 'No video found!';
            $response['error'] = 'No video found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->sess->userdata('adminId');
        $dbData['deleted_at'] = date('Y-m-d H:i:s');
        $dbData['deleted_by'] = $this->sess->userdata('adminId');
        $dbData['deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('video_leads',$dbData);

        echo json_encode($response);
        exit;
    }
    function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }

        }

        return rmdir($dir);
    }
    public  function delete_lead_per(){
        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'videos','delete_lead');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Video lead deleted Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));

        $result = $this->lead->getLeadByIdAllStatus($id);
        if(!$result){

            $response['code'] = 201;
            $response['message'] = 'No video found!';
            $response['error'] = 'No video found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        $uid=$result->unique_key;
        $this->db->where('id',$id);
        $this->db->delete('video_leads');
        $this->deleteDirectory("/var/www/html/uploads/$uid");
        echo json_encode($response);
        exit;
    }
    public function video_signed_pdf($full_name,$email,$phone,$country,$state,$city,$address,$zip,$date,$revenue_share,$unique_key,$data,$data_log,$api_result){

        $signimgpath= root_path().'/uploads/'.$unique_key.'/documents/'.$unique_key.'.png';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        //$certificate = 'file://'.root_path().'/admin/app/libraries/TCPDF-master/examples/data/cert/tcpdf.crt';
        $certificate = 'file://'.root_path().'/admin/app/libraries/TCPDF-master/examples/data/cert/signature.crt';

// set additional information
        $info = array(
            'Name' => 'WooGlobe',
            'Location' => 'viral@wooglobe.com',
            'Reason' => 'WooGlobe Contract Signing',
            'ContactInfo' => 'https://wooglobe.com/',
        );

// set document signature
        $pdf->setSignature($certificate, $certificate, 'tcpdfdemo', '', 2, $info);

// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('WooGlobe');
        $pdf->SetTitle('WooGlobe');
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
// add a page
        $pdf->AddPage('L','','A4');

        $html = '<img src="'.root_path().'/assets/img/wooglobe-con-1.png" width="1000px" height="600px">';
        $pdf->writeHTML($html);
        /*$fontname = TCPDF_FONTS::addTTFfont('calibri.ttf', 'TrueTypeUnicode', '', 96);
        $pdf->SetFont($fontname, '', 12);*/
// add a page
        $pdf->AddPage('P','','A4');
        $html = '
<style>
        h1.heading {
            text-align: center;
        }

    </style>
        <h1 class="heading"><FONT FACE="Helvetica Neue, serif">WooGlobe Content Agreement</FONT></h1>
        <h1 class="heading"><FONT FACE="Helvetica Neue, serif">Summary</FONT></h1>

<p STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">Thank
you for agreeing to use WooGlobe to distribute your video worldwide
across the web, TV and other platforms. Your agreement with WooGlobe
explains what permissions you give to us, what our role is and how we
work to earn you money. The full terms are set out in the WooGlobe
Content Agreement, which you should read carefully. A summary of the
key points in the Content Agreement is:</SPAN></FONT><FONT COLOR="#000000"><BR><BR></FONT><FONT COLOR="#000000"><U><B>Uses:</B></U></FONT><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">This
agreement gives WooGlobe and its partners the rights they need to
distribute and use your video(s) worldwide. Your video(s) may appear
on websites, on TV shows and in films, in advertising, in public
places and in any other type of media. We will exclusively manage
your video(s) on YouTube and similar platforms (no
restrictions).</SPAN></FONT><FONT COLOR="#000000"><BR><BR></FONT><FONT COLOR="#000000"><U><B>Exclusivity:</B></U></FONT><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">The
rights you grant to us are worldwide and exclusive.&nbsp;</SPAN></FONT></p>
<p STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><U><B>Term:</B></U></FONT><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">The
term of this agreement is perpetual. We need this in order to best
protect and monetise your video(s). </SPAN></FONT><FONT COLOR="#000000">You
may seek to terminate this Agreement under the conditions as set
forth in Section 4 of the agreement. <BR><BR></FONT><FONT COLOR="#000000"><U><B>Earnings:</B></U></FONT><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">We
will pay you agreed percentage, as set forth in agreement, of any
money earned from your video.</SPAN></FONT><FONT COLOR="#000000"> </FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">We
will do our best to earn you as much revenue as possible but we
cannot make any promises as to how much will be
earned.</SPAN></FONT><FONT COLOR="#000000"><BR><BR></FONT><FONT COLOR="#000000"><U><B>Ownership:</B></U></FONT><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">You
must have taken any videos you submit to us. You will retain your
copyright ownership of them. </SPAN></FONT>
</p>
<p STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><U><B>Privacy:</B></U></FONT><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">If
your videos feature any identifiable people, you must make sure you
have their permission to submit your videos to us and for us to use
them. The information you provide to us may be shared with our
clients and we, or our clients may contact you to further verify this
information.</SPAN></FONT><FONT COLOR="#000000"><BR><BR></FONT><FONT COLOR="#000000"><U><B>Your
obligations:</B></U></FONT><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">You
must ensure that your videos are lawful, that you own them and that
you are entitled to allow us to distribute them. Please make sure
that you comply with the WooGlobe Content Agreement at all times.&nbsp;</SPAN></FONT></p>';
// output the HTML content
        $pdf->writeHTML($html);

        $pdf->AddPage('P','','A4');

        $html = '

<style>
       h1.heading {
            text-align: center;
        }

        .top-form,
        .bottom-form {
            background-color: #ececec;
           
            
        }
        
        td {
            text-align: center;
        }

    </style>
        <h1 class="heading">VIDEO OWNER AGREEMENT</h1>
       
                <div class="top-form"  style="border: 3px solid #30538f;border-radius: 10px" >
                    <h3>&nbsp;Video Owner / Licensor</h3>
                        
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;Full Name <font color="red">*</font> :'.$full_name.'</p>
                        <p >&nbsp;&nbsp;&nbsp;&nbsp;Email <font color="red">*</font> : '.$email.' &nbsp;&nbsp; Phone <font color="red">*</font> : '.$phone.'</p>
                        <p >&nbsp;&nbsp;&nbsp;&nbsp;Address <font color="red">*</font> : '.$address.'</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;City <font color="red">*</font> :'.$city.'&nbsp;&nbsp;  State <font color="red">*</font> : '.$state.'</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;Zip Code <font color="red">*</font> : '.$zip.'  &nbsp;&nbsp;Country <font color="red">*</font> : '.$country.'</p>
                        
                </div>
                <div class="bottom-form" style="border: 3px solid #30538f">
                    <h3>&nbsp;Image(s): (i.e. your video(s))</h3>
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;Title: '.$data["video_title"].'</p>
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;URL: '.$data["video_url"].'</p>
                        <small>&nbsp;&nbsp;&nbsp;&nbsp;Includes all additional footage (e.g. "B-roll", raw footage, etc) submitted by Licensor
                            to WooGlobe in connection with the Images. Does not include Licensor\'s channel or other
                            works unless expressly stated.</small>
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;Share: '.$revenue_share.'%</p>
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;Where was this video filmed?<font color="red">*</font>: '.$data["question1"].'</p>
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;When was this video filmed?<font color="red">*</font>: '.$data["question3"].'</p>
                </div>
                    <h3>Declaration</h3>
                    <ul>
                        <li>I am 18 years of age or older and I either shot this video all by myself or own full
                            rights to the video.</li>
                        <li>By signing the agreement, I acknowledge that I have read and understood the detailed
                            WooGlobe Ltd. content agreement below, and that I accept and agree to adhere to all of
                            its terms, which includes the exclusive grant of rights of video to WooGlobe Ltd.</li>
                    </ul>
                    <div>
                    <table>
                        <tr>
                            <td>'.$full_name.'</td>
                            <td></td>
                            <td>'.$date.'</td>
                        </tr>
                        <tr>
                            <td style="border-top: 1px solid #7f7f7f">Name</td>
                            <td style="border-top: 1px solid #7f7f7f">Signature</td>
                            <td style="border-top: 1px solid #7f7f7f">Date</td>
                        </tr>
                    </table>  
                </div>


';

        $pdf->writeHTML($html);

        $pdf->Image($signimgpath, 100, 240, 18, 14, 'PNG');

// define active area for signature appearance
        $pdf->setSignatureAppearance(100, 240, 18, 14);

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// *** set an empty signature appearance ***
        $pdf->addEmptySignatureAppearance(100, 255, 18, 14);



        $pdf->AddPage('P','','A4');
        $html = '

<style>
        h1.heading {
            text-align: center;
        }
 td {
            text-align: center;
        }
    </style>
      
<p style="text-align: right;"><FONT COLOR="#7f7f7f"><FONT FACE="Helvetica Neue, serif"><FONT SIZE=2 STYLE="font-size: 10pt">WooGlobe Ltd.<br />
16 Weir Road, <br />
London, DA51BJ<br />
UK</FONT></FONT></FONT></p>

<h1 class="heading"><FONT FACE="Helvetica Neue, serif">Agreement Terms and Conditions</FONT></h1>

<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>1.
Licensed Rights</B></U></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">.
Licensor grants WooGlobe the exclusive, unlimited right to use,
refrain from using, change, alter, edit, modify, add to, subtract
from and rearrange the Images and to exhibit, distribute, broadcast,
reproduce, license others to reproduce and distribute, advertise,
promote, publish and otherwise exploit the Images by any and all
methods or means, whether now known or hereafter devised, in any
manner and in any and all media throughout the world, in perpetuity,
for any purpose whatsoever as WooGlobe in its sole discretion may
determine (the &quot;Licensed Rights&quot;), including for the
purpose of marketing, advertising, and promotion. Licensor
furthermore does hereby irrevocably appoint WooGlobe as its
attorney-in-fact to take any such action as may from time to time be
necessary to effect, transfer, or assign the rights granted to
WooGlobe herein, including without limitation copyright-related
actions, and assigns to WooGlobe the right to prosecute any and all
claims from the past, present, and future use of the Images by
unauthorized third parties. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>2.
Payments to Licensor.</B></U></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
In full consideration of all of the Licensed Rights granted
hereunder, WooGlobe will pay Licensor the Share of the net revenue
earned and received by WooGlobe from the exhibition, distribution,
broadcast, licensing and other exploitation of the Licensed Rights,
less proceeds received from uses intended to generate marketable
interest in the Images. Licensor shall be responsible for any taxes
relating to payments it receives to the appropriate tax authority and
governmental entities. Licensor must deliver to WooGlobe agreement to
these terms, any additional information requested by WooGlobe
relating to the Images, and the above-described images in a format
acceptable to WooGlobe in order to receive payment. Licensor
must provide the best quality video file available to WooGlobe and
add a line to any site where Licensor has previously posted the work,
stating: For licensing or usage, contact:licensing@wooglobe.com.</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">WooGlobe
shall process the payment to Licensor within fifteen (15) days after
the end of every quarter (i.e 15th April, 15th July, 15th Oct, 15th
Jan); however, if the amount owed to Licensor is less than </FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">seventy
five</FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
US dollars ($75 USD), WooGlobe reserves the right to carry the
royalty over for payment to Licensor until the amount exceeds </FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">seventy
five</FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
US dollars ($75 USD).</FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
If the amount never exceeds seventy-five US dollars ($75 USD) or if
WooGlobe ceases license acquisition operations, then no Payment will
come due. </FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">WooGlobe
shall not be responsible for any Payments to Licensor for revenue
earned in connection with the images but not received by WooGlobe for
any reason (for example, due to non-payment, or where WooGlobe does
not receive adequate reporting so as to enable WooGlobe to assign
revenue). Licensor agrees that if the outstanding Payment does not
exceed seventy-five US Dollars ($75 USD) for a period of twenty-four
(24) months, account maintenance costs will exceed expected future
revenue. In this event, any outstanding Payment will be charged as a
maintenance fee, and no future Payments are due. Licensor may choose
to be paid via PayPal, or electronic bank transfer (the “Payment
Method”). Any electronic bank transfer fees will be deducted from
the Licensor’s Payment prior to sending. Licensor agrees to provide
WooGlobe all the necessary and accurate information required to
process the Payment (the “Payment Details’) via their preferred
Payment Method. If Licensor fails to provide Payment Details to
WooGlobe within sixty (60) days of the execution of this Agreement or
the expiration of provided Payment Details, Licensor will forfeit the
outstanding Payment balance to WooGlobe. If after sixty (60) days
Licensor updates Payment Details, WooGlobe will make Payments to the
Licensor in accordance with the above terms for Net Revenue earned
for the period after Payment Details are updated. Licensor further
understands that Payments may be subject to withholding tax which
will be paid on behalf of Licensor to the appropriate tax authority.
</FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">Licensor
agrees that WooGlobe is entitled to deduct a reasonable sum from any
revenue to cover the costs incurred to generate interest in the
images. For the avoidance of doubt, any such deductions shall be made
prior to our calculation of the revenue share.</FONT></FONT></p>';
        $pdf->writeHTML($html);
        $pdf->AddPage('P','','A4');
        $html = '
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><u><b>3.
Licensor Representations and Warranties. </b></u></FONT></FONT><br />
<FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">(a)
Owner of Rights: Licensor has the sole, exclusive and unencumbered
ownership of all rights of every kind and character throughout the
universe in and to the Licensed Rights and has clear title to the
material upon which the Images are based. Licensor has the absolute
right to grant to WooGlobe, all rights, licenses and privileges
granted to or vested in WooGlobe under this Agreement. Licensor has
not authorized and will not authorize any other party to exercise any
right or take any action that impairs the rights herein granted to
WooGlobe. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">(b)
Rights Are Clear: Licensor has obtained all clearances and paid all
monies necessary for WooGlobe to exercise its exclusive rights
hereunder and there will not be any other rights to be cleared or any
payments required to be made by WooGlobe as a result of any use of
the Images pursuant to the rights and licenses herein granted
(including without limitation, payments in connection with contingent
participations, residuals, clearance rights, moral rights, union
fees, and music rights). Licensor has not previously entered into any
other agreement in connection with the Images. All of the individuals
and entities connected with the production of the Images, and all of
the individuals and entities whose names, voices, photographs,
likenesses, appearance, works, services and other materials appear or
have been used in the Images, have authorized and approved Licensor’s
use thereof, and WooGlobe shall have the right to use all names,
voices, photographs, likenesses, appearance and performances
contained in the Images in connection with the exploitation,
promotion, and use of the Licensed Rights. It is expressly understood
that WooGlobe has not assumed any obligations under any contracts
entered into by Licensor. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">(c)
No Infringement: No part of the Images, any materials contained
therein, or the exercise by WooGlobe of the Licensed Rights violates
or will violate, or infringes or will infringe, any trademark, trade
name, contract, agreement, copyright (whether common law or
statutory), patent, literary, artistic, music, dramatic, personal,
private, civil, property, privacy or publicity right or &quot;moral
rights of authors&quot; or any other right of any person or entity,
and shall not give rise to a claim of slander or libel. There are no
existing, anticipated, or threatened claims or litigation that would
adversely affect or impair any of the Licensed Rights. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>4.
Termination.</B></U>
Licensor may seek to terminate this Agreement after one year of
signing the agreement; however, this Agreement shall only be
terminable upon the mutual agreement of the parties, the consent of
which may be granted or denied in WooGlobe’s sole discretion. No
termination shall impact any prior license of the Images by WooGlobe
prior to termination, which shall continue in full effect under the
terms of this Agreement. Any
use of the images in promotions or compilations created by WooGlobe
or its affiliates, prior to the termination of this agreement, shall
survive termination and that such use shall not be a breach of any of
Licensor’s rights. WooGlobe may terminate this agreement
immediately with no obligation to the Licensor if Licensor is in
breach of any term of the contract. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>5.
Release and Indemnity.</B></U></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
Licensor hereby agrees to indemnify, release and hold harmless
WooGlobe, its successors, licensees, sub-distributors and assigns,
and the directors, officers, employees, representatives and agents of
each of the foregoing, from any and all claims, demands, causes of
action, damages, judgments, liabilities, losses, costs, expenses, and
attorney’s fees arising out of or resulting from (i) any breach by
Licensor of any warranty, representation or any other provision of
this Agreement, and/or (ii) any claims of or respecting slander,
libel, defamation, invasion of privacy or right of publicity, false
light, infringement of copyright or trademark, or violations of any
other rights arising out of or relating to any use by WooGlobe of the
rights granted under this Agreement. Licensor acknowledges that
WooGlobe is relying on the representations contained in this
Agreement and a breach by Licensor would cause WooGlobe irrevocable
injury and damage that cannot be adequately compensated by damages in
an action at law and Licensor therefore expressly agrees that,
without limiting WooGlobe’s remedies, WooGlobe shall be entitled to
injunctive and other equitable relief. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>6.
No Guarantee Regarding Revenue.</B></U></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
Licensor acknowledges and agrees that WooGlobe is not making any
representation, guarantee or agreement regarding the total amount of
revenue, if any, which will be generated by the Licensed Rights.
Licensor agrees that the judgment of WooGlobe regarding the
exploitation of the Licensed Rights shall be binding and conclusive
upon Licensor and agrees not to make any claim or action that
WooGlobe has not properly exploited the Licensed Rights, that more
revenue could have been earned than was actually earned by the
exploitation of the Licensed Rights, or that any buyout or one-time
payment to Licensor is insufficient in comparison to the revenue
earned by the exploitation of the Licensed Rights. Nothing in this
Agreement shall obligate WooGlobe to actually use or to exploit the
Licensed Rights. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>7.
Publicity/Confidentiality.</B></U></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
Licensor shall not release, disseminate, issue, authorize or cause
the release, dissemination or issuance of any publicity or
information concerning the Licensed Rights, WooGlobe, or the terms of
this Agreement without WooGlobe’s prior specific written consent
(including, without limitation, posting, participating or engaging in
social media discussions, news stories, blogs, reports or responses
thereto), and Licensor shall direct all licensing or other inquiries
relating to the Images solely to WooGlobe. The parties acknowledge
that the terms and provisions of this Agreement are confidential in
nature and agree not to disclose the content or substance thereof to
any third parties other than: (i) the parties’ respective attorneys
and accountants, (ii) as may be necessary to defend Licensor’s
and/or WooGlobe’s rights, and/or (iii) as may be reasonably
required in order to comply with any obligations imposed by the
Agreement, or any statute, ordinance, rule, regulation, other law, or
court order. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>8.
Confidentiality.</B></U>
Licensor acknowledges that the terms and provisions of this Agreement
are confidential in nature and agrees not to disclose the content or
substance thereof to any third parties, other than Licensor\'s
respective attorneys and accountants, or as may be reasonably
required in order to comply with any obligations imposed by this
Agreement. Licensor acknowledges that any unauthorized disclosure,
statement, or publicity may subject WooGlobe to substantial damages,
the exact amount of which are extremely difficult and impractical to
determine, and such unauthorized disclosure shall subject Licensor to
legal liability (including an injunction to prevent further
disclosure). </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>9.
Electronic Signature Agreement.</B></U>
The Licensor agrees that by entering their name into the space
designated above or through the use of any electronic signature
software/service or by any other means, Licensor is agreeing to the
terms of this agreement electronically. The Licensor agrees that the
electronic signature is the legal equivalent of manual signature on
this Agreement and that no certification authority or other third
party verification is necessary to validate Licensor’s e-signature.
The lack of such certification or third party verification will not
in any way affect the enforceability of Licensor’s e-signature or
any resulting contract between Licensor and WooGlobe. </FONT></FONT>
</p>
<p><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>10.
Severability.</B></U></FONT><FONT SIZE=2 STYLE="font-size: 11pt"> If any
provision of this Agreement is illegal and unenforceable in whole or
in part, the remainder of this Agreement shall remain enforceable to
the extent permitted by law. </FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>11.
Miscellaneous.</B></U></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
Licensor acknowledges and warrants that this Agreement has not been
induced by any representation or assurance not contained herein. This
Agreement supersedes and replaces all prior agreements, negotiations
or understandings in connection with the Licensed Rights, including
without limitation any simplified explanation of the terms herein,
and in the event there are any inconsistencies between this
English-language contract and any translations of terms and
conditions, the English-language version shall prevail. This
Agreement contains the entire understanding of the parties and shall
not be modified or amended except by a written document executed by
both parties. If any provision of this Agreement is found to be
unlawful or unenforceable, such provision shall be limited only to
the extent necessary, with all other provisions of the Agreement
remaining in effect. The waiver by either party or consent to a
breach of any provision of this Agreement by the other party shall
not operate or be construed as a waiver of, consent to, or excuse of
any other or subsequent breach by the other party. WooGlobe shall
have the right to assign freely this Agreement, the Licensed Rights
and/or any of WooGlobe’s other rights hereunder to any person or
entity (by operation of law or otherwise). Licensor may not assign
this Agreement or the Licensed Rights. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>12.
Choice of Law/Dispute Resolution.</B></U>
This Agreement shall be deemed to have been executed and delivered
within England, UK, and the rights and obligations of the parties
hereunder shall be construed and enforced in accordance with
English law, without regard to the conflicts of law principles
thereof. Any disputes relating to these terms and conditions shall be
subject to the non-exclusive jurisdiction of the courts of England.
The parties agree to the personal jurisdiction by and venue in
England, and waive any objection to such jurisdiction or venue
irrespective of the fact that a party may not be a resident of
England. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">Except
for WooGlobe’s equitable rights as set forth in this Agreement, the parties hereby
agree to submit any disputes or controversies arising from, relating
to or in connection with this Agreement or the parties’ respective
obligations in connection therewith to binding arbitration in England
in accordance with the English law and only for actual monetary
damages, if any. In
the event of any dispute, Licensor shall not be entitled to, and does
hereby waive all right to, any equitable relief whatsoever, including
the right to rescind its agreement to these Terms, to rescind any
rights granted hereunder, or to enjoin, restrain or interfere in any
manner with the marketing, advertisement, distribution or
exploitation of the Licensed Rights. All rights to recover
consequential, incidental and/or punitive damages are waived by
Licensor.</FONT></FONT></p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>13.
Terms &amp; Conditions</B></U></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><B>.</B></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
Licensor may be required to agree to additional terms and conditions
displayed on the WooGlobe website at www.WooGlobe.com and its
partners in connection with the management of this Agreement and the
payments related thereto, which will be incorporated herein by
reference and subject to change. </FONT></FONT>
</p>
';
        $pdf->writeHTML($html);

        $pdf->AddPage('P','','A4');
        $addres_html='';
        $location_country='';
        $location_region_code='';
        $location_city='';
        $location_continent_name='';
        $location_zip='';
        $location_latitude='';
        $location_longitude='';
        if(isset($api_result['country_name'])){
            $location_country =$api_result['country_name'];
        }
        if(isset($api_result['region_code'])){
            $location_region_code =$api_result['region_code'];
        }
        if(isset($api_result['city'])){
            $location_city =$api_result['city'];
        }
        if(isset($api_result['continent_name'])){
            $location_continent_name =$api_result['continent_name'];
        }
        if(isset($api_result['zip'])){
            $location_zip =$api_result['zip'];
        }
        if(isset($api_result['latitude'])){
            $location_latitude =$api_result['latitude'];
        }
        if(isset($api_result['longitude'])){
            $location_longitude =$api_result['longitude'];
            $addres_html =' <p>Continent : '.$location_continent_name.'</p>
        <p>Country : '.$location_country.'</p>';
        }

        $html = '<p style="text-align: left"><img src="'.root_path().'/admin/assets/assets/img/logo.png" width="80px" height="80px"></p>
        <span style="font-size: 30px">Signing Log</span>
        <p>Document id : '.$unique_key.'</p>
        <p>----------------------------------------------------------------------------------------------------------------------------</p>
        <p>WooGlobe</p>
        <p>Document Name:   '.$unique_key.'_signed.pdf</p>
        <p>Sent on:  '.$data_log["lead_rated_date"].' GMT</p>
        <p>Ip address: '.$data_log["user_ip_address"].'</p>
        <p>User Agent: '.$data_log["user_browser"].'</p>
        <p>Contrat Signed date time:  '.$data_log["contract_signed_datetime"].' GMT</p>'.$addres_html.'<br>
        ';

        $pdf->writeHTML($html);
// reset pointer to the last page
        $pdf->lastPage();
//Close and output PDF document
        $output_file=root_path(). '/uploads/'.$unique_key.'/documents/'.$unique_key.'_signed.pdf';
        if (! file_exists ( dirname ( $output_file ) )) {
            mkdir(dirname($output_file));
        }
        $pdf->Output($output_file, 'F');

    }
    public function rate_lead(){
        include('./vendor/signrequest/src/AtaneNL/SignRequest/Client.php');
        include('./vendor/signrequest/src/AtaneNL/SignRequest/CreateDocumentResponse.php');
        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'video_leads','can_rate');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Rated Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->lead->getLeadById($id);
        if(!$result){

            $response['code'] = 201;
            $response['message'] = 'No category found!';
            $response['error'] = 'No category found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        $dbData = $this->security->xss_clean($this->input->post());
        $this->validation->set_rules('rating_point','Rating Points','trim|required');

        if($dbData['rating_point'] >= 5) {
            $this->validation->set_rules('closing', 'Deal Closing Date', 'trim|required');
            $this->validation->set_rules('revenue', 'Revenue Share', 'trim|required');
        }
        $this->validation->set_message('required','This field is required.');
        if($this->validation->run() === false){

            $fields = array('rating_point','closing','revenue');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }else {


            $lead_id = $dbData['id'];
            $emailData = $this->lead->getLeadMailStatusByLeadId($lead_id);
            $closing = $dbData['closing'];
            $revenue = $dbData['revenue'];
            unset($dbData['id']);
            unset($dbData['closing']);
            unset($dbData['revenue']);
            $date =date("Y/m/d");
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->sess->userdata('adminId');
            $leadData = $this->lead->getLeadById($lead_id, 'vl.*');
            $leadTitle =  $leadData->video_title;
            $leadTitle = preg_replace('/[^a-zA-Z0-9]/', '', $leadTitle);
            $leadVideoUrl=$leadData->video_url;




            if ($dbData['rating_point'] >= 5) {

                $dbData['closing_date'] = $closing;
                $dbData['revenue_share'] = $revenue;
                if(!empty($leadData->staff_id) && isset($leadData->staff_id)|| !empty($leadData->third_party_staff_id) && isset($leadData->third_party_staff_id)){
                    $dbData['status'] = 3;
                    if(!empty($leadData->third_party_staff_id) && isset($leadData->third_party_staff_id)){
                        $thirdpartytitle=$this->lead->getThirdpartyById($leadData->third_party_staff_id);
                        $template = $this->app->getEmailTemplateByCode('welcome_third_party_intro_email');

                        if ($template) {

                            $subject = $thirdpartytitle->name;

                            $emailDbData['video_lead_id'] = $lead_id;
                            $emailDbData['email_sent'] = 1;
                            $emailDbData['mail_sent_time'] = date('Y-m-d H:i:s');
                            $this->db->insert('intro_email', $emailDbData);
                            $ids = array(
                                'video_leads' => $lead_id
                            );

                            $url = $this->data['root'].'video-contract/'.$leadData->slug;
                            //$url = '<p>If you are interested to make a contract with us then <a href="'.$url.'">click here</a></p>';
                            $message = dynStr($template->message, $ids);
                            $message = str_replace('@VIDEO_LEADS.URL',"<a href='".$leadVideoUrl."'>$leadVideoUrl</a>",$message);

                            $message = str_replace('@LINK',$url,$message);
                            $message = str_replace('@REVENUE',$dbData['revenue_share'],$message);
                            $leftover = 100 - $dbData['revenue_share'];
                            $message = str_replace('@LEFT',$leftover,$message);

                            //$this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');

                            $notification = array();

                            $notification['send_datime'] = date('Y-m-d H:i:s');
                            $notification['lead_id'] = $lead_id;
                            $notification['email_template_id'] = $template->id;
                            $notification['email_title'] = $template->title;
                            $notification['ids'] = json_encode($ids);
                            $this->db->insert('email_notification_history',$notification);
                            action_add($lead_id,0,0,$this->sess->userdata('adminId'),1,'Lead converted to deal');
                            $cur_time = date('Y-m-d H:i:s');
                            $date = array();
                            $date['lead_id'] = $lead_id;
                            $action_date = $this->db->insert('lead_action_dates',$date);
                            $insert = $this->lead->action_dates($cur_time,$lead_id);
                            //Extract log data
                            $data_log_query=$this->db->query('SELECT user_browser,user_ip_address,contract_signed_datetime,contract_view_datetime FROM `contract_logs` WHERE lead_id="'.$lead_id.'"');
                            $logs_query=$data_log_query->result();
                            $sent_log_query=$this->db->query('SELECT lead_rated_date  FROM `lead_action_dates` WHERE `lead_id` = "'.$lead_id.'"');
                            $sent_log_result=$sent_log_query->result();
                            $user_query=$this->db->query('SELECT *  FROM `users` WHERE `id` = "'.$leadData->client_id.'"');
                            $user_result=$user_query->result();
                            $video_query=$this->db->query('SELECT *  FROM `videos` WHERE `lead_id` = "'.$lead_id.'"');
                            $video_result=$video_query->result();
                            $data_log['user_browser']='';
                            $data_log['user_ip_address']='';
                            $data_log['contract_signed_datetime']='';
                            $data_log['contract_view_datetime']='';
                            if(isset($logs_query[0])){
                                $data_log['user_browser']=$logs_query[0]->user_browser;
                                $data_log['user_ip_address']=$logs_query[0]->user_ip_address;
                                $data_log['contract_signed_datetime']=$logs_query[0]->contract_signed_datetime;
                                $data_log['contract_view_datetime']=$logs_query[0]->contract_view_datetime;
                            }
                            if(isset($sent_log_result[0])){
                                $data_log['lead_rated_date']=$sent_log_result[0]->lead_rated_date;
                            }
                            if(isset($user_result[0])){
                                $user['full_name']=$user_result[0]->full_name;
                                $user['email']=$user_result[0]->email;
                                $user['paypal_email']=$user_result[0]->paypal_email;
                                $user['phone']=$user_result[0]->mobile;
                                $user['country']=$user_result[0]->country_code;
                                $user['state']=$user_result[0]->state_id;
                                $user['city']=$user_result[0]->city_id;
                                $user['address']=$user_result[0]->address;
                                $user['zip']=$user_result[0]->zip_code;
                            }
                            $data['video_title']=$leadData->video_title;
                            $data['video_url']=$leadData->video_url;
                            if(isset($video_result[0])){
                                $data['question1']=$video_result[0]->question_video_taken;
                                $data['question3']=$video_result[0]->question_when_video_taken;
                            }
                            // set IP address and API access key

                            $access_key = '1d092358b81f0bfd0755bb3b19ac3bbf';

// Initialize CURL:
                            $ch = curl_init('http://api.ipstack.com/'.$data_log['user_ip_address'].'?access_key='.$access_key.'');
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Store the data:
                            $json = curl_exec($ch);
                            curl_close($ch);

// Decode JSON response:
                            $api_result = json_decode($json, true);
                            //Generate Pdf
                            $country_name_va='';
                            if($user['country']){
                                $country_name=explode("-",$user['country']);
                                if(isset($country_name[1])){
                                    $country_name_va=$country_name[1];
                                }
                            }
                            $this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');

                            //$result_pdf=$this->video_signed_pdf($user['full_name'],$user['email'],$user['phone'],$country_name_va,$user['state'],$user['city'],$user['address'],$user['address'],date('Y-m-d',strtotime($data_log['contract_signed_datetime'])),$date,$dbData['revenue_share'],$leadData->unique_key,$data,$data_log,$api_result);

                        }
                    }else{
                        /*  $template = $this->app->getEmailTemplateByCode('welcome_intro_email');

                          if ($template) {

                              $unique_key = $this->lead->getUniqueKey($lead_id);

                              if($unique_key){
                                  $subject = $template->subject.'-'.$unique_key->unique_key;
                              }
                              else{
                                  $subject = $template->subject;
                              }
                              $emailDbData['video_lead_id'] = $lead_id;
                              $emailDbData['email_sent'] = 1;
                              $emailDbData['mail_sent_time'] = date('Y-m-d H:i:s');
                              $this->db->insert('intro_email', $emailDbData);
                              $ids = array(
                                  'video_leads' => $lead_id
                              );

                              $url = $this->data['root'].'video-contract/'.$leadData->slug;
                              //$url = '<p>If you are interested to make a contract with us then <a href="'.$url.'">click here</a></p>';
                              $message = dynStr($template->message, $ids);
                              $message = str_replace('@VIDEO_LEADS.URL',"<a href='".$leadVideoUrl."'>$leadVideoUrl</a>",$message);

                              $message = str_replace('@LINK',$url,$message);
                              $message = str_replace('@REVENUE',$dbData['revenue_share'],$message);
                              $leftover = 100 - $dbData['revenue_share'];
                              $message = str_replace('@LEFT',$leftover,$message);

                              //$this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');

                              $notification = array();

                              $notification['send_datime'] = date('Y-m-d H:i:s');
                              $notification['lead_id'] = $lead_id;
                              $notification['email_template_id'] = $template->id;
                              $notification['email_title'] = $template->title;
                              $notification['ids'] = json_encode($ids);
                              $this->db->insert('email_notification_history',$notification);
                              action_add($lead_id,0,0,$this->sess->urdata('adminId'),1,'Lead converted to deal');*/
                        $cur_time = date('Y-m-d H:i:s');
                        $date = array();
                        $date['lead_id'] = $lead_id;
                        $action_date = $this->db->insert('lead_action_dates',$date);
                        $insert = $this->lead->action_dates($cur_time,$lead_id);
                        //Extract log data
                        $data_log_query=$this->db->query('SELECT user_browser,user_ip_address,contract_signed_datetime,contract_view_datetime FROM `contract_logs` WHERE lead_id="'.$lead_id.'"');
                        $logs_query=$data_log_query->result();
                        $sent_log_query=$this->db->query('SELECT lead_rated_date  FROM `lead_action_dates` WHERE `lead_id` = "'.$lead_id.'"');
                        $sent_log_result=$sent_log_query->result();
                        $user_query=$this->db->query('SELECT *  FROM `users` WHERE `id` = "'.$leadData->client_id.'"');
                        $user_result=$user_query->result();
                        $video_query=$this->db->query('SELECT *  FROM `videos` WHERE `lead_id` = "'.$lead_id.'"');
                        $video_result=$video_query->result();
                        $data_log['user_browser']='';
                        $data_log['user_ip_address']='';
                        $data_log['contract_signed_datetime']='';
                        $data_log['contract_view_datetime']='';
                        if(isset($logs_query[0])){
                            $data_log['user_browser']=$logs_query[0]->user_browser;
                            $data_log['user_ip_address']=$logs_query[0]->user_ip_address;
                            $data_log['contract_signed_datetime']=$logs_query[0]->contract_signed_datetime;
                            $data_log['contract_view_datetime']=$logs_query[0]->contract_view_datetime;
                        }
                        if(isset($sent_log_result[0])){
                            $data_log['lead_rated_date']=$sent_log_result[0]->lead_rated_date;
                        }
                        if(isset($user_result[0])){
                            $user['full_name']=$user_result[0]->full_name;
                            $user['email']=$user_result[0]->email;
                            $user['paypal_email']=$user_result[0]->paypal_email;
                            $user['phone']=$user_result[0]->mobile;
                            $user['country']=$user_result[0]->country_code;
                            $user['state']=$user_result[0]->state_id;
                            $user['city']=$user_result[0]->city_id;
                            $user['address']=$user_result[0]->address;
                            $user['zip']=$user_result[0]->zip_code;
                        }
                        $data['video_title']=$leadData->video_title;
                        $data['video_url']=$leadData->video_url;
                        if(isset($video_result[0])){
                            $data['question1']=$video_result[0]->question_video_taken;
                            $data['question3']=$video_result[0]->question_when_video_taken;
                        }
                        // set IP address and API access key

                        $access_key = '1d092358b81f0bfd0755bb3b19ac3bbf';

                        // Initialize CURL:
                        $ch = curl_init('http://api.ipstack.com/'.$data_log['user_ip_address'].'?access_key='.$access_key.'');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        // Store the data:
                        $json = curl_exec($ch);
                        curl_close($ch);

                        // Decode JSON response:
                        $api_result = json_decode($json, true);
                        //Generate Pdf
                        $country_name_va='';
                        if($user['country']){
                            $country_name=explode("-",$user['country']);
                            if(isset($country_name[1])){
                                $country_name_va=$country_name[1];
                            }
                        }
                        //$this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');
                        //$result_pdf=$this->video_signed_pdf($user['full_name'],$user['email'],$user['phone'],$country_name_va,$user['state'],$user['city'],$user['address'],$user['address'],date('Y-m-d',strtotime($data_log['contract_signed_datetime'])),$dbData['revenue_share'],$leadData->unique_key,$data,$data_log,$api_result);

                    }
                }else{
                    $dbData['status'] = 10;
                    if ($emailData->num_rows() == 0) {

                        $template = $this->app->getEmailTemplateByCode('welcome_intro_email');

                        if ($template) {

                            $unique_key = $this->lead->getUniqueKey($lead_id);

                            if($unique_key){
                                $subject = $template->subject.'-'.$unique_key->unique_key;
                            }
                            else{
                                $subject = $template->subject;
                            }
                            $emailDbData['video_lead_id'] = $lead_id;
                            $emailDbData['email_sent'] = 1;
                            $emailDbData['mail_sent_time'] = date('Y-m-d H:i:s');
                            $this->db->insert('intro_email', $emailDbData);
                            $ids = array(
                                'video_leads' => $lead_id
                            );

                            $url = $this->data['root'].'video-contract/'.$leadData->slug;
                            //$url = '<p>If you are interested to make a contract with us then <a href="'.$url.'">click here</a></p>';
                            $message = dynStr($template->message, $ids);
                            $message = str_replace('@VIDEO_LEADS.URL',"<a href='".$leadVideoUrl."'>$leadVideoUrl</a>",$message);

                            $message = str_replace('@LINK',$url,$message);
                            $message = str_replace('@REVENUE',$dbData['revenue_share'],$message);
                            $leftover = 100 - $dbData['revenue_share'];
                            $message = str_replace('@LEFT',$leftover,$message);

                            $this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');

                            $notification = array();

                            $notification['send_datime'] = date('Y-m-d H:i:s');
                            $notification['lead_id'] = $lead_id;
                            $notification['email_template_id'] = $template->id;
                            $notification['email_title'] = $template->title;
                            $notification['ids'] = json_encode($ids);
                            $this->db->insert('email_notification_history',$notification);
                            action_add($lead_id,0,0,$this->sess->userdata('adminId'),1,'Lead converted to deal');
                            $cur_time = date('Y-m-d H:i:s');
                            $insert = $this->lead->action_dates($cur_time,$lead_id);;

                        }else{
                            action_add($lead_id,0,0,$this->sess->userdata('adminId'),1,'Lead not converted to deal');
                        }
                    }else{
                        action_add($lead_id,0,0,$this->sess->userdata('adminId'),1,'Email Data not found');
                    }
                }
            }
            else{
                $dbData['status'] = 5;
                action_add($lead_id,0,0,$this->sess->userdata('adminId'),1,'Poor rating video');
            }
            $this->db->where('id',$lead_id);
            $this->db->update('video_leads',$dbData);


        }
        header("Content-type: application/json");
        echo json_encode($response);
        exit;

    }

    public function import_leads()
    {
        auth();
        role_permitted(false,'video_leads');

        $url = 'getRecords';
        $param = 'selectColumns=Leads(First Name,Last Name,Email,Video URL,Video Title,Description,Created Time)';
        $zoho = file_get_content($url,$param);
        header("Content-type: application/json");
        $xml = simplexml_load_string($zoho, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        $rows = array();
        $count = 0;

        if(isset($array['result'])){
            if(isset($array['result']['Leads'])){
                if(isset($array['result']['Leads']['row'])){
                    $rows = $array['result']['Leads']['row'];

                    foreach ($rows as $row){
                        echo '<pre>';
                        print_r($row);
                        if(isset($row['FL']) && count($row['FL']) > 0){
                            $result = $this->db->query('
                                SELECT id 
                                FROM video_leads
                                WHERE zoho_lead_id = '.$row['FL'][0].'
                            ');
                            if($result->num_rows() == 0){
                                $dbData['zoho_lead_id'] = $row['FL'][0];
                                $dbData['first_name'] = $row['FL'][3];
                                $dbData['last_name'] = $row['FL'][4];
                                $dbData['email'] = $row['FL'][5];
                                $dbData['created_at'] = $row['FL'][7];
                                $dbData['message'] = $row['FL'][10];
                                $dbData['video_url'] = $row['FL'][12];
                                if(isset($row['FL'][13])){
                                    $dbData['video_title'] = $row['FL'][13];
                                }else{

                                    $dbData['video_title'] = '';
                                }

                                $slug = slug($dbData['video_title'],'video_leads','slug');
                                $dbData['slug'] = $slug;
                                $dbData['status'] = 1;
                                $dbData['updated_at'] = date('Y-m-d H:i:s');
                                $this->db->insert('video_leads',$dbData);
                                $id = $this->db->insert_id();
                                $random = random_string('alnum',8);
                                $random = strtoupper($random);
                                $random = $random.$id;

                                $key = hash("sha1",$random,FALSE);
                                $key = strtoupper($key);
                                $data = array(
                                    'unique_key' => $random,
                                    'encrypted_unique_key' => $key
                                );

                                $this->db->where('id', $id);
                                $this->db->update('video_leads',$data);
                                $count++;
                            }

                        }
                    }

                }
            }
        }
        $this->sess->flashdata('msg',$count.' Leads Imports Successfully!');
        header("Content-type: application/html");
        redirect('video_leads');


    }

    public function update_details(){
        $id = $this->security->xss_clean($this->input->post('id'));
        $check = $this->security->xss_clean($this->input->post('check'));

        $updatedeatils = $this->security->xss_clean($this->input->post('content'));
        $updatedeatils = preg_replace('/\s+/', '', $updatedeatils);
        if($check == 'title'){
            $result = $this->lead->updatedetails($id, $updatedeatils,$check);
        }elseif($check == 'url'){
            $result = $this->lead->updatedetails($id, $updatedeatils,$check);
        }elseif($check =='message'){
            $result = $this->lead->updatedetails($id, $updatedeatils,$check);
        }
        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Raw s3 Data updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['video_id'] = $result;
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Raw s3 Data not updated');
        }
    }

	
}
