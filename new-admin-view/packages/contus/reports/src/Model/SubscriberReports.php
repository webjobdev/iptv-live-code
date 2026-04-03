<?php

namespace Contus\Reports\Model;

use Contus\Base\Model;
use Contus\Organizations\Model\OrganizationDetail;

class SubscriberReports extends Model {
    protected $table = 'subscriber_reports';

    protected $fillable = [
        'report_name',
        'report_type',
        'organization',
        'report_fields',
        'report_filter',
        'generate',
    ];

    public function organization(){
        $data = $this->belongsTo(OrganizationDetail::class, 'organization',  'id');
        return $data;
    }

    public function GetOrganization(){
        $data = $this->belongsTo(OrganizationDetail::class, 'organization',  'id');
        return $data;
    }
}