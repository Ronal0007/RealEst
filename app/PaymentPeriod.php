<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentPeriod extends Model
{
    protected $fillable = ['name','duration'];
}
