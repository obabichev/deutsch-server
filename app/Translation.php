<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    public function word() {
        return $this->belongsTo('App\Word');
    }

    protected $fillable = [
        'pre',
        'val',
        'post',
    ];
}
