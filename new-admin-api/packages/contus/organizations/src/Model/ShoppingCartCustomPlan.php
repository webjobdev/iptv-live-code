<?php

namespace Contus\Organizations\Model;

use Contus\Base\Model;

class ShoppingCartCustomPlan extends Model {

    protected $table = 'shopping_cart_custom_plans';

    protected $fillable = [
        'plan_name',
        'description',
        'cover_image',
        'label',
        'additional_info'
    ];

    protected $appends = ['cover_image_url'];

    function getCoverImageUrlAttribute() {
        return asset($this->cover_image);
    }
}
