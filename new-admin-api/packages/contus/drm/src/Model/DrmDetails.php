<?php

namespace Contus\Drm\Model;

use Contus\Base\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DrmDetails extends Model {
    use HasFactory;

    protected $fillable = [
        'drm_name',
        'drm_provider',
        'px_value',
        'account_id',
        'site_key',
        'access_key',
        'publish_now',
        'drm_id'
    ];

    public function drmprofile() {
        return $this->hasOne(DrmProfileDetails::class,'drm_details_id', 'id');
    }
}
