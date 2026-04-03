<?php

namespace Contus\Video\Models;

use Contus\Base\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Contus\Base\Contracts\AttachableModel as AttachableModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Contus\Video\Models\Video;
use Contus\Video\Models\Collection;
use Contus\Base\BaseAuthenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Contus\Users\Models\NotificationUser;
use Contus\Users\Models\UserPlaylist;
use Contus\Users\Models\Like;
use Contus\Users\Models\WatchHistory;
//use Contus\Audio\Traits\AudioCustomerTrait;
use Contus\Users\Models\FavouriteVideo;

class DeviceLmit extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customer_limit';
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    /**
     * Morph class name
     *
     * @var string
     */
    protected $morphClass = 'customer_limit';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['customer_id','rondom_key'];

   
}
