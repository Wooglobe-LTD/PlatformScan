<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Aws\S3\S3Client;

class APP_Controller extends CI_Controller {

	public function __construct() {
        parent::__construct();

		$this->data['url'] = base_url();
		$this->data['admin'] = admin_url();
        $this->data['root'] = root_path();
		$this->data['assets'] = asset_url();
		$this->data['image'] = images_url();
		$this->data['upload'] = upload_url();
		$this->data['setting'] = settings();
		$this->data['site_title'] = $this->data['setting']->site_title;
		$this->data['title'] = 'WooGlobe';
		$this->data['menu'] = '';
		$this->data['active'] = '';
		$this->data['body_class'] = '';
		$this->data['banner'] = false;
		$this->data['js'] = array();
		$keywords = 'Trending Videos, Viral Videos, Best Funny Videos, Epic Fail,  Dash Cam Videos, Stunts Completion and Sports Videos, Crashed Videos, Natural Disasters, Fighting Videos, Animal Videos, Emotional Videos';
		$description = 'WooGlobe is a leader in user-generated content connecting creators and distributors around the world.';
		$this->data['keywords'] = $keywords;
		$this->data['description'] = $description;
		$this->data['share_title'] = 'WooGlobe';
		$this->data['share_description'] = $description;
		$this->data['share_url'] = $this->data['url'];
		//$this->data['share_image'] = 'http://wooglobedev.com/images/meta-logo.png';
		$this->data['share_image'] = $this->data['image'].'meta-logo.png';
		//echo $this->data['partner'];exit;
        $this->load->model('app_Model','app');
        $this->data['autocomplete'] = str_replace("'","",json_encode($this->getVideos()));
        $this->load->library('urlmaker');
        $this->load->library('TCPDF-master/Tcpdf');/*
        $this->load->library('Simplepie/library/SimplePie');*/

        //$this->load->library('aws_sdk');
    }
	
