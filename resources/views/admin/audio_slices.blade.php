@extends('simple_app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Audio Slices</div>

                    <div class="panel-body">

                        <table class="table table-hover table-fixed break-word">
                            <tr>
                                <th>Transcription Time</th>
                                <th>Text Format</th>
                                <th>Timestamping</th>
                                <th>Language</th>
                                <th>Speakers</th>
                                <th>Comment</th>
                                <th>Audio</th>
                                <th>Transcriptions</th>
                                <th>Transcriber</th>
                                <th>Transcriber price</th>
                                <th>Editor Rating</th>
                                <th>Editor Comment</th>
                                <th></th>
                            </tr>
                            @foreach ($slices as $slice)
                                <?php
                                $bg = '';
                                if ($slice->audio->order->deadline_at && $slice->status !== 'finished') {
                                    $bg = 'bg-warning';
                                    if ($slice->audio->order->deadline_at < date('Y-m-d H:i:s')) {
                                        $bg = 'bg-danger';
                                    }
                                }
                                if ($slice->status === 'finished') {
                                    $bg = 'bg-success';
                                }
                                ?>
                                <tr class="{{ $bg }}">
                                    <td>{{ $slice->for_humans_from }} - {{ $slice->for_humans_till }}</td>
                                    <td>{{ $slice->audio->order->textFormat->name }}</td>
                                    <td>{{ $slice->audio->order->timestamping->name }}</td>
                                    <td>{{ $slice->audio->order->language->name }}</td>
                                    <td>{{ $slice->audio->order->speaker->name }}</td>
                                    <td>{{ $slice->audio->comment }}</td>
                                    <td>
                                        @if ($slice->audio->isFileDeleted())
                                            Deleted
                                        @else
                                            <a target="_blank" href="{{ $slice->audio->download() }}">{{ $slice->audio->original_filename }}</a>
                                        @endif

                                        <a class="btn btn-xs btn-default" href="{{ route('admin_audios', ['audio_id' => $slice->audio->id]) }}">Go to audio</a>
                                    </td>
                                    <td>
                                        @if ($slice->transcription)
                                            <a href="{{ $slice->transcription->link() }}">{{ $slice->transcription->filename }}</a><br>
                                            <a class="btn btn-xs btn-default" target="_blank" href="{{ route('audio_slice_transcription_upload', [$slice->id]) }}">Reupload</a>
                                        @endif
                                    </td>
                                    <td>@if ($slice->transcriber) {{ $slice->transcriber->email }} @endif</td>
                                    <td>@if ($slice->transcriber_price) ${{ $slice->transcriber_price }} @endif</td>
                                    <td>
                                        @if ($slice->rating)
                                            {{ $slice->rating }}
                                        @endif
                                    </td>
                                    <td>{{ $slice->editor_comment }}</td>
                                    <td>
                                        <a class="btn btn-xs btn-default" href="{{ route('admin.audio-slices.edit', [$slice->id]) }}">Edit</a>
                                    </td>

                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

                <div class="text-center">
                    {!! $slices->appends(Input::except('page'))->render() !!}
                </div>
            </div>

        </div>
    </div>

@stop