<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class Category extends Eloquent{

    /**
     * Category constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $CI =& get_instance();
        $CI->load->model('Video');
        $CI->load->model('Category');
        $CI->load->model('User');
        $CI->load->model('Event');
        $CI->load->model('VideoComment');
    }

    /**
     * @var array
     */
    protected $with = ['subCategories'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subCategories()
    {
        return $this->hasMany('Category', 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function videos()
    {
        return $this->hasMany('Video')->where('is_wooglobe_video',0)->with('videoComments');
    }
    public function videoComments()
    {
       return $this->hasMany('VideoComment', 'video_id');
    }
    public function events()
    {
        return $this->belongsToMany('Event','ma_events');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('User', 'user_interests', 'category_id', 'user_id');
    }
}
