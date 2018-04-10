@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">

                    <div class="panel-heading">Chat with: {{ $recipient->email }}</div>

                    <div class="panel-body">

                        @if ($recipient->id == Auth::id())
                            You can't chat with yourself
                        @else

                            @foreach($messages->reverse() as $message)
                                <b>{{ $message->sender->email }}</b>: {{ $message->content }}<hr>
                            @endforeach
                        
                            <div class="divider"></div>

                            <form method="post" action="{{ route('store_message', [$recipient->id]) }}">
                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                                <div class="form-group">
                                    <label>Write message:</label>
                                    <textarea name="content" class="form-control"></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary pull-right">Send</button>

                            </form>

                        @endif




                    </div>
                </div>

                <div class="text-center">
                    {!! $messages->render() !!}
                </div>
            </div>

        </div>
    </div>

@stop