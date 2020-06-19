<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layout</title>
    <link rel="stylesheet" href="/css/libs.css">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="/fonts/ionicons.min.css">
</head>

<body>
    <div>
        <nav class="navbar navbar-light navbar-expand-md navigation-clean">
            <div class="container"><a class="navbar-brand" href="{{url('/')}}">Company Name</a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse"
                     id="navcol-1"></div>
            </div>
        </nav>
    </div>
    <div class="row" style="margin-top: 9em;">
        <div class="col-lg-6 offset-3 text-center">
            <h1>Account Suspende!!</h1><br>
            <small>Contact Administrator &nbsp;&nbsp;&nbsp;&nbsp;<a href="/logout">Go Home</a></small>
        </div>
    </div>


    <script src="{{url('/js/jquery.min.js')}}"></script>
    <script src="{{url('/js/bootstrap.min.js')}}"></script>
    <script src="{{url('/js/scripts.js')}}"></script>
</body>

</html>