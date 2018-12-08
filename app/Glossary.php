<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Glossary extends Model
{
    protected $fillable = [
        'title',
    ];

    public function owner()
    {
        return $this->belongsTo('\App\User');
    }

    public function cards()
    {
        return $this->hasMany('App\GlossaryCard');
    }
}
