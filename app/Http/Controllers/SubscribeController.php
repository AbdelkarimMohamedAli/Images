<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\SubscribeExport;
use App\Exports\RegisterExport;
use App\Exports\PindingExport;
use App\Exports\RejectedExport;
use App\Exports\ApprovalExport;
use Maatwebsite\Excel\Facades\Excel;

class SubscribeController extends Controller
{
    //
    public function export() 
    {
        return \Excel::download(new SubscribeExport, 'subscribe.xlsx');
    }
    public function exportregister() 
    {
        return \Excel::download(new RegisterExport, 'register.xlsx');
    }
    public function exportpending() 
    {
        return \Excel::download(new PindingExport, 'registerpending.xlsx');
    }
    public function exportrejected() 
    {
        return \Excel::download(new RejectedExport, 'registerrejected.xlsx');
    }
    public function exportapproval() 
    {
        return \Excel::download(new ApprovalExport, 'registerapproval.xlsx');
    }
}
