<?php

namespace App\Http\Controllers;

use App\Bank;
use App\Constant;
use App\Jobs\ActivityLogJob;
use App\Plotuse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ConstantController extends Controller
{
    public function index(){
        if (!auth()->user()->granted('constant_view')){
            return abort(403,'You dont have permissions to access this page!  Contact Administrator');
        }
        $constants = Constant::orderBy('year','desc')->paginate(15);
        $banks = Bank::all();
        $plotuses = Plotuse::pluck('name');

        //Logging
        $activity = [
            'name'=>'Constant',
            'description'=>'Viewing constants',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity));


        return view('constant.index',compact('constants','banks','plotuses'));
    }

    public function store(Request $request){
        if (!auth()->user()->granted('constant_add')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }

        $this->validate($request,[
            'year'=>'required',
            'advanceFactor'=>'required',
            'ramani'=>'required',
            'hati'=>'required',
            'usajiriFactor'=>'required',
            'ushuruSubstraction'=>'required',
            'ushuruFactor'=>'required',
            'ushuruAddition'=>'required'
        ]);
        if (Constant::where('year',$request->year)->count()>0){
            return redirect()->back()->with('message','Year ('.$request->year.') already exists');
        }
        $constant = Constant::create($request->all());

        //Logging
        $activity = [
            'name'=>'Constant',
            'description'=>'Create constant',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity,$constant));

        return redirect(route('constant.index'));
    }

    public function edit($id){
        $constant = Constant::find($id);

        //Logging
        $activity = [
            'name'=>'Constant',
            'description'=>'Attempting to edit constant',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity,$constant));

        return view('constant.edit',compact('constant'));
    }

    public function update(Request $request,$id){
        if (!auth()->user()->granted('constant_edit')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $this->validate($request,[
            'advanceFactor'=>'required',
            'ramani'=>'required',
            'hati'=>'required',
            'usajiriFactor'=>'required',
            'ushuruSubstraction'=>'required',
            'ushuruFactor'=>'required',
            'ushuruAddition'=>'required'
        ]);
        $constant = Constant::find($id);
        $oldConstant = $constant;
        $constant->update($request->except('year'));

        //Logging
        $activity = [
            'name'=>'Constant',
            'description'=>'Update constant',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now(),
            'old'=>$oldConstant
        ];

        $this->dispatch(new ActivityLogJob($activity,$constant));

        return redirect(route('constant.index'))->with('message','Updated successfully');
    }
}
