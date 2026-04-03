<?php

namespace Contus\Cms\Models;

use Contus\Base\Model;
use Contus\Base\BaseAuthenticatable;
use Contus\Cms\Models\SubscriptionPlan;

class Customer extends BaseAuthenticatable
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customers';
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    /**
     * Morph class name
     *
     * @var string
     */
    protected $morphClass = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','email','phone','acesstype','is_active','device_token','device_type','profile_picture','age', 'country_code', 'iso'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [ 'password' ];
    /**
     * The attribute will used to generate url
     *
     * @var array
     */
    protected $url = ['profile_picture'];

    /**
     * Tthe attributes used for soft delete
     */
    protected $dates = [ 'deleted_at' ];

    /**
     * Method to retrive subscription information with belongsToMany relations with only active subscription plan with start and end date
     *
     * @vendor Contus
     *
     * @package Customer
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany with active subscription plan
     */
    public function activeSubscriber()
    {
        return $this->belongsToMany(SubscriptionPlan::class, 'subscribers')->withPivot([ 'subscription_plan_id','start_date','end_date','is_active' ])->where('subscribers.is_active', 1)->orderBy('subscribers.id', 'desc');
    }
    /**
     * Check if the current user is a subscribed user or not
     *
     * @vendor Contus
     *
     * @package Customer
     * @return boolean|date
     */
    public function isExpires()
    {   
        $subscription = $this->activeSubscriber()->first();
        if (! isset($subscription) && is_null($this->expires_at)) {
            return false;
        }
        return $subscription->pivot->end_date;
    }

}
