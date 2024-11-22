<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class Video extends Eloquent{

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $CI =& get_instance();
        $CI->load->model('VideoComment');
        $CI->load->model('VideoDislike');
        $CI->load->model('VideoLike');
        $CI->load->model('VideoView');
        $CI->load->model('VideoExpression');
    }

    protected $appends = [
        'comments_count',
        'likes_count',
        'dislikes_count',
        'views_count',
        /*'like_expression_count',
        'dislike_expression_count'*/
    ];

    /**
     * @var array
     */
    protected $hidden = ['category_id', 'video_type_id', 'lead_id'];

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'video_type_id',
        'lead_id',
        'title',
        'description',
        'tags',
        'url',
        'thumbnail',
        'status',
        'embed',
        'is_wooglobe_video',
        'slug',
        'created_by',
        'updated_by',
        'deleted',
        'real_deciption_updated',
        'question_video_taken',
        'question_video_context',
        'question_when_video_taken',
        'question_video_information',
        'youtube_id',
        'is_category_verified',
        'is_tags_verified',
        'is_description_verified',
        'is_original_video_verified',
        'is_high_quality',
        'is_real_file',
        'is_complete_file',
        'mrss',
        'facebook_id',
        'bulk',
        'mrss_categories'
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'video_type_id' => 1,
        'status' => 1,
        'is_wooglobe_video' => 1,
        'created_by' => 1,
        'updated_by' => 1,
        'deleted_by' => null,
        'deleted' => 0
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function videoComments()
    {
        return $this->hasMany('VideoComment', 'video_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function videoDislikes()
    {
        return $this->hasMany('VideoDislike');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function videoLikes()
    {
        return $this->hasMany('VideoLike');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function videoViews()
    {
        return $this->hasMany('VideoView');
    }

    /**
     * @return int
     */
    public function getCommentsCountAttribute()
    {
        return $this->videoComments()->count();
    }

    /**
     * @return int
     */
  /*  public function getLikesCountAttribute()
    {
        return $this->videoLikes()->count();
    }*/

    /**
     * @return int
     */
  /* public function getDislikesCountAttribute()
    {
        return $this->videoDislikes()->count();
    }*/

    /**
     * @return int
     */
    public function getViewsCountAttribute()
    {
        return $this->videoViews()->count();
    }
    /*for video expressions*/

    public function VideoExpressions(){

        return $this->hasMany('VideoExpression');
    }
    public function getLikesCountAttribute($value)
    {
       return $this->VideoExpressions()->count('is_like');

    }

    public function getDislikesCountAttribute()
    {
       return $this->VideoExpressions()->count('is_dislike');


    }



    /*for home API*/
    public function getLatestTrendingVideosIntrest($start = 0,$limit = 3){

        $query = 'SELECT v.*,(SELECT COUNT(vv.id) FROM videos_views vv WHERE vv.video_id = v.id) as vcoun
            FROM videos v
            INNER JOIN video_leads vl
            ON v.lead_id = vl.id
            WHERE v.status = 1
             AND v.deleted = 0
             AND v.is_wooglobe_video = 1
             AND (v.youtube_id IS NOT NULL AND v.youtube_id != "")
             
             ORDER BY vcoun DESC';
        if($limit > 0){
            $query .=" LIMIT $start,$limit; ";
        }


        $videos = $this->db->query($query);

        return $videos;
    }



}
