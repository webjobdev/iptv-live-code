<?php

/**
 * Transcoded Audio Model for videos table in database
 *
 * @name TranscodedVideo
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Audio\Models;

use Contus\Base\Model;
use Contus\Audio\Models\AudioPreset;

class TranscodedAudio extends Model {

    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Audio
     * @var string
     */
    protected $table = 'transcoded_audios';

    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package Audio
     * @var array
     */
    protected $fillable = [ 'audio_id','is_active' ];
    /**
     * Constructor method
     * sets visible for customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'id','audio_id','is_active','creator_id','updator_id','created_at','updated_at','preset_id' ] );
    }
    /**
     * BelongsTo relationship between audio preset and transcoded audio table.
     */
    public function presets() {
        return $this->belongsTo ( AudioPreset::class, 'preset_id' );
    }
}
