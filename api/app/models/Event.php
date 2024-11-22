<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class Event extends Eloquent{

    /**
     * Category constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $CI =& get_instance();

        $CI->load->model('User');
        $CI->load->model('Category');
        $CI->load->model('Assignment');
    }
    protected $table='ma_events';




    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function category()
    {
        return $this->belongsTo('Category');
    }

}
