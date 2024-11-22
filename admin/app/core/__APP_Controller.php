<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Aws\S3\S3Client;
use Aws\MediaConvert\MediaConvertClient;
use Aws\Exception\AwsException;

use Shotstack\Client\Api\EndpointsApi;
use Shotstack\Client\Configuration;
use Shotstack\Client\Model\Edit;
use Shotstack\Client\Model\Output;
use Shotstack\Client\Model\Soundtrack;
use Shotstack\Client\Model\Timeline;
use Shotstack\Client\Model\Track;
use Shotstack\Client\Model\Clip;
use Shotstack\Client\Model\TitleAsset;
use Shotstack\Client\Model\ImageAsset;
use Shotstack\Client\Model\VideoAsset;
use Shotstack\Client\Model\Offset;

class APP_Controller extends CI_Controller
{
    protected $apiKey = "6e5xPxuSvC569BlGU6Lm231RcZACX0T992sTmlY6";
    protected $apiUrl = 'https://api.shotstack.io/v1';

    public function __construct()
    {
        parent::__construct();

        $this->data['url'] = base_url();
        $this->data['root'] = root_url();
        $this->data['asset'] = asset_url();
        $this->data['setting'] = settings();
        $this->data['root_path'] = root_path();
        $this->data['site_title'] = $this->data['setting']->site_title;
        $this->data['title'] = 'Admin Panel';
        $this->data['menu'] = '';
        $this->data['active'] = '';

        $this->data['menu_list'] = get_user_menu();


        $this->data['commomCss'] = array(
            'assets/icons/flags/flags.min.css',
            'assets/css/progress_style.css',
            'assets/css/main.min.css',
            'assets/css/themes/themes_combined.min.css',
            'assets/css/custom7.css',
        );
        $this->data['commonJs'] = array(
            'assets/js/common.min.js',
            'assets/js/uikit_custom.min.js',
            'assets/js/altair_admin_common.js',
            'assets/js/progress.js',
            'bower_components/parsleyjs/dist/parsley.min.js',
            'assets/js/pages/forms_validation.js',
            'assets/js/pages/components_preloaders.js',
            'assets/js/pages/components_notifications.min.js',
            'assets/js/custom3.js',
        );

        $this->load->model('app_model', 'app');
        $this->load->library('urlmaker');
        $this->load->library('aws_sdk');
        $this->load->library('TCPDF-master/tcpdf');

    }

    public function email($to,$to_name = '',$from,$from_name = '',$subject,$message,$cc = '',$bcc = '',$replyto = '',$replyto_name = '',$file_to_attach='',$unique_key='')
    {
        require 'vendor/autoload.php';

        $mail = new PHPMailer(true);
        try {
            //Server settings
            //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';                     // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'viral@wooglobe.com';         // SMTP username
            $mail->Password = 'Gl0be*W00';//'W00Gl0be!83';                           // SMTP password
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
            if (!empty($replyto)) {
                $mail->addReplyTo($replyto, $replyto_name);
            }
            if (!empty($inreplyto)) {
                $mail->addReplyTo($to, $to_name);
                $mail->addCustomHeader('In-Reply-To', $inreplyto);
                $mail->addCustomHeader('References', $inreplyto);
            }
            if (!empty($cc)) {
                $mail->addCC($cc);
            }
            if (!empty($bcc)) {
                $mail->addBCC($bcc);
            }
            if(!empty($file_to_attach)){
                $mail->AddAttachment( $file_to_attach , $unique_key.'_signed.pdf' );
            }
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->AltBody = $message;

            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            return false;
        }

    }

    public function upload($name)
    {
        $config['upload_path'] = './../uploads/videos/thumbnail/';
        $config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF';
        $config['encrypt_name'] = true;
        $config['remove_spaces'] = true;
        $config['file_ext_tolower'] = true;
        $this->load->library('upload', $config);
        $data['code'] = 200;
        $data['message'] = 'Image uploaded successfully';
        $data['error'] = '';
        if (!$this->upload->do_upload($name)) {
            $data['code'] = 201;
            $data['error'] = $this->upload->display_errors();

        } else {
            $data1 = $this->upload->data();

            $data['url'] = 'uploads/videos/thumbnail/' . $data1['file_name'];
            $data['name'] = $data1['file_name'];
        }

        return $data;

    }

