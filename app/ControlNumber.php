<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ControlNumber extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'number';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $dates = ['deleted_at'];
    public $timestamps = true;
    protected $fillable = ['number','customer_id','plot_id','status_id','payment_period_id','constant_id','user_id','jijiControl','created_at'];

    public function logs(){
        return $this->morphMany(Activity::class,'loggable','subject_type','subject_id','number');
    }


    public function payments(){
        return $this->hasMany(Payment::class,'control_number','number');
    }

    public function transfers(){
        return $this->hasMany(Transfer::class,'control_number','number');
    }

    public function plot(){
        return $this->belongsTo(Plot::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function status(){
        return $this->belongsTo(Status::class);
    }

    public function constant(){
        return $this->belongsTo(Constant::class);
    }

    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function period(){
        return $this->belongsTo(PaymentPeriod::class,'payment_period_id');
    }
    public function torelance(){
        return $this->hasMany(Torelance::class,'control_number','number');
    }

    public function scopeDefaulter($query,$period){
        if ($period==1){
            return $query->where('payment_period_id',1)->where('created_at','<=',Carbon::now()->subDays(90))->where('status_id',1);
        }else{
            return $query->where('payment_period_id',2)->where('created_at','<=',Carbon::now()->subDays(360))->where('status_id',1);
        }
    }

    public function scopeNonDefaulter90($query){
        return $query->where('payment_period_id',1)->where('created_at','>',Carbon::now()->subDays(90))->orWhere->where('payment_period_id',1)->where('status_id',2);
    }

    public function scopeNonDefaulter360($query){
        return $query->where('payment_period_id',2)->where('created_at','>',Carbon::now()->subDays(360))->orWhere->where('payment_period_id',2)->where('status_id',2);

    }

    public function getIsDefaulterAttribute(){
        return $this->created_at->addDays($this->period->duration)->timestamp<Carbon::now()->timestamp?true:false;
    }

    public static function scopeYearDefaulter($query){
        return $query->where('payment_period_id',2)->where('created_at','<',Carbon::now()->subDays(360))->where('status_id',1)->count();
    }

    public static function scopeMonthDefaulter($query){
        return $query->where('payment_period_id',1)->where('created_at','<',Carbon::now()->subDays(90))->where('status_id',1)->count();
    }

    public function getAddedDaysAttribute(){    //added days to the defaulter
        $addedDays =  $this->torelance()->sum('days');

        $default_at =  $this->created_at->addDays($this->period->duration);
        $remainadded = ceil(($default_at->addDays($addedDays)->timestamp-Carbon::now()->timestamp)/60/60/24);

        return $addedDays>0?$addedDays.'/'.$remainadded.' Days':'none';
    }

    public function getAmountAttribute(){   //amount per sqr meter
        return $this->plot->block->locality->project->projectPrices()->where('plotuse_id',$this->plot->plotuse->id)->first()->amount;
    }

    public function getPaidAttribute(){ //Total Paid amount
        return $this->payments()->sum('amount');
    }

    public function getPaidPercentAttribute(){ //Total Paid amount
        return floor(($this->paid/$this->totalRequiredAcq)*100);
    }



    public function getRemainAttribute(){   //Remain amount to pay
        $remain = $this->totalRequiredAcq-$this->paid;
        return $remain;
    }

    public function getPlotDetailsAttribute(){  //Plot details
        $details = $this->plot->number." - ".$this->plot->block->code." - ".$this->plot->block->locality->name;
        return "{$details}";
    }

    public function getPlotDetailFormattedAttribute(){  //Plot details
        $details = $this->plot->number." - ".$this->plot->block->code." - ".$this->plot->block->locality->name." - ".$this->plot->block->locality->project->name;
        return "{$details}";
    }

    public function getRemainPeriodAttribute(){ //Remain Period for Payment
        $remainedPeriod = ceil((($this->created_at->addDays($this->period->duration)->timestamp)-(Carbon::now()->timestamp))/60/60/24);
        return $remainedPeriod;
    }

    public function getAreaAttribute(){ //get plot area
        return $this->plot->size;
    }

    public function getAcqFactorAttribute(){  //get Acquisition
        return $this->plot->block->locality->project->acqfactor;
    }

    public function getDmcFactorAttribute(){  //get dmcFactor
        return $this->plot->block->locality->project->dmcfactor;
    }

    public function getLandValueAttribute(){ //get Land Value
        return $this->amount*$this->area;
    }

    public function getTotalActualAcqAttribute(){   //Actual Acquisition
        return $this->acqFactor*$this->area;
    }

    public function getDccAttribute(){  //get dcc amount
        return $this->dmcFactor*($this->landValue-$this->totalActualAcq);
    }

    public function getPlotRateAttribute(){ //get plot rate value
        return $this->plot->block->locality->project->projectPrices()->where('plotuse_id',$this->plot->plotuse->id)->first()->rate;
    }

    public function getLandRateAttribute(){     //get land Rate
        return $this->plotRate*$this->area;
    }

    public function getAdaUsajiriAttribute(){   //get ada Usajiri
        return $this->constant->usajiriFactor*$this->landRate;
    }

    public function getUshuruSerikaliAttribute(){       //get ushuru Serikali
        return (($this->landRate-2000)*$this->constant->ushuruFactor)+$this->constant->ushuruAddition;
    }

    public function getPremiumAttribute(){      //get Premium value
        return $this->constant->advanceFactor*$this->landValue;
    }

    public function getQValueAttribute(){   //get quarter value
//        $month = new \Carbon\Carbon('02/05/2019');
        $month = $this->created_at;
        $m = $month->month;
        if ($m>=1 && $m<4){
            $q = 0.5;
        }elseif ($m>=4 && $m<7){
            $q=0.25;
        }elseif ($m>=7 && $m<10){
            $q=1;
        }else{
            $q=0.75;
        }
        return $q;
    }

    public function getKodiAttribute(){     //get kodi value
        return $this->landRate*$this->qValue;
    }

    public function getKodiStartAttribute(){
        switch ($this->qValue){
            case 1:
                return "01/07/".$this->created_at->format('Y');
                break;
            case 0.75:
                return "01/10/".$this->created_at->format('Y');
                break;
            case 0.5:
                return "01/01/".$this->created_at->addYear()->format('Y');
                break;
            case 0.25:
                return "01/04/".$this->created_at->addYear()->format('Y');
                break;
            default:
                return "01/07/".$this->created_at->format('Y');
        }
    }

    public function getKodiEndAttribute(){
        return "30/06/".$this->created_at->addYear()->format('Y');
    }

    public function getTotalRequiredAcqAttribute(){   //Required Acquisition
        return $this->landValue-($this->constant->hati+$this->adaUsajiri+$this->ushuruSerikali+$this->kodi+$this->premium+$this->dcc+$this->constant->ramani);
    }
}
