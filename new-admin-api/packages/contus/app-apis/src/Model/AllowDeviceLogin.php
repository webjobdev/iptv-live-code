<?php

namespace Contus\AppApi\Model;

use Contus\Base\Model;
use Contus\Organizations\Model\OrgSubscribers;
use Contus\Organizations\Model\Organization;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AllowDeviceLogin extends Model
{

    use HasFactory;

    protected $table = 'allow_device_logins';
    protected $fillable = ['subscriber_id', 'device_type', 'device_id', 'last_login_at'];

    public function orgSubscriber()
    {
        return $this->belongsTo(OrgSubscribers::class, 'subscriber_id', 'id');
    }
}
