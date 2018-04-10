@extends('simple_app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Title</b></div>

                    <div class="panel-body">

                        <table class="table table-striped table-hover">
                            <thead>
                                <th>Code</th>
                                <th>Value</th>
                                <th>Comment</th>
                                <th>Usage</th>
                                <th>Expires at</th>
                                <th></th>
                            </thead>

                            <tbody>
                            @foreach ($rows as $row)
                                <tr>
                                    {{--<td><a target="_blank" href="/{{ $route }}/{{{ $row->id }}}">{{{ $row->code }}}</a></td>--}}
                                    <td>{{ $row->code }}</td>
                                    <td>
                                        @if ($row->type === 'amount')
                                            ${{ $row->value }}
                                        @else
                                            {{ $row->value }}%
                                        @endif
                                    </td>
                                    <td>{{ $row->comment }}</td>
                                    <td>
                                        @if ($row->single_use)
                                            single use
                                        @else
                                            multi-use
                                        @endif
                                    </td>
                                    <td>{{ $row->expires_at }}</td>
                                    <td>
                                        <a href="{{ url('/') }}/{{ $route }}/{{{ $row->id }}}/edit" type="button" class="btn btn-primary btn-xs">Edit</a>
                                        {{--<a onclick="return confirm('Delete?');" href="/{{ $route }}/{{{ $row->id }}}/destroy" type="button" class="btn btn-danger btn-xs">Delete</a>--}}
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