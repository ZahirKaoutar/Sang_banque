<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Centre extends Model
{
    protected $table="centers";
    protected $fillable=['name','liscence_number','user_id','adress','city'];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function stock(){
        return $this->hasMany(BloodStock::class, 'center_id');
    }
    public function bloodRequests(){
        return $this->hasMany(BloodRequest::class, 'center_id');
    }
    public function notifications(){
        return $this->hasMany(Notification::class, 'center_id');
    }
    public function reports(){
        return $this->hasMany(Report::class, 'center_id');
    }
    public function donations(){
        return $this->hasMany(Donation::class, 'center_id');
    }

}
