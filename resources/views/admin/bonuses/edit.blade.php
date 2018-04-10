@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">

                    <div class="panel-heading"><b>Bonus</b></div>
                    <div class="panel-body">

                        <form method="post" action="/{{ $route }}@if ($row->id)/{{ $row->id }}@endif">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="form-group">
                                <label>User:</label>
                                <select class="form-control" name="user_id">
                                    <?php $workers = \App\User::workers()->get(); ?>
                                    @foreach ($workers as $user)
                                        <option value="{{ $user->id }}" @if ($user->id == $row->user_id) selected @endif>{{ $user->email }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Amount (example: 10, or -10)</label>
                                <input class="form-control" type="text" name="amount" value="{{ old('question', $row->amount) }}">
                            </div>

                            <div class="form-group">
                                <label>Comment</label>
                                <textarea class="form-control height-400" name="comment">{{ old('answer', $row->comment) }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary pull-right">Save</button>

                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>

@stop