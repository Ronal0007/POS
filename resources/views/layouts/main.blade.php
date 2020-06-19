<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="@yield('refresh')" >
    <title>@yield('title')</title>
	<link href="{{ URL::asset('css/libs.css') }}" rel="stylesheet">
	<link href="{{ URL::asset('css/app.css') }}" rel="stylesheet">
	<link rel="icon" href="./assets/img/124dDB.jpg">
	 <link rel="stylesheet" href="./fonts/font-awesome.min.css">
    <link rel="stylesheet" href="./fonts/ionicons.min.css">
	<link rel="stylesheet" href="../css/bootstrap.min.css">
</head>

<body>
<div id="wrapper">
    <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <li class="sidebar-brand"> <a href="#">Point Of Sales</a></li>
            @if(Auth::user()->role->name=='Manager')
                <li> <a href="{{url('/main')}}">Dashboard<i class="ion ion-ios-pulse-strong"></i></a></li>
                <li> <a href="{{url('/sale')}}">Sale<i class="ion "></i></a></li>
                <li><a href="{{route('user.index')}}">Users</a></li>
                <li><a href="{{route('product.index')}}">Products</a></li>
                <li><a href="{{route('inventory.index')}}">Inventory</a></li>

                <li> <a href="{{url('/expense')}}">Expense<i class="ion ion-cash"></i></a></li>
            @else
                <li> <a href="{{url('/sale')}}">Stock<i class="ion ion-stats-bars"></i></a></li>
                <li> <a href="{{url('/expense')}}">Expense<i class="ion ion-cash"></i></a></li>
            @endif


        </ul>
    </div>
    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <a class="btn btn-light" role="button" href="#menu-toggle" id="menu-toggle"><i class="fa fa-bars" style="color:#188e55;"></i></a>

                    <div class="dropdown pull-right">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} ({{Auth::user()->role->name}}) <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" data-toggle="modal" data-target="#changePasswordModal">Change Password</a>
                            <a href="{{url('/logout')}}" class="dropdown-item"><i class="ion ion-power"></i> LogOut</a>
                        </div>
                    </div>

                    </div>
                </div>
            </div>
        @if(session('passwordError'))
            <div class="row">
                <div class="col-lg-7 offset-2">
                    <div class="alert alert-danger alert-dismissible text-center" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <em><strong>{{session('error')}}</strong></em>
                    </div>
                </div>
            </div>
        @endif
        @if(session('passwordMessage'))
            <div class="row">
                <div class="col-lg-6 offset-3">
                    <div class="alert alert-success alert-dismissible text-center" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <em><strong>{{session('message')}}</strong></em>
                    </div>
                </div>
            </div>
        @endif
            @yield('content')
        <div class="row" style="margin-top: 3em;">
            <div class="col-lg-12 text-center" style="">
                Copyright © 2019. Powered by <a href="http://ronaltech.co.tz" target="_blank">RonalTech Co. LTD</a> All right reserved
            </div>
        </div>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-lg-center" id="changePasswordModalTitle">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm" action="/change/password" method="post">
                        {{csrf_field()}}
                        <div class="form-group">
                            New password: <input id="passwd" type="password" class="form-control" name="password" required>
                        </div>
                        <div class="form-group">
                            Confirm password: <input id="cpasswd" type="password" class="form-control" name="confirmPassword" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-success pull-right" value="Submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@yield('modal')
<script src="{{url('/js/jquery.min.js')}}"></script>
<script src="{{url('/js/bootstrap.min.js')}}"></script>
<script src="{{url('/js/scripts.js')}}"></script>
<script>
    $('#changePasswordForm').submit(function (e) {
        if($('#passwd').val()!==$('#cpasswd').val()){
            alert('Password Don\'t Match');
            e.preventDefault();
        }

    });
</script>
@yield('script')
</body>

</html>