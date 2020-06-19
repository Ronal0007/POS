@extends('layouts.main')

@section('title')
    Add product
@stop

@section('content')
    <header style="margin-top:42px;margin-left:0px;padding-left:48px;border-bottom:2px solid #c1c1c1;padding-bottom:6px;">
        <h2>Add Product</h2>
    </header>
    <div class="row" style="margin-top:15px;">
        <div class="col-lg-8 offset-1">
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
            <header><h3 class="text-lg-center text-info" style="margin-bottom: 15px;">Enter product details</h3></header>
                {!! Form::open(['method'=>'POST','action'=>'ProductController@store']) !!}
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-lg-3 text-center"><label class="col-form-label" for="name">Name</label></div>
                        <div class="col-lg-9 col-xl-8"><input class="form-control" value="" type="text" name="name" placeholder="Enter name"></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-lg-3 text-center"><label class="col-form-label" for="quantity">Quantity</label></div>
                        <div class="col-lg-9 col-xl-8"><input class="form-control" type="number" name="quantity" placeholder="Enter Product quantity" min="0"></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-lg-3 text-center"><label class="col-form-label" for="cost">Cost</label></div>
                        <div class="col-lg-9 col-xl-8"><input class="form-control" type="number" name="cost" placeholder="Enter cost of the quantity"></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-lg-3 text-center"><label class="col-form-label" for="gender">Price Per</label></div>
                        <div class="col-lg-9 col-xl-8"><input class="form-control" type="number" name="price" placeholder="Enter selling price"></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-lg-3 text-center"><label class="col-form-label" for="gender">Has Different size?</label></div>
                        <div class="col-lg-9 col-xl-8"><input class="form-check" type="checkbox" name="sizeCheck" ></div>
                    </div>
                </div>
                <!-- <div class="form-group">
                    <div class="form-row">
                        <div class="col-lg-3 text-center"><label class="col-form-label" for="gender">Has ExpireDate?</label></div>
                        <div class="col-lg-9 col-xl-8">
                            <div class="row">
                                <div class="col-lg-1">
                                    <input id="expireCheck" style="margin-top: 5px;" class="form-check" type="checkbox" name="expireCheck" >
                                </div>
                                <div class="col-lg-4 ">
                                    <input id="expire_at" type="date" class="form-control" name="expire_at" min="{{date('Y-m-d',time())}}" disabled="true">
                                </div>
                                <div class="col-lg-7 expire">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <label style="margin-top: 4px;" for="">Alert before:</label>
                                        </div>
                                        <div class="col-lg-7">
                                            <input id="alert" type="number" name="alert" class="form-control" min="1" placeholder="Days" disabled="true">
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
                        <div class="col-lg-6 offset-4 text-center"><button class="btn btn-success btn-block" type="submit"><i class="fa fa-check"></i>&nbsp;Submit</button></div>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('script')
    <script>
        $(document).ready(function () {
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