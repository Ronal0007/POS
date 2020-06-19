<?php

namespace App\Http\Controllers;

use App\Expense;
use App\Http\Resources\SalesReport;
use App\Loss;
use App\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public $report;

    function __construct()
    {
        $this->report = new SalesReport();
    }

    public function today(){

        /*
         * Get sales data
         */
        $sales = Sale::whereDate('created_at','=', date("Y-m-d", strtotime( '0 days' ) ) )->get();
        $losses = Loss::whereDate('created_at','=', date("Y-m-d", strtotime( '0 days' ) ) )->get();
        $expenses = Expense::whereDate('created_at','=', date("Y-m-d", strtotime( '0 days' ) ) )->get();

        $this->report->profitLoss['expenses'] = $expenses->sum('amount');  //Sum of expenses
        $this->report->profitLoss['expensesData'] = $expenses; //Expense Data


        $this->report->profitLoss['loss'] = $losses->sum('loss');


        foreach ($sales as $sale){
            $this->report->profitLoss['profit']+=($sale->amount-(($sale->buyingPrice*$sale->quantity)+$sale->discount));
        }

        $this->report->profitLoss['netProfit'] = $this->report->profitLoss['profit']- $this->report->profitLoss['loss']-$this->report->profitLoss['expenses'];

//        Top profit
        $productProfit = array();
        foreach ($sales as $sale){
            if (array_key_exists($sale->product->name,$productProfit)){
                $productProfit[$sale->product->name]+=($sale->amount-(($sale->buyingPrice*$sale->quantity)+$sale->discount));
            }else{
                $productProfit[$sale->product->name]=($sale->amount-(($sale->buyingPrice*$sale->quantity)+$sale->discount));
            }
        }

        foreach ($losses as $loss) {
            if(array_key_exists($loss->product->name,$productProfit)){
                $productProfit[$loss->product->name]-=$loss->loss;
            }
        }

        arsort($productProfit);
        $this->report->topProfiting = array_slice($productProfit,0,5);

//        TopSold Item
        $topSold = array();
        foreach ($sales as $sale){
            if (array_key_exists($sale->product->name,$topSold)){
                $topSold[$sale->product->name]+=$sale->quantity;
            }else{
                $topSold[$sale->product->name]=$sale->quantity;
            }
        }

        arsort($topSold);
        $topSold = array_slice($topSold,0,5);
        $this->report->topSelling = $topSold;

        /*
         * Arrange sale data for displaying
         */

        $name = array();
        $qty = array();
        $amount = array();  //Including discount
        $lossAmount = array();
        $lossQty = array();

        foreach ($sales as $sale){
            if (in_array($sale->product->name,$name)){
                $index = array_search($sale->product->name,$name);
                $qty[$index]+=$sale->quantity;
                $amount[$index]+=($sale->amount-$sale->discount);
            }else{
                $name[] = $sale->product->name;
                $qty[] = $sale->quantity;
                $amount[] = ($sale->amount-$sale->discount);
                $lossAmount[$sale->product->name]=0;
                $lossQty[$sale->product->name]=0;
            }
        }

        foreach ($losses as $loss){
            if (array_key_exists($loss->product->name,$lossQty)){
                $lossAmount[$loss->product->name]+=$loss->loss;
                $lossQty[$loss->product->name]+=$loss->quantity;
            }else{
                $lossAmount[$loss->product->name]=$loss->loss;
                $lossQty[$loss->product->name]=$loss->quantity;
            }
        }


        $data = ['name'=>$name,'value'=>$amount,'title'=>'Today'];


        $this->report->from = "today";
        $this->report->sales->name =$name;
        $this->report->sales->amount=$amount;
        $this->report->sales->qty = $qty;
        $this->report->sales->lossQty = $lossQty;
        $this->report->sales->lossAmount = $lossAmount;
        $this->report->data=$data;
        $report = $this->report;
//                return response()->json($report->sales);
        return view('main.report.show',compact( 'report'));
    }

    public function yesterday(){

        $yesterday = date("Y-m-d", strtotime( '-1 days' ) );
        $days[date("D d/m/y", strtotime( '-1 days' ))]=0;

        /*
         * Get sales data
         */
        $sales = Sale::whereDate('created_at','>=', $yesterday )->whereDate('created_at','!=', date("Y-m-d", strtotime( '0 days' ) ) )->get();
        $losses = Loss::whereDate('created_at','>=', $yesterday )->whereDate('created_at','!=', date("Y-m-d", strtotime( '0 days' ) ) )->get();
        $expenses = Expense::whereDate('created_at','>=', $yesterday )->whereDate('created_at','!=', date("Y-m-d", strtotime( '0 days' ) ) )->get();

        $this->report->profitLoss['expenses'] = $expenses->sum('amount');  //Sum of expenses
        $this->report->profitLoss['expensesData'] = $expenses; //Expense Data

        $this->report->profitLoss['loss'] = $losses->sum('loss');

        foreach ($sales as $sale){
            $this->report->profitLoss['profit']+=($sale->amount-(($sale->buyingPrice*$sale->quantity)+$sale->discount));
        }

        $this->report->profitLoss['netProfit'] = $this->report->profitLoss['profit']- $this->report->profitLoss['loss']-$this->report->profitLoss['expenses'];

//        Top profit
        $productProfit = array();
        foreach ($sales as $sale){
            if (array_key_exists($sale->product->name,$productProfit)){
                $productProfit[$sale->product->name]+=($sale->amount-(($sale->buyingPrice*$sale->quantity)+$sale->discount));
            }else{
                $productProfit[$sale->product->name]=($sale->amount-(($sale->buyingPrice*$sale->quantity)+$sale->discount));
            }
        }

        foreach ($losses as $loss) {
            if(array_key_exists($loss->product->name,$productProfit)){
                $productProfit[$loss->product->name]-=$loss->loss;
            }
        }

        arsort($productProfit);
        $this->report->topProfiting = array_slice($productProfit,0,5);

//        TopSold Item
        $topSold = array();
        foreach ($sales as $sale){
            if (array_key_exists($sale->product->name,$topSold)){
                $topSold[$sale->product->name]+=$sale->quantity;
            }else{
                $topSold[$sale->product->name]=$sale->quantity;
            }
        }

        arsort($topSold);
        $topSold = array_slice($topSold,0,5);
        $this->report->topSelling = $topSold;

        /*
         * Arrange sale data for displaying
         */

        $name = array();
        $qty = array();
        $amount = array();  //Including discount
        $lossAmount = array();
        $lossQty = array();

        foreach ($sales as $sale){
            if (in_array($sale->product->name,$name)){
                $index = array_search($sale->product->name,$name);
                $qty[$index]+=$sale->quantity;
                $amount[$index]+=($sale->amount-$sale->discount);
            }else{
                $name[] = $sale->product->name;
                $qty[] = $sale->quantity;
                $amount[] = ($sale->amount-$sale->discount);
                $lossAmount[$sale->product->name]=0;
                $lossQty[$sale->product->name]=0;
            }
        }

        foreach ($losses as $loss){
            if (array_key_exists($loss->product->name,$lossQty)){
                $lossAmount[$loss->product->name]+=$loss->loss;
                $lossQty[$loss->product->name]+=$loss->quantity;
            }else{
                $lossAmount[$loss->product->name]=$loss->loss;
                $lossQty[$loss->product->name]=$loss->quantity;
            }
        }

        /*
        * Data for the chart
        */
        $data = ['name'=>$name,'value'=>$amount,'title'=>'Yesterday'];


        $this->report->from = "yesterday";
        $this->report->sales->name =$name;
        $this->report->sales->amount=$amount;
        $this->report->sales->qty = $qty;
        $this->report->sales->lossQty = $lossQty;
        $this->report->sales->lossAmount = $lossAmount;
        $this->report->data=$data;
        $report = $this->report;
//                return response()->json($report->sales);
        return view('main.report.show',compact( 'report'));
    }

    public function week(){
        $days = array();

        $week = date("Y-m-d", strtotime( '-7 days' ) );

        for($i=7;$i>0;$i--){
            $days[date("D d/m/y", strtotime( '-'.$i.' days' ))]=0;
        }

        /*
         * Get sales data
         */
        $sales = Sale::whereDate('created_at','>=', $week )->whereDate('created_at','!=', date("Y-m-d", strtotime( '0 days' ) ) )->get();
        $losses = Loss::whereDate('created_at','>=', $week )->whereDate('created_at','!=', date("Y-m-d", strtotime( '0 days' ) ) )->get();
        $expenses = Expense::whereDate('created_at','>=', $week )->whereDate('created_at','!=', date("Y-m-d", strtotime( '0 days' ) ) )->get();

        $this->report->profitLoss['expenses'] = $expenses->sum('amount');  //Sum of expenses
        $this->report->profitLoss['expensesData'] = $expenses; //Expense Data

        $this->report->profitLoss['loss'] = $losses->sum('loss');

        foreach ($sales as $sale){
            $this->report->profitLoss['profit']+=($sale->amount-(($sale->buyingPrice*$sale->quantity)+$sale->discount));
        }

        $this->report->profitLoss['netProfit'] = $this->report->profitLoss['profit']- $this->report->profitLoss['loss']-$this->report->profitLoss['expenses'];

//        Top profit
        $productProfit = array();
        foreach ($sales as $sale){
            if (array_key_exists($sale->product->name,$productProfit)){
                $productProfit[$sale->product->name]+=($sale->amount-(($sale->buyingPrice*$sale->quantity)+$sale->discount));
            }else{
                $productProfit[$sale->product->name]=($sale->amount-(($sale->buyingPrice*$sale->quantity)+$sale->discount));
            }
        }

        foreach ($losses as $loss) {
            if(array_key_exists($loss->product->name,$productProfit)){
                $productProfit[$loss->product->name]-=$loss->loss;
            }
        }

        arsort($productProfit);
        $this->report->topProfiting = array_slice($productProfit,0,5);

//        TopSold Item
        $topSold = array();
        foreach ($sales as $sale){
            if (array_key_exists($sale->product->name,$topSold)){
                $topSold[$sale->product->name]+=$sale->quantity;
            }else{
                $topSold[$sale->product->name]=$sale->quantity;
            }
        }

        arsort($topSold);
        $topSold = array_slice($topSold,0,5);
        $this->report->topSelling = $topSold;





        /*
         * Data for the chart
         */

        for($i=0;$i<sizeof($sales);$i++){
            if (array_key_exists($sales[$i]->created_at->format('D d/m/y'),$days)){
                $days[$sales[$i]->created_at->format('D d/m/y')]+= ($sales[$i]->amount-$sales[$i]->discount);
            }else{
                $days[$sales[$i]->created_at->format('D d/m/y')] = ($sales[$i]->amount-$sales[$i]->discount);
            }
        }
        $data = ['name'=>array_keys($days),'value'=>array_values($days),'title'=>'Last Seven Days'];

        /*
         * Arrange sale data for displaying
         */

        $name = array();
        $qty = array();
        $amount = array();  //Including discount
        $lossAmount = array();
        $lossQty = array();

        $totalDiscount=0;

        foreach ($sales as $sale){
            if (in_array($sale->product->name,$name)){
                $index = array_search($sale->product->name,$name);
                $qty[$index]+=$sale->quantity;
                $amount[$index]+=($sale->amount-$sale->discount);
            }else{
                $name[] = $sale->product->name;
                $qty[] = $sale->quantity;
                $amount[] = ($sale->amount-$sale->discount);
                $lossAmount[$sale->product->name]=0;
                $lossQty[$sale->product->name]=0;
            }
        }

        foreach ($losses as $loss){
            if (array_key_exists($loss->product->name,$lossQty)){
                $lossAmount[$loss->product->name]+=$loss->loss;
                $lossQty[$loss->product->name]+=$loss->quantity;
            }else{
                $lossAmount[$loss->product->name]=$loss->loss;
                $lossQty[$loss->product->name]=$loss->quantity;
            }
        }

        $this->report->from = "week";
        $this->report->sales->name =$name;
        $this->report->sales->amount=$amount;
        $this->report->sales->qty = $qty;
        $this->report->sales->lossQty = $lossQty;
        $this->report->sales->lossAmount = $lossAmount;
        $this->report->data=$data;
        $report = $this->report;
//                return response()->json($report->sales->lossQty);
        return view('main.report.show',compact('report'));
    }

    public function month(){
        $days = array();

        $month = date("Y-m-d", strtotime( '-30 days' ) );

        for($i=30;$i>0;$i--){
            $days[date("D d/m/y", strtotime( '-'.$i.' days' ))]=0;
        }

        /*
         * Get sales data
         */
        $sales = Sale::whereDate('created_at','>=', $month )->whereDate('created_at','!=', date("Y-m-d", strtotime( '0 days' ) ) )->get();
        $losses = Loss::whereDate('created_at','>=', $month )->whereDate('created_at','!=', date("Y-m-d", strtotime( '0 days' ) ) )->get();
        $expenses = Expense::whereDate('created_at','>=', $month )->whereDate('created_at','!=', date("Y-m-d", strtotime( '0 days' ) ) )->get();

        $this->report->profitLoss['expenses'] = $expenses->sum('amount');  //Sum of expenses
        $this->report->profitLoss['expensesData'] = $expenses; //Expense Data

        $this->report->profitLoss['loss'] = $losses->sum('loss');
//
//        $monthTotal = 0;
//        foreach ($sales as $sale){
//            $monthTotal+=($sale->amount-$sale->discount);
//        }
//        $this->report->monthTotal = $monthTotal;

        foreach ($sales as $sale){
            $this->report->profitLoss['profit']+=($sale->amount-(($sale->buyingPrice*$sale->quantity)+$sale->discount));
        }

        $this->report->profitLoss['netProfit'] = $this->report->profitLoss['profit']- $this->report->profitLoss['loss']-$this->report->profitLoss['expenses'];

//        Top profit
        $productProfit = array();
        foreach ($sales as $sale){
            if (array_key_exists($sale->product->name,$productProfit)){
                $productProfit[$sale->product->name]+=($sale->amount-(($sale->buyingPrice*$sale->quantity)+$sale->discount));
            }else{
                $productProfit[$sale->product->name]=($sale->amount-(($sale->buyingPrice*$sale->quantity)+$sale->discount));
            }
        }

        foreach ($losses as $loss) {
            if(array_key_exists($loss->product->name,$productProfit)){
                $productProfit[$loss->product->name]-=$loss->loss;
            }
        }

        arsort($productProfit);
        $this->report->topProfiting = array_slice($productProfit,0,5);

//        TopSold Item
        $topSold = array();
        foreach ($sales as $sale){
            if (array_key_exists($sale->product->name,$topSold)){
                $topSold[$sale->product->name]+=$sale->quantity;
            }else{
                $topSold[$sale->product->name]=$sale->quantity;
            }
        }

        arsort($topSold);
        $topSold = array_slice($topSold,0,5);
        $this->report->topSelling = $topSold;




        /*
         * Data for the chart
         */

        for($i=0;$i<sizeof($sales);$i++){
            if (array_key_exists($sales[$i]->created_at->format('D d/m/y'),$days)){
                $days[$sales[$i]->created_at->format('D d/m/y')]+= ($sales[$i]->amount-$sales[$i]->discount);
            }else{
                $days[$sales[$i]->created_at->format('D d/m/y')] = ($sales[$i]->amount-$sales[$i]->discount);
            }
        }
        $data = ['name'=>array_keys($days),'value'=>array_values($days),'title'=>'Last 30 Days','day'=>30];

        /*
         * Arrange sale data for displaying
         */

        $name = array();
        $qty = array();
        $amount = array();  //Including discount
        $lossAmount = array();
        $lossQty = array();

        $totalDiscount=0;

        foreach ($sales as $sale){
            if (in_array($sale->product->name,$name)){
                $index = array_search($sale->product->name,$name);
                $qty[$index]+=$sale->quantity;
                $amount[$index]+=($sale->amount-$sale->discount);
            }else{
                $name[] = $sale->product->name;
                $qty[] = $sale->quantity;
                $amount[] = ($sale->amount-$sale->discount);
                $lossAmount[$sale->product->name]=0;
                $lossQty[$sale->product->name]=0;
            }
        }

        foreach ($losses as $loss){
            if (array_key_exists($loss->product->name,$lossQty)){
                $lossAmount[$loss->product->name]+=$loss->loss;
                $lossQty[$loss->product->name]+=$loss->quantity;
            }else{
                $lossAmount[$loss->product->name]=$loss->loss;
                $lossQty[$loss->product->name]=$loss->quantity;
            }
        }

        $this->report->from = 'month';
        $this->report->sales->name =$name;
        $this->report->sales->amount=$amount;
        $this->report->sales->qty = $qty;
        $this->report->sales->lossQty = $lossQty;
        $this->report->sales->lossAmount = $lossAmount;
        $this->report->data=$data;
        $report = $this->report;
//                return response()->json($report->sales->lossQty);
        return view('main.report.show',compact('report'));
    }

    public function filter(Request $request){
        $days = array();
        $checkdate = true;

        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);

        if($from>$to){
           return back()->with('dateError','From date must be less than To date');
        }else{
            while($checkdate){
                $days[$from->format('D d/m/y')]=0;
                $from = $from->addDay();
                if($from>$to){
                    $checkdate =  false;
                }

            }
        }
        /*
         * Get sales data
         */
        $sales = Sale::whereDate('created_at','>=', $request->from )->whereDate('created_at','<=', $request->to )->get();
        $losses = Loss::whereDate('created_at','>=', $request->from )->whereDate('created_at','<=', $request->to )->get();
        $expenses = Expense::whereDate('created_at','>=', $request->from )->whereDate('created_at','<=', $request->to )->get();

        $this->report->profitLoss['expenses'] = $expenses->sum('amount');  //Sum of expenses
        $this->report->profitLoss['expensesData'] = $expenses; //Expense Data
        $this->report->profitLoss['loss'] = $losses->sum('loss');

        foreach ($sales as $sale){
            $this->report->profitLoss['profit']+=($sale->amount-(($sale->buyingPrice*$sale->quantity)+$sale->discount));
        }

        $this->report->profitLoss['netProfit'] = $this->report->profitLoss['profit']- $this->report->profitLoss['loss']-$this->report->profitLoss['expenses'];

