
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title')</title>

  @include('backend.partials.style')
  
</head>

<body>
  <!-- html content start -->
  <div class="layout-container">
    @include('backend.partials.sidebar')
    <div class="main-container">
        @yield('content')
    </div>
    <!-- html content end -->

   @include('backend.partials.script')
</body>

</html>