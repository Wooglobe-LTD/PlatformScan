<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class VideoComment extends Eloquent{
//    use \Illuminate\Database\Eloquent\SoftDeletes;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $CI =& get_instance();
        $CI->load->model('Video');
        $CI->load->model('User');
    }

    protected $table = 'videos_comments';
    protected $with = 'user';

    const UPDATED_AT = null;
//    protected $dates = ['deleted'];
//    const DELETED_AT = 'deleted';

    protected $fillable = [
        'user_id',
        'video_id',
        'comment'
    ];


    public function user()
    {
        return $this->belongsTo('User');
    }
}
