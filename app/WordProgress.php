<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WordProgress extends Model
{
    public function user()
    {
        return $this->belongsTo('\App\User');
    }

    public function word()
    {
        return $this->belongsTo('\App\Word');
    }

    protected $fillable = [
        'learned',
        'mistakes',
        'repeat',
        'successes',
        'excellent'
    ];
}
