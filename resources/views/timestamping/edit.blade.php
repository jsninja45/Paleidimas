@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">

                    <div class="panel-heading"><b>Timestamping</b></div>
                    <div class="panel-body">

                        <form method="post" action="{{ route('timestamping', [$timestamping->id]) }}">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="form-group">
                                <label>Name:</label>
                                <input class="form-control" type="text" name="name" value="{{ $timestamping->name }}">
                            </div>

                            <div class="form-group">
                                <label>Client price per hour:</label>
                                <input class="form-control" type="number" step="any" name="client_price_per_hour" value="{{ round($timestamping->client_price_per_minute * 60, 2) }}">
                            </div>

                            <div class="form-group">
                                <label>Editor price per hour:</label>
                                <input class="form-control" type="number" step="any" name="editor_price_per_hour" value="{{ round($timestamping->editor_price_per_minute * 60, 2) }}">
                            </div>

                            <button type="submit" class="btn btn-primary pull-right">Save</button>

                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>

@stop