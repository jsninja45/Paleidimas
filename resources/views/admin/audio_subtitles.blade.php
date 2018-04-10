@extends('simple_app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Audio Subtitles</div>

                    <div class="panel-body">

                        <table class="table table-hover table-fixed break-word">
                            <tr>
                                <th>Text Format</th>
                                <th>Timestamping</th>
                                <th>Language</th>
                                <th>Speakers</th>
                                <th>Comment</th>
                                <th>Audio</th>
                                <th>Subtitles</th>
                                <th>Subtitler</th>
                                <th>Subtitler price</th>
                            </tr>
                            @foreach ($subtitles as $subtitle)
                                <?php
                                $bg = '';
                                if ($subtitle->audio->order->deadline_at && $subtitle->status !== 'finished') {
                                    $bg = 'bg-warning';
                                    if ($subtitle->audio->order->deadline_at < date('Y-m-d H:i:s')) {
                                        $bg = 'bg-danger';
                                    }
                                }
                                if ($subtitle->status === 'finished') {
                                    $bg = 'bg-success';
                                }
                                ?>
                                <tr class="{{ $bg }}">
                                    <td>{{ $subtitle->audio->order->textFormat->name }}</td>
                                    <td>{{ $subtitle->audio->order->timestamping->name }}</td>
                                    <td>{{ $subtitle->audio->order->language->name }}</td>
                                    <td>{{ $subtitle->audio->order->speaker->name }}</td>
                                    <td>{{ $subtitle->audio->comment }}</td>
                                    <td>
                                        @if ($subtitle->audio->isFileDeleted())
                                            Deleted
                                        @else
                                            <a target="_blank" href="{{ $subtitle->audio->download() }}">{{ $subtitle->audio->original_filename }}</a>
                                        @endif

                                        <a class="btn btn-xs btn-default" href="{{ route('admin_audios', ['audio_id' => $subtitle->audio->id]) }}">Go to audio</a>
                                    </td>
                                    <td>
                                        <a href="{{ $subtitle->link() }}">{{ $subtitle->filename }}</a><br>
                                        <a class="btn btn-xs btn-default" target="_blank" href="{{ route('audio_subtitle_upload', [$subtitle->audio->id]) }}">Reupload</a>
                                        
                                    </td>
                                    <td>@if ($subtitle->audio->subtitler) {{ $subtitle->audio->subtitler->email }} @endif</td>
                                    <td>@if ($subtitle->audio->subtitler_price) ${{ $subtitle->audio->subtitler_price }} @endif</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

                <div class="text-center">
                    {!! $subtitles->appends(Input::except('page'))->render() !!}
                </div>
            </div>

        </div>
    </div>

@stop