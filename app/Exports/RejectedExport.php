<?php

namespace App\Exports;

use App\Personainfo;
use Maatwebsite\Excel\Concerns\FromCollection;

class RejectedExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Personainfo::where('status','rejected')->select('id','name','adress','company','phone','nationalid','job','nationality','email','industrialsector','status')->get();
    }
}
