<?php

namespace App\Http\Controllers;

use App\Events\SaleConfirmedEvent;
use App\Http\Resources\PrintReceipt;
use App\Product;
use App\Sale;
use App\Tempsale;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::whereDeleted(false)->orderBy('name','asc')->paginate(5);
        return view('main.sale.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        return $request->all();
        if (session('TNumber')){
            if (!empty(Product::whereSlug($request->slug))<1){
                return back()->with('error','Product Not Found');
            }
            $product = Product::whereSlug($request->slug)->first();
            $found = Tempsale::whereProductId($product->id)->whereTNumber(session('TNumber'))->get();
            if (sizeof($found)>0){
                $found = $found->first();
                if(($request->quantity + $found->quantity) > $product->quantity){
                    return back()->with('error',"Not enough product in the store, only ($product->quantity) available");
                }
                $found->quantity+=$request->quantity;
                $found->amount= $found->quantity*$product->price;
                $found->save();
            }else{
                $amount = $product->price*$request->quantity;
                Tempsale::create(['product_id'=>$product->id,'quantity'=>$request->quantity,'amount'=>$amount,'t_number'=>session('TNumber')]);
            }
        }else{
            $TNumber = $this->generateTransactionNumber();
            Transaction::create(['number'=>$TNumber,'user_id'=>auth::user()->id]);
            session(['TNumber'=>$TNumber]);

            if (!empty(Product::whereSlug($request->slug))<1){
                return back()->with('error','Product Not Found');
            }
            $product = Product::whereSlug($request->slug)->first();

            if($request->quantity > $product->quantity){
                return back()->with('error',"Not enough product in the store, only ($product->quantity) available");
            }
            $amount = $product->price*$request->quantity;
            Tempsale::create(['product_id'=>$product->id,'quantity'=>$request->quantity,'amount'=>$amount,'t_number'=>session('TNumber')]);

        }
        $products = Transaction::whereNumber(session('TNumber'))->first()->tempProducts;
        session(['products'=>$products]);
//        return session('products');
        return redirect('/sale');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($data)
    {
        $results = Product::where('slug','like','%'.$data.'%')->whereDeleted(false)->get();

        $output = '';
        if (sizeof($results)>0){

            foreach ($results as $result){
                $expire = \Carbon\Carbon::now();
                if (!$result->expire_at){
                    $alert = "<a class='btn text-success' data-price='$result->price' data-name='$result->name' data-slug='$result->slug' data-size='$result->saleSize' data-toggle='modal' data-target='#sellProductModal'><i class='ion ion-ios-cart'></i> Add to Cart</a>";
                }
                elseif ($expire>$result->expire_at){
                    $alert = "<a class='btn text-danger'>Expired</a>";
                }else{
                    $alert = "<a class='btn text-success' data-price='$result->price' data-name='$result->name' data-slug='$result->slug' data-size='$result->saleSize' data-toggle='modal' data-target='#sellProductModal'><i class='ion ion-ios-cart'></i> Add to Cart</a>";
                }
//                echo $alert;
                $output.="<tr>
                            <td>".$result->name."</td>
                            <td>$result->quantity</td>
                            <td>".number_format($result->price)."/=</td>
                            <td>
                                <div class='row'>
                                    <div class='col-lg-3 offset-3 pull-right'>
                                        $alert
                                    </div>
                                </div>
                            </td>
                        </tr>";
            }
            return response($output);
        }else{
            return 404;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
//        if(session('products')){
////            return session('products');
////            $
////            foreach (session('products') as $product){
////
////            }
//        }else{
//            return redirect('/sale');
//        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        Tempsale::whereProductId(Product::whereSlug($slug)->first()->id)->whereTNumber(session('TNumber'))->delete();
        $products = Transaction::whereNumber(session('TNumber'))->first()->tempProducts;
        if (sizeof($products)<1){
            Tempsale::whereTNumber(session('TNumber'))->delete();
            Transaction::whereNumber(session('TNumber'))->first()->delete();
            session()->forget(['TNumber','products']);
            return redirect('/sale')->with('message','Canceled');
        }
        session(['products'=>$products]);
        return redirect('/sale')->with('message','Product deleted');
    }


    public function discount(Request $request){
//        return $request->all();
        $product = Product::whereSlug($request->slug)->first();
        $tempsale = Tempsale::whereProductId($product->id)->whereTNumber(session('TNumber'))->get()->first();
        $tempsale->discount = $request->discount;
        $tempsale->save();
        $products = Transaction::whereNumber(session('TNumber'))->first()->tempProducts;
        session(['products'=>$products]);
        return redirect('/sale');
    }

    public function confirm(Request $request){
//        return $request->all();
        try{
            for ($i=0;$i<sizeof($request->name);$i++){

                $product = Product::whereSlug($request->slug[$i])->first();
                if($request->quantity[$i]>$product->quantity){
                    return back()->with('error',"Product (".$request->name[$i].") out of stock, only ".$product->quantity." remains");
                }else{
                    DB::beginTransaction();
                    $product = Product::whereSlug($request->slug[$i])->first();
                    $amount = $product->price*$request->quantity[$i];
                    Sale::create(['product_id'=>$product->id,'quantity'=>$request->quantity[$i],'amount'=>$amount,'t_number'=>session('TNumber'),'discount'=>$request->discount[$i],'buyingPrice'=>$product->buyingPrice]);
                    $product->quantity-=$request->quantity[$i];
                    $product->save();
                    DB::commit();
                }
            }

            Tempsale::whereTNumber(session('TNumber'))->delete();
            $transaction = Transaction::whereNumber(session('TNumber'))->first();
            $transaction->update(['status'=>1]);
            session()->forget(['TNumber','products']);

            event(new SaleConfirmedEvent($transaction));

            return redirect('/sale')->with('message','Sale completed successfully');

        }catch (\Exception $exception){
            return 'Error when confirming the sale';
        }
    }

    protected function generateTransactionNumber(){
        $letters = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $TNumber = '';
        while (true){
            $TNumber = substr(str_shuffle($letters),0,10);
            $found = Transaction::whereNumber($TNumber)->count();
            if ($found==0){
                break;
            }
        }
        return $TNumber;
    }
}
