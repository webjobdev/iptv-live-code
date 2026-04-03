<?php

/**
 * Comment Models.
 *
 * @name Comment
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;



use Contus\Base\MongoModel ;

class PlaylistVideos extends MongoModel
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
    protected $collection = 'playlist_videos';
    protected $connection = 'mongodb';


    

    /**
     * Belongs to relationship between video and comments
     */
    public function video()
    {
        return $this->belongsTo(Video::class, 'video_id', 'id');
    }

    
}
