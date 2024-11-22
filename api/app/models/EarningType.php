<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class EarningType extends Eloquent{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'earning_types';

//    protected $fillable = [
//        'user_id',
//        'video_id',
//        'comment'
//    ];

}
