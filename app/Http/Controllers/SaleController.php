<?php

namespace App\Http\Controllers;

use App\Bank;
use App\Constant;
use App\ControlNumber;
use App\Customer;
use App\Events\PaymentEvent;
use App\Gender;
use App\Http\Requests\SaleRequest;
use App\Jobs\ActivityLogJob;
use App\Jobs\UpdateDashboardJob;
use App\PaymentPeriod;
use App\Payment;
use App\Plot;
use App\Project;
use App\Suspence;
use App\Torelance;
use App\Transfer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use NumberToWords\NumberToWords;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->granted('control_view')){
            return abort(403,'Unauthorized request, Contact Administrator');
        };
        $sales = ControlNumber::nonDefaulter90()->orWhere->nondefaulter360()->paginate(10);

        //Logging
        $activity = [
            'name'=>'Sale',
            'description'=>'Viewing control numbers',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity));

        return view('sale.index',compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->granted('control_add')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $projects = Project::where('status_id',1)->pluck('name','id');
        $genders = Gender::pluck('name','id');
        $periods = PaymentPeriod::pluck('name','id');
        $constants = Constant::orderBy('year','desc')->pluck('year','id');

        //Logging
        $activity = [
            'name'=>'Sale',
            'description'=>'Attempting to sale plot',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity));

        return view('sale.new',compact('projects','genders','periods','constants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaleRequest $request)
    {
        if (!auth()->user()->granted('control_add')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }

        //Create customer
        $customer = Customer::create([
            'fname'=>$request->fname,
            'lname'=>$request->lname,
            'phone'=>$request->phone,
            'gender_id'=>$request->gender_id,
            'user_id'=>auth()->user()->id
        ]);

        $plot = Plot::find($request->plot_id);

//        Create control number
        $sale =  ControlNumber::create([

            'number'=>$this->generateControlNumber(
                $plot->block->locality->project->id,
                $plot->block->locality->id,
                $plot->block->code,
                $plot->number,
                $customer->id
            ),

            'customer_id'=>$customer->id,
            'plot_id'=>$request->plot_id,
            'status_id'=>1,
            'payment_period_id'=>$request->payment_period_id,
            'constant_id'=>$request->constant_id,
            'user_id'=>auth()->user()->id,
            'created_at'=>Carbon::parse($request->created_at)->format('Y-m-d H:i:s'),
            'jijiControl'=>$request->jijiControl
        ]);
        $plot->status_id = 2;
        $plot->save();

        //Logging
        $activity = [
            'name'=>'Sale',
            'description'=>'Selling plot',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity,$sale));

        return redirect(route('sale.index'))->with('message','Plot sold successfully No. ('.$sale->number.')');
    }

    /**
     * Show invoice.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($number)
    {
        if (!auth()->user()->granted('control_invoice')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $controlNumber = ControlNumber::find($number);
        $banks = Bank::all();

        $activity = [
            'name'=>'Sale',
            'description'=>'View Invoice',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity,$controlNumber));

        return view('sale.invoice',compact('controlNumber','banks'));
    }

    /**
     * Edit ControlNumber info.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($number)
    {
        if (!auth()->user()->granted('control_edit')){
        return abort(403,'Unauthorized request, Contact Administrator');
        }
        $sale = ControlNumber::find($number);
//        return [$sale->plot->id=>$sale->plot->number];

        if($sale->payments()->count()>0){
            return redirect()->back()->with('message','You cant edit because payments have already been done!');
        }
        $projects = Project::pluck('name','id');
        $genders = Gender::pluck('name','id');
        $periods = PaymentPeriod::pluck('name','id');
        $constants = Constant::orderBy('year','desc')->pluck('year','id');

        //Logging
        $activity = [
            'name'=>'Sale',
            'description'=>'Attempting to edit control number details',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity,$sale));

        return view('sale.edit',compact('sale','projects','genders','periods','constants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SaleRequest $request, $number)
    {
        if (!auth()->user()->granted('control_edit')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $sale = ControlNumber::find($number);
        $oldSale = $sale;
        if($sale->plot->id==$request->plot_id){ //Same plot

            //Update customer
            $customer = $sale->customer;
            $customer->fname = $request->fname;
            $customer->lname = $request->lname;
            $customer->gender_id = $request->gender_id;
            $customer->phone = $request->phone;
            $customer->save();

            //Update plot
            $sale->plot_id=$request->plot_id;
            $sale->payment_period_id = $request->payment_period_id;
            $sale->constant_id = $request->constant_id;
            $sale->created_at = Carbon::parse($request->created_at)->format('Y-m-d H:i:s');
            $sale->jijiControl=$request->jijiControl;
            $sale->save();

            //Logging
            $activity = [
                'name'=>'Sale',
                'description'=>'Control number edited',
                'user_id'=>auth()->id(),
                'logged_at'=>Carbon::now(),
                'old'=>$oldSale
            ];

            $this->dispatch(new ActivityLogJob($activity,$sale));


            return redirect(route('sale.index'))->with('message','Updated successfully');

        }else{
            $plot = Plot::find($request->plot_id);
            $plotstatus = $plot->status->id;

            if($plotstatus==2){ //Sold plot
                return redirect()->back()->with('msg','Plot ('.$plot->id.') already sold');
            }else{      //New plot

                //update former plot

                $sale->plot->status_id=1;
                $sale->plot->save();

                //Update customer
                $customer = $sale->customer;
                $customer->fname = $request->fname;
                $customer->lname = $request->lname;
                $customer->gender_id = $request->gender_id;
                $customer->phone = $request->phone;
                $customer->save();

                //Update controlNumber
                $sale->number=$this->generateControlNumber(
                    $plot->block->locality->project->id,
                    $plot->block->locality->id,
                    $plot->block->code,
                    $plot->number,
                    $customer->id
                );
                $sale->plot_id=$request->plot_id;
                $sale->payment_period_id = $request->payment_period_id;
                $sale->constant_id = $request->constant_id;
                $sale->created_at = Carbon::parse($request->created_at)->format('Y-m-d H:i:s');
                $sale->jijiControl=$request->jijiControl;
                $sale->save();

                //Update current plot
                $plot->status_id=2;
                $plot->save();

                //Logging
                $activity = [
                    'name'=>'Sale',
                    'description'=>'Control number edited',
                    'user_id'=>auth()->id(),
                    'logged_at'=>Carbon::now(),
                    'old'=>$oldSale
                ];

                $this->dispatch(new ActivityLogJob($activity,$sale));

                return redirect(route('sale.index'))->with('message','Updated successfully');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($number)
    {
        if (!auth()->user()->granted('control_delete')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $control = ControlNumber::find($number);
        $oldControl = $control;
        $control->customer->forceDelete();
        $control->torelance()->delete();
        $control->plot->status_id=1;
        $control->plot->save();

        //Logging
        $activity = [
            'name'=>'Sale',
            'description'=>'Delete control number',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now(),
            'old'=>$oldControl
        ];

        $this->dispatch(new ActivityLogJob($activity));

        $control->forceDelete();

        return redirect(route('sale.index'))->with('message','Control Number deleted!');
    }

    /**
     * @param $p project id
     * @param $l locality id
     * @param $b block code
     * @param $pl plot number
     * @param $c customer id
     * @return string control_number
     */
    private function generateControlNumber($p,$l,$b,$pl,$c){
        $project = str_pad($p,3,0,STR_PAD_LEFT);
        $locality = str_pad($l,3,0,STR_PAD_LEFT);
        $block = $b;
        $plot = str_pad($pl,3,0,STR_PAD_LEFT);
        $customer = str_pad($c,3,0,STR_PAD_LEFT);
        $number = $project.$locality.$block.$plot.$customer;
        return $number;
    }

    /**
     * Show payments
     *
     * @param $number
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function showPayment($number)
    {
        if (!auth()->user()->granted('payment_view')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $controlNumber = ControlNumber::find($number);

        //Logging
        $activity = [
            'name'=>'Sale',
            'description'=>'Viewing payments',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity,$controlNumber));

        return view('sale.payment',compact('controlNumber'));
    }


    /**
     * Receive payments
     *
     * @param Request $request
     * @param $number
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function receive(Request $request,$number)
    {
        if (!auth()->user()->granted('payment_add')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $this->validate($request,[
            'amount'=>'required',
            'depositor'=>'required'
        ]);
        $payment =[
            'amount'=>$request->amount,
            'slip'=>$request->slip,
            'depositor'=>$request->depositor,
            'user_id'=>auth()->user()->id
        ];
        $controlNumber = $this->storePayment($number,$payment);
        return redirect(route('sale.payment',$controlNumber->number));
    }

    private function storePayment($number,array $payment,Suspence $suspence=null)
    {
        $controlNumber = ControlNumber::find($number);
        if ($suspence){
            if($controlNumber->remain < $payment["amount"]){
                $payment["amount"] = $controlNumber->remain;
            }
            $payment = $controlNumber->payments()->save(new Payment($payment));

            //Logging
            $activity = [
                'name'=>'Payment',
                'description'=>'New Payment transfer from Suspence('.$suspence->control.')',
                'user_id'=>auth()->id(),
                'logged_at'=>Carbon::now()
            ];

            $this->dispatch(new ActivityLogJob($activity,$payment));

            $transfer = $controlNumber->transfers()->save(new Transfer([
                'suspence_id'=>$suspence->id,
                'payment_id'=>$payment->id,
                'amount'=>$payment["amount"],
                'user_id'=>auth()->user()->id
            ]));

            //Logging
            $activity = [
                'name'=>'Transfer',
                'description'=>'New Transfer from suspence('.$transfer->suspence->control.') to ('.$transfer->control->number.')',
                'user_id'=>auth()->id(),
                'logged_at'=>Carbon::now()
            ];

            $this->dispatch(new ActivityLogJob($activity,$transfer));


        }else{
            $payment = $controlNumber->payments()->save(new Payment($payment));

            //Logging
            $activity = [
                'name'=>'Payment',
                'description'=>'New Payment',
                'user_id'=>auth()->id(),
                'logged_at'=>Carbon::now()
            ];

            $this->dispatch(new ActivityLogJob($activity,$payment));
        }

        //If payment completed
        $amount = $controlNumber->totalRequiredAcq;
        $payedAmount = $controlNumber->paid;

        if ($amount<=$payedAmount) {
            $controlNumber->status_id=2;
            $controlNumber->save();
        }

        $this->dispatch(new UpdateDashboardJob());

        return $controlNumber;
    }

    /**
     * @param Payment $payment
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editPayment(Payment $payment,Request $request){
        if (!auth()->user()->granted('payment_edit')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $this->validate($request,[
            'amount'=>'required|min:1',
            'depositor'=>'required'
        ]);
        $input = $request->except('_token');
        $input['created_at']=Carbon::now()->format('Y-m-d H:i:s');
        $input['slip'] = 'Edited';
        $input['user_id'] = auth()->user()->id;
        $oldPayment = $payment;
        $payment->update($input);
        $control = $payment->controlNumber;
        $status = $control->statdus->id;
        if ($control->paid>=$control->totalRequiredAcq){
            $control->status_id = 2;
            $control->save();
        }else{
            $control->status_id = 1;
            $control->save();
        }

        //Logging
        $activity = [
            'name'=>'Payment',
            'description'=>'Edit Payment'.$status!=$control->status->id?', ControlNumber status changed':'',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now(),
            'old'=>$oldPayment
        ];

        $this->dispatch(new ActivityLogJob($activity,$payment));
        $this->dispatch(new UpdateDashboardJob());

        return redirect(route('sale.payment',$control->number))->with('message','Edited successfully');
    }

    /**
     * Print payment Receipt
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function printReceipt($id){
        if (!auth()->user()->granted('payment_print')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $payment = Payment::find($id);
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');

        //Logging
        $activity = [
            'name'=>'Payment',
            'description'=>'Print Payment receipt',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity,$payment));

        return view('sale.receipt',compact('payment','numberTransformer'));
    }

    /**
     * Print Clearance
     *
     * @param $number
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function printClearance($number){
        if (!auth()->user()->granted('payment_clearance')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $controlNumber = ControlNumber::find($number);

        //Logging
        $activity = [
            'name'=>'Payment',
            'description'=>'Print clearance form',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity,$controlNumber));

        return view('sale.clearance',compact('controlNumber'));
    }

    /**
     * Search control number
     *
     * @param Request $request
     */
    public function search(Request $request){       //Search control Number
        if (!auth()->user()->granted('control_view')){
            return abort(403,'Unauthorized request, Contact Administrator');
        };
        $number = Input::get('search');
        $sales = ControlNumber::where('number','like','%'.$number.'%')->orderBy('created_at','desc')->paginate(8)->setPath ( '' );
        $pagination = $sales->appends ( array (
            'search' => Input::get ( 'search' )
        ) );

        //Logging
        $activity = [
            'name'=>'Sale',
            'description'=>'Searching for control Number',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity));

        return view('sale.index',compact('sales','number'))->withQuery($number);
    }


    /**
     * View defaulters
     *
     * @param $period
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function defaulter($period){
        if (!auth()->user()->granted('defaulter_view')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }

        switch ($period){
            case '90':
                //Logging
                $activity = [
                    'name'=>'Defaulter',
                    'description'=>'Viewing 90 days defaulter',
                    'user_id'=>auth()->id(),
                    'logged_at'=>Carbon::now()
                ];

                $this->dispatch(new ActivityLogJob($activity));

                $controlNumbers = ControlNumber::defaulter(1)->paginate(10);
                break;
            case '360':
                //Logging
                $activity = [
                    'name'=>'Defaulter',
                    'description'=>'Viewing 360 days defaulter',
                    'user_id'=>auth()->id(),
                    'logged_at'=>Carbon::now()
                ];

                $this->dispatch(new ActivityLogJob($activity));

                $controlNumbers = ControlNumber::defaulter(2)->paginate(10);
                break;
            default:
                //Logging
                $activity = [
                    'name'=>'Defaulter',
                    'description'=>'Viewing 90 days defaulter',
                    'user_id'=>auth()->id(),
                    'logged_at'=>Carbon::now()
                ];

                $this->dispatch(new ActivityLogJob($activity));

                $period=90;
                $controlNumbers = ControlNumber::defaulter(1)->paginate(10);
                break;
        }

        return view('sale.defaulter',compact('controlNumbers','period'));
    }

    /**
     * Add payment period to defaulter
     *
     * @param Request $request
     * @param $number
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function torelate(Request $request,$number){
        if (!auth()->user()->granted('defaulter_torelate')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $control = ControlNumber::find($number);
        $torelance = new Torelance(['days'=>$request->days]);
        $torelance = $control->torelance()->save($torelance);

        //Logging
        $activity = [
            'name'=>'Defaulter',
            'description'=>'Extending ('.$torelance->days.') payment days to defaulter('.$control->number.')',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity));

        return redirect()->back();
    }

    /**
     * Revoke defaulter
     *
     * @param $number
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function revoke($number){
        if (!auth()->user()->granted('defaulter_revoke')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }

        $control = ControlNumber::find($number);
        $oldControl = $control;
        if($control->payments()->count()>0){
            $suspence = new Suspence();
            $suspence->customer = $control->customer->name;
            $suspence->amount = $control->payments()->sum('amount');
            $suspence->plot = $control->plotDetailFormatted;
            $suspence->control = $control->number;
            $suspence->user_id = auth()->user()->id;
            $suspence->save();

            //Logging
            $activity = [
                'name'=>'Suspence',
                'description'=>'Transfering Defaulter('.$control->number.') money to suspence account',
                'user_id'=>auth()->id(),
                'logged_at'=>Carbon::now(),
                'old'=>$oldControl
            ];

            $this->dispatch(new ActivityLogJob($activity,$suspence));

            $control->payments()->delete();
            $control->torelance()->delete();
            $control->customer->forceDelete();
            $control->plot->status_id=1;
            $control->plot->save();

            //Logging
            $activity = [
                'name'=>'Sale',
                'description'=>'Deleting control Number after transfer money to suspence account',
                'user_id'=>auth()->id(),
                'logged_at'=>Carbon::now(),
                'old'=>$oldControl
            ];

            $this->dispatch(new ActivityLogJob($activity));
            $this->dispatch(new UpdateDashboardJob());

            $control->forceDelete();

            return redirect()->back()->with('message','Payments shifted to Suspence Account');
        }else{
            $control->torelance()->delete();
            $control->customer->forceDelete();
            $control->plot->status_id=1;
            $control->plot->save();

            //Logging
            $activity = [
                'name'=>'Sale',
                'description'=>'Deleting control Number with no money in suspence account',
                'user_id'=>auth()->id(),
                'logged_at'=>Carbon::now(),
                'old'=>$oldControl
            ];

            $this->dispatch(new ActivityLogJob($activity));
            $this->dispatch(new UpdateDashboardJob());

            $control->forceDelete();
            return redirect()->back()->with('message','Defaulter revoked!');
        }
    }

    /**
     * View Suspences
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function suspence(){
        $suspences = Suspence::paginate(15);

        //Logging
        $activity = [
            'name'=>'Suspence',
            'description'=>'Viewing suspences',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity));

        return view('sale.suspence',compact('suspences'));
    }

    /**
     * search suspence
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchSuspence(){ //View suspences
        $search = Input::get('search');
        $suspences = Suspence::where('control','like',"%$search%")->orderBy('created_at','desc')->paginate(15)->setPath ( '' );
        $pagination = $suspences->appends ( array (
            'search' => Input::get ( 'search' )
        ) );

        //Logging
        $activity = [
            'name'=>'Suspence',
            'description'=>'Searching for ('.$search.') in suspences',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity));

        return view('sale.suspence',compact('suspences','search'));
    }

    /**
     * Transfer suspence money
     *
     * @param Request $request
     * @param $number
     * @return string
     */
    public function transfer(Suspence $suspence){
        if (!auth()->user()->granted('payment_transfer')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        Session::put('suspence',$suspence);

        //Logging
        $activity = [
            'name'=>'Suspence',
            'description'=>'Attempting to transfer money from suspence ('.$suspence->control.')',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity));

        return redirect(route('sale.index'));
    }

    public function transferTo($number){
        $suspence =  Session('suspence');
//        $controlNumber = ControlNumber::find($number);
//        if()
        $payment =[
            'amount'=>$suspence->remain,
            'slip'=>'Transfer from Suspence',
            'depositor'=>$suspence->customer,
            'user_id'=>auth()->user()->id
        ];

        $controlNumber = $this->storePayment($number,$payment,$suspence);
        if($suspence->remain <= 0){
            $suspence->delete();
        }
        Session::forget('suspence');
        return redirect(route('sale.payment',$controlNumber->number));
    }


    public function cancelTransfer(){
        $suspence =  Session('suspence');

        //Logging
        $activity = [
            'name'=>'Suspence',
            'description'=>'Transfer from suspence ('.$suspence->control.') canceled',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity));

        Session::forget('suspence');
        return redirect(route('sale.index'));
    }

}
