<?php

namespace Contus\Feedback\Model;

use Contus\Base\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Feedback extends Model {

    use HasFactory;

    protected $table = 'feedbacks';
    protected $fillable = ['subject', 'message', 'image'];

    protected $appends = ['image_url'];

    function getImageUrlAttribute() {
        return asset($this->image);
        // if ($this->image) {
        //     return $this->image  && str_starts_with($this->image, 'http') ? $this->image : Storage::url($this->image);
        // }
    }
}
