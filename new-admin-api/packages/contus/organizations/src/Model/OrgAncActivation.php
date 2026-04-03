<?php

namespace Contus\Organizations\Model;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrgAncActivation extends Model {
    use HasFactory;

    protected $table = 'org_announcement_activations';

    protected $fillable = [
        'organization_id',
        'subject',
        'message',
        'activation_agree'
    ];

    public function organization() {
        return $this->belongsTo(OrganizationDetail::class, 'organization_id', 'id');
    }
}
