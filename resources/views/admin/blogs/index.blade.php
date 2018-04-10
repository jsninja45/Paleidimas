@extends('simple_app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Blog</b></div>

                    <div class="panel-body">

                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Title</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach ($rows as $row)
                                <tr>
                                    <td>{{ date('Y-m-d', strtotime($row->created_at)) }}</td>
                                    <td><a target="_blank" href="{{ $row->link() }}">{{ $row->title }}</a></td>
                                    <td>
                                        <a href="{{ url('/') }}/{{ $route }}/{{ $row->id }}/edit" type="button" class="btn btn-primary btn-xs">Edit</a>
                                        <a onclick="return confirm('Are you sure?');" href="{{ url('/') }}/{{ $route }}/{{{ $row->id }}}/destroy" type="button" class="btn btn-danger btn-xs">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>

                        <a class="btn btn-primary pull-right" href="{{ url('/') }}/{{ $route }}/create">New</a>

                    </div>
                </div>

                {!! $rows->render() !!}

            </div>

        </div>
    </div>
@stop