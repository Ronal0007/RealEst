<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectPlotUse
{
    public $name,$plot=0,$sold=0,$price;

    public function __construct($name,$price)
    {
        $this->name = $name;
        $this->price = $price;
    }
}
