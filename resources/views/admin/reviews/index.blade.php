@extends('simple_app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Reviews</b></div>

                    <div class="panel-body">

                        <table class="table table-striped table-hover">
                            <tr>
                                <th>Date</th>
                                <th>Rating</th>
                                <th>Name</th>
                                <th>Content</th>
                                <th></th>
                                <th></th>
                            </tr>

                            @foreach ($reviews as $review)
                                <tr @if ($review->deleted_at) class="bg-warning" @endif>
                                    <td style="width: 100px;">{{ date('Y-m-d', strtotime($review->created_at)) }}</td>
                                    <td>{{ $review->rating }}</td>
                                    <td>{{ $review->name }}</td>
                                    <td>{{ $review->content }}</td>
                                    <th>
                                        @if ($review->deleted_at)
                                            <a class="btn btn-success btn-xs" href="{{ $review->link('confirm') }}">Confirm</a>
                                        @endif
                                    </th>
                                    <th><a onclick="return confirm('Are you sure?');" class="btn btn-danger btn-xs" href="{{ $review->link('delete') }}">Delete</a></th>
                                </tr>
                            @endforeach

                        </table>

                    </div>
                </div>

                {!! $reviews->render() !!}

            </div>

        </div>
    </div>
@stop