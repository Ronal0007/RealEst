<?php

namespace App\Jobs;

use App\Report;
use App\Suspence;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateDashboardJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Project payments data
        $projectPayment = array();

        foreach ($projects = \App\Project::all() as $project) {
            $projectPayment[$project->name] = $project->currentAmount;
        }

        $projectPayment['Suspences'] = 0;
        foreach ($suspences = Suspence::all() as $suspence) {
            $projectPayment['Suspences'] += $suspence->remain;
        }

//        $projectPayment['Suspences'] = Suspence::all()->sum('amount');

        $reportData = Report::where('name','dashboard')->first();
        if ($reportData==null){
            Report::create(['name'=>'dashboard','data'=>json_encode($projectPayment)]);
        }else{
            $reportData->update(['data'=>json_encode($projectPayment)]);
        }
    }
}
