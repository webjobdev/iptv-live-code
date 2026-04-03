<?php 

namespace Contus\Subscribers\Model;

use Contus\Base\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrgSubscriberPayment extends Model{
    use HasFactory;

    protected $table = '';

    protected $fillable = [
        'start_at',
        'end_at',
        'autopay',
        'subscriptions',
        'devices',
        'is_active',
    ];
}