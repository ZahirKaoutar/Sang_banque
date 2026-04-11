<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'center_id',
        'reasons',
    ];

    /**
     * Le donneur qui est signalé.
     */
    public function donor()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    /**
     * Le centre (ou l'agent du centre) qui a créé le rapport.
     */
    public function center()
    {
        return $this->belongsTo(Centre::class, 'center_id');
    }
}
