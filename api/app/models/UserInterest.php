<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class UserInterest extends Eloquent{
    /**
     * Category constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $CI =& get_instance();
        //$CI->load->model('Video');
    }
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'category_id',
    ];

}
