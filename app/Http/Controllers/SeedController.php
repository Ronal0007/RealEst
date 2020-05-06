<?php

namespace App\Http\Controllers;

use App\Block;
use App\Constant;
use App\ControlNumber;
use App\Customer;
use App\Http\Resources\PlotUseData;
use App\Jobs\SalePlotJob;
use App\Locality;
use App\Payment;
use App\Plot;
use App\Plotuse;
use App\Project;
use App\ProjectPrice;
use App\Role;
use App\Status;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SeedController extends Controller
{
    public function init(){
        $roles = ['admin','manager','dataentry','land'];
        foreach ($roles as $role){
            Role::create(['name'=>$role]);
        }

        $user = ['fname'=>'Admin','lname'=>'Admin', 'email'=>'jig@jig.com', 'password'=>Hash::make('jig'),'phone'=>'255652142241','gender_id'=>1,'role_id'=>1,'status_id'=>1];
        User::create($user);

        $statuses = ['active','inactive'];
        foreach ($statuses as $status){
            Status::create(['name'=>$status]);
        }

        DB::table('plotuses')->insert([
            ['name'=>'Commercial'],
            ['name'=>'Residential'],
            ['name'=>'Commercial/Residential'],
            ['name'=>'School'],
            ['name'=>'Market'],
            ['name'=>'Government'],
            ['name'=>'Worship'],
            ['name'=>'Godown'],
            ['name'=>'Dispensary'],
            ['name'=>'Filling Station'],
            ['name'=>'Car wash'],
            ['name'=>'Industrial'],
            ['name'=>'Garage'],
            ['name'=>'Service Industry']
        ]);

        DB::table('payment_periods')->insert([
            ['name'=>'90 Days','duration'=>90],
            ['name'=>'360 Days','duration'=>360]
        ]);

        DB::table('genders')->insert([
            ['name'=>'Male'],
            ['name'=>'Female']
        ]);

        Constant::create([
            'year'=>'2019',
            'advanceFactor'=>0.025,
            'ramani'=>20000,
            'hati'=>50000,
            'usajiriFactor'=>0.2,
            'ushuruSubstraction'=>2000,
            'ushuruFactor'=>0.05,
            'ushuruAddition'=>190
        ]);

        \App\Bank::create(['name'=>'NMB','account'=>'6548995336636']);
        \App\Bank::create(['name'=>'CRDB','account'=>'646546346546']);

        return "initialized";
    }

    public function seed(){
        $projects = ['Nala','Mahomanyika','Nkuhungu','Mazengo','Kikuyu'];
        foreach ($projects as $project) {
            $this->project($project);
        }
        return 'project seede...';
    }

    private function project($ProjectName){
        $data = [];

        $projectPrices = array();
        $plotuses = Plotuse::all();

        foreach ($plotuses as $plotuse){
            $name = str_replace('/','_',$plotuse->name);
            $name = str_replace(' ','_',$name);
            $data[$name] = $plotuse->id;
        }

        $project = Project::create(['name'=>$ProjectName,'acqfactor'=>3000,'dmcfactor'=>0.14,'status_id'=>1,'user_id'=>1]);

        //Fire Logs Event

        foreach ($data as $key=>$value){
            $projectPrices[] = new PlotUseData($value,5000,32);
        }
//        return $projectPrices;

        foreach ($projectPrices as $projectPrice) {
            ProjectPrice::create(['amount'=>$projectPrice->amount,'rate'=>$projectPrice->rate,'plotuse_id'=>$projectPrice->plotid,'project_id'=>$project->id]);
        }
    }

    public function locality(){
        $projects = Project::all();
        foreach ($projects as $project) {
            for ($i=1;$i<=6;$i++){
                $locality = new Locality(['name'=>$project->name.$i,'user_id'=>1]);
                $project->localities()->save($locality);
            }
        }
        return 'locality seede...';
    }

    public function block(){
        $codes = ['AA','BB','CC','DD','EE'];
        $localities = Locality::all();
        foreach ($localities as $locality) {
            foreach ($codes as $code) {
                $survey = substr(str_shuffle('0123456789'),0,4).substr(str_shuffle('ABCDEFGHIJ'),0,4);
                $block = new Block(['code'=>$code,'surveyNumber'=>$survey,'user_id'=>1]);
                $locality->blocks()->save($block);
            }
        }
        return 'block seede...';
    }

    public function plot($project){
       $blocks = Project::find($project)->blocks()->get();
        foreach ($blocks as $block) {
            for($i=1;$i<=50;$i++){
                try {
                    $plotuse = random_int(1, 14);
                    $size = random_int(400,800);
                    $survey = substr(str_shuffle('0123456789'),0,4).substr(str_shuffle('ABCDEFGHIJ'),0,4);
                    $plot = new Plot(['number'=>$i,'size'=>$size,'surveyNumber'=>$survey,'registeredNumber'=>$survey,'plotuse_id'=>$plotuse,'status_id'=>1,'user_id'=>1]);
                    $block->plots()->save($plot);
                } catch (\Exception $e) {
                    return $e;
                }

            }
        }
        return 'plot seede...'.$project;
    }

    public function sale($project){
        for($i=1;$i<=50;$i++){

            $plot = Plot::find(rand(1,7500));
            if ($plot->status->id==2){
                continue;
            }
            //Create customer
            $customer = Customer::create([
                'fname'=>'CustomerF'.$project.$i,
                'lname'=>'CustomerL'.$project.$i,
                'phone'=>'07'.str_shuffle('12365478'),
                'gender_id'=>rand(1,2),
                'user_id'=>auth()->user()->id
            ]);

//        Create control number
            $period = rand(1,2);
            $sale =  ControlNumber::create([

                'number'=>$this->generateControlNumber(
                    $plot->block->locality->project->id,
                    $plot->block->locality->id,
                    $plot->block->code,
                    $plot->number,
                    $customer->id
                ),

                'customer_id'=>$customer->id,
                'plot_id'=>$plot->id,
                'status_id'=>1,
                'payment_period_id'=>$period,
                'constant_id'=>1,
                'user_id'=>auth()->user()->id,
                'created_at'=>Carbon::createFromTimestamp($period==2?mt_rand(Carbon::now()->subDays(450)->timestamp,Carbon::now()->subDays(30)->timestamp):mt_rand(Carbon::now()->subDays(120)->timestamp,Carbon::now()->subDays(30)->timestamp))->format('Y-m-d H:i:s'),
                'jijiControl'=>str_shuffle('96857432140321462')
            ]);
            $plot->status_id = 2;
            $plot->save();
        }


        return 'Processing....Project:'.$project;
    }

    private function generateControlNumber($p,$l,$b,$pl,$c){
        $project = str_pad($p,3,0,STR_PAD_LEFT);
        $locality = str_pad($l,3,0,STR_PAD_LEFT);
        $block = $b;
        $plot = str_pad($pl,3,0,STR_PAD_LEFT);
        $customer = str_pad($c,3,0,STR_PAD_LEFT);
        $number = $project.$locality.$block.$plot.$customer;
        return $number;
    }

    public function payment(){
        $completed = 0;
        $select = ControlNumber::where('status_id',1)->get()->pluck('number')->toArray();
        for ($i=0;$i<100;$i++){
            $controlNumber = ControlNumber::find($select[array_rand($select)]);
            if ($controlNumber->status->id==2){
                continue;
            }
            for($pay=0;$pay<floor($controlNumber->totalRequiredAcq/800000);$pay++){

                $payment = new Payment([
                    'amount'=>$controlNumber->remain<800000?floor(900000/3):800000,
                    'slip'=>'',
                    'depositor'=>'Test Depositor',
                    'user_id'=>auth()->user()->id
                ]);
            }
            $controlNumber->payments()->save($payment);

            //If payment completed
            $amount = $controlNumber->totalRequiredAcq;
            $payedAmount = $controlNumber->paid;

            if ($amount<=$payedAmount) {
                $controlNumber->status_id=2;
                $controlNumber->save();
                $completed++;
            }
        }

        return 'payment finished, Completed '.$completed;
    }
}
