<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'type',
        'start_date',
        'duration',
        'content',
        'is_content_hidden',
        'location',
        'online_link',
        'tasks',
        'account_id',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'is_content_hidden' => 'boolean',
        'duration' => 'integer',
    ];

    const TYPE_EXAM = 'exam';
    const TYPE_TEST = 'test';
    const TYPE_SCHOOL_EVENT = 'school_event';
    const TYPE_PARENT_MEETING = 'parent_meeting';
    const TYPE_PERSONAL = 'personal';

    public static function getTypes()
    {
        return [
            self::TYPE_EXAM => 'Екзамен',
            self::TYPE_TEST => 'Контрольна робота',
            self::TYPE_SCHOOL_EVENT => 'Шкільний захід',
            self::TYPE_PARENT_MEETING => 'Батьківські збори',
            self::TYPE_PERSONAL => 'Особиста подія',
        ];
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants()
    {
        return $this->morphedByMany(Contact::class, 'participant', 'event_participants')
            ->union($this->morphedByMany(Teacher::class, 'participant', 'event_participants'))
            ->union($this->morphedByMany(ParentModel::class, 'participant', 'event_participants'));
    }

    public function students()
    {
        return $this->morphedByMany(Contact::class, 'participant', 'event_participants');
    }

    public function teachers()
    {
        return $this->morphedByMany(Teacher::class, 'participant', 'event_participants');
    }

    public function parents()
    {
        return $this->morphedByMany(ParentModel::class, 'participant', 'event_participants');
    }

    /**
     * Get the attachments for the event.
     */
    public function attachments()
    {
        return $this->hasMany(EventAttachment::class);
    }

    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%'.$search.'%')
                    ->orWhere('content', 'like', '%'.$search.'%')
                    ->orWhere('location', 'like', '%'.$search.'%');
            });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
} 