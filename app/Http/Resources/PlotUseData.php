<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlotUseData
{
    public $plotid,$amount,$rate;

    public function __construct($id,$amount,$rate)
    {
        $this->plotid = $id;
        $this->amount = $amount;
        $this->rate = $rate;
    }
}
