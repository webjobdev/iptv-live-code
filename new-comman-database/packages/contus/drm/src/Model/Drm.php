<?php

namespace Contus\Drm\Model;

use Contus\Base\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Drm extends Model{
    use HasFactory;

    protected $fillable = ['drm_name'];
}