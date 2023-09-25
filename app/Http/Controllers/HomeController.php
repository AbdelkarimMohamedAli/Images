<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        return view('index');
    }
    public function subscribes()
    {
        $subscribes = DB::table('subscribe')->paginate(10);

        return view('home', ['subscribes' => $subscribes]);
    }
    // public function register()
    // {
    //     $personals = DB::table('personainfo')->paginate(10);

    //     return view('register', ['personals' => $personals]);
    // }
    public function register_status_pending()
    {
        $personals = DB::table('personainfo')->where('status','pending')->paginate(10);

        $slug='pending';
        return view('register', ['personals' => $personals,'slug'=>$slug]);
    }
    
    public function register_status($slug)
    {
        $personals = DB::table('personainfo')->where('status',$slug)->paginate(10);
        $statuses=['pending','rejected','approved'];

        return view('registerstatus', ['personals' => $personals,'slug'=>$slug,'statuses'=>$statuses]);
    }
    public function register_status_id(Request $request,$id)
    {
        $data= $request->all();
        DB::table('personainfo')->where('id',$id)->update([
        'status' => $data['status']
    ]);

        return redirect()->back();
    }
    
}



