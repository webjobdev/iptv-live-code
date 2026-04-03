<?php

/**
 * Transcode Tracking Models.
 *
 * @name TranscodeTracking
 * @vendor Contus
 * @package Audio
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Playlist\Models;

use Carbon\Carbon;

use Contus\Base\MongoModel ;

class TranscoderTracking extends MongoModel
{
    protected $primaryKey = '_id';
    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Video
     * @var string
     */
    protected $collection = 'transcoder_tracking';
    protected $connection = 'mongodb';
    /**
     * Hidden variable to be returned
     *
     * @vendor Contus
     *
     * @package Video
     * @var array
     */
    protected $hidden = [];
}
