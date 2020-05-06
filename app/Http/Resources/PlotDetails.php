<?php

namespace App\Http\Resources;

class PlotDetails
{
    public $count,$data;
    public $plotdetails;

    public function __construct($count,$data,$plotdetails){
        $this->count = $count;
        $this->data = $data;
        $this->plotdetails = $plotdetails;
    }
}
