@extends('layouts.main')

@section('title')
    Edit product
@stop

@section('content')

    <style>
        .loss .active, .btn-loss{
            background: linear-gradient(245deg,#ff8300,#fa4251);
            color:#ffffff;
        }
    </style>
    <header style="margin-top:42px;margin-left:0px;padding-left:48px;border-bottom:2px solid #c1c1c1;padding-bottom:6px;">
        <h2>Edit Product</h2>
    </header>
        @if(session('error'))
            <div class="row" style="margin-top: 10px;">
                <div class="col-lg-6 offset-3">
                    <div class="alert alert-info alert-dismissible" role="alert">
                        <button  type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{session('error')}}
                    </div>
                </div>
            </div>
        @endif

    <div class="row" style="margin-top: 2em;">
        <div class="col-lg-10 offset-1">
            <ul class="nav nav-tabs nav-pills nav-justified">
                <li class="nav-item detail">
                    <a href="#details"class="nav-link active" data-toggle="tab">Details</a>
                </li>
                <li class="nav-item loss">
                    <a href="#loss"class="nav-link" data-toggle="tab">Manage Loss</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content">
                <div id="details" class="tab-pane active">
                    <div class="row" style="margin-top:15px;">
                        <div class="col-lg-8 offset-1">
                            <header>
                                <h3 class="text-lg-center text-info" style="margin-bottom: 15px;">Product details</h3>
                            </header>
                            {!! Form::model($product,['method'=>'PUT','action'=>['ProductController@update',$product->slug]]) !!}
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-lg-3 text-center"><label class="col-form-label" for="name">Name</label></div>
                                    <div class="col-lg-9 col-xl-8">{!! Form::text('name',null,['class'=>'form-control', 'placeholder'=>'Enter name']) !!}</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-lg-3 text-center"><label class="col-form-label" for="gender">Buying Price</label></div>
                                    <div class="col-lg-9 col-xl-8">
                                        {!! Form::number('buyingPrice',null,['id'=>'newPrice','class'=>'form-control', 'placeholder'=>'Enter price of a single product','min'=>1,'readonly']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-lg-3 text-center"><label class="col-form-label" for="gender">Selling Price</label></div>
                                    <div class="col-lg-9 col-xl-8">
                                        {!! Form::number('price',null,['id'=>'sellinPrice','class'=>'form-control', 'placeholder'=>'Enter price of a single product','min'=>1]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-lg-3 text-center"><label class="col-form-label" for="quantity">Quantity</label></div>
                                    <div class="col-lg-9 col-xl-8">
                                        {!! Form::number('quantity',null,['class'=>'form-control','placeholder'=>'Enter product quantity','min'=>1]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-lg-3 text-center"><label class="col-form-label" for="quantity">Has different size?</label></div>
                                    <div class="col-lg-9 col-xl-8">
                                        {!! Form::checkbox('saleSize',null) !!}
                                    </div>
                                </div>
                            </div>
                          <!--   <div class="form-group">
                                <div class="form-row">
                                    <div class="col-lg-3 text-center"><label class="col-form-label" for="gender">Has ExpireDate?</label></div>
                                    <div class="col-lg-9 col-xl-8">
                                        <div class="row">
                                            <div class="col-lg-1">
                                                <input id="expireCheck" style="margin-top: 5px;" class="form-check" type="checkbox" {{$product->expire_at?'checked':''}} name="expireCheck" >
                                            </div>
                                            <div class="col-lg-4 ">
                                                <input id="expire_at" type="date" value="{{$product->expire_at?date_format($product->expire_at,'Y-m-d'):null}}" class="form-control" name="expire_at" min="{{date('Y-m-d',time())}}" {{$product->expire_at?'':'disabled'}}>
                                            </div>
                                            <div class="col-lg-7 expire">
                                                <div class="row">
                                                    <div class="col-lg-5">
                                                        <label style="margin-top: 4px;" for="">Alert before:</label>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        {!! Form::number('alert',null,['id'=>'alert','class'=>'form-control', 'placeholder'=>'Days','min'=>1,'disabled'=>$product->expire_at?false:true]) !!}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-lg-9 offset-3 col-xl-8">
                                        @if(count($errors)>0)
                                            <div class="alert alert-danger alert-dismissible" role="alert">
                                                @foreach($errors->all() as $error)
                                                    {{$error}} <br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-lg-6 offset-4 text-center"><button class="btn btn-success btn-block" type="submit"><i class="fa fa-check"></i>&nbsp;Update</button></div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <div id="loss" class="tab-pane ">
                    <div class="row" style="margin-top:15px;">
                        <div class="col-lg-8 offset-1">
                            <header>
                                <h3 class="text-lg-center" style="margin-bottom: 15px;color: #ff8300;">Record Loss</h3>
                            </header>
                            {!! Form::model($product,['method'=>'POST','action'=>'ProductController@loss']) !!}
                            <input type="hidden" name="slug" value="{{$product->slug}}">
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-lg-3 text-center"><label class="col-form-label" for="name">Name</label></div>
                                    <div class="col-lg-9 col-xl-8">{!! Form::text('name',null,['class'=>'form-control', 'placeholder'=>'Enter name','readonly']) !!}</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-lg-3 text-center"><label class="col-form-label" for="gender">Buying Price</label></div>
                                    <div class="col-lg-9 col-xl-8">
                                        {!! Form::text('buyingPrice',null,['id'=>'buyingPrice','class'=>'form-control', 'placeholder'=>'Enter price of a single product','min'=>1,'readonly']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-lg-3 text-center"><label class="col-form-label" for="gender">Selling Price</label></div>
                                    <div class="col-lg-9 col-xl-8">
                                        {!! Form::text('price',null,['id'=>'sellingPrice','class'=>'form-control', 'placeholder'=>'Enter price of a single product','min'=>1,'readonly']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-lg-3 text-center"><label class="col-form-label" for="quantity">Quantity</label></div>
                                    <div class="col-lg-9 col-xl-8">
                                        {!! Form::text('quantity',null,['class'=>'form-control','placeholder'=>'Enter product quantity','min'=>1,'readonly']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-lg-3 text-center"><label class="col-form-label" for="quantity">Loss quantity</label></div>
                                    <div class="col-lg-9 col-xl-8">
                                        <div class="row">
                                            <div class="col-lg-5">
                                                <!-- {!! Form::number('lossQuantity',0,['id'=>'lossQuantity','class'=>'form-control','placeholder'=>'loss','min'=>0,'max'=>$product->quantity]) !!} -->
                                                <input id="lossQuantity" class="form-control" placeholder="loss" min="0" max="{{$product->quantity}}" step="{{$product->saleSize?0.25:1}}" type="number" name="lossQuantity">
                                            </div>
                                            <div class="col-lg-5">
                                                <label id="totalLoss" class="col-form-label text-danger"></label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-lg-9 offset-3 col-xl-8">
                                        @if(count($errors)>0)
                                            <div class="alert alert-danger alert-dismissible" role="alert">
                                                @foreach($errors->all() as $error)
                                                    {{$error}} <br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-lg-6 offset-4 text-center"><button class="btn btn-loss btn-block" type="submit"><i class="fa fa-check"></i>&nbsp;Submit</button></div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>








@stop

@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#lossQuantity').on('change',function (e) {
                var buying = $('#buyingPrice').val();
                var loss = $(this).val()*buying;
                $('#totalLoss').text('Loss '+loss+'/=');
            });

            $('#expireCheck').on('change',function (e) {
                if ($(this).is(':checked')){
                    $("#expire_at").attr({'disabled':false});
                    $("#alert").attr({'disabled':false});
                }else{
                    $("#expire_at").attr({'disabled':true});
                    $("#alert").attr({'disabled':true});
                }

            });
        });
    </script>
@stop