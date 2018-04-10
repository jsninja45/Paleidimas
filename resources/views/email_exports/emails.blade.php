@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">

                    <div class="panel-heading"><b>Emails</b></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <label>Emails:</label>
                            <textarea name="content" class="form-control height-400">@if ($emails){{ implode("\n", $emails) }}@endif</textarea>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

@stop