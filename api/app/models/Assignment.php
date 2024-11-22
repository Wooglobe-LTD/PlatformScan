<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class Assignment extends Eloquent{

    /**
     * Category constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $CI =& get_instance();

        $CI->load->model('User');
        $CI->load->model('Event');
    }


        protected $table='mobile_app_assignments';
    protected $fillable=['user_id','event_id','video'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsTo('User');
    }
    public function event()
    {
        return $this->belongsTo('Event');
    }
}
