@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Audio time left</b></div>

                    <div class="panel-body">

                        <table class="table table-striped table-hover">
                            <tr>
                                <th>Audio ID</th>
                                <th>Filename</th>
                                <th>Until deadline</th>
                                <th>Status</th>
                            </tr>
                            @foreach ($audios as $audio)
                                <?php
                                $bg = '';
                                if ($audio->order->deadline_at < date('Y-m-d H:i:s')) {
                                    $bg = 'bg-danger';
                                }
                                ?>
                                <tr class="{{ $bg }}">
                                    <td>{{ $audio->id }}</td>
                                    <td>{{ $audio->original_filename }}</td>
                                    <td>{{ timeLeftFull(strtotime($audio->order->deadline_at) - time()) }}</td>
                                    <td>{{ $audio->status }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

                <div class="text-center">
                    {!! $audios->render() !!}
                </div>
            </div>

        </div>
    </div>

@stop