<?php

namespace Contus\Drm\Model;

use Contus\Base\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DrmProfileDetails extends Model {
    use HasFactory;

    protected $table = 'drm_profile_details';

    protected $fillable = [
        'drm_provider',
        'drm_type',
        'authorization_url',
        'license_persistent',
        'license_limitation',
        'license_duration',
        'hdcp_type',
        'robustness',
        'is_active',
        'fps_certificate',
        'output_protection_level',
        'integration_type',
        'playready_security_level',
        'hardware_drm_required',
        'rooted_devices_allowed',
        'drm_details_id',
    ];

    public function drmprofile() {
        return $this->belongsTo(DrmDetails::class, 'drm_details_id');
    }
}
