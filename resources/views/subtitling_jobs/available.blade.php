@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Available Editing Jobs <span class="label label-info">{{ $total }}</span></div>

                    <div class="panel-body">



                        <table class="table table-striped table-hover">
                            <tr>
                                <th>ID</th>
                                <th>Time left</th>
                                <th>Transcription Time</th>
                                {{--<th>Text Format</th>--}}
                                {{--<th>Timestamping</th>--}}
                                <th>Language</th>
                                <th>Comment</th>
                                <th>Audio</th>
                                <th>Editor transcription</th>
                                <th>Earnings</th>
                                <th>Action</th>
                            </tr>
                            @foreach ($audios as $audio)
                                <tr>
                                    <td>{{ $audio->id }}</td>
                                    <td>{{ $audio->time_left }}</td>
                                    <td>{{ $audio->for_humans_from }} - {{ $audio->for_humans_till }}</td>
                                    {{--<td>{{ $audio->order->textFormat->name }}</td>--}}
                                    {{--<td>{{ $audio->order->timestamping->name }}</td>--}}
                                    <td>{{ $audio->order->language->name }}</td>
                                    <td>{{ $audio->comment }}</td>
                                    <td>@if (!$audio->isFileDeleted()) <a target="_blank" href="{{ $audio->link() }}">Download</a> @else Deleted @endif</td>
                                    <td>
                                        @if ($audio->transcription)
                                            <a href="{{ $audio->transcription->link() }}">{{ $audio->transcription->filename }}</a>
                                        @endif
                                    </td>
                                    <td>${{ $audio->subtitlerPrice($user) }}</td>
                                    <td>
                                        @if ($audio->isAvailableForSubtitling($user))
                                            <a class="btn btn-success btn-xs" href="{{ $audio->link('takeForSubtitling') }}">Make my job</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

@stop