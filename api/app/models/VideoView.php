<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class VideoView extends Eloquent{

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $CI =& get_instance();
        $CI->load->model('Video');
    }

    protected $table = 'videos_views';

    public $timestamps = false;

    protected $fillable = [
        'ip_address',
        'user_id',
        'video_id'
    ];

}
