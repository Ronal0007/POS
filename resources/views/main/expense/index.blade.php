@extends('layouts.main')

@section('title')
    Expenses
@stop

@section('content')
    <header style="margin-top:42px;margin-left:0px;padding-left:48px;border-bottom:2px solid #c1c1c1;padding-bottom:6px;">
        <h2>Expenses</h2>
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
                <div class="col-lg-4 offset-2" style="margin-bottom:14px;">
                    <form method="post" action="/expense/search">
                        {{csrf_field()}}
                        <div class="input-group">
                            <input name="search" class="form-control" type="text" placeholder="..Search..">
                            <div class="input-group-append">
                                <button type="submit" class="input-group-text btn btn-success" id="basic-addon2"><i class="ion ion-ios-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4">
                    <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                    <div class="collapse" id="collapseExample">
                        <div class="card card-body">
                            <form action="/expense/filter" method="post">
                                {{csrf_field()}}
                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-lg-3 text-center"><label class="col-form-label" for="name">From:</label></div>
                                        <div class="col-lg-9 col-xl-8"><input class="form-control" type="date" name="from" max="{{date('Y-m-d',time())}}"/></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-lg-3 text-center"><label class="col-form-label" for="name">To:</label></div>
                                        <div class="col-lg-9 col-xl-8"><input class="form-control" type="date" name="to" max="{{date('Y-m-d',time())}}"/></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-lg-6 offset-4 text-center"><button class="btn btn-success btn-block" type="submit"><i class="fa fa-check"></i>&nbsp;Submit</button></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <button data-toggle="modal" data-target="#addExpenseModal" class="btn btn-success pull-right"><i class="ion ion-android-add"></i>  Add Expense</button>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 offset-8 pull-right">
                    @if(count($errors)>0)
                        <ul>
                        @foreach($errors->all() as $error)
                            <li><em style="color:red;">{{$error}}</em></li>
                        @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col" style="margin-top:15px;">
                    <div id="searchResultContainer" hidden="true">
                        <h4>Search Result:  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><a href="/product">Show All</a></small></h4>
                    </div>

                    @if(count($expenses)>0)
                        <table id="product-table" class="table">
                            <thead>
                            <tr>
                                <th>Details</th>
                                <th>Amount</th>
                                <th>User</th>
                                <th>Cash To</th>
                                <th>Due</th>
                            </tr>
                            </thead>
                            <tbody id="productTable">
                            @foreach($expenses as $expense)
                                <tr>
                                    <td>{{$expense->cash_detail}}</td>
                                    <td>{{number_format($expense->amount)}}/=</td>
                                    <td>{{$expense->user->name}}</td>
                                    <td>{{$expense->cash_to}}</td>
                                    <td>{{$expense->created_at->diffForHumans()}}</td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <h4 class="text-center text-muted"><i>No Expense Recorded</i></h4>
                    @endif
                    <div id="pagenation" class="row">
                        <div class="offset-4">
                            {{$expenses->render()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addExpenseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Expense</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['method'=>'POST','action'=>'ExpenseController@store','id'=>'addExpenseForm']) !!}
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-lg-3 text-center"><label class="col-form-label" for="name">Details:</label></div>
                            <div class="col-lg-9 col-xl-8"><textarea class="form-control" type="text" name="cash_detail"></textarea></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-lg-3 text-center"><label class="col-form-label" for="name">Amount:</label></div>
                            <div class="col-lg-9 col-xl-8"><input class="form-control" type="number" name="amount"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-lg-3 text-center"><label class="col-form-label" for="name">Payed To:</label></div>
                            <div class="col-lg-9 col-xl-8"><input class="form-control" type="text" name="cash_to"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-lg-6 offset-4 text-center"><button class="btn btn-success btn-block" type="submit"><i class="fa fa-check"></i>&nbsp;Submit</button></div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop