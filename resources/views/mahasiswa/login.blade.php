<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="{{ asset("/bootstrap-3.3.7-dist/css/bootstrap.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/css/style_login.css") }}" rel="stylesheet" type="text/css" />
  </head>

  <body>
    <div class="login">
      <img id = "logo-unpar" src="{{ asset("/images/logo-unpar.png") }}" />
    <h1>Login</h1>

      <form method="POST" action="{{ url('/login') }}">
        <br>

        @if (Session::has('success_message'))
            <!-- Form Success Message -->
            <div class="alert alert-success">
                {!! Session::get('success_message') !!}
            </div>
        @endif
        @if (Session::has('error_message'))
            <!-- Form Error Message -->
            <div class="alert alert-danger">
                {!! Session::get('error_message') !!}
            </div>
        @endif

        <div>
            <input type="text" name="username" class="form-control" value="{{ old('username') }}" placeholder="Username" required autofocus />
            @if($errors->has('username'))
                <span class="help-block">
                    <strong>{{ $errors->first('username') }}</strong>
                </span>
            @endif
        </div>
        <div>
            <input type="password" name="password" placeholder="Password" required class="form-control" />
            @if($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
          {{ csrf_field() }}
          <button type="submit" class="btn btn-success" id="login">Login</button>
      </form>
    </div>
  </body>
</html>
