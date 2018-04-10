@extends('simple_app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a target="_blank" href="{{ env('INSTRUCTIONS_LINK', '#') }}">S2T instructions</a>
                </p>

                <div class="panel panel-default">
                    <div class="panel-heading">Available Editing Jobs <span class="label label-info">{{ $total }}</span></div>

                    <div class="panel-body">



                        <table class="table table-striped table-hover">
                            <tr>
                                <th>ID</th>
                                <th>Time left</th>
                                <th>Time limit</th>
                                <th>Transcription Time</th>
                                <th>Text Format</th>
                                <th>Timestamping</th>
                                <th>Language</th>
                                <th>Comment</th>
                                <th>Audio</th>
                                <th>Transcriptions</th>
                                <th>Earnings</th>
                                <th>Action</th>
                            </tr>
                            @foreach ($audios as $audio)
                                <tr>
                                    <td>{{ $audio->id }}</td>
                                    <td>{{ $audio->time_left }}</td>
                                    <td>{{ timeLeftFullWithoutSeconds(leftSeconds(editorDeadlineAt($audio))) }}</td>
                                    <td>{{ $audio->for_humans_from }} - {{ $audio->for_humans_till }}</td>
                                    <td>{{ $audio->order->textFormat->name }}</td>
                                    <td>{{ $audio->order->timestamping->name }}</td>
                                    <td>{{ $audio->order->language->name }}</td>
                                    <td>{{ $audio->comment }}</td>
                                    <td>@if (!$audio->isFileDeleted()) <a target="_blank" href="{{ $audio->link() }}">Download</a> @else Deleted @endif</td>
                                    <td>
                                        @foreach ($audio->slices as $k => $slice)
                                            @if ($slice->transcription)
                                                <a href="{{ $slice->transcription->link() }}">{{ secondsToTime($slice->from) }} - {{ secondsToTime($slice->till) }}</a>
                                                <a class="glyphicon glyphicon-envelope" href="{{ route('messages_with', [$slice->transcription->user_id]) }}"></a>
                                                <br>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>${{ $audio->editorPrice($user) }}</td>
                                    <td>
                                        @if ($audio->canUserTakeJob($user))
                                            <a class="btn btn-success btn-xs" href="{{ $audio->link('take') }}">Make my job</a>
                                        @elseif ($audio->canUserTakeJob($user, false))
                                            First finish your file
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>


            {{--<div class="col-md-6 col-md-offset-3">--}}
                {{--<div class="panel panel-default">--}}
                    {{--<div class="panel-heading"><b>Information</b></div>--}}

                    {{--<div class="panel-body">--}}

                        {{--<table class="table table-hover">--}}
                            {{--<tr>--}}
                                {{--<td>Earnings during this week???</td>--}}
                                {{--<td>$1???</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td>Rating</td>--}}
                                {{--<td>{{ $user->editingRating() }}</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td> Delay before you can take the job</td>--}}
                                {{--<td>{{ $user->job_delay }} minutes</td>--}}
                            {{--</tr>--}}
                        {{--</table>--}}

                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}


        </div>
    </div>

@stop