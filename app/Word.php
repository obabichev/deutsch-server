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

    protected $fillable = [
        'val_pre',
        'val',
        'val_post',
        'gender',
        'tr_pre',
        'tr',
        'tr_post',
        'type',
    ];
}
