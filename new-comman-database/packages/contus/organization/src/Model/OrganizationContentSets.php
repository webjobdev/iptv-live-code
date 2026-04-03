<?php

namespace Contus\Organization\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationContentSets extends Model {
    use HasFactory;

    protected $fillable = [
        'video_and_sound_quality',
        'resolution',
        'supported_devices',
        'download_devices',
        'no_ad',
        'monitization_plans_id'
    ];

    public function plan() {
        return $this->belongsTo(OrganizationMonitizationPlan::class, 'monitization_plans_id');
    }

    public function monitizationPlan() {
        return $this->belongsTo(OrganizationMonitizationPlan::class, 'monitization_plans_id', 'id');
    }
}
