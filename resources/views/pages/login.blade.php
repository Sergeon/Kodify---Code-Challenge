@extends ('layouts/login')


@section('content')

    <div class="brand-wrapper">

    </div>

    <div class="form-wrapper login">

        <h1>Bienvenido!</h1>

        <div class="row">
            <form class=" col m4 offset-m4 s12" action="login" method="post">
                <input class="col s12" type="text" name="username" value="" placeholder="Username">
                <input class="col s12" type="password" name="password" value="" placeholder="Password">
                 <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                <div class="button-wrapper">
                    <button class="btn waves-effect waves-light" type="submit" name="action">Log In
                        <i class="material-icons right">navigation</i>
                    </button>
                </div>
            </form>

            <p>
                {{ $errors->first('username') }}
                {{ $errors->first('password') }}
            </p>
        </div>
    </div>


@stop
