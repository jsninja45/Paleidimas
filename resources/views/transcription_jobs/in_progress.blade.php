@extends('simple_app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Transcription Jobs In Progress</b></div>

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
                                <th>Audio</th>
                                <th>Earnings</th>
                                <th>Transcriptions</th>
                                <th></th>
                                <th></th>
                            </tr>
                            @foreach ($audio_slices as $slice)
                                <tr>
                                    <td>{{ $slice->id }}</td>
                                    <td>{{ $slice->timeLeft }}</td>
                                    <td>{{ $slice->for_humans_from }} - {{ $slice->for_humans_till }}</td>
                                    <td>{{ $slice->audio->order->textFormat->name }}</td>
                                    <td>{{ $slice->audio->order->timestamping->name }}</td>
                                    <td>{{ $slice->audio->order->language->name }}</td>
                                    <td>
                                        {{ $slice->audio->comment }}
                                        {{--<a href="{{ route('audio_slice_edit_comment', [$slice->id]) }}">edit</a>--}}
                                    </td>
                                    <td>
                                        @if ($slice->audio->isFileDeleted())
                                            Deleted
                                        @else
                                            <a target="_blank" href="{{ $slice->audio->download() }}">{{ $slice->audio->original_filename }}</a>
                                        @endif
                                    </td>
                                    <td>${{ $slice->transcriberPrice($user) }}</td>
                                    <td>
                                        @if ($slice->transcription)
                                            <a href="{{ $slice->transcription->link() }}">{{ $slice->transcription->filename }}</a><br>
                                        @endif

                                        <a class="btn btn-primary btn-xs" href="{{ route('audio_slice_transcription_upload', [$slice->id]) }}">Upload</a>
                                    </td>
                                    <td>
                                        @if ($user->canUserFinishAudioSlice($slice))
                                            <a class="btn btn-success btn-xs" onclick="return confirm('Finish?');" href="{{ $slice->link('finish') }}">Finish</a>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-danger btn-xs" onclick="return confirm('Cancel?');" href="{{ $slice->link('cancel') }}">Refuse job</a>
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