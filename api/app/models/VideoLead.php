<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class VideoLead extends Eloquent{

    /**
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'client_id',
        'message',
        'video_title',
        'video_url',
        'slug',
        'zoho_lead_id',
        'shotVideo',
        'haveOriginalVideo',
        'status',
        'published_yt',
        'published_fb',
        'published_portal',
        'uploaded_edited_videos',
        'unique_key',
        'encrypted_unique_key',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted',
        'rating_point',
        'rating_comments',
        'closing_date',
        'revenue_share',
        'revenue_share',
        'deal_contact_id',
        'deal_potential_id',
        'information_pending',
        'load_view',
        'sr_uuid',
        'not_interested',
        'terms',
        'document_uuid',
        'file_as_pdf',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'client_id' => 0
    ];
}
