@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">

                    <div class="panel-heading"><b>Edit blog</b></div>
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
                                <label>URL: {{ URL::to('/') }}/blog/{{ $row->slug }}</label>
                                <input class="form-control" type="text" name="slug" value="{{ old('slug', $row->slug) }}">
                            </div>

                            <div class="form-group">
                                <label>Title</label>
                                <input class="form-control" type="text" name="title" value="{{ old('title', $row->title) }}">
                            </div>

                            <div class="form-group">
                                <label>Picture (750px x 277px)</label>
                                <input type="file" name="image" />
                                
                                @if ($row->hasImage())
                                    <br>
                                    <img style="width: 200px; height: auto;" src="{{ $row->imageUrl() }}" alt=""/>
                                @endif
                            </div>

                            <div class="form-group">
                                <label>Content</label>
                                <textarea class="form-control height-400 js-wysiwyg" name="content">{{ old('content', $row->content) }}</textarea>
                            </div>



                            <button type="submit" class="btn btn-primary pull-right">Save</button>

                        </form>

                        {{-- wysiwyg file upload --}}
                        <iframe id="form_target" name="form_target" style="display:none"></iframe>
                        <form id="my_form" action="/wysiwyg/upload" target="form_target" method="post" enctype="multipart/form-data" style="width:0px;height:0;overflow:hidden">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input name="image" type="file" onchange="$('#my_form').submit();this.value='';">
                        </form>

                    </div>

                </div>

            </div>

        </div>
    </div>

@stop