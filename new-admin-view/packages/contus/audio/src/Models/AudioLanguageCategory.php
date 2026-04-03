<?php

/**
 * Audio Language Category Model
 *
 * Audio Language Category management related model
 *
 * @name Albums
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Audio\Models;

use Contus\Base\Model;
use Carbon\Carbon;
use Contus\Base\Helpers\StringLiterals;

class AudioLanguageCategory extends Model
{
    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Audio
     * @var string
     */
    protected $table = 'audio_language_category';

    protected $hidden = ['creator_id', 'updated_at', 'updator_id'];

    protected $fillable = ['language_name', 'is_active'];
    /**
     * Get the formated created date
     *
     * @return object  
     */
    public function getFormattedCreatedDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('M d Y');
    }

}
?>