@extends('layouts.main')

@section('title')
    @if(Auth::user()->role->name=='Manager')
        Manager | Dashboard
    @else
        Seller
    @endif
@stop

@section('content')
    <style>

        /*card look*/
        .yesterday{
            background: #ff8300 !important;
        }

        .today{
            background: #00b5e9 !important;
        }
        .week{
            background: #00b26f !important;
        }
        .month{
            background: #fa4251 !important;
        }
        .card-icon{
            display: inline-block;
            position: absolute;
            bottom: -70px;
            right: -20px;
        }
        .card-icon i{
            font-size: 180px;
            color: #808080;
            opacity: .2;
            line-height: 1;
            vertical-align: baseline;
            display: inline-block;
        }
        .report{
            color:#ffffff;
            position: relative;
            overflow: hidden;
            margin-bottom: 2em;
        }
        .report .card-subtitle, .report a:hover{
            color: #eeeeee;
        }
        .report  a{
            color:white;
            position: relative;
        }
        .middel-report{
            margin-bottom:2em;
            height: fit-content;
            background: #ffffff !important;
            box-shadow: 0px 10px 20px 0px rgba(0, 0, 0, 0.03);

        }

        .dot-profit {
            background: #00b5e9;;
        }
        .dot-loss {
            background: #fa4251;
        }
        .dot-net {
            background: #42a347;
        }
        .dot-expense{
            background: #ffd95a;
        }
        .dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            -webkit-border-radius: 100%;
            -moz-border-radius: 100%;
            border-radius: 100%;
        }

        .title{
            font-family: "Poppins", sans-serif;
            font-size: 24px;
            margin: 0.5em 0 0.5em 1em;
        }
        .card-group-title .title{
            margin: 2em 0 2em 0;
        }
        .card-body .table-borderless td{
            margin-top: 3em;
            border-bottom: 1px solid #dee2e6;
            font-family: "Poppins", sans-serif;

        }
        .card-body .table-borderless tr td:nth-child(2), .card-body .table-borderless tr td:first-child{
            color:#808080;
        }

        .card-body .table-borderless tr td:last-child{
            color: #3b94a4;
        }

        .card-body .table{
            margin-top: 2em;
        }
        .loader {
            border: 8px solid #f3f3f3;
            border-radius: 50%;
            border-top: 8px solid #3498db;
            width: 100px;
            height: 100px;
            -webkit-animation: spin 2s linear infinite; /* Safari */
            animation: spin 2s linear infinite;
            left: 50%;
            margin-left: -4em;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    <header style="margin-top:30px;margin-left:0;padding-left:48px;padding-bottom:6px;">
        <h2>Dashboard</h2>
    </header>
    <div class="container">
        <div class="row" style="margin-top:15px;">
            <div class="col-lg-3">
                <div class="card today report">
                    <div class="card-body">
                        <h3 class="card-title text-center">Tzs {{$report->todayTotal?number_format($report->todayTotal):'0'}}</h3>
                        <h5 class="card-subtitle text-center">Today</h5>
                        <div class="card-icon">
                            <i class="ion ion-ios-cart"></i>
                        </div>
                        <a href="{{route('report.today')}}" class="card-link btn btn-block" style="margin-top: 2em;">View all   <i class="ion ion-ios-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card yesterday report">
                    <div class="card-body">
                        <h3 class="card-title text-center">Tzs {{$report->yesterdayTotal?number_format($report->yesterdayTotal):'0'}}</h3>
                        <h5 class="card-subtitle text-center">Yesterday</h5>
                        <div class="card-icon">
                            <i class="ion ion-android-time"></i>
                        </div>
                        <a href="{{route('report.yesterday')}}" class="card-link btn btn-block" style="margin-top: 2em;">View all   <i class="ion ion-ios-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card week report">
                    <div class="card-body">
                        <h3 class="card-title text-center">Tzs {{$report->weekTotal?number_format($report->weekTotal):'0'}}</h3>
                        <h5 class="card-subtitle text-center">Week ago</h5>
                        <div class="card-icon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <a href="{{route('report.week')}}" class="card-link btn btn-block" style="margin-top: 2em;">View all   <i class="ion ion-ios-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card month report">
                    <div class="card-body">
                        <h3 class="card-title text-center">Tzs {{$report->monthTotal?number_format($report->monthTotal):'0'}}</h3>
                        <h5 class="card-subtitle text-center">Month ago</h5>
                        <div class="card-icon">
                            <i class="fa fa-money"></i>
                        </div>
                        <a href="{{route('report.month')}}" class="card-link btn btn-block" style="margin-top: 2em;">View all   <i class="ion ion-ios-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 2em;padding-right: 3em;">
            <div class="col-lg-12">
                <div class="pull-right">
                    <div>

                        <em><small class="text-danger">{{session('dateError')?session('dateError'):''}}</small></em>
                    </div>
                    <label class="col-form-label">View Sale: </label>&nbsp;&nbsp;&nbsp;<button class="btn btn-success pull-right" data-toggle="modal" data-target="#filterModal">Choose Date <i class="fa fa-calendar"></i></button>

                </div>

            </div>
        </div>
    </div>

    {{--Filter Modal--}}
    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Choose dates</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['method'=>'POST','action'=>'ReportController@filter']) !!}
                        <div class="row">
                            <div class="col-lg-9">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <label>From: </label>
                                        </div>
                                        <div class="col-lg-9">
                                            <input type="date" max="{{date('Y-m-d',time())}}" name="from" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <label>To: </label>
                                        </div>
                                        <div class="col-lg-9">
                                            <input type="date" max="{{date('Y-m-d',time())}}" name="to" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <button class="btn btn-success btn-block" type="submit" style="margin-top: 30%;">View</button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>


    <script src="/js/chartjs/Chart.bundle.min.js"></script>
    @yield('report')

@stop
@section('script')

@stop
@yield('reportScript')