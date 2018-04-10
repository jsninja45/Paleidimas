@extends('app')

@section('title'){{ 'Login' }}@stop
@section('bodyClass'){{  'login-page' }}@stop

@section('content')

    <div class="auth-body">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-5">
                    <h3 class="heading">User account</h3>

                    @if ($errors->has())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @elseif (Session::has('error'))
                        <div class="alert alert-danger">
                            <ul><li>{{ Session::get('error') }}</li></ul>
                        </div>
                    @endif

                    <form role="form" method="POST" action="">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <input type="text" class="form-control" name="email" placeholder="Your Email" value="{{ old('email') }}">
                        </div>

                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="Password">
                        </div>

                        <div class="form-group">
                            <div class="checkbox">
                                <input type="checkbox" name="remember" id="rememberMe">
                                <label for="rememberMe">Remember password</label>
                            </div>
                        </div>

                        <div class="form-group auth-last-row clearfix">
                            <a class="forgot-password" href="{{ url('/password/email') }}">Forgot your password?</a>
                            <button type="submit" class="button-large button-auth-login orange">Login</button>
                        </div>
                    </form>
                </div>

                <div class="col-xs-12 col-md-6 col-lg-6 col-lg-push-1">
                    @include('auth.partial.social_login')

                    <div class="form-group">
                        <div class="sign-up-heading">New here?</div>
                    </div>

                    <div class="form-group clearfix">
                        <div class="sign-up-label">Sign up now!</div>
                        <a class="button-large button-auth button-auth-register" href="{{ url('/auth/register') }}">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
