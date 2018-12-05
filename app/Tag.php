<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name'
    ];

    public function words()
    {
        return $this->belongsToMany('App\Word')
            ->withTimestamps();
    }
}
