<?php

namespace Contus\Settings\Model;

use Contus\Base\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GeneralSetting extends Model {

    use HasFactory;

    protected $table = "general_settings";

    protected $fillable = [
        'key',
        'value',
    ];
}
