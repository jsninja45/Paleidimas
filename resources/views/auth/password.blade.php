@extends('app')

@section('title'){{ 'Request new password' }}@stop
@section('bodyClass'){{ 'new-password-page' }}@stop

@section('content')

    <div class="auth-body">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-5">
                    <h3 class="heading">@yield('title')</h3>

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @elseif ($errors->has())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form role="form" method="POST" action="{{ url('/password/email') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <input type="email" class="form-control input-icon-email" name="email" placeholder="Email address" value="{{ old('email') }}">
                        </div>

                        <div class="form-group auth-last-row">
                            <button type="submit" class="button-large button-auth">Reset my password</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@stop
