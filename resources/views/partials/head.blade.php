<head>


    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="author" content="">

    <meta name="_token" content="{!! csrf_token() !!}"/>

    <script type="text/javascript">

        var csrf_token = "{!! csrf_token() !!}";
    </script>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ elixir('assets/css/app.css') }}" media="screen" title="no title" charset="utf-8">

    <title>{{ $title or 'Laravel init Template'}}</title>

</head>
