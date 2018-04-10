@extends('simple_app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                {{--<div class="panel panel-default">--}}
                    {{--<div class="panel-body">--}}
                        {{--<div class="row">--}}
                            {{--<form method="get" autocomplete="off">--}}
                                {{--<div class="col-md-3">--}}
                                    {{--<select class="form-control" name="sender">--}}
                                        {{--<option value="" selected disabled></option>--}}
                                        {{--@foreach ($users as $user)--}}
                                            {{--<option value="{{ $user->id }}" @if (Input::get('sender') == $user->id) selected @endif>{{ $user->email }}</option>--}}
                                        {{--@endforeach--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                                {{--<div class="col-md-3">--}}
                                    {{--<select class="form-control" name="recipient">--}}
                                        {{--<option value="" selected disabled></option>--}}
                                        {{--@foreach ($users as $user)--}}
                                            {{--<option value="{{ $user->id }}" @if (Input::get('recipient') == $user->id) selected @endif>{{ $user->email }}</option>--}}
                                        {{--@endforeach--}}
                                    {{--</select>--}}
                                {{--</div>--}}

                                {{--<div class="col-md-2">--}}
                                    {{--<button class="btn btn-primary">Filter</button>--}}
                                    {{--<a href="{{ currentUrl() }}" class="btn btn-default">Reset</a>--}}
                                {{--</div>--}}
                            {{--</form>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

                <div class="panel panel-default">
                    <div class="panel-heading"><b>All messages</b></div>

                    <div class="panel-body">

                        <table class="table table-striped table-hover">
                            <tr>
                                <th>Date</th>
                                <th>Sender</th>
                                <th>Recipient</th>
                                <th>Message</th>
                            </tr>

                            <tbody>
                            @foreach ($messages as $message)
                                <tr>
                                    <td>{{ $message->created_at }}</td>
                                    <td>{{ $message->sender->email }}</td>
                                    <td>{{ $message->recipient->email }}</td>
                                    <td>{{ $message->content }}</td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>

                    </div>
                </div>

                {!! $messages->render() !!}

            </div>

        </div>
    </div>
@stop