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
                                <th>Rated</th>
                                <td>Client</td>
                                <td>Audio</td>
                                <th>Client rating</th>
                                <th>Client comment</th>
                                <th></th>
                            </tr>
                            @foreach ($audios as $audio)
                                <tr>
                                    <td>{{ $audio->rated_at }}</td>
                                    <td>{{ $audio->order->user->email }}</td>
                                    <td>{{ $audio->original_filename }}</td>
                                    <td>
                                        @if ($audio->rating)
                                            {{ $audio->rating }}/5
                                        @endif
                                    </td>
                                    <td>{{ $audio->rating_comment }}</td>
                                    <td><a class="btn btn-xs btn-primary" href="{{ route('admin_audios') }}?audio_id={{ $audio->id }}">More</a></td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop