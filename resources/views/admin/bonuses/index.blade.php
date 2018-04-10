@extends('simple_app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Bonuses</b></div>

                    <div class="panel-body">

                        <div class="alert alert-info">
                            Here you can add or subtract some amount of money to/from employee salary.<br>
                        </div>

                        <table class="table table-striped table-hover">
                            <tr>
                                <th>Date</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Comment</th>
                                <th></th>
                            </tr>

                            <tbody>
                            @foreach ($rows as $row)
                                <tr>
                                    <td>{{ $row->created_at }}</td>
                                    <td>{{ $row->user->email }}</td>
                                    <td>{{ $row->amount }}</td>
                                    <td>{{ $row->comment }}</td>
                                    <td>
                                        @if ($row->isEditable())
                                            <a onclick="return confirm('Are you sure?');" href="{{ url('/') }}/{{ $route }}/{{{ $row->id }}}/destroy" type="button" class="btn btn-danger btn-xs">Delete</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>

                        <a class="btn btn-primary pull-right" href="{{ url('/') }}/{{ $route }}/create">New</a>

                    </div>
                </div>

            </div>

        </div>
    </div>
@stop