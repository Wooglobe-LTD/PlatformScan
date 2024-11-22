<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class Earning extends Eloquent{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'earnings';

    /**
     * Earning constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $CI = & get_instance();
        $CI->load->model('User');
        $CI->load->model('EarningType');
    }


//    protected $fillable = [
//        'user_id',
//        'video_id',
//        'comment'
//    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User', 'partner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function earningType()
    {
        return $this->belongsTo('EarningType');
    }

}
