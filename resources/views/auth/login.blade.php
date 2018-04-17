@extends('layouts.login')

@section('title')
    Login
@stop

@section('content')
  <div class="login-box">
    <div class="login-logo">
      <a href="#"><b>{{ config('app.name') }}</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
      <p class="login-box-msg">Sign in to start your session</p>
      <form action="{{ url('/login') }}" method="post">
        {{ csrf_field() }}
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} has-feedback">
          @if ($errors->has('email'))
          <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{ $errors->first('email') }}</label>
          @endif
          <input name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autofocus="">
          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} has-feedback">
          @if ($errors->has('password'))
            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{ $errors->first('password') }}</label>
          @endif
          <input name="password" type="password" class="form-control" placeholder="Password" required>
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
          <div class="col-xs-8">
            @if (config('adminlte.remember_me', true))
            <div class="checkbox icheck">
              <label>
                <input type="checkbox" name="remember"> Remember Me
              </label>
            </div>
            @endif
          </div>
          <!-- /.col -->
          <div class="col-xs-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      @if (config('adminlte.login_facebook', true) || config('adminlte.login_twitter', true) || config('adminlte.login_google', true))
      <div class="social-auth-links text-center">
        <p>- OR -</p>
        @if (config('adminlte.login_facebook'))
        <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using Facebook</a>
        @endif
        @if (config('adminlte.login_twitter'))
        <a href="#" class="btn btn-block btn-social btn-twitter btn-flat"><i class="fa fa-twitter"></i> Sign in using Twitter</a>
        @endif
        @if (config('adminlte.login_google'))
        <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using Google+</a>
        @endif
      </div>
      @endif
      <!-- /.social-auth-links -->

      @if (config('adminlte.login_forgot_password', true))
      <a href="{{ url('/password/reset') }}">I forgot my password</a><br>
      @endif
      @if (config('adminlte.login_register', true))
      <a href="{{ url('/register') }}" class="text-center">Register a new membership</a>
      @endif

    </div>
    <!-- /.login-box-body -->
@endsection
