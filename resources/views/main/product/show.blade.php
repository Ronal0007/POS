@extends('layouts.main')

@section('title')
    Products
@stop

@section('content')
    <header style="margin-top:42px;margin-left:0px;padding-left:48px;border-bottom:2px solid #c1c1c1;padding-bottom:6px;">
        <h2>Print Barcode <small>(<i class="text-muted">{{$product->name}}</i>)</small></h2>
    </header>

    <div class="row" style="margin-top: 10px;">
        <div class="col-lg-7">
            <form>
                <input type="hidden" id="productBar" value="{{$product->id}}">
                <input type="hidden" id="productQr" value="{{$product->id}}">
            <div class="form-group">
                <div class="form-row">
                    <div class="col-lg-3 text-center"><label class="col-form-label" for="name">No. of Barcode:</label></div>
                    <div class="col-lg-9 col-xl-8">{!! Form::number('',null,['id'=>'number','class'=>'form-control', 'min'=>1,'max'=>120,'placeholder'=>'Number of barcodes to print max 120  min 1']) !!}</div>
                </div>
            </div>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-lg-3 text-center"><label for="type" class="col-form-label">Type:</label></div>
                        <div class="col-lg-9 col-xl-8">
                            <div class="radio">
                                <label for=""><input class="radioType" type="radio" checked name="type" value="qrcode"> QR-Code</label>
                            </div>
                            <div class="radio">
                                <label for=""><input class="radioType" type="radio" name="type" value="barcode"> Barcode</label>
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
                    <div class="col-lg-5 offset-6 text-center"><button id="gen-btn" class="btn btn-success btn-block" type="button"><i class="ion ion-eye"></i>&nbsp;View</button></div>
                </div>
            </div>
                <div id="con" class="form-group" hidden='true'>

                </div>
                <div id="con2" class="form-group" hidden="true">
                </div>
            </form>
        </div>
    </div>
    <div id="barcodes" class="row">

        <div id="printArea" class="col-lg-12"  style=" padding-right: 30px;"></div>
    </div>
@stop

@section('script')
    <script src="{{url('/js/jquery-barcode.js')}}"></script>
    <script src="{{url('/js/jquery.qrcode.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#gen-btn').click(function () {
                var product;
                var number = $("#number").val();
                var barType;

                $('.radioType').each(function () {
                    if ($(this).is(':checked')){
                        barType = $(this).val();

                    }
                });



                if (number<1){
                    alert('Value must be greater than zero \n \t\t OR \n \tInvalid value');
                    return;
                }
                if(number<1 || number>120){
                    alert('Value not within the range 1-100');
                    return;
                }





                if (barType=='barcode'){
                    product = $('#productBar').val();
                    var settings = {
                        output:'bmp',
                        // bgColor: $("#bgColor").val(),
                        // color: $("#color").val(),
                        barWidth: 3,
                        barHeight: 60,
                        // moduleSize: $("#moduleSize").val(),
                        // posX: $("#posX").val(),
                        // posY: $("#posY").val(),
                        // addQuietZone: $("#quietZoneSize").val()
                    };

                    $('#con').barcode(product,'code39',settings);
                    $('#barcodes .col-lg-12').html(function () {
                        var rows = Math.floor(number/4);
                        var cols = number%4;
                        var printBtn = '<button id="print2" class="btn btn-primary" onclick="printPage()" style="margin:10px;">Print</button>';
                        var rowHead = '<div class="row" style="border-bottom: 1px dashed black; margin-bottom: 20px; padding-bottom: 15px;">';
                        var rowFoot = '</div>';
                        var bars = "<div class='col-lg-3'>"+ $('#con').html() +"</div>" +
                            "<div class='col-lg-3'>"+ $('#con').html() +"</div>" +
                            "<div class='col-lg-3'>"+ $('#con').html() +"</div>" +
                            "<div class='col-lg-3'>"+ $('#con').html() +"</div>";

                        var singleRow = rowHead+bars+rowFoot;
                        var singleColumn = "<div class='col-lg-3'>"+ $('#con').html() +"</div>";
                        var rowsContent = "";
                        var colsContent = "";
                        var rowFromRemainder = "";

                        if (rows>0){
                            for (var i=0;i<rows;i++){
                                rowsContent+= singleRow;
                            }
                        }

                        if (cols>0){
                            for (var i=0;i<cols;i++){
                                colsContent+=singleColumn;
                            }
                            rowFromRemainder=rowHead+colsContent+rowFoot;
                        }

                        //Joinging rows
                        if (rows>0 && cols>0){
                            return printBtn+rowsContent+rowFromRemainder;
                        }
                        if(rows>0){
                            return printBtn+rowsContent;
                        }
                        if (cols>0){
                            return printBtn+rowFromRemainder;
                        }
                    });
                }else{
                    product = $('#productQr').val();
                    $('#con2').qrcode({
                       render:'canvas',
                        width:75,
                        height:75,
                        text:product
                    });
                   var canvas = $('#con2 canvas');
                   var img = canvas.get(0).toDataURL("image/png");

                    $('#barcodes .col-lg-12').html(function () {
                        var rows = Math.floor(number/12);
                        var cols = number%12;
                        var printBtn = '<button id="print2" class="btn btn-primary" onclick="printPage()" style="margin:10px;">Print</button>';
                        var rowHead = '<div class="row" style="border-bottom: 1px dashed black; margin-bottom: 20px; padding-bottom: 15px;">';
                        var rowFoot = '</div>';
                        var bars = "<div class='col-lg-1'><img src="+ img +"></div>" +
                            "<div class='col-lg-1'><img src="+ img +"></div>" +
                            "<div class='col-lg-1'><img src="+ img +"></div>" +
                            "<div class='col-lg-1'><img src="+ img +"></div>" +
                            "<div class='col-lg-1'><img src="+ img +"></div>" +
                            "<div class='col-lg-1'><img src="+ img +"></div>" +
                            "<div class='col-lg-1'><img src="+ img +"></div>" +
                            "<div class='col-lg-1'><img src="+ img +"></div>" +
                            "<div class='col-lg-1'><img src="+ img +"></div>" +
                            "<div class='col-lg-1'><img src="+ img +"></div>" +
                            "<div class='col-lg-1'><img src="+ img +"></div>" +
                            "<div class='col-lg-1'><img src="+ img +"></div>";

                        var singleRow = rowHead+bars+rowFoot;
                        var singleColumn = "<div class='col-lg-1'><img src="+ img +"></div>";
                        var rowsContent = "";
                        var colsContent = "";
                        var rowFromRemainder = "";

                        if (rows>0){
                            for (var i=0;i<rows;i++){
                                rowsContent+= singleRow;
                            }
                        }

                        if (cols>0){
                            for (var i=0;i<cols;i++){
                                colsContent+=singleColumn;
                            }
                            rowFromRemainder=rowHead+colsContent+rowFoot;
                        }

                        //Joinging rows
                        if (rows>0 && cols>0){
                            return printBtn+rowsContent+rowFromRemainder;
                        }
                        if(rows>0){
                            return printBtn+rowsContent;
                        }
                        if (cols>0){
                            return printBtn+rowFromRemainder;
                        }
                    });
                }

                var pageToPrint = $('#barcodes').html();
                $('#barcodes').hide();
                var popWin = window.open('','_blank');
                popWin.document.open();
                popWin.document.write("<html><title>Print</title>" +
                    "\n <link rel='stylesheet' href='/css/libs.css'>" +
                    pageToPrint +
                    "\<script>function printPage(){window.print();}<\/script>" +
                    "</html>");
                popWin.document.close();

            });
        });
    </script>
@stop