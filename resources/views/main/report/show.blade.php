@extends('main.index')

@section('report')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 card-group-title">
                <h4 class="title">{{ucfirst($report->data['title'])}}</h4>
            </div>
        </div>
    </div>
    @if(count($report->sales->name)>0)
        <div class="row">
            <div class="col-lg-4 ">
                <div class="card bg-light middel-report">
                    <div class="card-body">
                        <h3 class="title">Profit And Loss</h3>
                        <div><span class="dot dot-profit"></span><span> Profit</span></div>
                        <div><span class="dot dot-loss"></span><span> Loss</span></div>
                        <div><span class="dot dot-expense"></span><span> Expenses</span></div>
                        <div><span class=""></span><span> <i>Overall</i>:  <span class="font-weight-bold {{$report->profitLoss['netProfit']>0?'text-success':'text-danger'}}">Tzs {{number_format($report->profitLoss['netProfit'])}}/=</span></span></div>
                        <canvas id="chartView" style="margin-top: .5em;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 ">
                <div class="card bg-light middel-report">
                    <div class="card-body">
                        <h3 class="title">Top 5 Profit Item</h3>
                        <table class="table table-borderless">
                            <tbody>
                            @php($num=1)
                            @foreach($report->topProfiting as $name=>$amount)
                                <tr>
                                    <td>{{$num}}.</td>
                                    <td title="{{$name}}">{{substr($name,0,7)}}</td>
                                    <td>{{number_format($amount)}}/=</td>
                                </tr>
                                @php($num++)
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 ">
                <div class="card bg-light middel-report">
                    <div class="card-body">
                        <h3 class="title">Top 5 Sold Item</h3>
                        <canvas id="chartView2" class=""></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: -2em;">
            <div class="col-lg-9 offset-1">
                <div class="row">
                    <div class="col-lg-12 card-group-title">
                        <h4 class="title">Sales chart</h4>
                    </div>
                </div>
                <div class="card" style="padding-right: 8em;margin-top: -2em;">
                    <div class="card-body">
                        <canvas id="saleChart" style="margin:3em;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-10 offset-1">
                @if(count($report->sales->name)>0)
                    <div class="row">
                        <div class="col-lg-12 card-group-title">
                            <h4 class="title">Sales data</h4>
                        </div>
                    </div>
                    <div class="row" style="margin-top: -4em;">
                        <div class="col-lg-2 offset-10">
                            @if($report->from=='filter')
                                <a href="{{route('print.'.$report->from,['from'=>$report->data['from'],'to'=>$report->data['to']])}}" class="btn text-info pull-right"><h4><i class="ion ion-printer"></i><small>  Print</small></h4></a>
                            @else
                                <a href="{{route('print.'.$report->from)}}" class="btn text-info pull-right"><h4><i class="ion ion-printer"></i><small>  Print</small></h4></a>
                            @endif
                        </div>
                    </div>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                            <th>Loss Quantity</th>
                            <th>Loss Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($num=1)
                        @for($i=0;$i<count($report->sales->name);$i++)
                            <tr>
                                <td>{{$num}}.</td>
                                <td title="{{$report->sales->name[$i]}}">{{substr($report->sales->name[$i],0,7)}}</td>
                                <td>{{$report->sales->qty[$i]}}</td>
                                <td class="text-success font-weight-bolder">{{number_format($report->sales->amount[$i])}}</td>
                                <td class="{{$report->sales->lossQty[$report->sales->name[$i]]>0?'text-danger font-weight-bold':''}}">{{$report->sales->lossQty[$report->sales->name[$i]]}}</td>
                                <td class="{{$report->sales->lossQty[$report->sales->name[$i]]>0?'text-danger font-weight-bold':''}}">{{number_format($report->sales->lossAmount[$report->sales->name[$i]])}}</td>
                            </tr>
                            @php($num++)
                        @endfor
                        </tbody>
                    </table>
                @endif
                    @if(count($report->profitLoss['expensesData'])>0)
                        <div class="row">
                            <div class="col-lg-12 card-group-title">
                                <h4 class="title">Expenses</h4>
                            </div>
                        </div>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Details</th>
                                <th>Amount</th>
                                <th>Issuer</th>
                                <th>Payed To</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($num=1)
                            @foreach($report->profitLoss['expensesData'] as $expense)
                                <tr>
                                    <td>{{$num}}.</td>
                                    <td>{{$expense->cash_detail}}</td>
                                    <td>{{number_format($expense->amount)}}/=</td>
                                    <td>{{$expense->user->name}}</td>
                                    <td>{{$expense->cash_to}}</td>
                                    <td>{{$expense->created_at->format('D d-m-Y')}}</td>
                                    </tr>
                                @php($num++)
                            @endforeach
                            </tbody>
                        </table>
                    @endif
            </div>
        </div>
        <script>
            // window.onload = function () {

            try {
                var ctx = document.getElementById("saleChart");
                if (ctx) {
                    ctx.height = 110;

                    var myData = <?php echo json_encode($report->data) ?>;
                    console.log(myData);




                    var options = {
                        type: 'bar',
                        data: {
                            // labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            labels:myData.name,
                            datasets: [
                                {
                                    label: "Sale",
                                    // data: [45000,78000,32000,97000,15000,78000,78000,45000,102000,45000,90000,6800,78000],
                                    data:myData.value,
                                    borderColor: "transparent",
                                    // borderWidth: "0",
                                    pointBackgroundColor:'#555',
                                    backgroundColor: "#6cb2eb",
                                    // backgroundColor:['green','red','blue','orange']
                                },
                                // {
                                //     label: "Profit",
                                //     data: [45000,78000,32000,97000,15000,78000,78000,45000,102000,45000,90000,6800,78000],
                                //     // data:myData.value,
                                //     borderColor: "transparent",
                                //     // borderWidth: "0",
                                //     pointBackgroundColor:'#eee',
                                //     backgroundColor: "#e4e4e4",
                                //     // backgroundColor:['green','red','blue','orange']
                                // }
                            ]
                        },
                        options: {
                            maintainAspectRatio: true,
                            title: {
                                display: true,
                                text: myData.title,

                            },
                            legend: {
                                display: false
                            },
                            scales: {
                                xAxes: [{
                                    display: true,
                                    categoryPercentage: 1,
                                    barPercentage: 0.2
                                }],
                                yAxes: [{
                                    valueFormatString: "$#0",
                                    ticks: {
                                        beginAtZero: true,
                                        fontFamily: "Poppins",
                                        fontSize: 12
                                    },
                                    gridLines: {
                                        display: true,
                                        color: '#f2f2f2'

                                    }
                                }]
                            }
                        }
                    };
                    var myChart = new Chart(ctx, options);
                }
            } catch (error) {
                console.log(error);
            }

            var profits =  <?php echo json_encode($report->profitLoss) ?>;
            console.log(profits);
            var topsellingName = <?php echo json_encode(array_keys($report->topSelling)) ?>;
            var topsellingValue = <?php echo json_encode(array_values($report->topSelling)) ?>;

            console.log(topsellingName);
            console.log(topsellingValue);
            window.onload = function () {

                //Profit and loss
                try {
                    var ctx = document.getElementById("chartView");
                    if (ctx) {
                        ctx.height = 220;
                        var options = {
                            type: 'pie',
                            data: {
                                labels: ['Profit','Loss','Expenses'],
                                datasets: [
                                    {
                                        label: "Sale",
                                        data: [profits.profit,profits.loss, profits.expenses],
                                        borderColor: "transparent",
                                        // borderWidth: "0",
                                        pointBackgroundColor:'#555',
                                        backgroundColor: ["#00b5e9","#fa4251","#ffd95a"],
                                        // backgroundColor:['green','red','blue','orange']
                                    }
                                ]
                            },
                            options: {
                                maintainAspectRatio: true,
                                // cutoutPercentage: 80,
                                title: {
                                    display: false,
                                    text: 'Custom Chart Title'
                                },
                                legend: {
                                    display: false
                                },
                            }
                        };
                        console.log(options);
                        var myChart = new Chart(ctx,options );
                    }
                } catch (error) {
                    console.log(error);
                }

                //Top selling Item

                try {
                    var ctx = document.getElementById("chartView2");
                    if (ctx) {
                        ctx.height = 290;
                        var options = {
                            type: 'pie',
                            data: {
                                labels: topsellingName,
                                datasets: [
                                    {
                                        label: "Sale",
                                        data: topsellingValue,
                                        borderColor: "transparent",
                                        // borderWidth: "0",
                                        pointBackgroundColor:'#555',
                                        backgroundColor: ['#00b5e9','#ff8300','#00b26f','#fa4251','#666'],
                                        // backgroundColor:['green','red','blue','orange']
                                    }
                                ]
                            },
                            options: {
                                maintainAspectRatio: true,
                                // cutoutPercentage: 80,
                                title: {
                                    display: false,
                                    text: 'Selling item'
                                },
                                legend: {
                                    display: true
                                },
                            }
                        };
                        console.log(options);
                        var myChart = new Chart(ctx,options );
                    }
                } catch (error) {
                    console.log(error);
                }

            };
        </script>
    @else
        <div class="row" style="margin-bottom: 3em;">
            <div class="col-lg-12 text-center text-muted">
                <h3>No Sales Made at this time</h3>
            </div>
        </div>
    @endif
@stop