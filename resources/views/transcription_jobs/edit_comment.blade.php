@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit comment</div>

                    <div class="panel-body">

                        <form method="post" autocomplete="off">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="form-group">
                                <label>Comment</label>
                                <textarea class="form-control" name="comment" type="text" placeholder="Comment">{{ $comment }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary pull-right">Save</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

