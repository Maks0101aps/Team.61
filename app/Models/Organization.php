<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'region',
        'country',
        'postal_code',
        'account_id',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
} 