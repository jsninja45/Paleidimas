@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <form method="get" autocomplete="off">
                                <div class="col-md-2">
                                    <input class="form-control" type="text" name="email" placeholder="Email" value="{{ Input::get('email') }}">
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="spent" name="order" @if (Input::has('order')) checked @endif> Order by spent money
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <button class="btn btn-primary">OK</button>
                                    <a href="{{ currentUrl() }}" class="btn btn-default">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">Clients</div>

                    <div class="panel-body">

                        <table class="table table-striped table-hover">
                            <tr>
                                <th>Email</th>
                                <th>Spent</th>
                                <th>From affiliate</th>
                                <th>IP</th>
                                <th></th>
                                <th></th>
                                <th>Undelete</th>
                            </tr>
                            @foreach ($clients as $client)
                                <tr>
                                    <td>{{ $client->email }}</td>
                                    <td>${{ $client->spent }}</td>
                                    <td>
                                        @if ($client->affliate_abovealloffers)
                                            Yes
                                        @endif
                                    </td>
                                    <td>{{ $client->ip }}</td>
                                    <td><a class="btn btn-default btn-xs" href="{{ route('login_as', [$client->id]) }}">Login as</a></td>
                                    <td>
                                        <a class="btn btn-primary btn-xs" href="{{ route('admin_orders') }}?client_id={{ $client->id }}">Orders</a>
                                        <a class="btn btn-default btn-xs" href="{{ route('admin_user', [$client->id]) }}/edit">Edit</a>
                                    </td>
                                    <td>
                                        @if ($client->deleted)
                                            <a onclick="return confirm('Are you sure?');" class="btn btn-default btn-xs" href="{{ $client->link('undelete') }}">Undelete</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>

                        {!! $clients->appends(Request::only(['email', 'order']))->render() !!}
                    </div>
                </div>

            </div>

        </div>
    </div>

@stop