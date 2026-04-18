<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'message',
        'sent_at',
        'status',
        'user_id',
        'center_id',
        'blood_group_needed',
        'donor_response',
        'responded_at',
        'donation_recorded',
    ];
    
public function centre(){
    return $this->belongsTo(Centre::class,'center_id');
}
public function donor(){
    return $this->belongsTo(User::class,'user_id');
}

}
