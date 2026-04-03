<?php

/**
 * CollectionPlaylist Models.
 *
 * @name CollectionPlaylist
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Base\Model;
use Contus\Video\Models\Video;
use Contus\Base\Helpers\StringLiterals;

class CollectionPlaylist extends Model {

    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Video
     * @var string
     */
    protected $table = 'collection_playlists';

    /**
    * The attributes that are mass assignable.
    *
    * @vendor Contus
    *
    * @package Video
    * @var array
    */
   protected $fillable = [ 'playlist_id','group_id' ,'parent_cateogry_id','creator_id','updator_id'];


  }
