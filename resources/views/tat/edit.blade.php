@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">

                    <div class="panel-heading">Turn Around Time</div>
                    <div class="panel-body">

                        <form method="post" action="{{ route('tat', [$tat->id]) }}">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="form-group">
                                <label>Days:</label>
                                <input class="form-control" type="number" name="days" value="{{ $tat->days }}">
                            </div>

                            <div class="form-group">
                                <label>Client Price Per Hour:</label>
                                <input class="form-control" type="number" step="any" name="client_price_per_hour" value="{{ round($tat->client_price_per_minute * 60, 2) }}">
                            </div>

                            <div class="form-group">
                                <label>Editor Price Per Hour:</label>
                                <input class="form-control" type="number" step="any" name="editor_price_per_hour" value="{{ round($tat->editor_price_per_minute * 60, 2) }}">
                            </div>

                            <div class="form-group">
                                <label>Transcriber Price Per Hour:</label>
                                <input class="form-control" type="number" step="any" name="transcriber_price_per_hour" value="{{ round($tat->transcriber_price_per_minute * 60, 2) }}">
                            </div>

                            <div class="form-group">
                                <label>Seperate audio into peaces x minutes long:</label>
                                <input class="form-control" type="number" name="slice_duration" value="{{ round($tat->slice_duration / 60) }}">
                            </div>

                            <div class="form-group">
                                <label>How much time transcriber has to finish the job (hours):</label>
                                <input class="form-control" type="number" name="max_transcription_duration" value="{{ round($tat->max_transcription_duration / 3600) }}">
                            </div>

                            <button type="submit" class="btn btn-primary pull-right">Save</button>

                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>

@stop