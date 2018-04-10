@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">

                    <div class="panel-heading"><b>ayPal email</b></div>
                    <div class="panel-body">

                        <form method="post" action="{{ route('set_paypal_email') }}">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="form-group">
                                <label>PayPal email</label>
                                <input class="form-control" type="email" name="paypal_email" value="{{ $user->paypal_email }}">
                            </div>

                            <button type="submit" class="btn btn-primary pull-right">Save</button>

                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>

@stop