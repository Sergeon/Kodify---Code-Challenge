<!doctype html>
<html>
    @include('partials.head')
<body>


    <main>
        @yield('content')
    </main>



    <script type="text/javascript" src="{{ elixir('assets/js/all.js') }}">
    </script>

</body>
</html>
