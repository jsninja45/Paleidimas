@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                {{-- filter --}}
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <form method="get" autocomplete="off">
                                <div class="col-md-2">
                                    <select class="form-control" name="language">
                                        <option value="">All</option>
                                        @foreach ($languages as $language)
                                            <option value="{{ $language->id }}" @if (Input::get('language') == $language->id) selected @endif>{{ $language->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <button class="btn btn-primary">Filter</button>
                                    <a href="{{ currentUrl() }}" class="btn btn-default">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Subtitlers ({{ $total_subtitlers }}) &ensp;
                        <span class="text-primary">Working: {{ $total_working }}</span> &ensp;
                        <span class="text-primary">Active: {{ $total_finished }}</span> &ensp;
                        <span class="text-primary">Rated: {{ $total_rated }}</span>
                    </div>

                    <div class="panel-body">

                        <table class="table table-striped table-hover">
                            <tr>
                                <th>Email</th>
                                <th>Jobs In Progress</th>
                                <th>Completed Jobs</th>
                                <th>Average rating</th>
                                <th>Current earnings</th>
                                <th></th>
                            </tr>
                            @foreach ($subtitlers as $subtitler)
                                <tr>
                                    <td>{{ $subtitler->email }}</td>
                                    <td>{{ $subtitler->subtitlerAudios()->inEditing()->count() }}</td>
                                    <td>{{ $subtitler->subtitlerAudios()->finishedEditing()->count() }}</td>
                                    <?php $editing_rating = round($subtitler->editingRating(), 1); ?>
                                    <td>@if ($editing_rating) {{ $editing_rating }} @else - @endif</td>
                                    <td>${{ \App\Salary::earnedTillNow($subtitler) }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-xs" href="{{ route('admin_audio-subtitles') }}?user_id={{ $subtitler->id }}">Audio subtitles</a>
                                        <a class="btn btn-default btn-xs" href="{{ route('admin_user', [$subtitler->id]) }}/edit">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>

                        {!! $subtitlers->appends(Request::only(['language']))->render() !!}
                    </div>
                </div>

            </div>

        </div>
    </div>

@stop