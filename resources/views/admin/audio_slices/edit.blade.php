@extends('simple_app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Edit audio slice</b></div>

                    <div class="panel-body">

                        <form action="{{ route('admin.audio-slices.update', [$slice->id]) }}" method="post" enctype="multipart/form-data">

                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="form-group">
                                <label>Editor rating</label>
                                <select class="form-control" name="rating">
                                    <option value="">Not rated</option>
                                    @for ($i = 1; $i < 6; $i++)
                                        <option value="{{ $i }}" @if ($i == $slice->rating) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Transcriber price override (just for <b>available</b> audios)</label>
                                <input class="form-control" type="number" step="0.01" name="transcriber_price_override" value="{{ $slice->transcriber_price_override }}">
                            </div>

                            <button class="btn btn-primary pull-right">Save</button>

                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>
@stop