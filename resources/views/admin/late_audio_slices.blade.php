@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Audio slice time left</b></div>

                    <div class="panel-body">

                        <table class="table table-striped table-hover">
                            <tr>
                                <th>Audio slice ID</th>
                                <th>Filename</th>
                                <th>Until deadline</th>
                                <th>Status</th>
                            </tr>
                            @foreach ($audio_slices as $slice)
                                <?php
                                $bg = '';
                                if ($slice->deadline_at < date('Y-m-d H:i:s')) {
                                    $bg = 'bg-danger';
                                }
                                ?>
                                <tr class="{{ $bg }}">
                                    <td>{{ $slice->id }}</td>
                                    <td>{{ $slice->audio->original_filename }}</td>
                                    <td>{{ timeLeftFull(strtotime($slice->audio->order->deadline_at) - time()) }}</td>
                                    <td>{{ $slice->status }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

                <div class="text-center">
                    {!! $audio_slices->render() !!}
                </div>
            </div>

        </div>
    </div>

@stop