@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">

                    <div class="panel-heading"><b>Title</b></div>
                    <div class="panel-body">

                        <form method="post" action="/{{ $route }}@if ($row->id)/{{ $row->id }}@endif">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="form-group">
                                <label>Code</label>
                                <input class="form-control" type="text" name="code" value="{{ $row->code }}">
                            </div>

                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control" name="type">
                                    <option value="amount" @if ($row->type === 'amount') selected @endif>Dollars</option>
                                    <option value="percent" @if ($row->type === 'percent') selected @endif>Percent</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Value (dollars or percent)</label>
                                <input class="form-control" type="text" name="value" value="{{ $row->value }}">
                            </div>

                            <div class="form-group">
                                <label>Comment</label>
                                <textarea class="form-control" name="comment">{{ $row->comment }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Usage</label>
                                <select class="form-control" name="single_use">
                                    <option value="1" @if ($row->type == 1) selected @endif>Single use</option>
                                    <option value="0" @if ($row->type == 0) selected @endif>Multi-use</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Expires at (example: <b>{{ date('Y-m-d H:i:s', strtotime('+7 day')) }}</b>)</label>
                                <input class="form-control" type="text" name="expires_at" value="{{ $row->expires_at }}">
                            </div>

                            <button type="submit" class="btn btn-primary pull-right">Save</button>

                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>

@stop