<?php

namespace Contus\Subscribers\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubsciprionComment extends Model{
    use HasFactory;

    protected $table = 'subscription_comment';

    protected $fillable = [
        'subscriber_id',
        'subscription_and_payments_id',
        'comment',
    ];
}