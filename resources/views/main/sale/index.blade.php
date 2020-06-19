@extends('layouts.main')

@section('refresh')
    60
@stop
@section('title')
    Sale
@stop

@section('content')
    <header style="margin-top:42px;margin-left:0px;padding-left:48px;border-bottom:2px solid #c1c1c1;padding-bottom:6px;">
        <h2>Product sales</h2>
    </header>

    @if(session('error'))
        <div class="row">
            <div class="col-lg-7 offset-2">
                <div class="alert alert-danger alert-dismissible text-center" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <em><strong>{{session('error')}}</strong></em>
                </div>
            </div>
        </div>
    @endif
    @if(session('message'))
        <div class="row">
            <div class="col-lg-6 offset-3">
                <div class="alert alert-success alert-dismissible text-center" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <em><strong>{{session('message')}}</strong></em>
                </div>
            </div>
        </div>
    @endif
    <div class="row" style="margin-top: 2em;">
        <div class="col-lg-4     offset-2">
            <form>
                <input type="hidden" value="{{csrf_token()}}" name="token" id="token">
                <input id="search" type="text" placeholder="Search Product Name" class="form-control">
            </form>
        </div>
    </div>
    <div class="row" style="margin-top: 2em;">
        <div class="col-lg-6">
            <div id="searchResultContainer" hidden="true">
                <h4>Search Result:  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><a href="/sale">Show All</a></small></h4>
            </div>

            @if(count($products)>0)
                <h4 class="text-center">Available Product</h4>
                <table id="product-table" class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Available-Qty</th>
                    <th>Price @</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id="productTable">
                @foreach($products as $product)
                    <tr>
                        <td title="{{$product->name}}" data-toggle="tooltip" data-placement="top">{{substr($product->name,0,10)}}</td>
                        <td>{{$product->quantity}}</td>
                        <td>{{number_format($product->price)}}/=</td>
                        <td>
                            <div class="row ">
                                <div class="col-lg-3">
                                    @php
                                    
                                    
                                    $expire = \Carbon\Carbon::now();
                                    if (!$product->expire_at){
                                    $alert = "<a class='btn text-success' data-price='$product->price' data-name='$product->name' data-slug='$product->slug' data-size='$product->saleSize' data-toggle='modal' data-target='#sellProductModal'><i class='ion ion-ios-cart'></i> Add to Cart</a>";
                                    }
                                    elseif ($expire>$product->expire_at){
                                        $alert = "<a class='btn text-danger'>Expired</a>";
                                    }else{
                                    $alert = "<a class='btn text-success' data-price='$product->price' data-name='$product->name' data-slug='$product->slug' data-size='$product->saleSize' data-toggle='modal' data-target='#sellProductModal'><i class='ion ion-ios-cart'></i> Add to Cart</a>";
                                    }
                                    echo $alert;
                                    @endphp
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                </table>
            @else
                <h4 class="text-center text-muted"><i>No Product</i></h4>
            @endif
            <div id="pagenation" class="row">
                <div class="offset-4">
                    {{$products->render()}}
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            @if(count((array)session('products'))>0)
                <h4 class="text-center">Selected Product </h4>
                <div class="table-responsive-sm">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Price@</th>
                            <th>Qty</th>
                            <th>Cost</th>
                            <th>Discount</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {{--@php--}}
                            {{--$slug = array();--}}
                            {{--$name = array();--}}
                            {{--$qty = array();--}}
                            {{--$price = array();--}}
                            {{--$buying = array();--}}
                            {{--$discount = array();--}}


                            {{--foreach (session('products') as $item){--}}
                                {{--if (in_array($item->product->name,$name)){--}}
                                    {{--$index = array_search($item->product->name,$name);--}}
                                    {{--$qty[$index]+=1;--}}
                                {{--}else{--}}
                                    {{--$name[] = $item->product->name;--}}
                                    {{--$qty[] = 1;--}}
                                    {{--$price[] = $item->product->price;--}}
                                    {{--$slug[] = $item->product->slug;--}}
                                    {{--$buying[] = $item->product->buyingPrice;--}}
                                    {{--$discount[] = $item->discount;--}}
                                {{--}--}}
                            {{--}--}}
                        {{--@endphp--}}

                        {{--@for($i=0;$i<sizeof($name);$i++)--}}
                        @php
                            $num=1;
                            $totalCost = 0;
                            $totalQty = 0;
                            $totalDiscount=0;


                        @endphp
                        @foreach(session('products') as $item)
                            <tr>
                                <td>{{$num}}.</td>
                                <td>{{substr($item->product->name,0,6)}}..</td>
                                <td>{{number_format($item->product->price)}}/=</td>
                                <td>x&nbsp;&nbsp;{{$item->quantity}}</td>
                                <td>{{number_format($item->amount)}}/=</td>
                                <td class="text-center">{{$item->discount>0?$item->discount:0}}/=</td>
                                <td>
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <a href="#discountModal" type="button" data-quantity="{{$item->quantity}}" data-buying="{{$item->product->buyingPrice}}" data-price="{{$item->product->price}}" data-name="{{$item->product->name}}" data-slug="{{$item->product->slug}}" data-toggle="modal" class="btn text-danger" title="Add discount"><i class="ion ion-disc"></i></a>
                                        </div>
                                        <div class="col-lg-4">
                                            {!! Form::open(['method'=>'DELETE', 'action'=>['SaleController@destroy',$item->product->slug]]) !!}
                                            <button type="submit" class="btn text-danger" title="delete {{$item->product->name}}"><i class="ion ion-android-delete"></i></button>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </td>
                                {{--<td>{{$slug[$i]}}</td>--}}
                            </tr>
                            @php($num++)
                            @php($totalCost+=($item->product->price*$item->quantity))
                            @php($totalQty+=$item->quantity)
                            @php($totalDiscount+=$item->discount)
                        @endforeach
                        <tr>
                            <td class="text-lg-center" colspan="3">TOTAL</td>
                            <td class="text-center"><strong>{{$totalQty}}</strong></td>
                            <td class="text-center"><strong>{{number_format($totalCost)}}/=</strong></td>
                            <td class="text-center"><strong>{{number_format($totalDiscount)}}/=</strong></td>
                        </tr>
                        <tr>
                            <td class="text-lg-center text-info" colspan="4"><H5>AMOUNT DUE</H5></td>
                            <td class="text-lg-center text-success" colspan="3"><H5><strong>{{number_format($totalCost-$totalDiscount)}}/=</strong></H5></td>
                        </tr>
                        </tbody>
                    </table>

                </div>
                <div class="row">
                    <div class="col-lg-5 offset-1">
                        <a href="/sale/cancel" class="btn btn-danger btn-block pull-right"><i class="fa fa-close"></i>Cancel</a>
                    </div>
                    <div class="col-lg-5">
                        {!! Form::open(['method'=>'POST','action'=>'SaleController@confirm']) !!}
                            @foreach(session('products') as $item)
                                <input type='hidden' name="name[]" value='{{$item->product->name}}' readonly>
                                <input type="hidden" name="quantity[]" value="{{$item->quantity}}" readonly>
                                <input type="hidden" name="slug[]" value="{{$item->product->slug}}" readonly>
                                <input type="hidden" name="discount[]" value="{{$item->discount}}" readonly>
                            @endforeach
                            <button class="btn btn-success btn-block" type="submit"><i class="ion ion-checkmark"></i>Confirm</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{--Modals--}}

    {{--Sell Modal--}}
    <div class="modal fade" id="sellProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add to Cart</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['method'=>'POST','action'=>'SaleController@store','id'=>'addInventoryForm']) !!}
                    <input id="productSlug" type="hidden" name="slug">
                    <div class="row justify-content-center">
                        <div class="col-lg-4"><label class="pull-right" id="productName"></label></div>
                        <div class="col-lg-4"><input id="productQuantity" name="quantity" type="number" class="form-control" value="1" min="1"></div>
                        <div class="col-lg-4"><label id="productPrice"></label></div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-lg-9 offset-3 col-xl-8">
                                @if(count($errors)>0)
                                    @foreach($errors->all() as $error)
                                        <div class="alert alert-danger alert-dismissible text-center" role="alert">
                                            <em><strong>{{$error}}</strong></em>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-lg-6 offset-4 text-center"><button id="addInventoryBtn" class="btn btn-success btn-block" type="submit"><i class="fa fa-check"></i>&nbsp;Submit</button></div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{--Discount Modal--}}
    <div class="modal fade" id="discountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center">Add Discount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['method'=>'PUT', 'action'=>'SaleController@discount']) !!}
                    <input id="productSlug" type="hidden" name="slug">
                    <div class="row">
                        <label id="productPrice1" hidden></label>
                        <label id="productQuantity" hidden></label>
                        <label id="productBuyingPrice" hidden></label>
                        <div class="col-lg-4"><label class="pull-right" id="productName"></label></div>
                        <div class="col-lg-3"><input id="discountQuantity" name="discount" type="number" class="form-control" value="0" min="0"></div>
                        <div class="col-lg-5">
                            <label id="productBuying"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <h5 id="productProfit"></h5>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-lg-9 offset-3 col-xl-8">
                                @if(count($errors)>0)
                                    @foreach($errors->all() as $error)
                                        <div class="alert alert-danger alert-dismissible text-center" role="alert">
                                            <em><strong>{{$error}}</strong></em>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-lg-6 offset-4 text-center"><button id="addInventoryBtn" class="btn btn-success btn-block" type="submit"><i class="fa fa-check"></i>&nbsp;Submit</button></div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
