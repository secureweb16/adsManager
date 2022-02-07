<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Dashboard</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="{{ asset('common/js/front-end.js') }}"></script>
  <link href="{{ asset('common/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('common/fonts/font-awesome.min.css') }}" rel="stylesheet">
  <link href="{{ asset('common/css/nprogress.css') }}" rel="stylesheet">
  <link href="{{ asset('common/css/custom.min.css') }}" rel="stylesheet">
  <link href="{{ asset('common/css/front-end.css') }}" rel="stylesheet">

</head>


<body class="auth-board">
  <div class="outer-div">
    @yield('content')
  </div>
</body>
</html>