    public function delete_yt_fb_mrss_thumbnail($key, $directory)
    {

        $path = $_SERVER['DOCUMENT_ROOT'] . "/uploads/$key/edited_videos/$directory/thumbnail/";
        $files = glob($path . "*"); // get all file names

        foreach ($files as $file) { // iterate files
            if (is_file($file))
                unlink($file); // delete file
        }

        return true;
    }


    public function upload_edited_thumbnail($name, $key, $directory)
    {
        $this->delete_yt_fb_mrss_thumbnail($key, $directory);

        $config['upload_path'] = "./../uploads/$key/edited_videos/$directory/thumbnail/";
        $config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF';
        $config['encrypt_name'] = true;
        $config['remove_spaces'] = true;
        $config['file_ext_tolower'] = true;
        //$this->load->library('upload', $config);
        $this->load->library('upload');
        $this->upload->initialize($config);
        $data['code'] = 200;
        $data['message'] = 'Image uploaded successfully';
        $data['error'] = '';
        if (!$this->upload->do_upload($name)) {
            $data['code'] = 201;
            $data['error'] = $this->upload->display_errors();

        } else {
            $data1 = $this->upload->data();

            $data['url'] = "uploads/$key/edited_videos/$directory/thumbnail/" . $data1['file_name'];
            $data['name'] = $data1['file_name'];
            $data['data1'] = $data1;
        }

        return $data;

    }


    public function signature()
    {
        $username = "usman.ali.sarwar.wg@gmail.com";
        $password = "Usmanali6624";
        $integrator_key = "1fdc4d8d-42d4-4c0a-8063-1428389edfe3";

        // change to production (www.docusign.net) before going live
        $host = "https://demo.docusign.net/restapi";

        // create configuration object and configure custom auth header
        $config = new DocuSign\eSign\Configuration();
        $config->setHost($host);
        $config->addDefaultHeader("X-DocuSign-Authentication", "{\"Username\":\"" . $username . "\",\"Password\":\"" . $password . "\",\"IntegratorKey\":\"" . $integrator_key . "\"}");

        // instantiate a new docusign api client
        $apiClient = new DocuSign\eSign\ApiClient($config);
        $accountId = null;

        try {
            //*** STEP 1 - Login API: get first Account ID and baseURL
            $authenticationApi = new DocuSign\eSign\Api\AuthenticationApi($apiClient);
            $options = new \DocuSign\eSign\Api\AuthenticationApi\LoginOptions();
            $loginInformation = $authenticationApi->login($options);

            if (isset($loginInformation['login_accounts']) && count($loginInformation['login_accounts']) > 0) {
                $documentFileName = "./../../test.pdf";
                $documentName = "Sign Me";
                $envelop_summary = null;
                $loginAccount = $loginInformation['login_accounts'][0];

                $host = $loginAccount->getBaseUrl();
                $host = explode("/v2", $host);
                $host = $host[0];

                // UPDATE configuration object
                $config->setHost($host);

                // instantiate a NEW docusign api client (that has the correct baseUrl/host)
                $apiClient = new DocuSign\eSign\ApiClient($config);

                if (isset($loginInformation)) {
                    $accountId = $loginAccount->getAccountId();
                    if (!empty($accountId)) {
                        //*** STEP 2 - Signature Request from a Template
                        // create envelope call is available in the EnvelopesApi
                        $envelopeApi = new DocuSign\eSign\Api\EnvelopesApi($apiClient);
                        // assign recipient to template role by setting name, email, and role name.  Note that the
                        // template role name must match the placeholder role name saved in your account template.
                        $document = new DocuSign\eSign\Model\Document();
                        $document->setDocumentBase64(base64_encode(file_get_contents(__DIR__ . $documentFileName)));
                        $document->setName($documentName);
                        $document->setDocumentId("1");
                        // Create a |SignHere| tab somewhere on the document for the recipient to sign
                        $signHere = new \DocuSign\eSign\Model\SignHere();
                        $signHere->setXPosition("100");
                        $signHere->setYPosition("100");
                        $signHere->setDocumentId("1");
                        $signHere->setPageNumber("1");
                        $signHere->setRecipientId("1");
                        $tabs = new DocuSign\eSign\Model\Tabs();
                        $tabs->setSignHereTabs(array($signHere));
                        $signer = new \DocuSign\eSign\Model\Signer();
                        $signer->setEmail('maliks_usman786@yahoo.com');
                        $signer->setName('Usman Ali');
                        $signer->setRecipientId("1");


                        $signer->setTabs($tabs);
                        // Add a recipient to sign the document
                        $recipients = new DocuSign\eSign\Model\Recipients();
                        $recipients->setSigners(array($signer));
                        $envelop_definition = new DocuSign\eSign\Model\EnvelopeDefinition();
                        $envelop_definition->setEmailSubject("[DocuSign PHP SDK] - Please sign this doc");
                        // set envelope status to "sent" to immediately send the signature request
                        $envelop_definition->setStatus('sent');
                        $envelop_definition->setRecipients($recipients);
                        $envelop_definition->setDocuments(array($document));
                        $options = new \DocuSign\eSign\Api\EnvelopesApi\CreateEnvelopeOptions();
                        $options->setCdseMode(null);
                        $options->setMergeRolesOnDraft(null);
                        $envelop_summary = $envelopeApi->createEnvelope($accountId, $envelop_definition, $options);
                        echo '<pre>';
                        print_r($envelop_summary);

                    }
                }
            }
        } catch (DocuSign\eSign\ApiException $ex) {
            echo "Exception: " . $ex->getMessage() . "\n";
        }
        echo '<pre>';
        print_r('end');
        exit;
    }

