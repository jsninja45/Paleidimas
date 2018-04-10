@extends('app')

@section('title')
    Sign in
@stop

@section('content')
    <div class="upload-body upload-register-body">
        <div class="container">
            <h3 class="heading">Sign in</h3>

            <div class="upload-header">
                <div class="row">
                    <div class="col-xs-12 col-md-5">
                        <span class="auth-text">Already have an account?</span>
                        <div class="button-medium button-yellow button-auth-login js-toggle-login">Sign in</div>
                    </div>

                    <div class="col-xs-12 col-md-7">
                        @include('upload.partial.progress', ['step' => 2])
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-9">

                    @if ($errors->has())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    {{-- HIDDEN:login  --}}
                    <form class="js-login" role="form" method="POST" autocomplete="off" action="{{ route('upload_login') }}" style="display:none">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-horizontal">
                            <div class="col-xs-12 col-md-6">
                                <input type="email" class="form-control" name="email" placeholder="Email">
                            </div>

                            <div class="col-xs-12 col-md-6">
                                <input type="password" class="form-control" name="password" placeholder="Password">
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <button class="button-medium button-neon pull-right">Login</button>
                        </div>
                    </form>


                    {{-- Register --}}
                    <form class="js-register" autocomplete="off" role="form" method="POST" action="{{ route('upload_register') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-horizontal">
                            <div class="col-xs-12 col-md-6">
                                <input type="email" class="form-control" name="email" placeholder="Email address *" value="{{ old('email') }}">
                            </div>

                            <div class="col-xs-12 col-md-6">
                                <input type="password" class="form-control" name="password" placeholder="Password *">
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <button type="submit" class="button-medium button-neon pull-right">Register</button>
                        </div>
                    </form>

                    <div class="social-login clearfix">
                        <div class="auth-text">Or log in with...</div>

                        <a class="auth-social auth-facebook" href="{{ route('social_login', ['facebook']) }}">Sign in with Facebook</a>
                        <a class="auth-social auth-google" href="{{ route('social_login', ['google']) }}">Sign in with Google</a>
                    </div>


                </div>


                <div class="col-xs-12 col-md-3">
                    @include('upload.partial.my_order', ['show_delivery_estimates' => true])
                </div>
            </div>

        </div>

    </div>



    <script>

        $(function() {
            $(".js-toggle-login").click(toggleChangeEmail);
            $(".js-register").submit(validateRegister);
            $(".js-login").submit(validateLogin);

            function toggleChangeEmail() {
                $(".js-login").slideToggle();
            }

            function validateRegister(e) {
                var form = $(this);

                var email = form.find('[name=email]');
                if ($.trim(email.val()) == '') {
                    email.addClass('input-error');
                    e.preventDefault();
                }

                var password = form.find('[name=password]');
                if ($.trim(password.val()) == '') {
                    password.addClass('input-error');
                    e.preventDefault();
                }
            }

            function validateLogin(e) {
                var form = $(this);

                var email = form.find('[name=email]');
                if ($.trim(email.val()) == '') {
                    email.addClass('input-error');
                    e.preventDefault();
                }

                var password = form.find('[name=password]');
                if ($.trim(password.val()) == '') {
                    password.addClass('input-error');
                    e.preventDefault();
                }
            }
        });

    </script>



@stop