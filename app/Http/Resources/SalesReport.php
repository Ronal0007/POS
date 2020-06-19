<?php

namespace App\Http\Resources;

use App\Loss;
use App\Product;
use App\Sale;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesReport
{
    public $todayTotal = 0;
    public $yesterdayTotal =0;
    public $weekTotal = 0;
    public $monthTotal = 0;
    public $from;
    public $topSelling = [];
    public $topProfiting = [];
    public $expiringProduct = [];
    public $data = [];
    public $sales;
    public $profitLoss = [
        'profit'=>0,
        'loss'=>0,
        'expenses'=>0,
        'expensesData',
        'netProfit'=>0
    ];

    function __construct()
    {
        $this->sales = new ReportSalesData();

        $today = date("Y-m-d", strtotime( '0 days' ) );
        $todaySales = Sale::whereDate('created_at','=', $today )->get();

        foreach ($todaySales as $sale){
            $this->todayTotal+=($sale->amount-$sale->discount);
        }


        $yesterday = date("Y-m-d", strtotime( '-1 days' ) );
        $yesterdaySales = Sale::whereDate('created_at','=', $yesterday )->get();

        foreach ($yesterdaySales as $sale){
            $this->yesterdayTotal+=($sale->amount-$sale->discount);
        }

        $week = date("Y-m-d", strtotime( '-7 days' ) );
        $weekSales = Sale::whereDate('created_at','>=', $week )->whereDate('created_at','!=', date("Y-m-d", strtotime( '0 days' ) ) )->get();
        $weekLoss = Loss::whereDate('created_at','>=', $week )->whereDate('created_at','!=', date("Y-m-d", strtotime( '0 days' ) ) );
        $this->profitLoss['loss'] = $weekLoss->sum('loss');

        foreach ($weekSales as $sale){
            $this->weekTotal+=($sale->amount-$sale->discount);
        }

        $month = date("Y-m-d", strtotime( '-1 month' ) );
        $monthSales = Sale::whereDate('created_at','>=', $month )->whereDate('created_at','!=', date("Y-m-d", strtotime( '0 days' ) ) )->get();

        foreach ($monthSales as $sale){
            $this->monthTotal+=($sale->amount-$sale->discount);
        }

        $products = Product::all();
        foreach ($products as $product) {
            if(!$product->expire_at){
                continue;
            }
            $alert = $product->alert;
            $expire = \Carbon\Carbon::now()->addDay($alert);
            if (\Carbon\Carbon::now()>$product->expire_at){
                $this->expiringProduct[$product->name] = "Expired";
            }elseif ($expire>=$product->expire_at){
                $this->expiringProduct[$product->name] = $product->expire_at->diffForHumans();
            }
        }

    }
}
