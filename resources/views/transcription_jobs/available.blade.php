@extends('simple_app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a target="_blank" href="{{ env('INSTRUCTIONS_LINK', '#') }}">S2T instructions</a>
                </p>

                <div class="panel panel-default">
                    <div class="panel-heading"><b>Available Transcription Jobs</b> <span class="label label-info">{{ $total }}</span></div>

                    <div class="panel-body">

                        <table class="table table-striped table-hover">
                            <tr>
                                <th>ID</th>
                                <th>How fast</th>
                                <th>Transcription Time</th>
                                <th>Text Format</th>
                                <th>Timestamping</th>
                                <th>Language</th>
                                <th>Comment</th>
                                <th>Audio</th>
                                <th>Earnings</th>
                                <th>Action</th>
                            </tr>
                            @foreach ($audio_slices as $slice)
                                <tr>
                                    <td>{{ $slice->id }}</td>
                                    <td>{{ duration2($slice->audio->order->tat->max_transcription_duration) }}</td>
                                    <td>{{ $slice->for_humans_from }} - {{ $slice->for_humans_till }}</td>
                                    <td>{{ $slice->audio->order->textFormat->name }}</td>
                                    <td>{{ $slice->audio->order->timestamping->name }}</td>
                                    <td>{{ $slice->audio->order->language->name }}</td>
                                    <td>{{ $slice->audio->comment }}</td>
                                    <td>
                                        @if ($slice->audio->isFileDeleted())
                                            Deleted
                                        @else
                                            <a target="_blank" href="{{ $slice->audio->download() }}">{{ $slice->audio->original_filename }}</a> <small>({{ $slice->audio->size }} mb)</small>
                                        @endif
                                    </td>
                                    <td>${{ $slice->transcriberPrice($user) }}</td>
                                    <td>
                                        @if ($slice->isAvailable($user))
                                            <a class="btn btn-success btn-xs" href="{{ $slice->link('take') }}">Make my job</a>
                                        @elseif ($slice->isAvailable($user, false))
                                            You need to wait {{ $slice->minutesTillTimePassed($user) }} minutes
                                        @else
                                            First finish your file
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