@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">

                    <div class="panel-heading">Language</div>
                    <div class="panel-body">

                        <form method="post" action="{{ route('language', [$language->id]) }}" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="form-group">
                                <label>Language:</label>
                                <input class="form-control" type="text" name="name" value="{{ $language->name }}">
                            </div>

                            <div class="form-group">
                                <label>Picture (86px x 86px)</label>
                                <input type="file" name="image" />

                                @if ($language->hasImage())
                                    <br>
                                    <img class="img-responsive" src="{{ $language->imageUrl() }}" alt=""/>
                                @endif
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="hide" value="1" @if ($language->hide) checked @endif> Hide language
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary pull-right">Save</button>

                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>

@stop