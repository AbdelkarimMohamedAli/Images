<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    //
    protected $table = 'subscribe';

    protected $fillable = [
        'name', 'email', 'companyname','phonecompany','phone','job','industrialsector',
    ];
}
