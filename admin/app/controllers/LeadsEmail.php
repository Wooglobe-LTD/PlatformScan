<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LeadsEmail extends APP_Controller {

	public function __construct()
	{

		parent::__construct();
		$this->data['active'] = 'leads_email';
		$css = array(
			'bower_components/uikit/css/uikit.almost-flat.min.css',
			'assets/skins/dropify/css/dropify.css',
			'assets/js/vid_up/jquery.fileuploader.min.css',
			'assets/js/vid_up/jquery.fileuploader-theme-dragdrop.css',
			'assets/js/vid_up/font/font-fileuploader.css',
			'assets/js/vid_up/font/font-fileuploader.ttf',
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
			'bower_components/dragula.js/dist/dragula.min.js',
			'assets/js/pages/page_scrum_board.js',
			'assets/js/jquery.charactercounter.min.js',
			'assets/js/vid_up/jquery.fileuploader.min.js',
			'assets/js/leads_email.js',
			//'assets/js/videos.js'getTemplateId,// exclusive partner
			'assets/js/vid_up/custom.js',
			'assets/js/vid_up/jquery.fileuploader.min.js',
			'bower_components/tinymce/tinymce.min.js',
			'assets/js/pages/forms_wysiwyg.js',
			//'assets/js/pages/page_mailbox.js',
		);
		$this->data['commomCss'] = array_merge($css, $this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'], $js);

		$this->data['assess'] = array(
			'list' => role_permitted_html(false, 'leads_email'),
			'can_send' => role_permitted_html(false, 'leads_email', 'can_send'),


		);


	}
	public function index()
	{
		auth();
		role_permitted(false, 'leads_email');
		$this->validation->set_rules('ids', 'Leads Ids', 'trim|required');
		if ($this->validation->run() == FALSE) {
			$this->data['title'] = 'Send Leads Welcome Emails';
			$this->data['content'] = $this->load->view('leads_emails/ids', $this->data, true);
			$this->load->view('common_files/template', $this->data);
		} else {
			$ids = $this->input->post('ids');
			$ids = explode(PHP_EOL, $ids);
			$ids = implode("','",$ids);
			$leads = "
            SELECT vl.* 
            FROM video_leads vl
            WHERE vl.unique_key IN('$ids')
        ";

			$leads = $this->db->query($leads);

			foreach ($leads->result() as $lead){
				$emailData = getEmailTemplateByCode('welcome_email');
				$already_accountemailData = getEmailTemplateByCode('deal_information_received');
				$token_results = $this->db->query('SELECT verify_token,password FROM `users`WHERE `id`= "' . $lead->client_id . '"')->result();
				$token = $token_results[0]->verify_token;
				if(empty($token)){
					$this->load->helper('string');
					$token = random_string('alnum', 20);
					$dbDatatoken['verify_token'] = $token;
					$dbDatatoken['token_expiry_time'] = date('Y-m-d H:i:s',strtotime('+20 days',strtotime(date('Y-m-d H:i:s'))));
					$this->db->where('id', $lead->client_id);
					$this->db->update('users', $dbDatatoken);
					$token_results = $this->db->query('SELECT verify_token,password FROM `users`WHERE `id`= "' . $lead->client_id . '"')->result();
					$token = $token_results[0]->verify_token;
					action_add($result->lead_id, $result->id, 0, $lead->client_id, 1, 'Token Added');
				}
				if($emailData){
					$str = $emailData->message;
					if($lead->unique_key){
						$subject = $emailData->subject.'(Important)-'.$lead->unique_key;
					}
					else{
						$subject = $emailData->subject;
					}
					$ids = array(
						'users' => $lead->client_id
					);

					$message = dynStr($str,$ids);
					// $url = $this->data['root'].'new-login/'.$token -> waqas;
					$url = "www.wooglobe.com".'/new-login/'.$token;
					
					$message = str_replace('@LINK',$url,$message);
					if($_SERVER['HTTP_HOST'] == 'localhost') {
						$file_to_attach = $_SERVER['DOCUMENT_ROOT'].'/uat/uploads/'.$lead->unique_key.'/documents/'.$lead->unique_key. '_signed.pdf';

					}else{
						$file_to_attach = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$lead->unique_key.'/documents/'.$lead->unique_key. '_signed.pdf';

					}
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
			$this->sess->set_flashdata('msg', 'Emails send successfully!');
			redirect('send_lead_emails/');
		}
	}
}
