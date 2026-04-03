<?php 

namespace Contus\Subscribers\Model;

use Contus\Base\Model;

class SubscriberNote extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'org_subscriber_notes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subscriber_id',
        'note_type',
        'sub_note_type',
        'subject',
        'description',
        'updates'
    ];

    public function subscriber_details()
    {
        return $this->belongsTo(OrgSubscribers::class, 'subscriber_id');
    }
}
