<?php

namespace App\Exports;

use App\Personainfo;
use Maatwebsite\Excel\Concerns\FromCollection;

class RegisterExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
       // return Personainfo::all();
        return Personainfo::select('id','name','adress','company','phone','nationalid','job','nationality','email','industrialsector','status')->get();
    }
}
