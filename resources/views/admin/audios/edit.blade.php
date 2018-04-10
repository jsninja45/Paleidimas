@extends('simple_app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Edit audio</b></div>

                    <div class="panel-body">

                        <form action="{{ route('admin.audios.update', [$audio->id]) }}" method="post" enctype="multipart/form-data">

                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="form-group">
                                <label>Editor price override (just for <b>available</b> audios)</label>
                                <input class="form-control" type="number" step="0.01" name="editor_price_override" value="{{ $audio->editor_price_override }}">
                            </div>

                            <div class="form-group">
                                <label>Comment</label>
                                <textarea class="form-control height-400" name="comment">{{ $audio->comment }}</textarea>
                            </div>

                            <button class="btn btn-primary pull-right">Save</button>

                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>
@stop
