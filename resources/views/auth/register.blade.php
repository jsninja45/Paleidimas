@extends('app')

@section('title'){{ 'Sign up' }}@stop
@section('bodyClass'){{ 'register-page' }}@stop

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
                    @endif

                    <form role="form" method="POST" action="">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <input type="email" class="form-control" name="email" placeholder="Your Email" value="{{ old('email') }}">
                        </div>

                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="Password">
                        </div>

                        <div class="form-group auth-last-row clearfix">
                            <button type="submit" class="button-large button-auth">Create new account</button>
                        </div>
                    </form>
                </div>

                <div class="col-xs-12 col-md-6 col-lg-6 col-lg-push-1">
                    @include('auth.partial.social_login')
                </div>
            </div>
        </div>
    </div>

@stop
