<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductRequest;
use App\Http\Requests\ProductLossRequest;
use App\Http\Requests\ProductRequest;
use App\Inventory;
use App\Loss;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::whereDeleted(false)->orderBy('created_at','desc')->paginate(10);
        return view('main.product.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('main.product.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->expireCheck){
            $this->validate($request,[
                'name'=>'required',
                'quantity' =>'required',
                'cost' =>'required',
                'price' => 'required',
                'expire_at' => 'required',
                'alert' => 'required'
            ]);
        }else{
            $this->validate($request,[
                'name'=>'required',
                'quantity' =>'required',
                'cost' =>'required',
                'price' => 'required'
            ]);
        }



        if (sizeof(Product::whereName($request->name)->get())>0){
            return back()->with('error','Product name ('.$request->name.') already exist');
        }

        $input = $request->all();
        $input['buyingPrice'] = $request->cost/$request->quantity;
        $input['saleSize'] = $request->sizeCheck?true:false;
        $product = Product::create($input);
        Inventory::create(['quantity'=>$request->quantity,'product_id'=>$product->id,'cost'=>$request->cost]);
        return redirect('/product');
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
                if(!$result->expire_at){
                    $alert = '';
                }
                elseif ($expire>$result->expire_at){
                    $alert = "<span class='text-danger'>Expired</span>";
                    $expire = true;
                }else{
                    $alert = $result->expire_at->diffForHumans();
                }

                if($expire===true){
                    $link = '';
                }else{
                    $link = "<a href='#' class='btn text-success' data-price='$result->price' data-name='$result->name' data-slug='$result->slug' data-toggle='modal' data-target='#addProductModal'><i class='fa ion-android-add-circle'></i></a>";
                }

                $output.="<tr>
                            <td>".$result->name."</td>
                            <td>$result->quantity</td>
                            <td>".number_format($result->price)."</td>
                            <td>".$result->created_at->diffForHumans()."</td>
                            <td>".$result->updated_at->diffForHumans()."</td>
                            <td>$alert</td>
                            <td>
                                <div class='row'>
                                    <div class='col-lg-3 offset-3 pull-right'>
                                    ".$link."
                                    </div>
                                    <div class='col-lg-3 pull-right'>
                                        <a href='/product/$result->slug/edit' class='btn text-info'><i class='ion ion-edit'></i></a>
                                    </div>
                                    <div class='col-lg-3 pull-right'>
                                        <form class='deleteProductForm' data-name='$result->name' method='POST' action='/product/$result->id' accept-charset='UTF-8'><input name='_method' type='hidden' value='DELETE'><input name='_token' type='hidden' value='".csrf_token()."'>
                                            <button type='submit' class='btn text-danger'><i class='ion ion-ios-trash'></i></button>
                                        </form>
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
    public function edit($slug)
    {
        $product = Product::whereSlug($slug)->first();
        return view('main.product.edit',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {

        if($request->expireCheck){
            $this->validate($request,[
                'name'=>['required','min:3'],
                'price'=>'required',
                'quantity'=>'required',
                'expire_at' => 'required',
                'alert' => 'required'
            ]);
        }else{
            $this->validate($request,[
                'name'=>['required','min:3'],
                'price'=>'required',
                'quantity'=>'required',
            ]);
        }

        //return $request->all();
        $product = Product::whereSlug($slug)->first();
        $product->name = $request->name;
        $product->saleSize = $request->saleSize?true:false;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->expire_at = $request->expireCheck?$request->expire_at:null;
        $product->alert = $request->expireCheck?$request->alert:null;
        $product->save();
        return redirect('product')->with('status','Product ('.$product->name.') updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//        return Product::find($id);
        $product = Product::find($id);
        $product->deleted = true;
        $product->save();
        return redirect('/product')->with('status',"Product deleted successfully");
    }

    public function loss(ProductLossRequest $request){
//        return $request->lossQuantity;
        $lossQuantity = (integer) $request->lossQuantity;

        if($lossQuantity<1){
            return back()->with('error','No losses created!');
        }else{
            $product = Product::whereSlug($request->slug)->first();
            $product->quantity-=$lossQuantity;
            $product->save();
            Loss::create(['product_id'=>$product->id,'quantity'=>$lossQuantity,'loss'=>($lossQuantity*$product->buyingPrice)]);
            return redirect("product/$product->slug/edit ")->with('error','Loss recorded successfully');
            return view('main.product.edit',compact('product'))->with('error','Loss recorded successfully');
        }
    }
}