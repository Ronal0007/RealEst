<?php

namespace App\Http\Controllers;

use App\Jobs\ActivityLogJob;
use App\Locality;
use App\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LocalityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->granted('locality_view')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $localities = Locality::paginate(8);
        $projects = Project::pluck('name','id');

        //Logging
        $activity = [
            'name'=>'Locality',
            'description'=>'Viewing localities',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity));


        return view('locality.index',compact('localities','projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->granted('locality_add')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }

        $this->validate($request,[
            'name'=>'required',
            'project_id'=>'required|min:1'
        ]);
        $project = Project::find($request->project_id);
        $projlocalities = $project->localities()->get();
        $projlocalitiesTrashed = $project->localities()->onlyTrashed()->get();
        if ($projlocalities->where('name',$request->name)->count()>0){
            return redirect(route('locality.index'))->with('message','Locality ('.$request->name.') already exists in project ('.$project->name.')');
        }elseif ($projlocalitiesTrashed->where('name',$request->name)->count()>0){
            $trashed = $projlocalitiesTrashed->where('name',$request->name)->first();
            $oldLocality = $trashed;
            $trashed->deleted_at = null;
            $trashed->created_at = Carbon::now();
            $trashed->save();

            //Logging
            $activity = [
                'name'=>'Locality',
                'description'=>'Revoking deleted locality',
                'user_id'=>auth()->id(),
                'logged_at'=>Carbon::now(),
                'old'=>$oldLocality
            ];

            $this->dispatch(new ActivityLogJob($activity,$trashed));

            return redirect(route('locality.index'))->with('message','Locality added successfully');
        }
        else{
            $locality = Locality::create(['name'=>$request->name,'project_id'=>$request->project_id,'user_id'=>auth()->user()->id]);

            //Logging
            $activity = [
                'name'=>'Locality',
                'description'=>'Create new Locality',
                'user_id'=>auth()->id(),
                'logged_at'=>Carbon::now()
            ];

            $this->dispatch(new ActivityLogJob($activity,$locality));

            return redirect(route('locality.index'))->with('message','Locality added successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateLocality(Request $request){
        $locality = Locality::find($request->id);
        $oldLocality = $locality;

        $count = Project::find($request->project)->localities()->whereName($request->name)->count();
        if ($count>0){
            return 400;
        }else{
            $locality->name = $request->name;
            $locality->project_id = $request->project;
            $locality->save();

            //Logging
            $activity = [
                'name'=>'Locality',
                'description'=>'Update locality',
                'user_id'=>auth()->id(),
                'logged_at'=>Carbon::now(),
                'old'=>$oldLocality
            ];

            $this->dispatch(new ActivityLogJob($activity,$locality));


            return "200";
        }


    }

    public function getLocalities(Request $request){
        $localities = Locality::where('project_id',$request->project)->pluck('name','id');
        return $localities;
    }

    public function deleteLocality($slug)
    {
        if (!auth()->user()->granted('locality_delete')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $locality = Locality::whereSlug($slug)->first();
        if($locality->blocks()->count()==0){

            //Logging
            $activity = [
                'name'=>'Locality',
                'description'=>'Delete locality',
                'user_id'=>auth()->id(),
                'logged_at'=>Carbon::now(),
                'old'=>$locality
            ];

            $this->dispatch(new ActivityLogJob($activity));


            $locality->delete();
            return redirect(route('locality.index'))->with('message','Locality deleted!');
        }else{
            return redirect(route('locality.index'))->with('message','Cant delete locality with Blocks data!');
        }

    }
    public function showPlots($slug){
        if (!auth()->user()->granted('plot_view')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $locality = Locality::whereSlug($slug)->first();
        $locality->setRelation('p',$locality->plots()->paginate(15));

        //Logging
        $activity = [
            'name'=>'Locality',
            'description'=>'View locality plots',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity,$locality));


        return view('locality.showPlots',compact('locality'));
    }

}
