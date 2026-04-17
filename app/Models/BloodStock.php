<?php

namespace App\Models;
use App\Models\Centre;

use Illuminate\Database\Eloquent\Model;

class BloodStock extends Model
{
    protected $fillable = ['blood_group', 'center_id', 'quantity_units', 'expiry_date'];

    public function centre(){
        return $this->belongsTo(Centre::class, 'center_id');
    }
}
