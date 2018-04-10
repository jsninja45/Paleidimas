@extends('simple_app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Title</b></div>

                    <div class="panel-body">

                        <table class="table table-striped table-hover">
                            <tr>
                                <th>ID</th>
                                <th></th>
                            </tr>

                            <tbody>
                            @foreach ($rows as $row)
                                <tr>
                                    <td><a target="_blank" href="{{ url('/') }}/{{ $route }}/{{{ $row->id }}}">{{{ $row->id }}}</a></td>
                                    <td>
                                        <a href="{{ url('/') }}/{{ $route }}/{{{ $row->id }}}/edit" type="button" class="btn btn-primary btn-xs">Edit</a>
                                        <a onclick="return confirm('Are you sure?');" href="{{ url('/') }}/{{ $route }}/{{{ $row->id }}}/destroy" type="button" class="btn btn-danger btn-xs">Delete</a>
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