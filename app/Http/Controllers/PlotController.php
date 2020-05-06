<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Block;
use App\Http\Resources\PlotDetails;
use App\Http\Resources\PlotResource;
use App\Jobs\ActivityLogJob;
use App\Locality;
use App\Plot;
use App\Plotuse;
use App\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PlotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->granted('plot_view')){
            return abort(403,'You dont have permission to access this page, Contact Administrator');
        }

        $plots = Plot::paginate(8);

        //Logging
        $activity = [
            'name'=>'Plot',
            'description'=>'Viewing plots',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity));

        return view('plot.index',compact('plots'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->granted('plot_add')){
            return abort(403,'You dont have permission to access this page, Contact Administrator');
        }

        $projects = Project::pluck('name','id');
        $plotuses = Plotuse::pluck('name','id');

        //Logging
        $activity = [
            'name'=>'Plot',
            'description'=>'Attempting to create plot',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity));

        return view('plot.create',compact('projects','plotuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->granted('plot_add')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }

        $this->validate($request,[
            'number'=>'required|min:1',
            'size'=>'required|min:1',
            'surveyNumber'=>'required',
            'registeredNumber' =>'required',
            'block_id'=>'required|min:1',
            'plotuse_id'=>'required|min:1'
        ]);

        $input = $request->all();
        $input['user_id']=auth()->user()->id;
        $block = Block::find($request->block_id);
        $blockPlots = $block->plots()->get();
        if($blockPlots->where('number',$request->number)->count()>0){
            return redirect()->back()->withInput()->with('message','Plot ('.$request->number.') already exists in block('.$block->code.')');
        }else{
            $plot = Plot::create($input);

            //Logging
            $activity = [
                'name'=>'Plot',
                'description'=>'Create plot',
                'user_id'=>auth()->id(),
                'logged_at'=>Carbon::now()
            ];

            $this->dispatch(new ActivityLogJob($activity,$plot));

            return redirect(route('plot.index'))->with('message','Plot added successfully');
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
    public function edit($slug)
    {
        if (!auth()->user()->granted('plot_edit')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }

        $plot = Plot::whereSlug($slug)->first();
        $projects = Project::pluck('name','id');
        $plotuses = Plotuse::pluck('name','id');

        //Logging
        $activity = [
            'name'=>'Plot',
            'description'=>'Attempting to edit plot',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity,$plot));

        return view('plot.edit',compact('projects','plot','plotuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        if (!auth()->user()->granted('plot_edit')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }

        $this->validate($request,[
            'number'=>'required|min:1',
            'size'=>'required|min:1',
            'surveyNumber'=>'required',
            'registeredNumber' =>'required',
            'block_id'=>'required|min:1',
            'plotuse_id'=>'required|min:1'
        ]);

        $plot = Plot::whereSlug($slug)->first();
        $oldPlot = $plot;

        if ($request->block_id==$plot->block->id){
            $plot->update($request->all());

            //Logging
            $activity = [
                'name'=>'Plot',
                'description'=>'Update plot',
                'user_id'=>auth()->id(),
                'logged_at'=>Carbon::now(),
                'old'=>$oldPlot
            ];

            $this->dispatch(new ActivityLogJob($activity,$plot));

            return redirect(route('plot.index'))->with('message','Plot updated');
        }else{
            $plotBlockCount = Block::find($request->block_id)->plots()->whereNumber($request->number)->count();
            if ($plotBlockCount>0){
                return redirect()->back()->with('message','Plot already exists in this block');
            }else{
                $plot->update($request->all());

                //Logging
                $activity = [
                    'name'=>'Plot',
                    'description'=>'Update plot',
                    'user_id'=>auth()->id(),
                    'logged_at'=>Carbon::now(),
                    'old'=>$oldPlot
                ];

                $this->dispatch(new ActivityLogJob($activity,$plot));


//                return "updated";

                return redirect(route('plot.index'))->with('message','Plot updated');
            }
        }




    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletePlot($slug)
    {
        if (!auth()->user()->granted('plot_delete')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $plot =  Plot::whereSlug($slug)->first();
        if ($plot->status->id==1){

            //Logging
            $activity = [
                'name'=>'Plot',
                'description'=>'Delete plot',
                'user_id'=>auth()->id(),
                'logged_at'=>Carbon::now(),
                'old'=>$plot
            ];

            $this->dispatch(new ActivityLogJob($activity));

            $plot->forceDelete();
            return redirect(route('plot.index'))->with('message','Plot deleted');
        }else{
            return redirect(route('plot.index'))->with('message','Plot already sold, can not be deleted!');
        }
    }

    public function getPlots(Request $request){
        $block = $request->block;
        $plotDetails = array();
        $count = Block::find($block)->plots()->count();
        $plots = Block::find($block)->plots()->where('status_id','!=','2')->orderBy('number')->get();

        foreach ($plots as $plot) {
            $amount = $plot->block->locality->project->projectPrices()->where('plotuse_id',$plot->plotuse->id)->first()->amount;
//        return $amount;
            $plotDetails[$plot->id] = "Plot No. ".str_pad($plot->number,3,0,STR_PAD_LEFT)." Block: "
                .$plot->block->code." Location: '".$plot->block->locality->name."' Plot use: ".$plot->plotuse->name.
                ", Area: ".$plot->size.", Amount: ".number_format(($plot->size*$amount));
        }

        $plots =  $plots->pluck('id','number');
//    return $plots;

        $data = new PlotDetails($count,$plots,$plotDetails);
        $data =  response()->json($data);
        return $data;
    }

    public function getPlotsWhenCreate(Request $request){
        $block = $request->block;
        $plots = Block::find($block)->plots()->pluck('number');
        return response()->json($plots);
    }
}
