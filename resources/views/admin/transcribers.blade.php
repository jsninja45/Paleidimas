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
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="order" @if (Input::get('order') === 'rating') checked @endif value="rating"> Order by rating
                                        </label>
                                    </div>
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
                        Transcribers ({{ $total_transcribers }}) &ensp;
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
                            @foreach ($transcribers as $transcriber)
                                <tr>
                                    <td>{{ $transcriber->email }}</td>
                                    <td>{{ $transcriber->audioSlices()->inProgress()->count() }}</td>
                                    <td>{{ $transcriber->audioSlices()->finished()->count() }}</td>
                                    <td>@if ($transcriber->precalculated_rating) {{ $transcriber->precalculated_rating }} @else - @endif</td>
                                    <td>${{ \App\Salary::earnedTillNow($transcriber) }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-xs" href="{{ route('admin_audio_slices') }}?transcriber_id={{ $transcriber->id }}">Audio slices</a>
                                        <a class="btn btn-default btn-xs" href="{{ route('admin_user', [$transcriber->id]) }}/edit">Edit</a>
                                    </td>
                                </tr>
                            @endforeach

                        </table>

                        {!! $transcribers->appends(Request::only(['language', 'order']))->render() !!}
                    </div>
                </div>

            </div>

        </div>
    </div>

@stop