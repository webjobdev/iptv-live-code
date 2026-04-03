<?php

namespace Contus\Settings\Model;

use Contus\Base\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriberSetting extends Model {

    use HasFactory;

    protected $table = "subscriber_setting";

    protected $fillable = [
        'product_type',
        'accessories_name',
        'days',
        'device_type',
        'month_type',
        'price',
        'is_active',
    ];
}
