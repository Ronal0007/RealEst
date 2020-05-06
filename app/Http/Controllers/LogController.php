<?php

namespace App\Http\Controllers;

use App\Activity;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class LogController extends Controller
{

    public function index(){
        if (Input::has('slug')){
            $request = Input::capture();
            $this->validate($request,[
                'slug'=>'required',
                'from'=>'required',
                'to'=>'required'
            ]);

            $users = User::all()->pluck('name','slug');
            $user = User::whereSlug($request->slug)->first();
            $logs = $user->logs()->whereDate('created_at','>=',$request->from)->whereDate('created_at','<=',$request->to)->orderBy('logged_at','desc')->paginate(15)->setPath ( '' );
            $pagination = $logs->appends ( array (
                'from' => $request->from,
                'to'=>$request->to,
                'slug'=>$request->slug
            ) );
            $header = "Logs for user '".$user->name."' from ".$request->from." to ".$request->to;
            return view('log.index',compact('logs','users','header'));
        }
        else{
           $users = User::all()->pluck('name','slug');
           return view('log.index',compact('users'));
        }
    }
}
