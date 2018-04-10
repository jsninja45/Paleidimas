@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">

                @if (!$subtitler_audios->isEmpty())
                    <div class="panel panel-default">
                        <div class="panel-heading"><b>Earned For Subtitling</b></div>
                        <div class="panel-body">
                            <table class="table table-striped table-hover">
                                <tr>
                                    <th>Audio ID</th>
                                    <th>Filename</th>
                                    <th>Finished</th>
                                    <th>Amount</th>
                                </tr>

                                @foreach ($subtitler_audios as $audio)
                                    <tr>
                                        <td>{{ $audio->id }}</td>
                                        <td>{{ $audio->original_filename }}</td>
                                        <td>{{ $audio->finished_at }}</td>
                                        <td>${{ $audio->subtitler_price }}</td>
                                    </tr>
                                @endforeach

                            </table>

                        </div>
                    </div>
                @endif

                @if (!$audios->isEmpty())
                    <div class="panel panel-default">
                        <div class="panel-heading"><b>Earned For Editing</b></div>
                        <div class="panel-body">
                            <table class="table table-striped table-hover">
                                <tr>
                                    <th>Audio ID</th>
                                    <th>Filename</th>
                                    <th>Finished</th>
                                    <th>Amount</th>
                                </tr>

                                @foreach ($audios as $audio)
                                    <tr>
                                        <td>{{ $audio->id }}</td>
                                        <td>{{ $audio->original_filename }}</td>
                                        <td>{{ $audio->finished_at }}</td>
                                        <td>${{ $audio->editor_price }}</td>
                                    </tr>
                                @endforeach

                            </table>

                        </div>
                    </div>
                @endif

                @if (!$audio_slices->isEmpty())
                    <div class="panel panel-default">
                        <div class="panel-heading"><b>Earned For Transcriptions</b></div>
                        <div class="panel-body">
                            <table class="table table-striped table-hover">
                                <tr>
                                    <th>Audio part ID</th>
                                    <th>Filename</th>
                                    <th>Finished</th>
                                    <th>Amount</th>
                                </tr>

                                @foreach ($audio_slices as $slice)
                                    <tr>
                                        <td>{{ $slice->id }}</td>
                                        <td>{{ $slice->audio->original_filename }}</td>
                                        <td>{{ $slice->finished_at }}</td>
                                        <td>${{ $slice->transcriber_price }}</td>
                                    </tr>
                                @endforeach

                            </table>

                        </div>
                    </div>
                @endif

                @if (!$bonuses->isEmpty())
                    <div class="panel panel-default">
                        <div class="panel-heading"><b>Bonuses/Deductions</b></div>
                        <div class="panel-body">
                            <table class="table table-striped table-hover">
                                <tr>
                                    <th>Amount</th>
                                </tr>

                                @foreach ($bonuses as $bonus)
                                    <tr>
                                        <td>{{ $bonus->amount }}</td>
                                    </tr>
                                @endforeach

                            </table>

                        </div>
                    </div>
                @endif

                {{-- no payments --}}
                @if ($audios->isEmpty() && $audio_slices->isEmpty())
                        <div class="panel panel-default">
                            <div class="panel-heading"><b>No Payments</b></div>
                            <div class="panel-body">


                            </div>
                        </div>
                @endif

            </div>
        </div>
    </div>

@stop