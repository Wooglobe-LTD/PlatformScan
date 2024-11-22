<?php
/**
 * Created by PhpStorm.
 * User: T3500
 * Date: 3/15/2018
 * Time: 11:26 AM
 */

class Gmail
{

    const MAILBOX_INBOX	 	= 'INBOX';
    const MAILBOX_ALL	 	= '[Gmail]/All Mail';
    const MAILBOX_DRAFTS 	= '[Gmail]/Drafts';
    const MAILBOX_IMPORTANT = '[Gmail]/Important';
    const MAILBOX_SENT 		= '[Gmail]/Sent Mail';
    const MAILBOX_SPAM		= '[Gmail]/Spam';
    const MAILBOX_STARRED	= '[Gmail]/Starred';
    const MAILBOX_TRASH 	= '[Gmail]/Trash';

    private $client;
    private $token;

    // Constructor
    function __construct() {
        define('SCOPES', implode(' ', array(
            Google_Service_Gmail::MAIL_GOOGLE_COM,
            Google_Service_Drive::DRIVE,
            Google_Service_YouTube::YOUTUBE,
            Google_Service_YouTube::YOUTUBE_READONLY,
            Google_Service_YouTube::YOUTUBE_UPLOAD,
            Google_Service_YouTube::YOUTUBEPARTNER,
            Google_Service_YouTube::YOUTUBEPARTNER_CHANNEL_AUDIT,

        )));
        $this->client = new Google_Client();
        $this->client->setApplicationName(APPLICATION_NAME);
        $this->client->setScopes(SCOPES);
        $this->client->setAuthConfig(CLIENT_SECRET_PATH);
        $this->client->setAccessType('offline');
        $this->client->setApprovalPrompt('force');

        $credentialsPath = CREDENTIALS_PATH;

        if(!file_exists($credentialsPath)){
            redirect('gmail');
        }
        $accessToken = json_decode(file_get_contents($credentialsPath), true);
        $this->token = $accessToken['access_token'];

        $this->client->setAccessToken($accessToken);
        if ($this->client->isAccessTokenExpired()) {
            $refreshTokenSaved = $this->client->getRefreshToken();
            $this->client->fetchAccessTokenWithRefreshToken($refreshTokenSaved);
            //$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($credentialsPath, json_encode($this->client->getAccessToken()));
        }
    }

	function getRawMessages() {

		$service = new Google_Service_Gmail($this->client);

		// Print the labels in the user's account.
		$user = 'me';
		$results = $this->listGmailMessages($service,$user,array(),true);

		return $results;
	}

	function getMessageDetail($message_id) {

		$service = new Google_Service_Gmail($this->client);

		// Print the labels in the user's account.
		$user = 'me';
		$messages = $this->getGmailMessage($service,$user,$message_id);
		$labels = implode(',',$messages->getLabelIds());
		$message_info['uid'] = $message_id;
		$message_info['message_id'] = $message_id;
		$message_info['labels'] = $labels;
		$messages = $messages->getPayload();
		$headers = $messages->getHeaders();

		$messages = $messages->getParts();
		//echo '<pre>';
		//print_r($messages);
		foreach ($headers as $header){
			if ($header->name === 'From') {
				$message_info['from_name'] = $header->value;
				$from = $this->getAddressFromString($header->value);
				$message_info['from_email'] = $from;
			}
			if ($header->name === 'To') {
				$message_info['to_name'] = $header->value;
				$to = $this->getAddressFromString($header->value);
				$message_info['to_email'] = $to;
			}

			if ($header->name === 'Subject') {


				$message_info['subject'] = $header->value;
			}

			if ($header->name === 'Date') {


				$message_info['message_date'] = $header->value;
				$message_info['converted_date_time'] = date('Y-m-d H:i:s',strtotime($header->value));
			}
		}
		foreach ($messages as $message){

			$body = $message['body']->data;

			$san = strtr($body, '-_', '+/');
			$decode = base64_decode($san);
			$message_info['message'] = $decode;
			//echo '<pre>';
			//print_r($decode);
			//exit;
		}

		return $message_info;
	}

