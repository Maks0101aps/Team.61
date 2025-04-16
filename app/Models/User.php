<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Contact;
use App\Models\Teacher;
use App\Models\ParentModel;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    // User roles
    const ROLE_TEACHER = 'teacher';
    const ROLE_PARENT = 'parent';
    const ROLE_STUDENT = 'student';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'first_name',
        'last_name',
        'middle_name',
        'account_id',
        'password_change_required',
        'photo_path',
        'owner',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'owner' => 'boolean',
            'email_verified_at' => 'datetime',
            'password_change_required' => 'boolean',
        ];
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::needsRehash($password) ? Hash::make($password) : $password;
    }

    public function isDemoUser()
    {
        return $this->email === 'johndoe@example.com';
    }

    public function isAdmin()
    {
        return $this->owner === true;
    }

    public function isTeacher()
    {
        return $this->role === self::ROLE_TEACHER;
    }

    public function isParent()
    {
        return $this->role === self::ROLE_PARENT;
    }

    public function isStudent()
    {
        return $this->role === self::ROLE_STUDENT;
    }

    /**
     * Get the contact ID for the user if they are a student
     */
    public function contactId()
    {
        if (!$this->isStudent()) {
            return null;
        }

        $student = Contact::where('email', $this->email)->first();
        return $student ? $student->id : null;
    }

    /**
     * Get the teacher ID for the user if they are a teacher
     */
    public function teacherId()
    {
        if (!$this->isTeacher()) {
            return null;
        }

        $teacher = Teacher::where('email', $this->email)->first();
        return $teacher ? $teacher->id : null;
    }

    /**
     * Get the parent ID for the user if they are a parent
     */
    public function parentId()
    {
        if (!$this->isParent()) {
            return null;
        }

        $parent = ParentModel::where('email', $this->email)->first();
        return $parent ? $parent->id : null;
    }

    public function scopeOrderByName($query)
    {
        $query->orderBy('last_name')->orderBy('first_name');
    }

    public function scopeWhereRole($query, $role)
    {
        switch ($role) {
            case 'user': return $query->where('owner', false);
            case 'owner': return $query->where('owner', true);
            case self::ROLE_TEACHER: return $query->where('role', self::ROLE_TEACHER);
            case self::ROLE_PARENT: return $query->where('role', self::ROLE_PARENT);
        }
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', '%'.$search.'%')
                    ->orWhere('last_name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        })->when($filters['role'] ?? null, function ($query, $role) {
            $query->whereRole($role);
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
