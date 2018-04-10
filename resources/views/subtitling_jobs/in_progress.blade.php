@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Editing Jobs In Progress</div>

                    <div class="panel-body">

                        <table class="table table-striped table-hover">
                            <tr>
                                <th>ID</th>
                                <th>Time left</th>
                                <th>Transcription Time</th>
                                <th>Text Format</th>
                                <th>Timestamping</th>
                                <th>Language</th>
                                <th>Comment</th>
                                <th>Earnings</th>
                                <th>Audio</th>
                                <th>Editor transcription</th>
                                <th>Subtitling</th>
                                <th></th>
                                <th></th>
                            </tr>
                            @foreach ($audios as $audio)
                                <tr>
                                    <td>{{ $audio->id }}</td>
                                    <td>{{ $audio->time_left }}</td>
                                    <td>{{ $audio->for_humans_from }} - {{ $audio->for_humans_till }}</td>
                                    <td>{{ $audio->order->textFormat->name }}</td>
                                    <td>{{ $audio->order->timestamping->name }}</td>
                                    <td>{{ $audio->order->language->name }}</td>
                                    <td>{{ $audio->comment }}</td>
                                    <td>${{ $audio->editor_price }}</td>
                                    <td>@if (!$audio->isFileDeleted()) <a target="_blank" href="{{ $audio->link() }}">{{ $audio->original_filename }}</a> @else Deleted @endif</td>
                                    <td>
                                        @if ($audio->transcription)
                                            <a href="{{ $audio->transcription->link() }}">{{ $audio->transcription->filename }}</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($audio->subtitle)
                                            <a href="{{ $audio->subtitle->link() }}">{{ $audio->subtitle->filename }}</a>
                                        @endif

                                        <a class="btn btn-primary btn-xs" href="{{ route('audio_subtitle_upload', [$audio->id]) }}">Upload</a>
                                    </td>
                                    <td>
                                        @if ($user->canUserFinishSubtitling($audio))
                                            <a class="btn btn-success btn-xs" onclick="return confirm('Finish?');" href="{{ $audio->link('finishSubtitling') }}">Finish</a>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-danger btn-xs" onclick="return confirm('Cancel?');" href="{{ $audio->link('cancelSubtitling') }}">Refuse job</a>
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