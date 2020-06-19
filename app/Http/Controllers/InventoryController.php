<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductLossRequest;
use App\Inventory;
use App\Product;
use Illuminate\Http\Request;
use PharIo\Manifest\InvalidApplicationNameException;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inventories = Inventory::orderBy('created_at','desc')->paginate(15);
        $capital =  DB::table('inventories')->sum('cost');
//        return $inventories;
        return view('main.inventory.index',compact('inventories','capital'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = Product::whereSlug($request->slug)->first();
        $product->quantity+=$request->quantity;
        $product->price = $request->price;
        $product->buyingPrice=($request->cost/$request->quantity);
        $product->save();
        Inventory::create(['quantity'=>$request->quantity,'product_id'=>$product->id,'cost'=>$request->cost,'newPrice'=>$request->price]);
        return redirect('/product')->with('status','Inventory added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function search($search){
        $products = Product::where('name','like','%'.$search.'%')->get();
        $output = '';
        foreach ($products as $product){
            foreach ($product->inventory as $inventory) {
                $output.="<tr>
                            <td>".$inventory->product->name."</td>
                            <td>$inventory->quantity</td>
                            <td>".number_format($inventory->cost)."</td>
                            <td>".number_format(round(floatval($inventory->cost/$inventory->quantity)))."</td>
                            <td>".number_format($inventory->newPrice)."</td>
                            <td>".number_format($inventory->product->price)."</td>
                            <td>".$inventory->created_at->format('D d.m.Y H:m:s')."</td>
                        </tr>";
            }
        }

        return $output;
    }
}
