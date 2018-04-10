@extends('simple_app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>File upload</b></div>

                    <div class="panel-body">

                        <form action="{{ route('audio_transcription_upload', [$audio_id]) }}" method="post" enctype="multipart/form-data">

                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="form-group">
                                <input type="file" name="file" accept=".doc, .docx">
                            </div>

                            <button class="btn btn-primary pull-right">Upload file</button>

                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>
@stop