@section('script')
    <script>
        $(document).ready(function () {
            $('#search').keyup(function () {
                var token = $('#token').val();
                var search = $('#search').val();

                if (search.length<1){
                    $('#search').focus();
                    return;
                }

                var url = 'sale/'+search;
                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        'X-CSRF-Token': token
                    },
                    data: {
                        name:search
                    }
                }).done(function (data) {
                    if (data==404){
                        $('#productTable').html("<h4 class='text-muted' style='margin:3em 0 0 5em;'>Not found</h4>");
                        $('#searchResultContainer').removeAttr('hidden');
                        $('#pagenation').hide();
                    }else{
                        $('#searchResultContainer').removeAttr('hidden');
                        $('#productTable').html(data);
                        $('#pagenation').hide();
                    }
                });


            });

            $('#sellProductModal').on('show.bs.modal', function (e) {
                var slug = $(e.relatedTarget).data('slug');
                var name = $(e.relatedTarget).data('name');
                var price = $(e.relatedTarget).data('price');
                var hasSize = $(e.relatedTarget).data('size');
                console.log(hasSize);
                if (hasSize===1){
                    $('#productQuantity').attr('step','0.25');
                    $('#productQuantity').attr('min','0.25');
                    $('#productQuantity').val(0.25);
                }
                $('#productName').html(name);
                $('#productSlug').val(slug);
                $('#productPrice').text('\t x '+ price);
            });

            $('#sellProductModal').on('hide.bs.modal', function (e) {

                $('#productName').html('');
                $('#productSlug').val('');
                $('#productPrice').text('');
                $('#productQuantity').val(1);
            });

            $('#discountModal').on('show.bs.modal', function (e) {
                var slug = $(e.relatedTarget).data('slug');
                var name = $(e.relatedTarget).data('name');
                var buying = $(e.relatedTarget).data('buying');
                var price = $(e.relatedTarget).data('price');
                var quantity = $(e.relatedTarget).data('quantity');

                $('#discountModal #productName').html(name);
                $('#discountModal #productSlug').val(slug);
                $('#discountModal #productPrice1').text(price);
                $('#discountModal #productQuantity').text(quantity);
                $('#discountModal #productBuying').html('Buying Cost: '+buying+'<br>Selling price: '+price);
                $('#discountModal #productBuyingPrice').text(buying);
            });

            $('#discountModal #discountQuantity').on('keyup',function () {
                var sellingPrice = $('#productPrice1').text();
                var buyingPrice = $('#productBuyingPrice').text();
                var discount = $(this).val();
                var quantity = $('#discountModal #productQuantity').text();

                var profit = (parseFloat(sellingPrice)*parseFloat(quantity)) - (parseFloat(discount)+(parseFloat(buyingPrice)*parseFloat(quantity)));
                if(isNaN(profit)){
                    profit =  (parseFloat(sellingPrice)*parseFloat(quantity)) - ((parseFloat(buyingPrice)*parseFloat(quantity)));
                }
                //$('#productProfit').text('Profit after discount: '+profit);
            });

            $('#discountModal').on('hide.bs.modal', function () {


                $('#discountModal #productName').html('');
                $('#discountModal #productSlug').val('');
                $('#discountModal #productPrice1').text('');
                $('#discountModal #productBuying').text('');
                $('#discountModal #productBuyingPrice').text('');
                $('#discountModal #productQuantity').val(0);
            });


        });
    </script>
@stop