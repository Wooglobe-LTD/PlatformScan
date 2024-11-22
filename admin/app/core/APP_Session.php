<?php
/**
 * Created by PhpStorm.
 * User: T3500
 * Date: 8/15/2018
 * Time: 11:31 AM
 */

class APP_Session extends CI_Session {

    function __construct() {
        parent::__construct();

        $this->tracker();
    }

  public function tracker() {
        $this->CI->load->helper('url');

        $tracker =& $this->userdata('_tracker');

        if( !IS_AJAX ) {
            $tracker[] = array(
                'uri'   =>      $this->CI->uri->uri_string(),
                'ruri'  =>      $this->CI->uri->ruri_string(),
                'timestamp' =>  time()
            );
        }

        $this->set_userdata( '_tracker', $tracker );
    }


   public function last_page( $offset = 0, $key = 'uri' ) {
        if( !( $history = $this->userdata('_tracker') ) ) {
            return $this->config->item('base_url');
        }

        $history = array_reverse($history);

        if( isset( $history[$offset][$key] ) ) {
            return $history[$offset][$key];
        } else {
            return $this->config->item('base_url');
        }
    }
}