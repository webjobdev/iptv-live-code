<?php

/**
* VideoTrait
*
* To manage the functionalities related to the Videos module from Video Controller
*
* @vendor Contus
*
* @package Video
* @version 1.0
* @author Contus<developers@contus.in>
* @copyright Copyright (C) 2018 Contus. All rights reserved.
* @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
*/
namespace Contus\Cms\Traits;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Contus\Cms\Models\CategoryTranslation;
use Location;

trait CategoryTrait
{
        public function categoryTranslation() {
        return $this->hasMany(CategoryTranslation::class, 'category_id');
        }

        public function getTitleAttribute($value) {
        $trans = $this->categoryTranslation()->where('language_id', $this->fetchLanugageId())->first();
        if(!empty($trans)) {
        return $trans->title;
        }
        return $value;
        }

        public function fetchTranslationInfo($vId) {
        return app('cache')->tags([getCacheTag(), 'categories_translation'])->remember(getCacheKey(1).'_global_categories_translation_'.$vId, getCacheTime(), function (){
        return $this->categoryTranslation()->where('language_id', $this->fetchLanugageId())->first();
        });
        }

}
