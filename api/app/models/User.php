<?php
use \Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent{

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $CI =& get_instance();
        $CI->load->model('Earning');
        $CI->load->model('Category');
    }

    protected $hidden = ['password'];

    protected $with = [
        'earnings', 'interested_categories'
    ];

    protected $fillable = [
        'full_name',
        'username',
        'password',
        'gender',
        'dob',
        'email',
        'paypal_email',
        'mobile',
        'country_code',
        'country_id',
        'state_id',
        'city_id',
        'address',
        'address2',
        'zip_code',
        'picture',
        'status',
        'interest',
        'role_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'bulk_user',
        'verify_token',
        'token_expiry_time',
        'social_account_id'
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'country_code' => 0,
        "address2" => "",
        "bulk_user" => 0
    ];


    /**
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = sha1($value);
    }

    public function earnings()
    {
        return $this->hasMany('Earning', 'partner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function interested_categories()
    {
        return $this->belongsToMany('Category', 'user_interests', 'user_id','category_id');
    }
    /*video expression */
    public function video_expression(){

        return $this->hasMany('video_expression');
    }


}
