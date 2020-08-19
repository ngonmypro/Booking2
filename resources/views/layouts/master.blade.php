<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>@yield('pageTitle')</title>
      @include('layouts.inc-css')

  </head>
  <style type="text/css">
html, body{
padding:0px;
margin:0px;
height:100%;
width: 99%;
}

#result{
  font-size: 80%;
}
</style>
  <body>
    <div class="container body">
      <div class="main_container">
        @yield('content')
      </div>
    </div>
    @include('layouts.inc-scripts')
  </body>
</html>
