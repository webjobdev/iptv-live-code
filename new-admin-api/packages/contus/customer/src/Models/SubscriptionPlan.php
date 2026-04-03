<?php

namespace Contus\Customer\Models;

use Contus\Base\Model;
use Carbon\Carbon;
use Contus\Customer\Models\SubscriptionPlanTranslation;


class SubscriptionPlan extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'subscription_plans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'name','type','amount','amount_israel','no_of_device','description','duration','is_active','trial' ];

    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'id','is_active','creator_id','updator_id','created_at','updated_at' ] );
    }

    /**
     * funtion to automate operations while Saving
     */
    public function bootSaving() {
        $this->setDynamicSlug ( 'name' );
    }
    /**
     * Belongs to many relation with customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function SubscriberInfo() {
        return $this->belongsToMany ( Customer::class, 'subscribers' );
    }

    public function getIsSubscribeAttribute() {
        $subscribe = [];
        if(auth() && auth()->user()) {
            $subscribe = $this->Subscribers()->where('customer_id', auth()->user()->id)->get()->toArray();
        }
        
        return (!empty($subscribe)) ? 1 : 0;
    }

    /**
     * Belongs to many relation with customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function Subscribers() {
        return $this->hasMany ( Subscribers::class )->where('is_active', 1);
    }

    /**
     * Get the formated created date
     *
     * @return object
     */
    public function getFormattedCreatedDateAttribute()
    {
        return  Carbon::parse($this->created_at)->format('M d Y');
    }

    public function subscriptionTranslation()
    {
        return $this->hasMany(SubscriptionPlanTranslation::class);
    }
}
