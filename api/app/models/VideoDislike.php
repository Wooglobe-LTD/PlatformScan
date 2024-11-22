<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class VideoDislike extends Eloquent{

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $CI =& get_instance();
        $CI->load->model('Video');
    }

    protected $table = 'videos_dislike';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'video_id'
    ];

}
