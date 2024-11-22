<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class Country extends Eloquent{

    /**
     * Category constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $CI =& get_instance();

        $CI->load->model('User');
    }

    /**
     * @var array
     */
    protected $table = 'countries';


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('User');
    }
}
