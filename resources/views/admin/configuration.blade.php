@extends('simple_app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">

                    <div class="panel-heading"><b>Turn Around Time</b></div>
                    <div class="panel-body">

                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Days</th>
                                    <th>Client</th>
                                    <th>Editor</th>
                                    <th>Transcriber</th>
                                    <th>Audio Slice Duration</th>
                                    <th>Max Audio Slice Transcription Time</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tats as $tat)
                                    <tr>
                                        <td>{{ $tat->days }}</td>
                                        <td>${{ round($tat->client_price_per_minute * 60, 2) }}/h</td>
                                        <td>${{ round($tat->editor_price_per_minute * 60, 2) }}/h</td>
                                        <td>${{ round($tat->transcriber_price_per_minute * 60, 2) }}/h</td>
                                        <td>{{ round($tat->slice_duration / 60) }} min</td>
                                        <td>{{ round($tat->max_transcription_duration / 3600) }} h</td>
                                        <td>
                                            <a class="btn btn-primary btn-xs" href="{{ route('tat', [$tat->id]) }}/edit">Edit</a>
                                            {{--<a onclick="return confirm('Delete?');" href="{{ route('tat', [$tat->id]) }}/destroy">Delete</a>--}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{--<a class="btn btn-primary pull-right" href="/tat/create">New</a>--}}
                    </div>
                </div>

            </div>
            <div class="col-md-8 col-md-offset-2">

                <div class="panel panel-default">
                    <div class="panel-heading"><b>Timestamping</b></div>
                    <div class="panel-body">

                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Client price per hour</th>
                                    <th>Editor price per hour</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($timestampings as $timestamp)
                                    <tr>
                                        <td>{{ $timestamp->name }}</td>
                                        <td>${{ round($timestamp->client_price_per_minute * 60, 2) }}/h</td>
                                        <td>${{ round($timestamp->editor_price_per_minute * 60, 2) }}/h</td>
                                        <td>
                                            <a class="btn btn-primary btn-xs" href="{{ route('timestamping', [$timestamp->id]) }}/edit">Edit</a>
                                            {{--<a onclick="return confirm('Delete?');" href="{{ route('timestamping', [$timestamp->id]) }}/destroy">Delete</a>--}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

                        {{--<a class="btn btn-primary pull-right" href="/timestamping/create">New</a>--}}

                    </div>
                </div>


                <div class="panel panel-default">
                    <div class="panel-heading"><b>Languages</b></div>
                    <div class="panel-body">

                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Hidden</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($languages as $language)
                                <tr>
                                    <td>{{ $language->name }}</td>
                                    <td>@if ($language->hide) Hidden @endif</td>
                                    <td>
                                        <a class="btn btn-primary btn-xs" href="{{ route('language', [$language->id]) }}/edit">Edit</a>
                                        {{--<a onclick="return confirm('Delete?');" href="{{ route('language', [$language->id]) }}/destroy">Delete</a>--}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>

                        <a class="btn btn-primary pull-right" href="{{ url('language/create') }}">New</a>

                    </div>
                </div>


                <div class="panel panel-default">
                    <div class="panel-heading"><b>Rating Delay</b></div>
                    <div class="panel-body">

                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Rating</th>
                                    <th>Delay</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($rating_delays as $delay)
                                <tr>
                                    <td>{{ $delay->rating_till }} - {{ $delay->rating_till + $rating_delay_difference }}</td>
                                    <td>{{ round($delay->delay / 60) }} min</td>
                                    <td>
                                        <a class="btn btn-primary btn-xs" href="{{ url('/') }}/rating_delays/{{ $delay->id }}/edit">Edit</a>
                                        <a class="btn btn-danger btn-xs" onclick="return confirm('Delete?');" href="{{ url('/') }}/rating_delays/{{ $delay->id }}/destroy">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>

                        <a class="btn btn-primary pull-right" href="{{ url('/rating_delays/create') }}">New</a>

                    </div>
                </div>


                <div class="panel panel-default">
                    <div class="panel-heading"><b>No Rating Delay</b></div>
                    <div class="panel-body">

                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Delay</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $slice_no_rating_delay }} min</td>
                                    <td>
                                        <a class="btn btn-primary btn-xs" href="{{ route('admin_slice_no_rating_delay') }}">Edit</a>
                                    </td>
                                </tr>
                            </tbody>

                        </table>

                    </div>
                </div>





            </div>

        </div>
    </div>

@stop