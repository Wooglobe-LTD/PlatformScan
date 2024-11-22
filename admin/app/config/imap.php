<?php
defined('BASEPATH') || exit('No direct script access allowed');

$config['imap']['encrypto'] = 'SSL';
$config['imap']['validate'] = false;
$config['imap']['host']     = 'imap.gmail.com';
$config['imap']['port']     = 995;
$config['imap']['username'] = 'viral@wooglobe.com';
$config['imap']['password'] = 'bbospbhczbaffjxu';

$config['imap']['folders'] = [
	'inbox'  => 'INBOX',
	'sent'   => 'Sent',
	'trash'  => 'Trash',
	'spam'   => 'Spam',
	'drafts' => 'Drafts',
];

$config['imap']['expunge_on_disconnect'] = false;

$config['imap']['cache'] = [
	'active'     => false,
	'adapter'    => 'file',
	'backup'     => 'file',
	'key_prefix' => 'imap:',
	'ttl'        => 60,
];
