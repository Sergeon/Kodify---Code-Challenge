@extends ('layouts/template')


@section('content')


    <div class="container">

        <h1>Hola, {{$empresa->user->username}}</h1>

        @include('pages/empresa/partials/detalle-empresa')

        @include('partials/database/form')

        <h3>Empresas relacionadas con {{ $empresa->nombre }}</h3>
        <div id="listado-relaciones-empresas">
            {!! $listado !!}
        </div>

    </div>

@stop
