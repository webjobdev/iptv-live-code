<?php

/**
 * Categories Models.
 *
 * @name Categories
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Models;

use Contus\Base\Model;
use Contus\Base\Helpers\StringLiterals;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Support\Facades\Cache;
use Contus\Cms\Traits\CategoryTrait;

class Category extends Model{

    use CategoryTrait;

    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Video
     * @var string
     */
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package Video
     * @var array
     */
    protected $fillable = [ 'title',StringLiterals::ISACTIVE,'parent_id','level' ];
    /**
     * The attribute will used to generate url
     *
     * @var array
     */
    protected $url = [ 'image_url' ];

    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'id','is_active','image_path','is_deletable','is_leaf_category','level','parent_id','updated_at','created_at','updator_id','creator_id','pivot', 'preference_order' ] );
    }
}
