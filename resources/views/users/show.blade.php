@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">User profile</div>

                    <div class="panel-body">

                        <table class="table table-striped table-hover">
                            {{--<tr>--}}
                                {{--<td>Name</td>--}}
                                {{--<td>{{ $user->name }}</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>Email</td>--}}
                                {{--<td>{{ $user->email }}</td>--}}
                            {{--</tr>--}}
                            <tr>
                                <td>PayPal email</td>
                                <td>{{ $user->paypal_email }}</td>
                            </tr>
                            <tr>
                                <td>Send email about new jobs</td>
                                <td>{{ $user->new_job_email ? 'Yes' : 'No' }}</td>
                            </tr>
                        </table>

                        <a class="btn btn-primary pull-right" href="{{ route('edit_profile', [$user->id]) }}">Edit</a>

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

