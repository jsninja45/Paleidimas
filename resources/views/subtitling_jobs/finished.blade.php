@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Finished Transcription Jobs</div>

                    <div class="panel-body">
                        <table class="table table-striped table-hover">
                            <tr>
                                <th>ID</th>
                                <th>Transcription Time</th>
                                {{--<th>Text Format</th>--}}
                                {{--<th>Timestamping</th>--}}
                                {{--<th>Language</th>--}}
                                <th>Comment</th>
                                <th>Audio</th>
                                <th>Editor transcription</th>
                                <th>Subtitles</th>
                                <th>Earnings</th>
                                <th>Client rating</th>
                                <th>Client comment</th>
                            </tr>
                            @foreach ($audios as $audio)
                                <tr>
                                    <td>{{ $audio->id }}</td>
                                    <td>{{ $audio->for_humans_from }} - {{ $audio->for_humans_till }}</td>
                                    {{--<td>{{ $audio->order->textFormat->name }}</td>--}}
                                    {{--<td>{{ $audio->order->timestamping->name }}</td>--}}
                                    {{--<td>{{ $audio->order->language->name }}</td>--}}
                                    <td>{{ $audio->comment }}</td>
                                    <td>@if (!$audio->isFileDeleted()) <a target="_blank" href="{{ $audio->link() }}">{{ $audio->original_filename }}</a> @else Deleted @endif</td>
                                    <td>
                                        @foreach ($audio->slices as $k => $slice)
                                            <a href="{{ $slice->transcription->link() }}">{{ secondsToTime($slice->from) }} - {{ secondsToTime($slice->till) }}</a>
                                            <a class="glyphicon glyphicon-envelope" href="{{ route('messages_with', [$slice->transcription->user_id]) }}"></a>

                                                -
                                            @if (!$slice->isRated())
                                                <a class="btn btn-primary btn-xs" href="{{ $slice->link('rate') }}">rate it</a>
                                            @else
                                                <span title="{{ $slice->editor_comment }}">rating: {{ $slice->rating }}</span>
                                            @endif
                                            <br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if ($audio->subtitle)
                                            <a href="{{ $audio->subtitle->link() }}">{{ $audio->subtitle->filename }}</a>
                                        @endif
                                    </td>
                                    <td>${{ $audio->subtitler_price }}</td>
                                    <td>
                                        @if ($audio->rating)
                                            {{ $audio->rating }}/5
                                        @endif
                                    </td>
                                    <td>{{ $audio->rating_comment }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop