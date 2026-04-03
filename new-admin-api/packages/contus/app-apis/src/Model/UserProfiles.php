<?php

namespace Contus\AppApi\Model;

use Contus\Base\Model;
use Contus\Organizations\Model\OrganizationDetail;
use Contus\Organizations\Model\OrgSubscribers;

class UserProfiles extends Model
{
    protected $table = 'subscriber_profiles';

    protected $fillable = ['subscriber_id', 'name', 'avatar'];

    public function subscriber()
    {
        return $this->belongsTo(OrgSubscribers::class, 'subscriber_id', 'id');
    }
    public function organization()
    {
        return $this->belongsTo(OrganizationDetail::class, 'organization_id', 'id');
    }
}
