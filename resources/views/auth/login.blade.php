<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./css/libs.css">
    <link rel="stylesheet" href="./css/app.css">
    <link rel="stylesheet" href="./fonts/font-awesome.min.css">
    <link rel="stylesheet" href="./fonts/ionicons.min.css">
</head>

<body>
<div>
    <nav class="navbar navbar-light navbar-expand-md navigation-clean">
        <div class="container"><a class="navbar-brand" href="{{url('/')}}">Point of Sales</a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse"
                 id="navcol-1"></div>
        </div>
    </nav>
</div>
<div class="login-clean">
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
        {{ csrf_field() }}
        <h2 class="sr-only">Login Form</h2>
        <div class="illustration"><i class="icon ion-ios-navigate"></i></div>
        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
            <input id="username" type="text" class="form-control" name="username" placeholder="Username" value="{{ old('username') }}" autofocus required>

            @if ($errors->has('username'))
                <span class="text-danger">
                    <small>{{ $errors->first('username') }}</small>
                </span>
            @endif
        </div>

        <div class="form-group">
            <input id="password" type="password" placeholder="Password" class="form-control" name="password">

            @if ($errors->has('password'))
                <span class="text-danger">
                    <small>{{ $errors->first('password') }}</small>
                </span>
            @endif
        </div>

        <div class="form-group">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="remember"> Remember Me
                </label>
            </div>
        </div>

        <div class="form-group">
            <button class="btn btn-primary btn-block" type="submit">Log In</button>
        </div>
    </form>
    <div class="row" style="margin-top: 5em;">
        <div class="col-lg-12 text-center" style="">
            Copyright &copy; 2019. Powered by <a href="http://technologyhomesite.com" target="_blank">Technology Homesite Ltd.</a> All right reserved
        </div>
    </div>
</div>

<script src="{{url('/js/jquery.min.js')}}"></script>
<script src="{{url('/js/bootstrap.min.js')}}"></script>
<script src="{{url('/js/scripts.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#email').focus();
    });
</script>
</body>

</html>