//        Top profit
        $productProfit = array();
        foreach ($sales as $sale){
            if (array_key_exists($sale->product->name,$productProfit)){
                $productProfit[$sale->product->name]+=($sale->amount-(($sale->buyingPrice*$sale->quantity)+$sale->discount));
            }else{
                $productProfit[$sale->product->name]=($sale->amount-(($sale->buyingPrice*$sale->quantity)+$sale->discount));
            }
        }

        foreach ($losses as $loss) {
            if(array_key_exists($loss->product->name,$productProfit)){
                $productProfit[$loss->product->name]-=$loss->loss;
            }
        }

        arsort($productProfit);
        $this->report->topProfiting = array_slice($productProfit,0,5);

//        TopSold Item
        $topSold = array();
        foreach ($sales as $sale){
            if (array_key_exists($sale->product->name,$topSold)){
                $topSold[$sale->product->name]+=$sale->quantity;
            }else{
                $topSold[$sale->product->name]=$sale->quantity;
            }
        }

        arsort($topSold);
        $topSold = array_slice($topSold,0,5);
        $this->report->topSelling = $topSold;




        /*
         * Data for the chart
         */

        for($i=0;$i<sizeof($sales);$i++){
            if (array_key_exists($sales[$i]->created_at->format('D d/m/y'),$days)){
                $days[$sales[$i]->created_at->format('D d/m/y')]+= ($sales[$i]->amount-$sales[$i]->discount);
            }else{
                $days[$sales[$i]->created_at->format('D d/m/y')] = ($sales[$i]->amount-$sales[$i]->discount);
            }
        }

        $from = Carbon::parse($request->from)->format('D d/m/Y');
        $to = Carbon::parse($request->to)->format('D d/m/Y');

        $data = ['name'=>array_keys($days),'value'=>array_values($days),'title'=>"Sales from $from to $to","from"=>$request->from,"to"=>$request->to];

        /*
         * Arrange sale data for displaying
         */

        $name = array();
        $qty = array();
        $amount = array();  //Including discount
        $lossAmount = array();
        $lossQty = array();

        foreach ($sales as $sale){
            if (in_array($sale->product->name,$name)){
                $index = array_search($sale->product->name,$name);
                $qty[$index]+=$sale->quantity;
                $amount[$index]+=($sale->amount-$sale->discount);
            }else{
                $name[] = $sale->product->name;
                $qty[] = $sale->quantity;
                $amount[] = ($sale->amount-$sale->discount);
                $lossAmount[$sale->product->name]=0;
                $lossQty[$sale->product->name]=0;
            }
        }

        foreach ($losses as $loss){
            if (array_key_exists($loss->product->name,$lossQty)){
                $lossAmount[$loss->product->name]+=$loss->loss;
                $lossQty[$loss->product->name]+=$loss->quantity;
            }else{
                $lossAmount[$loss->product->name]=$loss->loss;
                $lossQty[$loss->product->name]=$loss->quantity;
            }
        }

        $this->report->from = 'filter';
        $this->report->sales->name =$name;
        $this->report->sales->amount=$amount;
        $this->report->sales->qty = $qty;
        $this->report->sales->lossQty = $lossQty;
        $this->report->sales->lossAmount = $lossAmount;
        $this->report->data=$data;
        $report = $this->report;
