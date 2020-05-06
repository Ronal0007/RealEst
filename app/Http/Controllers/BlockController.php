<?php

namespace App\Http\Controllers;

use App\Block;
use App\Http\Resources\PlotResource;
use App\Jobs\ActivityLogJob;
use App\Locality;
use App\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->granted('block_view')){
            return abort(403,'You dont have permission to access this page, Contact Administrator');
        }

        $activity = [
            'name'=>'Block',
            'description'=>'Viewing Blocks',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity));

        $blocks = Block::paginate(8);
        $projects = Project::pluck('name','id');
        return view('block.index',compact('blocks','projects'));
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
        if (!auth()->user()->granted('block_add')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $this->validate($request,[
            'code'=>'required',
            'surveyNumber'=>'required',
            'locality_id'=>'min:1'
        ]);
        $locality = Locality::find($request->locality_id);
        $locBlock = $locality->blocks()->get();
        $locBlockTrashed = $locality->blocks()->onlyTrashed()->get();
        if($locBlock->where('code',$request->code)->count()>0){
            //Logging
            $activity = [
                'name'=>'Block',
                'description'=>'Attempting create already exists block('.$request->code.')',
                'user_id'=>auth()->id(),
                'logged_at'=>Carbon::now()
            ];

            $this->dispatch(new ActivityLogJob($activity));

            return redirect(route('block.index'))->with('message','Block ('.$request->code.') already exists in ('.$locality->name.') locality');
        }elseif ($locBlockTrashed->where('code',$request->code)->count()>0){
            $trashed = $locBlockTrashed->where('code',$request->code)->first();
            $oldBlock = $trashed;
            $trashed->deleted_at = null;
            $trashed->created_at = Carbon::now();
            $trashed->save();

            $activity = [
                'name'=>'Block',
                'description'=>'Revoking deleted Block',
                'user_id'=>auth()->id(),
                'logged_at'=>Carbon::now(),
                'old'=>$oldBlock
            ];

            $this->dispatch(new ActivityLogJob($activity,$trashed));

            return redirect(route('block.index'))->with('message','Block added successfully');
        }else{

            $input = $request->all();
            $input['user_id'] = auth()->user()->id;
            $block = Block::create($input);

            $activity = [
                'name'=>'Block',
                'description'=>'Create new Block',
                'user_id'=>auth()->id(),
                'logged_at'=>Carbon::now()
            ];

            $this->dispatch(new ActivityLogJob($activity,$block));

            return redirect(route('block.index'))->with('message','Block added successfully');
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
        if (!auth()->user()->granted('block_edit')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $this->validate($request,[
            'code'=>'required',
            'surveyNumber'=>'required',
            'locality_id'=>'min:1'
        ]);

        $block = Block::find($id);
        if($request->locality_id!=$block->locality_id){
            $locality = Locality::find($request->locality_id);
            $locBlock = $locality->blocks()->get();
            if($locBlock->where('code',$request->code)->count()>0){
                return redirect(route('block.index'))->with('message','Block ('.$request->code.') already exists in ('.$locality->name.') locality');
            }
        }

        $oldBlock = $block;
        $block->code = $request->code;
        $block->surveyNumber = $request->surveyNumber;
        $block->locality_id = $request->locality_id;
        $block->save();

        $activity = [
            'name'=>'Block',
            'description'=>'Updating Block',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now(),
            'old'=>$oldBlock
        ];

        $this->dispatch(new ActivityLogJob($activity,$block));

        return redirect(route('block.index'))->with('message','Block updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteblock($slug)
    {
        if (!auth()->user()->granted('block_delete')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $block = block::whereSlug($slug)->first();
        if($block->plots()->count()==0){

            $activity = [
                'name'=>'Block',
                'description'=>'Deleting Block',
                'user_id'=>auth()->id(),
                'logged_at'=>Carbon::now(),
                'old'=>$block
            ];

            $this->dispatch(new ActivityLogJob($activity));

            $block->delete();
            return redirect(route('block.index'))->with('message','block deleted!');
        }else{
            return redirect(route('block.index'))->with('message','Cant delete block with Blocks data!');
        }

    }

    public function getBlocks(Request $request){
        $count = Locality::find($request->locality)->blocks()->count();
        $blocks = Locality::find($request->locality)->blocks()->pluck('code','id');
        $data = new PlotResource($count,$blocks);
        $data =  response()->json($data);
        
        return $data;
    }

    public function showPlots($slug){
        if (!auth()->user()->granted('plot_view')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $block = Block::whereSlug($slug)->first();
        $block->setRelation('p',$block->plots()->paginate(15));

        $activity = [
            'name'=>'Block',
            'description'=>'Viewing plot from block',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity,$block));

        return view('block.showPlots',compact('block'));
    }
}
