@extends('app')

@section('title'){{ 'Account settings' }}@stop
@section('bodyClass'){{  'settings-page' }}@stop

@section('scripts')
    @parent

    <script>
        (function($) {
            $(function() {

                // Change forms
                $(".auth-body").on('click', '.button-change', function () {
                    var $form = $($(this).data('target'));

                    if ($form.is(':visible')) {
                            $form.stop().slideUp(400);
                    } else {
                        if ($(".auth-body .change-form:visible").length > 0) {
                            $(".auth-body .change-form:visible").stop().slideUp(400);
                            $form.stop().delay(400).slideDown(400);
                        } else {
                            $form.stop().slideDown(400);
                        }
                    }
                });

                $("#receiveNewsletters").on('change', function () {
                    var status = ($(this).is(":checked")) ? 1 : 0;
                    $.ajax("/user/{{ $user->id }}/set-newsletter?newsletter=" + status);
                });

                $("#finished_audio_email").on('change', function() {
                    var status = ($(this).is(":checked")) ? 1 : 0;
                    $.ajax("/user/{{ $user->id }}/email-settings?finished-audio-email=" + status);
                });

            });

            $(".js-change-email-form").submit(validateEmailForm);
            $(".js-change-password-form").submit(validatePasswordForm);

            var can_submit = false;
            function validateEmailForm(e) {
                if (!can_submit) {
                    e.preventDefault();
                }

                var form = $(this);

                var new_email = form.find("[name=new_email]");
                var password = form.find("[name=password]");

                if (!$.trim(new_email.val())) {
                    new_email.addClass('input-error');
                    e.preventDefault();
                }

                if (!$.trim(password.val())) {
                    password.addClass('input-error');
                    e.preventDefault();
                }

                if (!can_submit) {
                    var data = {
                        email: "{{ $user->email }}",
                        password: password.val()
                    };

                    var request = $.ajax({
                        dataType: 'json',
                        type: 'get',
                        url: '{{ route('auth.checkCredentials') }}',
                        data: data,
                        success: function() {
                            can_submit = true;
                            form.submit();
                        },
                        error: function() {
                            password.addClass('input-error');
                        }
                    });
                }

                return false;
            }

            function validatePasswordForm(e) {
                if (!can_submit) {
                    e.preventDefault();
                }

                var form = $(this);

                var new_password = form.find("[name=new_password]");
                var password = form.find("[name=password]");

                if (!$.trim(new_password.val())) {
                    new_password.addClass('input-error');
                    e.preventDefault();
                }

                if (!$.trim(password.val())) {
                    password.addClass('input-error');
                    e.preventDefault();
                }

                if (!can_submit) {
                    var data = {
                        email: "{{ $user->email }}",
                        password: password.val()
                    };

                    var request = $.ajax({
                        dataType: 'json',
                        type: 'get',
                        url: '{{ route('auth.checkCredentials') }}',
                        data: data,
                        success: function() {
                            can_submit = true;
                            form.submit();
                        },
                        error: function() {
                            password.addClass('input-error');
                        }
                    });
                }

                return false;
            }
        })(jQuery);
    </script>
@stop

@section('content')
    <div class="auth-body">
        <div class="container">
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

            <div class="inline-block-fix">
                {{-- Data fields --}}
                <div class="data-field"><strong>Email:</strong> {{ $user->email }}</div>
                @unless ($user->onlySocialLogin())
                    <div class="data-field"><strong>Password:</strong> ********</div>
                @endunless

                {{-- Delete account button--}}
                <button class="button-large button-delete" data-toggle="popover" data-class="confirmation-popup" data-target="#deleteAccountPopup">Delete account</button>

                <div id="deleteAccountPopup" style="display: none">
                    <form method="POST" action="{{ url('/user/' . $user->id . '/delete-account') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <span class="question">Are you sure you want to remove your account?</span>
                        <button type="submit" class="button-medium">Yes</button>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>

            {{-- Data change buttons --}}
            <div class="inline-block-fix">
                <div class="button-large button-change" data-target="#changeEmailForm">Change email</div>
                @unless ($user->onlySocialLogin())
                    <div class="button-large button-change" data-target="#changePasswordForm">Change password</div>
                @endunless
            </div>

            {{-- Email change form (HIDDEN) --}}
            <div id="changeEmailForm" class="change-form change-email">
                <h4>Change email</h4>
                <form role="form" method="POST" autocomplete="off" action="{{ url('/user/' . Auth::id() . '/change-email') }}" class="js-change-email-form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <input type="email" class="form-control" name="new_email" placeholder="New email address">
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="Password">
                    </div>

                    <div class="form-group">
                        <button class="button-large button-auth js-save-change-email-form">Save</button>
                        <div class="clearfix"></div>
                    </div>
                </form>
            </div>

            {{-- Password change form (HIDDEN) --}}
            @unless ($user->onlySocialLogin())
                <div id="changePasswordForm" class="change-form change-password">
                    <h4>Change password</h4>
                    <form role="form" method="POST" autocomplete="off" action="{{ url('/user/' . Auth::id() . '/change-password') }}" class="js-change-password-form">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <input class="form-control" name="new_password" placeholder="New password">
                        </div>

                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="Current Password">
                        </div>

                        <div class="form-group">
                            <button class="button-large button-auth">Save</button>
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>
            @endunless

            {{-- Newsletter checkbox --}}
            <div class="checkbox">
                <input type="checkbox" name="remember" id="receiveNewsletters" @if ($user->newsletter) checked @endif>
                <label for="receiveNewsletters">I want to receive newsletters</label>
            </div>

            {{-- Newsletter about finished orders checkbox --}}
            <div class="checkbox">
                <input type="checkbox" name="finished_audio_email" id="finished_audio_email" @if ($user->finished_audio_email) checked @endif>
                <label for="finished_audio_email">I want to receive an e-mail every time an audio or video file is completed</label>
            </div>
        </div>
    </div>
@stop