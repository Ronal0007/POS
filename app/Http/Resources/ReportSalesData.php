<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportSalesData
{
    public $name;
    public $qty;
    public $amount;
    public $lossAmount;
    public $lossQty;
    public $discount;
    public function __construct()
    {

    }
}
