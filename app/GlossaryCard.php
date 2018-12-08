<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GlossaryCard extends Model
{
    public function word()
    {
        return $this->belongsTo('App\Word');
    }

    public function translation()
    {
        return $this->belongsTo('App\Translation');
    }

    public function glossary()
    {
        return $this->belongsTo('App\Glossary');
    }
}
