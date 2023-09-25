<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;


use Carbon\Carbon;
use Helpers;
use Session;
use Cache;
use Redirect;
use Str;
use DB;


class SystemController extends Controller
{
     public function savedata(Request $request)
    {

    $validated = $request->validate([
        'name' => 'required',
        'email' => 'required',
        'phone' => 'required',
    ]);

$data = $request->all();



DB::table('subscribe')->insert($data);
return array(

    "response"=>'success'

);

    }
    public function savePersonal(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);
    
    $data = $request->all();
    
    
    
    DB::table('subscribe')->insert($data);
    return array(
    
        "response"=>'success'
    
    );
    }

    public function personal(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required|numeric',
            'adress'=>'required',
            'company'=>'required',
            'nationalid'=>'required|numeric',
            'job'=>'required',
            'nationality'=>'required',
            'emailconfirm'=>'required',
            'industrialsector'=>'required',
        ]);
    
    $data = $request->all();
    
    
    
    DB::table('personainfo')->insert($data);
    if($data['lang']=='ar'){
        return redirect()->to('https://imceegypt.com/success/');

    }else{
        return redirect()->to('https://imceegypt.com/en/success/');

    }


    // return array(
    
    //     "response"=>'success'
    
    // );
    }
}

