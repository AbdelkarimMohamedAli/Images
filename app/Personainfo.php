<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Personainfo extends Model
{
    //
    protected $table = 'personainfo';

    protected $fillable = [
        'name', 'adress', 'company','phone','nationalid','job','nationality','email','industrialsector','emailconfirm','status',
    ];
}
