@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">

                    <div class="panel-heading"><b>Rating Delay</b></div>
                    <div class="panel-body">

                        @if ($row->id)
                            <form method="post" action="/{{ $route }}/{{ $row->id }}">
                        @else
                            <form method="post" action="/{{ $route }}">
                        @endif
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="form-group">
                                <label>Rating (till)</label>
                                <input class="form-control" type="number" step="any" name="rating_till" value="{{ $row->rating_till }}">
                            </div>

                            <div class="form-group">
                                <label>Delay (minutes)</label>
                                <input class="form-control" type="number" name="delay" value="{{ round($row->delay / 60) }}">
                            </div>

                            <button type="submit" class="btn btn-primary pull-right">Save</button>

                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>

@stop