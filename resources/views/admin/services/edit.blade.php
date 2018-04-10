@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">

                    <div class="panel-heading"><b>Title</b></div>
                    <div class="panel-body">

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="post" action="/{{ $route }}@if ($row->id)/{{ $row->id }}@endif" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="form-group">
                                <label>URL</label>
                                <input class="form-control" type="text" name="slug" value="{{ old('slug', $row->slug) }}">
                            </div>

                            <?php
                            $services = \App\Service::all();
                            ?>
                            <div class="form-group">
                                <label>Parent category</label>
                                <select class="form-control" name="parent_service_id">
                                    <option value=""></option>
                                    @foreach ($services as $s)
                                        <option value="{{ $s->id }}" @if ($s->id == $row->parent_service_id) selected @endif>{{ $s->title }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group">
                                <label>Title</label>
                                <input class="form-control" type="text" name="title" value="{{ old('title', $row->title) }}">
                            </div>

                            <div class="form-group">
                                <label>Picture (105px x 105px)</label>
                                <input type="file" name="thumbnail" />

                                @if (isset($this->id) AND $row->hasImage('thumbnail'))
                                    <br>
                                    <img src="{{ $row->imageUrl('thumbnail') }}" alt=""/>
                                @endif
                            </div>

                            <div class="form-group">
                                <label>Short content</label>
                                <textarea class="form-control height-150" name="short_content">{{ old('short_content', $row->short_content) }}</textarea>
                            </div>

                            {{--<div class="form-group">--}}
                                {{--<label>Picture (750px x 277px)</label>--}}
                                {{--<input type="file" name="image" />--}}

                                {{--@if ($row->hasImage('image'))--}}
                                    {{--<br>--}}
                                    {{--<img style="width: 200px; height: auto;" src="{{ $row->imageUrl('image') }}" alt=""/>--}}
                                {{--@endif--}}
                            {{--</div>--}}

                            <div class="form-group">
                                <label>Content</label>
                                <textarea class="form-control height-400 js-wysiwyg" name="content">{{ old('content', $row->content) }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary pull-right">Save</button>

                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>

@stop