<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventAttachment extends Model
{
    protected $fillable = [
        'event_id',
        'filename',
        'original_filename',
        'mime_type',
        'size',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
