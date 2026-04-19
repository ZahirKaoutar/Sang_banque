<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'center_id',
        'medical_notes',
        'donation_date',
        'test_result',
        'observed_blood_group'
    ];

    protected $casts = [
        'donation_date' => 'datetime',
    ];


    public function donor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

   
    public function centre()
    {
        return $this->belongsTo(Centre::class, 'center_id');
    }
}
