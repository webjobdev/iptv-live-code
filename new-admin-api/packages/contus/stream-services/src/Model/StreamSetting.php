<?php

namespace Contus\StreamServices\Model;

use Contus\Base\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StreamSetting extends Model {

    use HasFactory;

    protected $table = 'stream_settings';
    protected $fillable = ['name', 'node', 'preset', 'status_type', 'input_type', 'started', 'last_reset', 'restarts', 'cpu', 'rss', 'created_by', 'status'];

}