    function getMessages() {

        $service = new Google_Service_Gmail($this->client);

        // Print the labels in the user's account.
        $user = 'me';
        $results = $this->listGmailMessages($service,$user,array(),true);
        $messages_information = array();

		/*echo '<pre>';
		print_r($results);
		exit;*/
        foreach ($results as $message){
            $message_info = array();
            $message_info['message_id'] = $message->getId();
            $messages = $this->getGmailMessage($service,$user,$message->getId());
            /*echo '<pre>';
            print_r($messages);
            exit;*/
            $labels = implode(',',$messages->getLabelIds());
            $message_info['labels'] = $labels;
            $messages = $messages->getPayload();
            $headers = $messages->getHeaders();

            $messages = $messages->getParts();
            //echo '<pre>';
            //print_r($messages);
            foreach ($headers as $header){
                if ($header->name === 'From') {
					$message_info['from_name'] = $header->value;
                    $from = $this->getAddressFromString($header->value);
                    $message_info['from_email'] = $from;
                }
                if ($header->name === 'To') {
					$message_info['to_name'] = $header->value;
                    $to = $this->getAddressFromString($header->value);
                    $message_info['to_email'] = $to;
                }

                if ($header->name === 'Subject') {


                    $message_info['subject'] = $header->value;
                }

                if ($header->name === 'Date') {


                    $message_info['message_date'] = $header->value;
					$message_info['converted_message_date'] = date('Y-m-d H:i:s',strtotime($header->value));
                }
            }
            foreach ($messages as $message){

                $body = $message['body']->data;

                $san = strtr($body, '-_', '+/');
                $decode = base64_decode($san);
                $message_info['message'] = $decode;
                //echo '<pre>';
                //print_r($decode);
                //exit;
            }
			/*echo '<pre>';
			print_r($message_info);
			exit;*/
            $messages_information[] = $message_info;

        }
        return $messages_information;
    }

    public function createGmailFilter($userId,$from) {



        $filter = new Google_Service_Gmail_Filter(array(
            'criteria' => array(
                'from' => $from
            ),
            'action' => array(
                'addLabelIds' => array('INBOX','SENT')
            )
        ));




        return $filter;

    }

    function listGmailMessages($userId,$labelIds = array(),$spam = false, $q='') {print "list gmail msg:".date('Y-m-d H:i:s');
        $service = new Google_Service_Gmail($this->client);
        $pageToken = NULL;
        $messages = array();
        $opt_param = array();
        //$opt_param['labelIds'] = $this->listGmailLabels($service,$userId);
        $opt_param['labelIds'] = $labelIds;
        $opt_param['includeSpamTrash'] = $spam;
       //$opt_param['maxResults'] = 500;
        if($q){
            $opt_param['q'] = $q;
        }

        do {print "<br>do while:".date('Y-m-d H:i:s');
            try {print "try at:".date('Y-m-d H:i:s');
                if ($pageToken) {
                    $opt_param['pageToken'] = $pageToken;
                }
                //$opt_param['q'] = 'from='.$from;
                $messagesResponse = $service->users_messages->listUsersMessages($userId, $opt_param);
                if ($messagesResponse->getMessages()) {
                    $messages = array_merge($messages, $messagesResponse->getMessages());
                    $pageToken = $messagesResponse->getNextPageToken();
                }
            } catch (Exception $e) {
                print 'An error occurred: ' . $e->getMessage();
            }
        } while ($pageToken);
        /*$threadIds = array();

        foreach ($messages as $message) {

                if(!in_array($message->getThreadId(),$threadIds)){
                    $threadIds[] = $message->getThreadId();
                }
        }*/
        return $messages;
    }

    function listGmailLabels($service, $userId) {
        $labels = array();
        $labelsIds =array();

        try {
            $labelsResponse = $service->users_labels->listUsersLabels($userId);

            if ($labelsResponse->getLabels()) {
                $labels = array_merge($labels, $labelsResponse->getLabels());
            }

            foreach ($labels as $label) {
                $labelsIds[] = $label->getId();
            }
        } catch (Excetion $e) {
            print 'An error occurred: ' . $e->getMessage();
        }

        return $labelsIds;
    }

    function listGmailThreads($service, $userId,$from,$subject) {
        $threads = array();
        $pageToken = NULL;
        do {
            try {
                $opt_param = array();
                if ($pageToken) {
                    $opt_param['pageToken'] = $pageToken;
                }
                $opt_param['q'] = 'from='.$from;
                $threadsResponse = $service->users_threads->listUsersThreads($userId, $opt_param);
                if ($threadsResponse->getThreads()) {
                    $threads = array_merge($threads, $threadsResponse->getThreads());
                    $pageToken = $threadsResponse->getNextPageToken();
                }
            } catch (Exception $e) {
                print 'An error occurred: ' . $e->getMessage();
                $pageToken = NULL;
            }
        } while ($pageToken);

        /*foreach ($threads as $thread) {
            print 'Thread with ID: ' . $thread->getId() . '<br/>';
        }*/

        return $threads;
    }

    function getGmailMessage($userId, $messageId) {
        $service = new Google_Service_Gmail($this->client);
        try {
            $message = $service->users_messages->get($userId, $messageId);
            //print 'Message with ID: ' . $message->getId() . ' retrieved.';
            return $message;
        } catch (Exception $e) {
            print 'An error occurred: ' . $e->getMessage();
        }
    }

    function constructAuthString($email, $accessToken) {
        return base64_encode("user=$email\1auth=Bearer $accessToken\1\1");
    }

