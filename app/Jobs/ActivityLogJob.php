<?php

namespace App\Jobs;

use App\Activity;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ActivityLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Model
     */
    private $model;
    /**
     * @var array
     */
    private $activity;

    /**
     * Create a new job instance.
     *
     * @param array $activity
     * @param Model $model
     */
    public function __construct(array $activity,Model $model=null)
    {
        //
        $this->model = $model;
        $this->activity = $activity;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->model){
            $this->model->logs()->create($this->activity);
        }else{
            Activity::create($this->activity);
        }
    }
}
