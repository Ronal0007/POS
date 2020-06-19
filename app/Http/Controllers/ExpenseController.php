<?php

namespace App\Http\Controllers;

use App\Expense;
use App\Http\Requests\ExpenseRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::orderBy('created_at','desc')->paginate(12);
        return view('main.expense.index',compact('expenses'));
    }

    public function store(ExpenseRequest $request)
    {
        $expense = $request->all();
        $expense['user_id'] = Auth::user()->id;
        Expense::create($expense);
        return redirect('/expense');
    }

    public function show(Request $request){
        $search = $request->search;
        $expenseResults = Expense::where('cash_detail','like','%'.$search.'%')->orderBy('created_at','desc')->get();
        $date = '';
        return view('main.expense.show',compact('expenseResults','date'));
    }
    public function filter(Request $request){
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);
        $date = "from ".$from->format('D d/m/y')." to ".$to->format('D d/m/y');

//        if($from>$to){
//            return back()->with('status','From date must be less than To date');
//        }
        $expenseResults = Expense::whereDate('created_at','>=', $request->from )->whereDate('created_at','<=', $request->to )->get();
        return view('main.expense.show',compact('expenseResults','date'));
    }
}
