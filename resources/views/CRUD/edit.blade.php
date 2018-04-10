@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">

                    <div class="panel-heading"><b>Title</b></div>
                    <div class="panel-body">

                        <form method="post" action="/{{ $route }}@if ($row->id)/{{ $row->id }}@endif">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="form-group">
                                <label>Write message:</label>
                                <input class="form-control" type="text" name="question" value="{{ old('question', $row->question) }}">
                            </div>

                            <div class="form-group">
                                <label>Delay (minutes)</label>
                                <textarea class="form-control height-400 js-wysiwyg" name="answer">{{ old('answer', $row->answer) }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary pull-right">Save</button>

                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>

@stop