//                return response()->json($report->sales->lossQty);
        return view('main.report.show',compact('report'));
    }
    
    public function printToday(){

        /*
         * Get sales data
         */
        $sales = Sale::whereDate('created_at','=', date("Y-m-d", strtotime( '0 days' ) ) )->get();
        $losses = Loss::whereDate('created_at','=', date("Y-m-d", strtotime( '0 days' ) ) )->get();

        /*
         * Arrange sale data for displaying
         */

        $name = array();
        $qty = array();
        $amount = array();  //Including discount
        $lossAmount = array();
        $lossQty = array();

        foreach ($sales as $sale){
            if (in_array($sale->product->name,$name)){
                $index = array_search($sale->product->name,$name);
                $qty[$index]+=$sale->quantity;
                $amount[$index]+=($sale->amount-$sale->discount);
            }else{
                $name[] = $sale->product->name;
                $qty[] = $sale->quantity;
                $amount[] = ($sale->amount-$sale->discount);
                $lossAmount[$sale->product->name]=0;
                $lossQty[$sale->product->name]=0;
            }
        }

        foreach ($losses as $loss){
            if (array_key_exists($loss->product->name,$lossQty)){
                $lossAmount[$loss->product->name]+=$loss->loss;
                $lossQty[$loss->product->name]+=$loss->quantity;
            }else{
                $lossAmount[$loss->product->name]=$loss->loss;
                $lossQty[$loss->product->name]=$loss->quantity;
            }
        }
        
        /*
         * Create array form excel
         */
        $sales_array[] = array('Name','Quantity','Amount','Loss Quantity','Loss Amount');

        for($i=0;$i<count($name);$i++){
            $sales_array[] = array(
                'Name'=>$name[$i],
                'Quantity'=>$qty[$i],
                'Amount'=>$amount[$i],
                'Loss Quantity'=>$lossQty[$name[$i]],
                'Loss Amount'=>$lossAmount[$name[$i]]
            );
        }

        Excel::create('Sales '.Carbon::now()->format('D_d_m_Y'),function ($excel) use ($sales_array){
            $excel->setTitle('Sales data');
            $excel->sheet('Sales sheet 1',function ($sheet) use ($sales_array){
                $sheet->fromArray($sales_array,null,'A4',false,false);
            });
        })->download('csv');
//                return response()->json($report->sales);
//        return view('main.report.show',compact( 'report'));
    }

    public function printYesterday(){
        $yesterday = date("Y-m-d", strtotime( '-1 days' ) );
        $days[date("D d/m/y", strtotime( '-1 days' ))]=0;

        /*
         * Get sales data
         */
        $sales = Sale::whereDate('created_at','>=', $yesterday )->whereDate('created_at','!=', date("Y-m-d", strtotime( '0 days' ) ) )->get();
        $losses = Loss::whereDate('created_at','>=', $yesterday )->whereDate('created_at','!=', date("Y-m-d", strtotime( '0 days' ) ) )->get();


        /*
         * Arrange sale data for displaying
         */

        $name = array();
        $qty = array();
        $amount = array();  //Including discount
        $lossAmount = array();
        $lossQty = array();

        foreach ($sales as $sale){
            if (in_array($sale->product->name,$name)){
                $index = array_search($sale->product->name,$name);
                $qty[$index]+=$sale->quantity;
                $amount[$index]+=($sale->amount-$sale->discount);
            }else{
                $name[] = $sale->product->name;
                $qty[] = $sale->quantity;
                $amount[] = ($sale->amount-$sale->discount);
                $lossAmount[$sale->product->name]=0;
                $lossQty[$sale->product->name]=0;
            }
        }

        foreach ($losses as $loss){
            if (array_key_exists($loss->product->name,$lossQty)){
                $lossAmount[$loss->product->name]+=$loss->loss;
                $lossQty[$loss->product->name]+=$loss->quantity;
            }else{
                $lossAmount[$loss->product->name]=$loss->loss;
                $lossQty[$loss->product->name]=$loss->quantity;
            }
        }

        /*
         * Create array form excel
         */
        $sales_array[] = array('Name','Quantity','Amount','Loss Quantity','Loss Amount');

        for($i=0;$i<count($name);$i++){
            $sales_array[] = array(
                'Name'=>$name[$i],
                'Quantity'=>$qty[$i],
                'Amount'=>$amount[$i],
                'Loss Quantity'=>$lossQty[$name[$i]],
                'Loss Amount'=>$lossAmount[$name[$i]]
            );
        }


//        return $sales_array;
        Excel::create('Sales '.Carbon::yesterday()->format('D_d_m_Y'),function ($excel) use ($sales_array){
            $excel->setTitle('Sales data');
            $excel->sheet('Sales sheet 1',function ($sheet) use ($sales_array){
                $sheet->fromArray($sales_array,null,'A4',false,false);
            });
        })->download('csv');

    }

    public function printWeek()
    {
        $days = array();

        $week = date("Y-m-d", strtotime('-7 days'));

        for ($i = 7; $i > 0; $i--) {
            $days[date("D d_m_Y", strtotime('-' . $i . ' days'))] = 0;
        }

        /*
         * Get sales data
         */
        $sales = Sale::whereDate('created_at', '>=', $week)->whereDate('created_at', '!=', date("Y-m-d", strtotime('0 days')))->get();
        $losses = Loss::whereDate('created_at', '>=', $week)->whereDate('created_at', '!=', date("Y-m-d", strtotime('0 days')))->get();

        /*
         * Arrange sale data for displaying
         */

        $name = array();
        $qty = array();
        $amount = array();  //Including discount
        $lossAmount = array();
        $lossQty = array();

        foreach ($sales as $sale) {
            if (in_array($sale->product->name, $name)) {
                $index = array_search($sale->product->name, $name);
                $qty[$index] += $sale->quantity;
                $amount[$index] += ($sale->amount - $sale->discount);
            } else {
                $name[] = $sale->product->name;
                $qty[] = $sale->quantity;
                $amount[] = ($sale->amount - $sale->discount);
                $lossAmount[$sale->product->name] = 0;
                $lossQty[$sale->product->name] = 0;
            }
        }

        foreach ($losses as $loss) {
            if (array_key_exists($loss->product->name, $lossQty)) {
                $lossAmount[$loss->product->name] += $loss->loss;
                $lossQty[$loss->product->name] += $loss->quantity;
            } else {
                $lossAmount[$loss->product->name] = $loss->loss;
                $lossQty[$loss->product->name] = $loss->quantity;
            }
        }

        /*
         * Create array form excel
         */
        $sales_array[] = array('Name', 'Quantity', 'Amount', 'Loss Quantity', 'Loss Amount');

        for ($i = 0; $i < count($name); $i++) {
            $sales_array[] = array(
                'Name' => $name[$i],
                'Quantity' => $qty[$i],
                'Amount' => $amount[$i],
                'Loss Quantity' => $lossQty[$name[$i]],
                'Loss Amount' => $lossAmount[$name[$i]]
            );
        }


//        return $sales_array;
        Excel::create('Sales from '.key($days).' to '.key(array_reverse($days)), function ($excel) use ($sales_array) {
            $excel->setTitle('Sales data');
            $excel->sheet('Sales sheet 1', function ($sheet) use ($sales_array) {
                $sheet->fromArray($sales_array, null, 'A3', false, false);
            });
        })->download('csv');
    }

    public function printMonth(){
        $days = array();

        $month = date("Y-m-d", strtotime( '-30 days' ) );

        for($i=30;$i>0;$i--){
            $days[date("D d/m/y", strtotime( '-'.$i.' days' ))]=0;
        }

        /*
         * Get sales data
         */
        $sales = Sale::whereDate('created_at','>=', $month )->whereDate('created_at','!=', date("Y-m-d", strtotime( '0 days' ) ) )->get();
        $losses = Loss::whereDate('created_at','>=', $month )->whereDate('created_at','!=', date("Y-m-d", strtotime( '0 days' ) ) )->get();


        /*
         * Arrange sale data for displaying
         */

        $name = array();
        $qty = array();
        $amount = array();  //Including discount
        $lossAmount = array();
        $lossQty = array();

        foreach ($sales as $sale){
            if (in_array($sale->product->name,$name)){
                $index = array_search($sale->product->name,$name);
                $qty[$index]+=$sale->quantity;
                $amount[$index]+=($sale->amount-$sale->discount);
            }else{
                $name[] = $sale->product->name;
                $qty[] = $sale->quantity;
                $amount[] = ($sale->amount-$sale->discount);
                $lossAmount[$sale->product->name]=0;
                $lossQty[$sale->product->name]=0;
            }
        }

        foreach ($losses as $loss){
            if (array_key_exists($loss->product->name,$lossQty)){
                $lossAmount[$loss->product->name]+=$loss->loss;
                $lossQty[$loss->product->name]+=$loss->quantity;
            }else{
                $lossAmount[$loss->product->name]=$loss->loss;
                $lossQty[$loss->product->name]=$loss->quantity;
            }
        }

        /*
         * Create array form excel
         */
        $sales_array[] = array('Name', 'Quantity', 'Amount', 'Loss Quantity', 'Loss Amount');

        for ($i = 0; $i < count($name); $i++) {
            $sales_array[] = array(
                'Name' => $name[$i],
                'Quantity' => $qty[$i],
                'Amount' => $amount[$i],
                'Loss Quantity' => $lossQty[$name[$i]],
                'Loss Amount' => $lossAmount[$name[$i]]
            );
        }


//        return $sales_array;
        Excel::create('Sales from '.key($days).' to '.key(array_reverse($days)), function ($excel) use ($sales_array) {
            $excel->setTitle('Sales data');
            $excel->sheet('Sales sheet 1', function ($sheet) use ($sales_array) {
                $sheet->fromArray($sales_array, null, 'A3', false, false);
            });
        })->download('csv');
    }

    public function printFiltered($from,$to){

        /*
         * Get sales data
         */
        $sales = Sale::whereDate('created_at','>=', $from )->whereDate('created_at','<=', $to )->get();
        $losses = Loss::whereDate('created_at','>=', $from )->whereDate('created_at','<=', $to )->get();

        $from = Carbon::parse($from)->format('D d/m/Y');
        $to = Carbon::parse($to)->format('D d/m/Y');

        /*
         * Arrange sale data for displaying
         */

        $name = array();
        $qty = array();
        $amount = array();  //Including discount
        $lossAmount = array();
        $lossQty = array();

        foreach ($sales as $sale){
            if (in_array($sale->product->name,$name)){
                $index = array_search($sale->product->name,$name);
                $qty[$index]+=$sale->quantity;
                $amount[$index]+=($sale->amount-$sale->discount);
            }else{
                $name[] = $sale->product->name;
                $qty[] = $sale->quantity;
                $amount[] = ($sale->amount-$sale->discount);
                $lossAmount[$sale->product->name]=0;
                $lossQty[$sale->product->name]=0;
            }
        }

        foreach ($losses as $loss){
            if (array_key_exists($loss->product->name,$lossQty)){
                $lossAmount[$loss->product->name]+=$loss->loss;
                $lossQty[$loss->product->name]+=$loss->quantity;
            }else{
                $lossAmount[$loss->product->name]=$loss->loss;
                $lossQty[$loss->product->name]=$loss->quantity;
            }
        }

        /*
         * Create array form excel
         */
        $sales_array[] = array('Name', 'Quantity', 'Amount', 'Loss Quantity', 'Loss Amount');

        for ($i = 0; $i < count($name); $i++) {
            $sales_array[] = array(
                'Name' => $name[$i],
                'Quantity' => $qty[$i],
                'Amount' => $amount[$i],
                'Loss Quantity' => $lossQty[$name[$i]],
                'Loss Amount' => $lossAmount[$name[$i]]
            );
        }

        Excel::create('Sales from '.$from.' to '.$to, function ($excel) use ($sales_array) {
            $excel->setTitle('Sales data');
            $excel->sheet('Sales sheet 1', function ($sheet) use ($sales_array) {
                $sheet->fromArray($sales_array, null, 'A3', false, false);
            });
        })->download('csv');
    }
}
