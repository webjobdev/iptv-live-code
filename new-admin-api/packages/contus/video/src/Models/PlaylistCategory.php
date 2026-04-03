<?php

/**
 * PlaylistCategory Models.
 *
 * @name PlaylistCategory
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Base\Model;
use Contus\Video\Models\Playlist;
use Contus\Base\Helpers\StringLiterals;

class PlaylistCategory extends Model {

    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Video
     * @var string
     */
    protected $table = 'playlist_categories';

        /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package Video
     * @var array
     */
    protected $fillable = [ 'playlist_id','category_id' ];

    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'id','playlist_id','category_id','created_at','updated_at' ] );
    }

    /**
     * Belongsto relationship between video_categories and videos
     */
    public function playlist() {
        return $this->belongsTo ( Playlist::class, 'playlist_id' )->select ( 'id', 'title' );
    }
    /**
     * Belongsto relationship between video_categories and categories
     */
    public function category() {
        return $this->belongsTo ( Category::class, 'category_id' );
    }

  }
