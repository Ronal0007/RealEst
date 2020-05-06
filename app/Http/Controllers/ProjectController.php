<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\PlotUseData;
use App\Http\Resources\ProjectPlotUse;
use App\Jobs\ActivityLogJob;
use App\Plotuse;
use App\Project;
use App\ProjectPrice;
use App\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->granted('project_view')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $projects = Project::paginate(15);

        //Logging
        $activity = [
            'name'=>'Project',
            'description'=>'Viewing projects',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity));

        return view('project.index',compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->granted('project_add')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $statuses = Status::pluck('name','id');
        $plotuses = Plotuse::all();

        //Logging
        $activity = [
            'name'=>'Project',
            'description'=>'Attempint to create project',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity));

        return view('project.create',compact('statuses','plotuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->granted('project_add')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
//        return $request->all();
        $data = [];
        $dataValidator = [
            'name'=>'required',
            'status_id'=>'required|min:1',
            'dmcfactor'=>'required',
            'acqfactor'=>'required|min:1'
        ];

        $projectPrices = array();
        $plotuses = Plotuse::all();

        foreach ($plotuses as $plotuse){
            $name = str_replace('/','_',$plotuse->name);
            $name = str_replace(' ','_',$name);
            $data[$name] = $plotuse->id;
        }

        foreach ($data as $key=>$value){
            $dataValidator[$key.'_amount'] = 'required|min:0';
            $dataValidator[$key.'_rate'] = 'required|min:1|max:99';
        }
//        return $dataValidator;

        $this->validate($request,$dataValidator);

        $project = Project::create(['name'=>$request->name,'acqfactor'=>$request->acqfactor,'dmcfactor'=>$request->dmcfactor,'status_id'=>$request->status_id,'user_id'=>auth()->user()->id]);

        //Fire Logs Event

        foreach ($data as $key=>$value){
            if($request->exists($key.'_amount')){
                $projectPrices[] = new PlotUseData($value,$request->input($key.'_amount'),$request->input($key.'_rate'));
            }
        }
//        return $projectPrices;

        foreach ($projectPrices as $projectPrice) {
            ProjectPrice::create(['amount'=>$projectPrice->amount,'rate'=>$projectPrice->rate,'plotuse_id'=>$projectPrice->plotid,'project_id'=>$project->id]);
        }

        //Logging
        $activity = [
            'name'=>'Project',
            'description'=>'Create project',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity,$project));

        return redirect(route('project.index'))->with('message','Project created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        if (!auth()->user()->granted('project_view')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }

        $data = array();
        $color = [
            "rgba(226, 114, 23, 0.7)",
            "rgba(250, 28, 240, 0.7)",
            "rgba(186, 231, 242, 0.7)",
            "rgba(49, 143, 166, 0.7)",
            "rgba(32, 116, 30, 0.7)",
            "rgba(28, 233, 105, 0.7)",
            "rgba(186, 140, 87, 0.7)",
            "rgba(84, 202, 109, 0.7)",
            "rgba(223, 202, 82, 0.7)",
            "rgba(71, 252, 83, 0.7)",
            "rgba(144, 205, 97, 0.7)",
            "rgba(116, 97, 245, 0.7)",
            "rgba(162, 73, 184, 0.7)",
            "rgba(20, 234, 125, 0.7)"
        ];

        $plotuses = [];
        $plotusesdata = [];
        $project = Project::whereSlug($slug)->first();
        $plots = $project->plots()->get();

        foreach ($plots as $plot) {
            if (count($plotuses)>0){
                if (in_array($plot->plotuse->name,$plotuses)){
                    foreach ($plotusesdata as $item) {
                        if ($item->name==$plot->plotuse->name){
                            $item->plot+=1;
                            $item->sold += $plot->status->id==2?1:0;

                            $data[$item->name] += 1;
                            break;
                        }
                    }
                }else{
                    $data[$plot->plotuse->name] = 1;

                    $plotuses[] = $plot->plotuse->name;
                    $plotusedata = new ProjectPlotUse($plot->plotuse->name,$project->amount($plot->plotuse->id));
                    $plotusedata->plot+=1;
                    $plotusedata->sold += $plot->status->id==2?1:0;
                    $plotusesdata[] = $plotusedata;
                }
            }else{
                $data[$plot->plotuse->name] = 1;

                $plotuses[] = $plot->plotuse->name;
                $plotusedata = new ProjectPlotUse($plot->plotuse->name,$project->amount($plot->plotuse->id));
                $plotusedata->plot+=1;
                $plotusedata->sold += $plot->status->id==2?1:0;
                $plotusesdata[] = $plotusedata;
            }

        }

        //Logging
        $activity = [
            'name'=>'Project',
            'description'=>'Viewing project',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity,$project));

        return view('project.show',compact('project','plotusesdata','data','color'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        if (!auth()->user()->granted('project_edit')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $project = Project::whereSlug($slug)->first();
        if ($project->controlNumbers()->count()>0){
            return redirect(route('project.index'))->with('message','Project ('.$project->name.') in use can not be edited!');
        }
        $statuses = Status::pluck('name','id');

        //Logging
        $activity = [
            'name'=>'Project',
            'description'=>'Attempting to edit project',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity,$project));

        return view('project.edit',compact('project','statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectRequest $request, $slug)
    {
        if (!auth()->user()->granted('project_edit')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }

        $project = Project::whereSlug($slug)->first();
        $oldProject = $project;
        $project->name = $request->get('name');
        $project->status_id = $request->get('status_id');
        $project->acqfactor = $request->get('acqfactor');
        $project->dmcfactor = $request->get('dmcfactor');
        $project->save();

        //Logging
        $activity = [
            'name'=>'Project',
            'description'=>'Viewing projects',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now(),
            'old'=>$oldProject
        ];

        $this->dispatch(new ActivityLogJob($activity,$project));

        $data = [];
        $plotuses = Plotuse::all();
        foreach ($plotuses as $plotuse){
            $name = str_replace('/','_',$plotuse->name);
            $name = str_replace(' ','_',$name);
            $data[$name] = $plotuse->id;
        }

        $projectPrices = array();
        foreach ($data as $key=>$value){
            if($request->exists($key.'_amount')){
                $projectPrices[] = new PlotUseData($value,$request->input($key.'_amount'),$request->input($key.'_rate'));
            }
        }
//        return $projectPrices;
        $projectPriceValues = array();
//        return response()->json($projectPrice);
        foreach ($projectPrices as $newprojectPrice) {
            $projectPrice = ProjectPrice::where('project_id',$project->id)->where('plotuse_id',$newprojectPrice->plotid)->first();
            $projectPrice->amount = $newprojectPrice->amount;
            $projectPrice->rate = $newprojectPrice->rate;
            $projectPrice->save();
//            $projectPriceValues[] = $projectPrice;
        }
//        return $projectPriceValues;

        return redirect(route('project.index'))->with('message','Project ('.$project->name.') Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteProject($slug){
        if (!auth()->user()->granted('project_delete')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $project = Project::whereSlug($slug)->first();
        if($project->localities()->count()==0){

            //Logging
            $activity = [
                'name'=>'Project',
                'description'=>'Delete project',
                'user_id'=>auth()->id(),
                'logged_at'=>Carbon::now(),
                'old'=>$project
            ];

            $this->dispatch(new ActivityLogJob($activity));

            $project->projectPrices()->forceDelete();
            $project->forceDelete();
        }
        return redirect(route('project.index'))->with('message','Project deleted');
    }


    public function showPlots($slug){
        if (!auth()->user()->granted('plot_view')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $project = Project::whereSlug($slug)->first();
        $project->setRelation('p',$project->plots()->paginate(15));

        //Logging
        $activity = [
            'name'=>'Project',
            'description'=>'Viewing project plots',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity,$project));

        return view('project.showPlots',compact('project'));
    }
}


