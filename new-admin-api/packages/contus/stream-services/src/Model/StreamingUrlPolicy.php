<?php

namespace Contus\StreamServices\Model;

use Carbon\Carbon;
use Contus\Base\Model;
use Contus\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StreamingUrlPolicy extends Model {

    use HasFactory;

    protected $table = 'streaming_url_policy';

    protected $fillable = ['policy_name', 'rules', 'where', 'operator', 'condition', 'logical_operator', 'updated_by', 'status'];

    public function user() {
        return $this->hasMany(User::class, 'id', 'updated_by');
    }

    public function getFormattedUpdatedAtAttribute() {
        return $this->updated_at
        ? Carbon::parse($this->updated_at)->format('d M Y')
        : null;
    }

    protected $appends = ['formatted_updated_at'];
}
