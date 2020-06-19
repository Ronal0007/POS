@extends('layouts.main')

@section('title')
    Inventory
@stop

@section('content')
    <header style="margin-top:42px;margin-left:0px;padding-left:48px;border-bottom:2px solid #c1c1c1;padding-bottom:6px;">
        <h2>Inventory</h2>
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

<div class="row" style="margin-top: 1em;">
    <div class="col-lg-5 offset-4">
        <h3>Total capital:  <span class="text-info">TZS {{number_format($capital)}}/=</span></h4>
    </div>
</div>
    <div class="row" style="margin-top:35px;">
        <div class="col-lg-12 col-xl-11 offset-lg-0 ">
            <div class="row">
                <div class="col-lg-5 offset-lg-2" style="margin-bottom:14px;">
                    <form>
                        <input type="hidden" id="token" value="{{csrf_token()}}">
                        <div class="input-group">
                            <input id="searchInventory" class="form-control" type="text" placeholder="Type to Search">
                        </div>
                    </form>
                </div>
                <div class="col-lg-5    ">
                    <a href="{{route('product.create')}}" class="btn btn-success pull-right"><i class="ion ion-android-add"></i>  Add New Product</a>
                </div>
            </div>
            <div class="row">
                <div class="col" style="margin-top:15px;">
                    <div id="inventorySearchResultContainer" hidden="true">
                        <h4>Search Result:  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><a href="/inventory">Show All</a></small></h4>
                    </div>

                    @if(count($inventories)>0)
                        <table id="inventory-table" class="table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Purchased Qty</th>
                            <th>Quantity Cost</th>
                            <th>Cost per Item</th>
                            <th>Suggested Selling Price @</th>
                            <th>Current Selling Price @</th>
                            <th>Time</th>
                        </tr>
                        </thead>
                        <tbody id="inventoryTableBody">
                        @foreach($inventories as $inventory)
                            <tr>
                                <td>{{$inventory->product->name}}</td>
                                <td>{{$inventory->quantity}}</td>
                                <td>{{number_format($inventory->cost)}}</td>
                                <td>{{number_format(round(floatval($inventory->cost/$inventory->quantity)))}}</td>
                                <td>{{number_format($inventory->newPrice)}}</td>
                                <td>{{number_format($inventory->product->price)}}</td>
                                <td>{{$inventory->created_at->format('D d.m.Y H:m:s')}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        </table>
                    @else
                        <h4 class="text-center text-muted"><i>No Inventories</i></h4>
                    @endif
                    <div id="inventoryPagination" class="row">
                        <div class="offset-4">
                            {{$inventories->render()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>
        $(document).ready(function () {
            $('#searchInventory').keyup(function () {
                var token = $('#token').val();
                var search = $(this).val();

                if (search.length<1){
                    $('#search').focus();
                    return;
                }

                var url = 'inventory/search/'+search;
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
                        $('#inventoryTableBody').html("<h2 class='text-muted' style='margin:3em 0 0 10em;'>Not found</h2>");
                        $('#inventorySearchResultContainer').removeAttr('hidden');
                        $('#inventoryPagination').hide();
                    }else{
                        $('#inventorySearchResultContainer').removeAttr('hidden');
                        $('#inventoryTableBody').html(data);
                        $('#inventoryPagination').hide();
                    }
                });
            });
        });
    </script>
@stop