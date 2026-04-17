<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hopital extends Model
{

    protected $fillable=['name','liscence_number','user_id','adress','city'];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public  function bloodRequests(){
        return $this->hasMany(BloodRequest::class);
    }
}
