<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParentModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Типы родителей
    const TYPE_MOTHER = 'mother';
    const TYPE_FATHER = 'father';
    
    // Локализованные названия типов родителей
    public static function getParentTypes()
    {
        return [
            self::TYPE_MOTHER => 'Мати',
            self::TYPE_FATHER => 'Батько',
        ];
    }

    protected $fillable = [
        'account_id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'parent_type',
        'phone',
        'address',
        'city',
        'region',
        'country',
        'postal_code',
        'street',
        'house_number',
        'district',
    ];

    public function children(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'parent_student', 'parent_model_id', 'student_id');
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->middle_name.' '.$this->last_name;
    }

    public function getParentTypeNameAttribute()
    {
        return self::getParentTypes()[$this->parent_type] ?? 'Невідомо';
    }

    public function scopeOrderByName($query)
    {
        $query->orderBy('last_name')->orderBy('first_name');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', '%'.$search.'%')
                    ->orWhere('last_name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
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
