<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    use HasFactory;

    // Cette étape est CRUCIALE pour ton projet Fil Rouge
    protected $fillable = [
        'hopital_id',
        'center_id',
        'quantity_needed',
        'quantity_fulfilled',
        'blood_group',
        'priority',
        'status',
        'description',
    ];

    // N'oublie pas de définir les relations pour plus tard
    public function hopital()
    {
        return $this->belongsTo(Hopital::class);
    }

    public function centre()
    {
        return $this->belongsTo(Centre::class, 'center_id');
    }
}
