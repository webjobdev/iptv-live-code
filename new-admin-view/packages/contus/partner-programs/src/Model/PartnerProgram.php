<?php

namespace Contus\PartnerProgram\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerProgram extends Model {

    use HasFactory;

    protected $table = 'partner_programs';

    protected $fillable = ['program_name', 'partner_provider', 'partner_code', 'partner_app_logo', 'api_key', 'partner_api_link', 'description'];
}
