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

    public function translation()
    {
        return $this->belongsTo('\App\Translation');
    }

    /**
     * @return \DateTime mixed
     */
    public function getRepeat()
    {
        return new \DateTime($this->repeat);
    }

    public function setRepeat(\DateTime $repeat)
    {
        $this->repeat = $repeat;
    }

    protected $fillable = [
        'learned',
        'mistakes',
        'repeat',
        'successes',
        'excellent'
    ];
}
