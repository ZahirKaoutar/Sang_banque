<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Centre extends Model
{
    protected $table="centers";
    protected $fillable=['name','liscence_number','user_id','adress','city'];
    public function agentcentre(){
        return $this->belongsTo(User::class);
    }
    public function  stock(){
        return $this->hasMany(BloodStock::class);
    }
    public function bloodRequests(){
        return $this->hasMany(BloodRequest::class);
    }
    public function notifications(){
        return $this->hasMany(Notification::class);
    }
    public function reports(){
        $this->hasMany(Report::class);
    }
     public function donations(){
        $this->hasMany(Donation::class);
    }

}
