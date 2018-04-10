@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">User profile</div>

                    <div class="panel-body">

                        <form method="post" action="{{ route('profile', [$user->id]) }}" autocomplete="off">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                            @if($errors->has())
                                @foreach ($errors->all() as $error)
                                    <div class="form-group">
                                        <div class="bg-danger">{{ $error }}</div>
                                    </div>
                                @endforeach
                            @endif

                            {{--<div class="form-group">--}}
                                {{--<label>Email</label>--}}
                                {{--<input class="form-control" name="email" type="email" placeholder="Email" value="{{ $user->email }}">--}}
                            {{--</div>--}}

                            <div class="form-group">
                                <label>PayPal email</label>
                                <input class="form-control" name="paypal_email" type="email" placeholder="PayPal email" value="{{ $user->paypal_email }}">
                            </div>

                            <div class="form-group">
                                <label>Send email about new jobs</label>
                                <select class="form-control" name="new_job_email">
                                    <option value="1" @if ($user->new_job_email == 1) selected @endif>Yes</option>
                                    <option value="0" @if ($user->new_job_email == 0) selected @endif>No</option>
                                </select>
                            </div>

                            {{--<div class="form-group">--}}
                                {{--<label>Password (only if you want to change)</label>--}}
                                {{--<input class="form-control" name="password" type="password" placeholder="Change password">--}}
                            {{--</div>--}}

                            <button type="submit" class="btn btn-primary pull-right">Save</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

