<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <title>Attendance management system</title>
  


    <!-- Bootstrap 4.5.2 CSS -->
    <link rel="stylesheet"
          href="{{ asset('css/bootstrap-min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('css/style.css') }}">

    @stack('css')

</head>

<body>



    <main class="py-4"
          style="background: radial-gradient(circle at top right, #F4EBEF 16%, #F5F5FA 10.1%);">
        @yield('content_body')
    </main>


    {{-- @include('frontend.partials.another_solution') --}}
  

    <!-- Bootstrap JS -->
    {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> --}}
    <script src="{{ asset('js/jquery-3-5-1-slim-min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-min.js') }}"></script>
    <script src="{{ asset('js/alpinejs.js') }}"></script>



    @stack('js')
</body>

</html>
