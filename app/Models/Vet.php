<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vet extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'license_number',
        'clinic_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}