<?php

namespace App\Models;
use App\Models\Centre;

use Illuminate\Database\Eloquent\Model;

class BloodStock extends Model
{
    public function centre(){
        return $this->belongsTo(Centre::class);
    }
}
