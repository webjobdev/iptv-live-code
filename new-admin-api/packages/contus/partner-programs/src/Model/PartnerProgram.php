<?php

namespace Contus\PartnerProgram\Model;

use Carbon\Carbon;
use Contus\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerProgram extends Model {

    use HasFactory;

    protected $table = 'partner_programs';

    protected $fillable = ['program_name', 'partner_provider', 'partner_code', 'partner_app_logo', 'api_key', 'partner_api_link', 'description', 'created_by'];

    protected $appends = ['partner_app_logo_url', 'formatted_updated_at'];

    function getPartnerAppLogoUrlAttribute() {
        return asset($this->partner_app_logo);
    }

    public function user() {
        return $this->hasMany(User::class, 'id', 'created_by');
    }

    public function getFormattedUpdatedAtAttribute() {
        return $this->updated_at
            ? Carbon::parse($this->updated_at)->format('d M Y')
            : null;
    }
}
