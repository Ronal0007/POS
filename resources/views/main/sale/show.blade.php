@extends('layouts.main')

@section('title')
    Sale
@stop

@section('content')
    <header style="margin-top:42px;margin-left:0px;padding-left:48px;border-bottom:2px solid #c1c1c1;padding-bottom:6px;">
        <h2>Product sale</h2>
    </header>
    <div class="row" style="margin-top: 5px;">
        <div class="col-lg-4 offset-3">

            <div id="sale-area" class="" style="margin: 10px 0 10px 30px;">
                <form method="post" action="/sale">
                    {{csrf_field()}}
                    <input type="hidden" name="transaction" value="{{$TNumber}}">
                    <div class="form-group">
                        <input id="code" type="text" name="code" class="form-control-lg">
                    </div>
                </form>
            </div>




        </div>

    </div>
    <div class="row">
        <div class="col-lg-7">
            <h4 class="text-center text-info">Product Description</h4>
            <div class="row" style="margin-bottom: 5px;">
                <div class="col-lg-4">
                    <div class="card">
                        <img src="/image/product/1545169043_screwdriver.jpg" class="card-img-top" style="width: 100px !important; height: 100px; align-self: center;"/>
                        <div class="card-body">
                            {{--<h5 class="card-title">Screw driver @ 2,500/=</h5>--}}
                            <p class="card-text"><strong>Screw driver</strong> @ 2,500/=</p>
                            <a href="#" class="btn btn-danger pull-right text-light" type="submit"><i class="ion ion-ios-trash"></i>&nbsp;delete</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <h4 class="text-center">Product List</h4>
            <table class="table table-hover table-bordered">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Price@</th>
                    <th>Qty</th>
                    <th>Cost</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1.</td>
                    <td>Screw driver</td>
                    <td>2,500/=</td>
                    <td>x&nbsp;&nbsp;3</td>
                    <td>7,500/=</td>
                </tr>
                <tr>
                    <td class="text-lg-center text-info" colspan="3">GRAND TOTAL</td>
                    <td><strong>3</strong></td>
                    <td><strong>7,500/=</strong></td>
                </tr>
                </tbody>
            </table>
            <a href="#" class="btn btn-success btn-block col-lg-7 offset-5"><strong><i class="ion ion-checkmark"></i>&nbsp;Confirm Sale</strong></a>
        </div>
    </div>
@stop