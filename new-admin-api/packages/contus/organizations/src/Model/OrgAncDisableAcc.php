<?php

namespace Contus\Organizations\Model;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrgAncDisableAcc extends Model {
    use HasFactory;

    protected $table = 'org_announcement_disable_accounts';

    protected $fillable = [
        'organization_id',
        'subject',
        'message',
    ];

     public function organization() {
        return $this->belongsTo(OrganizationDetail::class, 'organization_id', 'id');
    }
}
