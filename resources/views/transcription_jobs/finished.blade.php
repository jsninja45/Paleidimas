@extends('simple_app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Finished Transcription Jobs</b></div>

                    <div class="panel-body">
                        <table class="table table-striped table-hover">
                            <tr>
                                <th>ID</th>
                                <th>Transcription Time</th>
                                <th>Text Format</th>
                                <th>Timestamping</th>
                                <th>Language</th>
                                <th>Comment</th>
                                <th>Audio</th>
                                <th>Transcriptions</th>
                                <th>Earnings</th>
                                <th>Editor Rating</th>
                                <th>Editor Comment</th>
                            </tr>
                            @foreach ($audio_slices as $slice)
                                <tr>
                                    <td>{{ $slice->id }}</td>
                                    <td>{{ $slice->for_humans_from }} - {{ $slice->for_humans_till }}</td>
                                    <td>{{ $slice->audio->order->textFormat->name }}</td>
                                    <td>{{ $slice->audio->order->timestamping->name }}</td>
                                    <td>{{ $slice->audio->order->language->name }}</td>
                                    <td>{{ $slice->audio->comment }}</td>
                                    <td>
                                        @if ($slice->audio->isFileDeleted())
                                            Deleted
                                        @else
                                            <a target="_blank" href="{{ $slice->audio->download() }}">{{ $slice->audio->original_filename }}</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($slice->transcription)
                                            <a href="{{ $slice->transcription->link() }}">{{ $slice->transcription->filename }}</a><br>
                                        @endif
                                    </td>
                                    <td>${{ $slice->transcriber_price }}</td>
                                    <td>
                                        @if ($slice->rating)
                                            {{ $slice->rating }}
                                        @endif
                                    </td>
                                    <td>{{ $slice->editor_comment }}</td>

                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop