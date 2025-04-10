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

    /**
     * Get the event that owns the attachment.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
