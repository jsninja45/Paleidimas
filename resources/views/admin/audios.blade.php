@extends('simple_app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-default">
                    <div class="panel-heading">Audios</div>

                    <div class="panel-body">

                        <div class="alert alert-info">
                            <b>Statuses:</b><br>
                            1. <b>in_transcription</b> - file is in transcription</b><br>
                            2. <b>available_for_editing</b> - transcription is finished, file is ready for editing<br>
                            3. <b>in_editing</b> - editor is editing</b><br>
                            3. <b>available_for_subtitling</b> - [optional] transcription is finished and file is ready for subtitling</b><br>
                            3. <b>in_subtitling</b> - [optional] subtitler is editing</b><br>
                            4. <b>finished</b>
                        </div>

                        <table class="table table-hover table-fixed break-word">
                            <tr>
                                <th>Audio ID</th>
                                <th>Name</th>
                                <th>Time left</th>
                                <th>TAT</th>
                                <th>Status</th>
                                <th>Length</th>
                                <th>Client paid</th>
                                <th>File comment</th>
                                <th>Client rating</th>
                                <th>Client comment</th>
                                <th>Editor</th>
                                <th>Editor price</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            @foreach ($audios as $audio)
                                <?php
                                $bg = '';
                                if ($audio->order->deadline_at && $audio->status !== 'finished') {
                                    $bg = 'bg-warning';
                                    if ($audio->order->deadline_at < date('Y-m-d H:i:s')) {
                                        $bg = 'bg-danger';
                                    }
                                }
                                if ($audio->status === 'finished') {
                                    $bg = 'bg-success';
                                }
                                ?>
                                <tr class="{{ $bg }}">
                                    <td>{{ $audio->id }}</td>
                                    <td>{{ $audio->original_filename }}</td>
                                    <td>
                                        @if ($audio->order->paid_at && $audio->status !== 'finished')
                                            {{ timeLeftFull(leftSeconds($audio->order->deadline())) }}
                                        @endif
                                    </td>
                                    <td>{{ $audio->order->tat->days }} days</td>
                                    <td>{{ $audio->status }}</td>
                                    <td>{{ secondsToTime($audio->duration) }}</td>
                                    <td>@if ($audio->client_price) ${{ $audio->client_price }} @endif</td>
                                    <td>
                                        {{ $audio->comment }}
                                    </td>
                                    <td>
                                        @if ($audio->rating)
                                            {{ $audio->rating }}/5
                                        @endif
                                    </td>
                                    <td>{{ $audio->rating_comment }}</td>
                                    <td>@if ($audio->editor) {{ $audio->editor->email }} @endif</td>
                                    <td>@if ($audio->editor_price) ${{ $audio->editor_price }} @endif</td>
                                    <td>
                                        @if (!$audio->isFileDeleted())
                                            <a target="_blank" class="btn btn-default btn-xs" href="{{ $audio->link() }}">Client audio</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($audio->transcription)
                                            <a target="_blank" class="btn btn-default btn-xs" href="{{ $audio->transcription->link() }}">Transcription</a>
                                            <a class="btn btn-default btn-xs" href="{{ route('audio_transcription_upload', [$audio->id]) }}">Reupload</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($audio->subtitle)
                                            <a target="_blank" class="btn btn-default btn-xs" href="{{ $audio->subtitle->link() }}">Subtitles</a>
                                            <a class="btn btn-default btn-xs" href="{{ route('audio_subtitle_upload', [$audio->id]) }}">Reupload</a>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-primary btn-xs" href="{{ route('admin_audio_slices') }}?audio_id={{ $audio->id }}">More</a>
                                    </td>
                                    <td>
                                        <a class="btn btn-xs btn-default" href="{{ route('admin.audios.edit', [$audio->id]) }}">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

                <div class="text-center">
                    {!! $audios->appends(Input::except('page'))->render() !!}
                </div>
            </div>

        </div>
    </div>

@stop