	public function email($to,$to_name = '',$from,$from_name = '',$subject,$message,$cc = '',$bcc = '',$replyto = '',$replyto_name = '',$file_to_attach='',$unique_key=''){
		
		$mail = new PHPMailer(true); 
		try {
			//Server settings
			//$mail->SMTPDebug = 2;                                 // Enable verbose debug output
			$mail->isSMTP();                                      // Set mailer to use SMTP
		    $mail->Host = 'smtp.gmail.com';  					 // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
            
            $mail->Username = 'viral@wooglobe.com';         // SMTP username
            
            $mail->Password = 'bbospbhczbaffjxu';

			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to
			$mail->SMTPOptions = array(
					'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
					)
			);
			//Recipients
			$mail->setFrom($from, $from_name);
			$mail->addAddress($to, $to_name);     // Add a recipient
			if(!empty($replyto)){
				$mail->addReplyTo($replyto, $replyto_name);
			}
			if(!empty($cc)){
				$mail->addCC($cc);
			}
			if(!empty($bcc)){
				$mail->addBCC($bcc);
			}
            if(!empty($file_to_attach)){
                $mail->AddAttachment( $file_to_attach , $unique_key.'_signed.pdf' );
            }
			//Content
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = $subject;
			$mail->Body    = $message;
			$mail->AltBody = $message;

			$mail->send();
			return true;
		} catch (Exception $e) {
			//echo 'Message could not be sent.';
			//echo 'Mailer Error: ' . $mail->ErrorInfo;
			return false;
		}
		
	}

	public function profile_upload($name){

		$config['upload_path']          = './uploads/profiles/';
		$config['allowed_types']        = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF';
		$config['encrypt_name']        	= true;
		$config['remove_spaces']        = true;
		$config['file_ext_tolower']     = true;
		$this->load->library('upload', $config);
		$data['code'] = 200;
		$data['message'] = 'Image uploaded successfully';
		$data['error'] = '';
		if ( ! $this->upload->do_upload($name))
		{
			$data['code'] = 201;
			$data['error'] = $this->upload->display_errors();

		}
		else
		{
			$data1 = $this->upload->data();

			$data['url'] = 'uploads/profiles/'.$data1['file_name'];
			$data['name'] =  $data1['file_name'];
		}

		return $data;

	}

    public function upload_local_video($name,$uid){


        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Video uploaded successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $config['upload_path']          = './uploads/videos/raw_videos/';
        $config['allowed_types']        = 'avi|mp4|wmv|flv|mkv|AVI|MP4|FLV|MKV';
        $config['file_name']            = strtolower($uid).'_'.time();
        $config['encrypt_name']        	= false;
        $config['remove_spaces']        = true;
        $config['file_ext_tolower']     = true;
        $config['max_size'] = 0;
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload($name))
        {
            $error = array('error' => $this->upload->display_errors());

            $response['code'] = 201;
            $response['message'] = 'Video not uploaded successfully!';

            $response['error'] = $error;
        }
        else
        {
            $data = $this->upload->data();

            $response['url'] = 'uploads/videos/raw_videos/'.$data['file_name'];
        }


       return $response;

    }

    public function getVideos()
    {
        $videos = $this->app->getVideos();
        return $videos;

    }

    public function get_s3_client() {
        $config = array(
            /*'key' => S3_KEY,
            'secret' => S3_SECRET,*/
            'region' => S3_REGION,
            'version' => S3_VERSION,
            'credentials' => array(
                'key' => S3_KEY,
                'secret'  => S3_SECRET,
            )
        );
        $s3 = S3Client::factory($config);
        return $s3;
    }

    public function upload_file_s3($source_file, $target_file_key, $file_extension, $delete_source = FALSE,
                                 $app_user_id = NULL) {
//        if (is_null($app_user_id)) {
//            $user_id = get_user_id();
//        } else {
//            $user_id = $app_user_id;
//        }

        /**
         *         "323"       => "text/h323",
        "acx"       => "application/internet-property-stream",
        "ai"        => "application/postscript",
        "aif"       => "audio/x-aiff",
        "aifc"      => "audio/x-aiff",
        "aiff"      => "audio/x-aiff",
        "asf"       => "video/x-ms-asf",
        "asr"       => "video/x-ms-asf",
        "asx"       => "video/x-ms-asf",
        "au"        => "audio/basic",
        "avi"       => "video/x-msvideo",
        "axs"       => "application/olescript",
        "bas"       => "text/plain",
        "bcpio"     => "application/x-bcpio",
        "bin"       => "application/octet-stream",
        "bmp"       => "image/bmp",
        "c"         => "text/plain",
        "cat"       => "application/vnd.ms-pkiseccat",
        "cdf"       => "application/x-cdf",
        "cer"       => "application/x-x509-ca-cert",
        "class"     => "application/octet-stream",
        "clp"       => "application/x-msclip",
        "cmx"       => "image/x-cmx",
        "cod"       => "image/cis-cod",
        "cpio"      => "application/x-cpio",
        "crd"       => "application/x-mscardfile",
        "crl"       => "application/pkix-crl",
        "crt"       => "application/x-x509-ca-cert",
        "csh"       => "application/x-csh",
        "css"       => "text/css",
        "dcr"       => "application/x-director",
        "der"       => "application/x-x509-ca-cert",
        "dir"       => "application/x-director",
        "dll"       => "application/x-msdownload",
        "dms"       => "application/octet-stream",
        "doc"       => "application/msword",
        "dot"       => "application/msword",
        "dvi"       => "application/x-dvi",
        "dxr"       => "application/x-director",
        "eps"       => "application/postscript",
        "etx"       => "text/x-setext",
        "evy"       => "application/envoy",
        "exe"       => "application/octet-stream",
        "fif"       => "application/fractals",
        "flr"       => "x-world/x-vrml",
        "gif"       => "image/gif",
        "gtar"      => "application/x-gtar",
        "gz"        => "application/x-gzip",
        "h"         => "text/plain",
        "hdf"       => "application/x-hdf",
        "hlp"       => "application/winhlp",
        "hqx"       => "application/mac-binhex40",
        "hta"       => "application/hta",
        "htc"       => "text/x-component",
        "htm"       => "text/html",
        "html"      => "text/html",
        "htt"       => "text/webviewhtml",
        "ico"       => "image/x-icon",
        "ief"       => "image/ief",
        "iii"       => "application/x-iphone",
        "ins"       => "application/x-internet-signup",
        "isp"       => "application/x-internet-signup",
        "jfif"      => "image/pipeg",
        "jpe"       => "image/jpeg",
        "jpeg"      => "image/jpeg",
        "jpg"       => "image/jpeg",
        "js"        => "application/x-javascript",
        "latex"     => "application/x-latex",
        "lha"       => "application/octet-stream",
        "lsf"       => "video/x-la-asf",
        "lsx"       => "video/x-la-asf",
        "lzh"       => "application/octet-stream",
        "m13"       => "application/x-msmediaview",
        "m14"       => "application/x-msmediaview",
        "m3u"       => "audio/x-mpegurl",
        "man"       => "application/x-troff-man",
        "mdb"       => "application/x-msaccess",
        "me"        => "application/x-troff-me",
        "mht"       => "message/rfc822",
        "mhtml"     => "message/rfc822",
        "mid"       => "audio/mid",
        "mny"       => "application/x-msmoney",
        "mov"       => "video/quicktime",
        "movie"     => "video/x-sgi-movie",
        "mp2"       => "video/mpeg",
        "mp3"       => "audio/mpeg",
        "mpa"       => "video/mpeg",
        "mpe"       => "video/mpeg",
        "mpeg"      => "video/mpeg",
        "mpg"       => "video/mpeg",
        "mpp"       => "application/vnd.ms-project",
        "mpv2"      => "video/mpeg",
        "ms"        => "application/x-troff-ms",
        "mvb"       => "application/x-msmediaview",
        "nws"       => "message/rfc822",
        "oda"       => "application/oda",
        "p10"       => "application/pkcs10",
        "p12"       => "application/x-pkcs12",
        "p7b"       => "application/x-pkcs7-certificates",
        "p7c"       => "application/x-pkcs7-mime",
        "p7m"       => "application/x-pkcs7-mime",
        "p7r"       => "application/x-pkcs7-certreqresp",
        "p7s"       => "application/x-pkcs7-signature",
        "pbm"       => "image/x-portable-bitmap",
        "pdf"       => "application/pdf",
        "pfx"       => "application/x-pkcs12",
        "pgm"       => "image/x-portable-graymap",
        "pko"       => "application/ynd.ms-pkipko",
        "pma"       => "application/x-perfmon",
        "pmc"       => "application/x-perfmon",
        "pml"       => "application/x-perfmon",
        "pmr"       => "application/x-perfmon",
        "pmw"       => "application/x-perfmon",
        "pnm"       => "image/x-portable-anymap",
        "pot"       => "application/vnd.ms-powerpoint",
        "ppm"       => "image/x-portable-pixmap",
        "pps"       => "application/vnd.ms-powerpoint",
        "ppt"       => "application/vnd.ms-powerpoint",
        "prf"       => "application/pics-rules",
        "ps"        => "application/postscript",
        "pub"       => "application/x-mspublisher",
        "qt"        => "video/quicktime",
        "ra"        => "audio/x-pn-realaudio",
        "ram"       => "audio/x-pn-realaudio",
        "ras"       => "image/x-cmu-raster",
        "rgb"       => "image/x-rgb",
        "rmi"       => "audio/mid",
        "roff"      => "application/x-troff",
        "rtf"       => "application/rtf",
        "rtx"       => "text/richtext",
        "scd"       => "application/x-msschedule",
        "sct"       => "text/scriptlet",
        "setpay"    => "application/set-payment-initiation",
        "setreg"    => "application/set-registration-initiation",
        "sh"        => "application/x-sh",
        "shar"      => "application/x-shar",
        "sit"       => "application/x-stuffit",
        "snd"       => "audio/basic",
        "spc"       => "application/x-pkcs7-certificates",
        "spl"       => "application/futuresplash",
        "src"       => "application/x-wais-source",
        "sst"       => "application/vnd.ms-pkicertstore",
        "stl"       => "application/vnd.ms-pkistl",
        "stm"       => "text/html",
        "svg"       => "image/svg+xml",
        "sv4cpio"   => "application/x-sv4cpio",
        "sv4crc"    => "application/x-sv4crc",
        "t"         => "application/x-troff",
        "tar"       => "application/x-tar",
        "tcl"       => "application/x-tcl",
        "tex"       => "application/x-tex",
        "texi"      => "application/x-texinfo",
        "texinfo"   => "application/x-texinfo",
        "tgz"       => "application/x-compressed",
        "tif"       => "image/tiff",
        "tiff"      => "image/tiff",
        "tr"        => "application/x-troff",
        "trm"       => "application/x-msterminal",
        "tsv"       => "text/tab-separated-values",
        "txt"       => "text/plain",
        "uls"       => "text/iuls",
        "ustar"     => "application/x-ustar",
        "vcf"       => "text/x-vcard",
        "vrml"      => "x-world/x-vrml",
        "wav"       => "audio/x-wav",
        "wcm"       => "application/vnd.ms-works",
        "wdb"       => "application/vnd.ms-works",
        "wks"       => "application/vnd.ms-works",
        "wmf"       => "application/x-msmetafile",
        "wps"       => "application/vnd.ms-works",
        "wri"       => "application/x-mswrite",
        "wrl"       => "x-world/x-vrml",
        "wrz"       => "x-world/x-vrml",
        "xaf"       => "x-world/x-vrml",
        "xbm"       => "image/x-xbitmap",
        "xla"       => "application/vnd.ms-excel",
        "xlc"       => "application/vnd.ms-excel",
        "xlm"       => "application/vnd.ms-excel",
        "xls"       => "application/vnd.ms-excel",
        "xlsx"      => "vnd.ms-excel",
        "xlt"       => "application/vnd.ms-excel",
        "xlw"       => "application/vnd.ms-excel",
        "xof"       => "x-world/x-vrml",
        "xpm"       => "image/x-xpixmap",
        "xwd"       => "image/x-xwindowdump",
        "z"         => "application/x-compress",
        "zip"
         */
        $content_types = array();
        $content_types['xlsx'] = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
        $content_types['jpeg'] = "image/jpeg";
        $content_types['jpg'] = "image/jpg";
        $content_types['png'] = "image/png";
        $content_types['pdf'] = "application/pdf";
        $content_types['zip'] = "application/octet-stream";
        $content_types['mp4'] = "video/mp4";
        $content_types['flv'] = "video/x-flv";
        $content_types['3gp'] = "video/3gpp";
        $content_types['mkv'] = "video/x-matroska";
        $content_types['avi'] = "video/x-msvideo";
        $file_extension = strtolower($file_extension);
        switch ($file_extension) {
            case "jpeg": case "jpg": case "png":

            $image_info = getimagesize($source_file);
            $file_type = $image_info['mime'];
            break;

            default:
                $file_type = $content_types[$file_extension];
                break;
        }

        $s3 = $this->get_s3_client();

        $file_data = $s3->putObject(array(
            'Bucket' => S3_BUCKET,
            'Key' => $target_file_key,
            'Body' => fopen($source_file, 'r'),
            'ACL' => 'public-read',
            'ContentType' => $file_type
        ));


        $file_url = $file_data['ObjectURL'];
        if ($delete_source) {
            unlink($source_file);
        }
        return $file_url;
    }
    public function upload_file_s3_new($permission = 'public-read',$source_file, $target_file_key, $file_extension, $delete_source = FALSE,
                                       $app_user_id = NULL)
    {
        if(file_exists($source_file)) {


//        if (is_null($app_user_id)) {
//            $user_id = get_user_id();
//        } else {
//            $user_id = $app_user_id;
//        }

            /**
             *         "323"       => "text/h323",
             * "acx"       => "application/internet-property-stream",
             * "ai"        => "application/postscript",
             * "aif"       => "audio/x-aiff",
             * "aifc"      => "audio/x-aiff",
             * "aiff"      => "audio/x-aiff",
             * "asf"       => "video/x-ms-asf",
             * "asr"       => "video/x-ms-asf",
             * "asx"       => "video/x-ms-asf",
             * "au"        => "audio/basic",
             * "avi"       => "video/x-msvideo",
             * "axs"       => "application/olescript",
             * "bas"       => "text/plain",
             * "bcpio"     => "application/x-bcpio",
             * "bin"       => "application/octet-stream",
             * "bmp"       => "image/bmp",
             * "c"         => "text/plain",
             * "cat"       => "application/vnd.ms-pkiseccat",
             * "cdf"       => "application/x-cdf",
             * "cer"       => "application/x-x509-ca-cert",
             * "class"     => "application/octet-stream",
             * "clp"       => "application/x-msclip",
             * "cmx"       => "image/x-cmx",
             * "cod"       => "image/cis-cod",
             * "cpio"      => "application/x-cpio",
             * "crd"       => "application/x-mscardfile",
             * "crl"       => "application/pkix-crl",
             * "crt"       => "application/x-x509-ca-cert",
             * "csh"       => "application/x-csh",
             * "css"       => "text/css",
             * "dcr"       => "application/x-director",
             * "der"       => "application/x-x509-ca-cert",
             * "dir"       => "application/x-director",
             * "dll"       => "application/x-msdownload",
             * "dms"       => "application/octet-stream",
             * "doc"       => "application/msword",
             * "dot"       => "application/msword",
             * "dvi"       => "application/x-dvi",
             * "dxr"       => "application/x-director",
             * "eps"       => "application/postscript",
             * "etx"       => "text/x-setext",
             * "evy"       => "application/envoy",
             * "exe"       => "application/octet-stream",
             * "fif"       => "application/fractals",
             * "flr"       => "x-world/x-vrml",
             * "gif"       => "image/gif",
             * "gtar"      => "application/x-gtar",
             * "gz"        => "application/x-gzip",
             * "h"         => "text/plain",
             * "hdf"       => "application/x-hdf",
             * "hlp"       => "application/winhlp",
             * "hqx"       => "application/mac-binhex40",
             * "hta"       => "application/hta",
             * "htc"       => "text/x-component",
             * "htm"       => "text/html",
             * "html"      => "text/html",
             * "htt"       => "text/webviewhtml",
             * "ico"       => "image/x-icon",
             * "ief"       => "image/ief",
             * "iii"       => "application/x-iphone",
             * "ins"       => "application/x-internet-signup",
             * "isp"       => "application/x-internet-signup",
             * "jfif"      => "image/pipeg",
             * "jpe"       => "image/jpeg",
             * "jpeg"      => "image/jpeg",
             * "jpg"       => "image/jpeg",
             * "js"        => "application/x-javascript",
             * "latex"     => "application/x-latex",
             * "lha"       => "application/octet-stream",
             * "lsf"       => "video/x-la-asf",
             * "lsx"       => "video/x-la-asf",
             * "lzh"       => "application/octet-stream",
             * "m13"       => "application/x-msmediaview",
             * "m14"       => "application/x-msmediaview",
             * "m3u"       => "audio/x-mpegurl",
             * "man"       => "application/x-troff-man",
             * "mdb"       => "application/x-msaccess",
             * "me"        => "application/x-troff-me",
             * "mht"       => "message/rfc822",
             * "mhtml"     => "message/rfc822",
             * "mid"       => "audio/mid",
             * "mny"       => "application/x-msmoney",
             * "mov"       => "video/quicktime",
             * "movie"     => "video/x-sgi-movie",
             * "mp2"       => "video/mpeg",
             * "mp3"       => "audio/mpeg",
             * "mpa"       => "video/mpeg",
             * "mpe"       => "video/mpeg",
             * "mpeg"      => "video/mpeg",
             * "mpg"       => "video/mpeg",
             * "mpp"       => "application/vnd.ms-project",
             * "mpv2"      => "video/mpeg",
             * "ms"        => "application/x-troff-ms",
             * "mvb"       => "application/x-msmediaview",
             * "nws"       => "message/rfc822",
             * "oda"       => "application/oda",
             * "p10"       => "application/pkcs10",
             * "p12"       => "application/x-pkcs12",
             * "p7b"       => "application/x-pkcs7-certificates",
             * "p7c"       => "application/x-pkcs7-mime",
             * "p7m"       => "application/x-pkcs7-mime",
             * "p7r"       => "application/x-pkcs7-certreqresp",
             * "p7s"       => "application/x-pkcs7-signature",
             * "pbm"       => "image/x-portable-bitmap",
             * "pdf"       => "application/pdf",
             * "pfx"       => "application/x-pkcs12",
             * "pgm"       => "image/x-portable-graymap",
             * "pko"       => "application/ynd.ms-pkipko",
             * "pma"       => "application/x-perfmon",
             * "pmc"       => "application/x-perfmon",
             * "pml"       => "application/x-perfmon",
             * "pmr"       => "application/x-perfmon",
             * "pmw"       => "application/x-perfmon",
             * "pnm"       => "image/x-portable-anymap",
             * "pot"       => "application/vnd.ms-powerpoint",
             * "ppm"       => "image/x-portable-pixmap",
             * "pps"       => "application/vnd.ms-powerpoint",
             * "ppt"       => "application/vnd.ms-powerpoint",
             * "prf"       => "application/pics-rules",
             * "ps"        => "application/postscript",
             * "pub"       => "application/x-mspublisher",
             * "qt"        => "video/quicktime",
             * "ra"        => "audio/x-pn-realaudio",
             * "ram"       => "audio/x-pn-realaudio",
             * "ras"       => "image/x-cmu-raster",
             * "rgb"       => "image/x-rgb",
             * "rmi"       => "audio/mid",
             * "roff"      => "application/x-troff",
             * "rtf"       => "application/rtf",
             * "rtx"       => "text/richtext",
             * "scd"       => "application/x-msschedule",
             * "sct"       => "text/scriptlet",
             * "setpay"    => "application/set-payment-initiation",
             * "setreg"    => "application/set-registration-initiation",
             * "sh"        => "application/x-sh",
             * "shar"      => "application/x-shar",
             * "sit"       => "application/x-stuffit",
             * "snd"       => "audio/basic",
             * "spc"       => "application/x-pkcs7-certificates",
             * "spl"       => "application/futuresplash",
             * "src"       => "application/x-wais-source",
             * "sst"       => "application/vnd.ms-pkicertstore",
             * "stl"       => "application/vnd.ms-pkistl",
             * "stm"       => "text/html",
             * "svg"       => "image/svg+xml",
             * "sv4cpio"   => "application/x-sv4cpio",
             * "sv4crc"    => "application/x-sv4crc",
             * "t"         => "application/x-troff",
             * "tar"       => "application/x-tar",
             * "tcl"       => "application/x-tcl",
             * "tex"       => "application/x-tex",
             * "texi"      => "application/x-texinfo",
             * "texinfo"   => "application/x-texinfo",
             * "tgz"       => "application/x-compressed",
             * "tif"       => "image/tiff",
             * "tiff"      => "image/tiff",
             * "tr"        => "application/x-troff",
             * "trm"       => "application/x-msterminal",
             * "tsv"       => "text/tab-separated-values",
             * "txt"       => "text/plain",
             * "uls"       => "text/iuls",
             * "ustar"     => "application/x-ustar",
             * "vcf"       => "text/x-vcard",
             * "vrml"      => "x-world/x-vrml",
             * "wav"       => "audio/x-wav",
             * "wcm"       => "application/vnd.ms-works",
             * "wdb"       => "application/vnd.ms-works",
             * "wks"       => "application/vnd.ms-works",
             * "wmf"       => "application/x-msmetafile",
             * "wps"       => "application/vnd.ms-works",
             * "wri"       => "application/x-mswrite",
             * "wrl"       => "x-world/x-vrml",
             * "wrz"       => "x-world/x-vrml",
             * "xaf"       => "x-world/x-vrml",
             * "xbm"       => "image/x-xbitmap",
             * "xla"       => "application/vnd.ms-excel",
             * "xlc"       => "application/vnd.ms-excel",
             * "xlm"       => "application/vnd.ms-excel",
             * "xls"       => "application/vnd.ms-excel",
             * "xlsx"      => "vnd.ms-excel",
             * "xlt"       => "application/vnd.ms-excel",
             * "xlw"       => "application/vnd.ms-excel",
             * "xof"       => "x-world/x-vrml",
             * "xpm"       => "image/x-xpixmap",
             * "xwd"       => "image/x-xwindowdump",
             * "z"         => "application/x-compress",
             * "zip"
             */
            $content_types = array();
            $content_types['xlsx'] = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
            $content_types['jpeg'] = "image/jpeg";
            $content_types['jpg'] = "image/jpg";
            $content_types['png'] = "image/png";
            $content_types['pdf'] = "application/pdf";
            $content_types['zip'] = "application/octet-stream";
            $content_types['mp4'] = "video/mp4";
            $content_types['flv'] = "video/x-flv";
            $content_types['3gp'] = "video/3gpp";
            $content_types['mkv'] = "video/x-matroska";
            $content_types['avi'] = "video/x-msvideo";
            $content_types['mov'] = "video/quicktime";
            $file_extension = strtolower($file_extension);
            switch ($file_extension) {
                case "jpeg":
                case "jpg":
                case "png":

                    $image_info = getimagesize($source_file);
                    $file_type = $image_info['mime'];
                    break;

                default:
                    $file_type = $content_types[$file_extension];
                    break;
            }

            $s3 = $this->get_s3_client();

            $file_data = $s3->putObject(array(
                'Bucket' => S3_BUCKET,
                'Key' => $target_file_key,
                'Body' => fopen($source_file, 'r'),
                'ACL' => $permission,
                'ContentType' => $file_type
            ));


            $file_url = $file_data['ObjectURL'];
            if ($delete_source) {
                unlink($source_file);
            }
            return $file_url;
        }else{
            return false;
        }
    }

    public function upload_zip_file2($source_file, $target_file_key, $delete_source = FALSE, $app_user_id = NULL) {

        $s3 = $this->get_s3_client();

        $upload = $s3->upload(S3_BUCKET, $target_file_key, fopen($source_file, 'rb'), 'public-read');
        /*
          $uploader = new MultipartUploader($s3, $source_file, array(
          'bucket' => S3_BUCKET,
          'key' => $target_file_key,
          ));

          $uploader->upload();
         *
         */
        $file_url = $s3->getBaseUrl() . "/" . S3_BUCKET . "/" . $target_file_key;
        if ($delete_source) {
            unlink($source_file);
        }
        return $file_url;
    }

    public function upload_zip_file($source_file, $target_file_key, $delete_source = FALSE, $app_user_id = NULL) {

        $s3 = $this->get_s3_client();

        $upload = $s3->upload(S3_BUCKET, $target_file_key, fopen($source_file, 'rb'), 'public-read');

        $file_url = $s3->getBaseUrl() . "/" . S3_BUCKET . "/" . $target_file_key;
        if ($delete_source) {
            unlink($source_file);
        }
        return $file_url;
    }

    public function delete_file($target_file_key) {
        $s3 = $this->get_s3_client();

        $s3->deleteObject(array(
            'Bucket' => S3_BUCKET,
            'Key' => $target_file_key
        ));
    }

    public function get_aws_base_url() {
        $s3 = $this->get_s3_client();
        $url = $s3->getBaseUrl() . "/" . S3_BUCKET . "/" . S3_BASE_FOLDER . "/";
        return $url;
    }
}
