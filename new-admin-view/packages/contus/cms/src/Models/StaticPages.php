<?php

namespace Contus\Cms\Models;

use Contus\Base\Model;
use Carbon\Carbon;
use Contus\Cms\Models\StaticPagesTranslation;

class StaticPages extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'static_pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'title','content','is_active' ];

    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'id','is_active' ] );
    }

    /**
     * funtion to automate operations while Saving
     */
    public function bootSaving() {
        $this->setDynamicSlug ( 'title' );
    }
    /**
     * Get the formated updated date
     *
     * @return object
     */
    public function getFormattedUpdatedDateAttribute()
    {
        return  Carbon::parse($this->updated_at)->format('M d Y');
    }

    public function staticPagesTranslation() 
    {
        return $this->hasMany(StaticPagesTranslation::class, 'static_page_id');
    }
}
