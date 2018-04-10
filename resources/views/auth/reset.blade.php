@extends('app')

@section('title'){{ 'Reset password' }}@stop
@section('bodyClass'){{ 'new-password-page' }}@stop

@section('content')

    <div class="auth-body">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-5">
                    <h3 class="heading">@yield('title')</h3>

                    @if ($errors->has())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="js-reset-password" role="form" method="POST" autocomplete="off" action="{{ url('/password/reset') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="password_confirmation">

                        <div class="form-group">
                            <input type="email" class="form-control" name="email" placeholder="Your Email" value="{{ old('email') }}">
                        </div>

                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="New Password">
                        </div>

                        <div class="form-group auth-last-row">
                            <button type="submit" class="button-large button-auth">Reset password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>

        $(function() {
            $('.js-reset-password').submit(formSubmit);

            function formSubmit() {
                // fill hidden password field
                var form = $(this);
                var password = form.find('[name=password]');
                var password_confirmation = form.find('[name=password_confirmation]');

                password_confirmation.val(password.val());
            }
        })

    </script>

@endsection
