<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pending_downloads extends APP_Controller
{	
	function send_pending_download_links ($video_id, $admin_id)
	{
		if (!empty($admin_id))
		{
			$admin = $this->db->select('name, email')->from('admin')->where('id', $admin_id)->get()->row();
			
			if (!empty($admin))
			{
				$this->load->model('Video_Model', 'video');
				$video = $this->video->getVideoById($video_id);
				$video_title = $video->title;
						
				$video_folder = $this->db->select('unique_key')->from('videos')->join('video_leads', 'video_leads.id = videos.lead_id')->where('videos.id = '.$video_id)->get();
					
				if (!empty($video_folder->row()->unique_key))
				{	
					$url = str_replace('admin/', '', base_url());
								
					$message = 'The file '.$video_title.' is now ready. Please <a href="'.$url.'uploads/'.$video_folder->row()->unique_key.'/raw_videos/'.$video_id.'.zip">download file by clicking this link</a>';	
					//$this->email('nadirawan17@gmail.com',$admin->name, 'norelpty@viralgreats.com', 'WooGlobe', $video_title.' download is ready ', $message);				
					$this->email($admin->email,$admin->name, 'norelpty@viralgreats.com', 'WooGlobe', 'Your downloads are ready', $message);							
				}
			}		
		}
	}

	function run_genfile_cmd ()
    {
        if ($this->input->post('cmd'))
        {
			$cmd = $this->input->post('cmd');
			//session_destroy();
			//shell_exec($cmd.' > /dev/null 2> /dev/null &');
			exec($cmd.' > /dev/null 2> /dev/null &');
			exit;					
        }
    }
}