    function oauth2Authenticate($imap, $email, $accessToken) {
        $authenticateParams = array('XOAUTH2',
            $this->constructAuthString($email, $accessToken));
        $imap->sendRequest('AUTHENTICATE', $authenticateParams);
        while (true) {
            $response = "";
            $is_plus = $imap->readLine($response, '+', true);
            if ($is_plus) {
                error_log("got an extra server challenge: $response");
                // Send empty client response.
                $imap->sendRequest('');
            } else {
                if (preg_match('/^NO /i', $response) ||
                    preg_match('/^BAD /i', $response)) {
                    error_log("got failure response: $response");
                    return false;
                } else if (preg_match("/^OK /i", $response)) {
                    return true;
                } else {
                    // Some untagged response, such as CAPABILITY
                }
            }
        }
    }

    function tryImapLogin($email, $accessToken,$selection) {
        /**
         * Make the IMAP connection and send the auth request
         */
        $imap = new Zend_Mail_Protocol_Imap('imap.gmail.com', '993', true);
        if ($this->oauth2Authenticate($imap, $email, $accessToken)) {
            $imap->select($selection);
            return $imap;
        } else {
            echo '<h1>Failed to login</h1>';
        }
    }

    function showInbox($imap) {
        /**
         * Print the INBOX message count and the subject of all messages
         * in the INBOX
         */

        $storage = new Zend_Mail_Storage_Imap($imap);

        return $storage;
    }

    function getAddressFromString($address_string) {
        $address_array  = imap_rfc822_parse_adrlist($address_string, $_SERVER['HTTP_HOST']);
        if (!is_array($address_array) || count($address_array) < 1)
            return FALSE;
        return $address_array[0]->mailbox . '@' . $address_array[0]->host;
    }

    public function getPushNotification(){
        $service = new Google_Service_Gmail($this->client);
        $watchreq = new Google_Service_Gmail_WatchRequest();
        $watchreq->setLabelIds(array('INBOX'));
       // $watchreq->setTopicName('projects/composed-field-201410/topics/wooglobe');
        $watchreq->setTopicName('projects/gmail-api-334006/topics/Gmail');
        $msg = $service->users->watch('me', $watchreq);

return $msg;
        // echo '<pre>';
        // print_r($msg->historyId);
        // exit;
    }

//$accessToken1 = $accessToken['access_token'];
//$gmail_email = 'usman.ali.sarwar.wg@gmail.com';
    /*$debug = false;
    $protocol = new \Anod\Gmail\Imap($debug);
    $gmail = new \Anod\Gmail\Gmail($protocol);
    $gmail->setId("WooGlobe","0.1","Wooglobe","usman.ali.sarwar.wg@gmail.com");

    $gmail->connect();
    $gmail->authenticate($gmail_email, $accessToken1);
    $gmail->sendId();
    $gmail->selectAllMail();
    $messages = new \Zend\Mail\Storage\Imap($protocol);
    echo '<pre>';
    print_r($messages->countMessages());
    echo '<pre>';
    print_r($messages->getMessage(1)->getHeaders());*/
    /*set_include_path('./app/third_party/gmail/');
    require_once 'Zend/Mail/Protocol/Imap.php';
    require_once 'Zend/Mail/Storage/Imap.php';
    $imap = $this->tryImapLogin($gmail_email,$accessToken1,self::MAILBOX_SENT);
    $messages = $this->showInbox($imap);
    echo '<pre>';
    print_r($messages->countMessages());
    for($i = 1; $i <=$messages->countMessages(); $i++ ){
        echo '<pre>';
        print_r($messages->getMessage($i));
    }

    exit;*/

    public function uploadOnDrive($name,$file,$mimType,$folderId){
        $service = new Google_Service_Drive($this->client);


        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => $name,
            'parents' => array($folderId)
        ));
        $content = file_get_contents($file);
        $file = $service->files->create($fileMetadata, array(
            'data' => $content,
            'mimeType' => $mimType,
            'uploadType' => 'multipart',
            'fields' => 'id'));


       return $file->id;
    }

    function listHistory($userId, $startHistoryId) {
        $service = new Google_Service_Gmail($this->client);
        $opt_param = array('startHistoryId' =>$startHistoryId);

        $pageToken = NULL;
        $histories = array();

        do {
            try {
                if ($pageToken) {
                    $opt_param['pageToken'] = $pageToken;
                }
                $historyResponse = $service->users_history->listUsersHistory($userId, $opt_param);
                if ($historyResponse->getHistory()) {
                    $histories = array_merge($histories, $historyResponse->getHistory());
                    $pageToken = $historyResponse->getNextPageToken();
                }
            } catch (Exception $e) {
                print 'An error occurred: ' . $e->getMessage();
            }
        } while ($pageToken);


        return $histories;
    }

}
