<?php

namespace App\Http\Resources;

use App\Activity;
use App\Report;
use App\Plot;

class Dashboard
{
    public $data,$userLogs,$otherLogs;

    public function __construct()
    {
        //Project payments data
        if(Report::whereName('dashboard')->count()>0){

        $projectPayment = json_decode(Report::whereName('dashboard')->first()->data);
        }else{
            $projectPayment = null;
        }

        //Plot use info
        $plotUseName = array();
        $plotUseCount = array();
        $color = array();
        $plotuses = \App\Plotuse::all();
        if(Plot::all()->count()>0){
            foreach ($plotuses as $plotus) {
                $plotUseName[]=$plotus->name;
                $plotUseCount[]=$plotus->plots()->count();
                $color[] = 'rgba('.rand(1,255).', '.rand(1,255).', '.rand(1,255).', 0.7)';
            }
        }else{
            $plotUseName=null;
            $plotUseCount=null;
        }
        $this->data = array('project'=>$projectPayment,'plotUseName'=>$plotUseName,'plotUseCount'=>$plotUseCount,'color'=>$color);

        //Log data
        $this->userLogs = Activity::UserLogs()->orderBy('created_at','desc')->limit(5)->get();
        $this->otherLogs = Activity::OtherLogs()->orderBy('created_at','desc')->limit(5)->get();
    }
}
