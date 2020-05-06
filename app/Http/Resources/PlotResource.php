<?php

namespace App\Http\Resources;


class PlotResource
{
    public $count,$data;

    public function __construct($count,$data){
        $this->count = $count;
        $this->data = $data;
    }
}