    public function email_reply($to, $to_name, $from, $message, $inreplyto = '')
    {

        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';                     // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'viral@wooglobe.com';         // SMTP username
            $mail->Password = 'Gl0be*W00';//'W00Gl0be!83';                           // SMTP password
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
            $mail->setFrom($from);
            $mail->addAddress($to, $to_name);     // Add a recipient
            if (!empty($inreplyto)) {
                $mail->addCustomHeader('In-Reply-To:', $inreplyto);
                $mail->addCustomHeader('References:', $inreplyto);
            }


            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Body = $message;
            $mail->AltBody = $message;

            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            return false;
        }

    }

    public function get_s3_client()
    {
        $config = array(
            /*'key' => S3_KEY,
            'secret' => S3_SECRET,*/
            'region' => S3_REGION,
            'version' => S3_VERSION,
            'credentials' => array(
                'key' => S3_KEY,
                'secret' => S3_SECRET,
            )
        );
        $s3 = S3Client::factory($config);
        return $s3;
    }

    public function upload_file_s3($source_file, $target_file_key, $file_extension, $delete_source = FALSE,
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
                'ACL' => 'public-read',
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

    public function upload_zip_file2($source_file, $target_file_key, $delete_source = FALSE, $app_user_id = NULL)
    {

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

    public function upload_zip_file($source_file, $target_file_key, $delete_source = FALSE, $app_user_id = NULL)
    {

        $s3 = $this->get_s3_client();

        $upload = $s3->upload(S3_BUCKET, $target_file_key, fopen($source_file, 'rb'), 'public-read');

        $file_url = $s3->getBaseUrl() . "/" . S3_BUCKET . "/" . $target_file_key;
        if ($delete_source) {
            unlink($source_file);
        }
        return $file_url;
    }

    public function delete_file($target_file_key)
    {
        $s3 = $this->get_s3_client();

        $s3->deleteObject(array(
            'Bucket' => S3_BUCKET,
            'Key' => $target_file_key
        ));
    }

    public function get_aws_base_url()
    {
        $s3 = $this->get_s3_client();
        $url = $s3->getBaseUrl() . "/" . S3_BUCKET . "/" . S3_BASE_FOLDER . "/";
        return $url;
    }

    public function addVideoWaterMark($s3_source,$source){
        $config = Configuration::getDefaultConfiguration()
            ->setHost($this->apiUrl)
            ->setApiKey('x-api-key', $this->apiKey);

        $client = new EndpointsApi(null, $config);
        $clips = [];
        $start = 0;
        $length = 4;

        // To Get Video Length
        include_once('./app/third_party/getid3/getid3/getid3.php');
        $getID3 = new getID3;
        $file = $getID3->analyze($source);
        if(isset($file['filesize']) && $file['filesize'] > 0){
            $length = $file['playtime_seconds'];
        }


        // To Get Video Length;
        $imageAsset = new ImageAsset();
        $imageAsset->setSrc('https://www.wooglobe.com/images/logo.png');
        $offet = New Offset();
        $offet->setX(-1);
        $offet->setY(1);
        $watermark = new Clip();
        $watermark
            ->setAsset($imageAsset)
            ->setStart(0)
            ->setLength($length)
            ->setPosition('bottomRight')
            ->setEffect('zoomIn')
            ->setFit('none')
            ->setScale(0.5)
            //->setOffset($offet)
            ->setOpacity(0.35);

        $track1 = new Track();
        $track1
            ->setClips([$watermark]);
        // Video - bottom layer (track2)
        $videoAsset = new VideoAsset();
        $videoAsset
            ->setSrc($s3_source);

        $video = new Clip();
        $video
            ->setAsset($videoAsset)
            ->setStart(0)
            ->setLength($length);

        $track2 = new Track();
        $track2
            ->setClips([$video]);
        $timeline = new Timeline();
        $timeline
            ->setBackground("#000000")
            ->setTracks([$track1, $track2]); // Put track1 first to go above track2

        $output = new Output();
        $output
            ->setFormat('mp4')
            ->setResolution('sd');
        $edit = new Edit();
        $edit
            ->setTimeline($timeline)
            ->setOutput($output);

        try {
            //$response = $client->postRender($edit)->getResponse();
            $response = $client->getRender('664bfe75-4455-46b5-a64a-ca8f62081fd5')->getResponse();
        } catch (Exception $e) {
            die('Request failed: ' . $e->getMessage());
        }


        echo '<pre>';
        print_r($response);
        exit;
    }

    public function awsMediaConvert($s3_source,$source,$uId){
        include_once('./app/third_party/getid3/getid3/getid3.php');
        $getID3 = new getID3;
        $file = $getID3->analyze($source);
        $bitrates = 0;
        $seconds = 0;
        $resolution_x = 0;
        $resolution_y = 0;
        if(isset($file['filesize']) && $file['filesize'] > 0){
            $bitrates = $file['bitrate'];
            $seconds = ($file['playtime_seconds'] * 1000);
            if(isset($file['video']['rotate']) && $file['video']['rotate'] == 90)
            {
                $resolution_x = $file['video']['resolution_y'];
                $resolution_y = $file['video']['resolution_x'];

            }
            else
            {
                $resolution_x = $file['video']['resolution_x'];
                $resolution_y = $file['video']['resolution_y'];
            }
        }

        $per20 = ((20/100)*$resolution_x);
        $per3 = ((3/100)*$resolution_x);
        $perH5 = ((5/100)*$resolution_y);
        $factor = @(722/$per20);
        $waterMarkW = (int)(722/$factor);
        $waterMarkH = (int)(170/$factor);
        $videoTemplate = json_decode(file_get_contents('./video_tem/watermark.json'),true);
        $videoTemplate['Settings']['OutputGroups'][0]['Outputs'][0]['VideoDescription']['CodecSettings']['H264Settings']['MaxBitrate'] = $bitrates;
        $videoTemplate['Settings']['OutputGroups'][0]['Outputs'][0]['VideoDescription']['CodecSettings']['H264Settings']['Bitrate'] = $bitrates;
        $videoTemplate['Settings']['OutputGroups'][0]['Outputs'][0]['NameModifier'] = "_$uId";
        //unset($videoTemplate['Settings']['OutputGroups'][0]['Outputs'][0]['NameModifier']);
        $videoTemplate['Settings']['Inputs'][0]['ImageInserter']['InsertableImages'][0]['Width'] = $waterMarkW;
        $videoTemplate['Settings']['Inputs'][0]['ImageInserter']['InsertableImages'][0]['Height'] = $waterMarkH;
        $videoTemplate['Settings']['Inputs'][0]['ImageInserter']['InsertableImages'][0]['ImageX'] = (int)(($resolution_x - $waterMarkW) - $per3);
        $videoTemplate['Settings']['Inputs'][0]['ImageInserter']['InsertableImages'][0]['ImageY'] = (int)$perH5;
        $videoTemplate['Settings']['Inputs'][0]['ImageInserter']['InsertableImages'][0]['Duration'] = $seconds;
        $videoTemplate['Settings']['Inputs'][0]['FileInput'] = $s3_source;
        //$destination = str_replace('//','/',$videoTemplate['Settings']['OutputGroups'][0]['OutputGroupSettings']['FileGroupSettings']['Destination']);

        $destination = str_replace('./../','',$source);
        $destination = explode('/',$destination);
        unset($destination[count($destination)-1]);
        $destination = implode('/',$destination).'/';
        //$destination = explode('.',$destination);
        //$destination =$destination[0];
        $destination = str_replace('raw_videos','watermark_video',$destination);
        $destination = $videoTemplate['Settings']['OutputGroups'][0]['OutputGroupSettings']['FileGroupSettings']['Destination'].$destination;
        $videoTemplate['Settings']['OutputGroups'][0]['OutputGroupSettings']['FileGroupSettings']['Destination'] = $destination;
        /*echo '<pre>';
        print_r($videoTemplate['Settings']['OutputGroups'][0]['OutputGroupSettings']['FileGroupSettings']['Destination']);
        exit;*/
        $client = new Aws\MediaConvert\MediaConvertClient([
            'credentials' => array(
                'key' => S3_KEY,
                'secret' => S3_SECRET,
            ),
            'version' => '2017-08-29',
            'region' => 'us-west-2'
        ]);
        $result = $client->describeEndpoints([]);
        $single_endpoint_url = $result['Endpoints'][0]['Url'];
        $mediaConvertClient = new MediaConvertClient([
            'version' => '2017-08-29',
            'region' => 'us-west-2',
            'credentials' => array(
                'key' => S3_KEY,
                'secret' => S3_SECRET,
            ),
            'endpoint' => $single_endpoint_url
        ]);
        $result = false;
        try {
            $result = $mediaConvertClient->createJob([
                "Role" => "arn:aws:iam::819785585059:role/VideoEditor",
                "Settings" => $videoTemplate['Settings'], //JobSettings structure
                "Queue" => "arn:aws:mediaconvert:us-west-2:819785585059:queues/Default",
                "UserMetadata" => [
                    "Customer" => "Amazon"
                ],
            ]);
            $result = $result->get('Job');
            $result = $result['Id'];
            //$result = true;
        } catch (AwsException $e) {
            // output error message if fails
            //echo $e->getMessage();
            //echo "\n";
            /* echo '<pre>';
             print_r($e->getMessage());
             exit;*/
            $this->db->insert('api_error',['uid'=>$uId,'log'=>$e->getMessage()]);
            echo '<pre>';
            print_r($e->getMessage());
        }

        return $result;

    }

    public function awsMediaConvertGetJob($jobId,$name){

        $client = new Aws\MediaConvert\MediaConvertClient([
            'credentials' => array(
                'key' => S3_KEY,
                'secret' => S3_SECRET,
            ),
            'version' => '2017-08-29',
            'region' => 'us-east-2'
        ]);
        $result = $client->describeEndpoints([]);
        $single_endpoint_url = $result['Endpoints'][0]['Url'];
        $mediaConvertClient = new MediaConvertClient([
            'version' => '2017-08-29',
            'region' => 'us-east-2',
            'credentials' => array(
                'key' => S3_KEY,
                'secret' => S3_SECRET,
            ),
            'endpoint' => $single_endpoint_url
        ]);
        $result = false;

        try {

            $res = $mediaConvertClient->getJob([
                "Id" => $jobId,
            ]);

            $res = $res->get('Job');

            if(isset($res['Status']) && $res['Status'] == 'COMPLETE'){
                $outputFile = str_replace('s3://wooglobe/','',$res['Settings']['OutputGroups'][0]['OutputGroupSettings']['FileGroupSettings']['Destination']);
                $s3 = $this->get_s3_client();

                $result = $s3->putObjectAcl(array(
                    'Bucket' => S3_BUCKET,
                    'Key' => $outputFile.$name,
                    'ACL' => 'public-read',
                ));

                $result = $s3->getObjectUrl(S3_BUCKET,$outputFile.$name);


            }
            //$result = true;
        } catch (AwsException $e) {
            // output error message if fails
            //echo $e->getMessage();
            //echo "\n";
            $this->db->insert('api_error',['uid'=>$name,'log'=>$e->getMessage()]);
             echo '<pre>';
             print_r($e->getMessage());
        }

        return $result;

    }

    public function awsMediaConvertGetJob2($name){


        $result = false;

        try {



                $outputFile = $name;
                $s3 = $this->get_s3_client();

                $result = $s3->putObjectAcl(array(
                    'Bucket' => S3_BUCKET,
                    'Key' => $outputFile,
                    'ACL' => 'public-read',
                ));

                $result = $s3->getObjectUrl(S3_BUCKET,$outputFile);


            //$result = true;
        } catch (AwsException $e) {
            // output error message if fails
            //echo $e->getMessage();
            //echo "\n";
            $this->db->insert('api_error',['uid'=>$name,'log'=>$e->getMessage()]);
            echo '<pre>';
            print_r($e->getMessage());
        }

        return $result;

    }
}
