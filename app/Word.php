<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    public function tags()
    {
        return $this->belongsToMany('App\Tag')
            ->withTimestamps();
    }

    public function translations() {
        return $this->hasMany('App\Translation');
    }

    protected $fillable = [
        'pre',
        'val',
        'post',
        'gender',
        'type',
    ];
}
