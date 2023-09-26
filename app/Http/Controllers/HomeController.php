<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Image;


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

    public function index()
    {
        $img['upload'] = Image::count();
        $img['pending'] = Image::where('status','pending')->count();
        $img['valid'] = Image::where('status','valid')->count();
        $img['rejected'] = Image::where('status','rejected')->count();
        $img['duplication'] = Image::where('status','duplication')->count();
        $img['complete'] = $img['valid']+$img['rejected'];
        $rejected = Image::where('status','rejected')->take(30)->orderby('_id','desc')->get();
        $valid = Image::where('status','valid')->take(30)->orderby('_id','desc')->get();
        $duplication =  Image::where('status','duplication')->take(30)->orderby('_id','desc')->get();


        return view('index', ['img' => $img,'rejected' => $rejected,'valid' => $valid,'duplication'=>$duplication]);

    }


}



