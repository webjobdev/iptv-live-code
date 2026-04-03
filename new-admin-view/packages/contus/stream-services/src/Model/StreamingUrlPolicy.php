<?php

namespace Contus\StreamServices\Model;

use Contus\Base\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StreamingUrlPolicy extends Model {

    use HasFactory;

    protected $table = 'streaming_url_policy';

    protected $fillable = ['policy_name', 'rules', 'where', 'operator', 'condition', 'logical_operator', 'updated_by', 'status'];
}
