@extends('layouts.main')

@section('title')
    Products
@stop

@section('content')
    <header style="margin-top:42px;margin-left:0px;padding-left:48px;border-bottom:2px solid #c1c1c1;padding-bottom:6px;">
        <h2>Products</h2>
    </header>

    @if(session('status'))
        <div class="row" style="margin-top: 10px;">
            <div class="col-lg-6 offset-3">
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button  type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{session('status')}}
                </div>
            </div>
        </div>
    @endif

    <div class="row" style="margin-top:35px;">
        <div class="col-lg-12 col-xl-11 offset-lg-0 ">
            <div class="row">
                <div class="col-lg-5 offset-lg-2" style="margin-bottom:14px;">
                    <form>
                        <input type="hidden" id="token" value="{{csrf_token()}}">
                        <div class="input-group">
                            <input id="search" class="form-control" type="text" placeholder="Type to Search">
                        </div>
                    </form>
                </div>
                <div class="col-lg-5    ">
                    <a href="{{route('product.create')}}" class="btn btn-success pull-right"><i class="ion ion-android-add"></i>  Add New Product</a>
                </div>
            </div>
            <div class="row">
                <div class="col" style="margin-top:15px;">
                    <div id="searchResultContainer" hidden="true">
                        <h4>Search Result:  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><a href="/product">Show All</a></small></h4>
                    </div>

                        @if(count($products)>0)
                            <table id="product-table" class="table">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Available-Qty</th>
                                    <th>Price @</th>
                                    <th>Created at</th>
                                    <th>Updated at</th>
                                    <th>Expire</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="productTable">
                                    @foreach($products as $product)
                                        <tr>
                                            <td>{{$product->name}}</td>
                                            <td>{{$product->quantity}}</td>
                                            <td>{{number_format($product->price)}}/=</td>
                                            <td>{{$product->created_at->diffForHumans()}}</td>
                                            <td>{{$product->updated_at->diffForHumans()}}</td>
                                            <td>
                                                @php
                                                    $expire = \Carbon\Carbon::now();
                                                if(!$product->expire_at){
                                                    $alert = '';
                                                }
                                                elseif ($expire>$product->expire_at){
                                                $alert = "<span class='text-danger'>Expired</span>";
                                                $expire = true;
                                                }else{
                                                $alert = $product->expire_at->diffForHumans();
                                                }
                                                echo $alert;
                                                @endphp
                                            </td>
                                            <td>
                                                <div class="row ">
                                                    <div class="col-lg-3 offset-3 pull-right">
                                                        @if($expire===true)
                                                        @else
                                                            <a href="#" class="btn text-success" data-price="{{$product->price}}" data-name="{{$product->name}}" data-slug="{{$product->slug}}" data-toggle="modal" data-target="#addProductModal"><i class="fa ion-android-add-circle"></i></a>
                                                        @endif
                                                        </div>
                                                    <div class="col-lg-3 pull-right">
                                                        <a href="{{route('product.edit',$product->slug)}}" class="btn text-info"><i class="ion ion-edit"></i></a>
                                                    </div>
                                                    <div class="col-lg-3 pull-right">
                                                        {!! Form::open(['method'=>'DELETE', 'action'=>['ProductController@destroy',$product->id],'class'=>'deleteProductForm','data-name'=>$product->name]) !!}
                                                        <button type="submit" class="btn text-danger"><i class="ion ion-ios-trash"></i></button>
                                                        {!! Form::close() !!}
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
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Inventory</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['method'=>'POST','action'=>'InventoryController@store','id'=>'addInventoryForm']) !!}
                        <input id="productSlug" type="hidden" name="slug">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-lg-3 text-center"><label class="col-form-label" for="name">Name</label></div>
                                <div class="col-lg-9 col-xl-8"><input id="productName" class="form-control" type="text"readonly></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-lg-3 text-center"><label class="col-form-label" for="quantity">Quantity</label></div>
                                <div class="col-lg-9 col-xl-8"><input id="productQuantity" class="form-control" type="number" name="quantity" placeholder="Enter Product quantity" min="0"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-lg-3 text-center"><label class="col-form-label" for="cost">Cost</label></div>
                                <div class="col-lg-9 col-xl-8"><input id="productCost" class="form-control" type="number" name="cost" placeholder="Enter cost of the quantity"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-lg-3 text-center"><label class="col-form-label" for="gender">New Price Per</label></div>
                                <div class="col-lg-9 col-xl-8"><input id="productPrice" class="form-control" type="number" name="price" placeholder="Enter selling price"></div>
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

    <div class="modal fade" id="deleteProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Inventory</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <em>Are you sure you want to delete <span id="deleteName"></span> </em>

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

                var url = 'product/'+search;
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
                        $('#productTable').html("<h2 class='text-muted' style='margin:3em 0 0 10em;'>Not found</h2>");
                        $('#searchResultContainer').removeAttr('hidden');
                        $('#pagenation').hide();
                    }else{
                        $('#searchResultContainer').removeAttr('hidden');
                        $('#productTable').html(data);
                        $('#pagenation').hide();
                    }
                });
            });

            $('#addProductModal').on('show.bs.modal', function (e) {
                var slug = $(e.relatedTarget).data('slug');
                var name = $(e.relatedTarget).data('name');
                var price = $(e.relatedTarget).data('price');

                $('#productName').val(name);
                $('#productSlug').val(slug);
                $('#productPrice').val(price);
            });

            $('#deleteProductModal').on('show.bs.modal', function (e) {
                var name = $(e.relatedTarget).data('name');

                $('#deleteName').text(name);
            });

            $('#addInventoryForm').submit(function () {
                var quantity = $('#productQuantity').val();
                var cost = $('#productCost').val();
                var price = $('#productPrice').val();

                if (quantity.length<1){
                    $('#productQuantity').focus();
                    return false;
                }
                if (cost.length<1){
                    $('#productCost').focus();
                    return false;
                }
                if (price.length<1){
                    $('#productPrice').focus();
                    return false;
                }
            });
            $('.deleteProductForm').submit(function (e) {
                var name = $(this).data('name');

                return confirm('Are you sure you want to delete '+name.toUpperCase());
                // return confirm('Are you sure you want to delete this product?');
            });
        });


    </script>
@stop