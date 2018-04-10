@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">

                    <div class="panel-heading"><b>User:</b> {{ $user->email }}</div>
                    <div class="panel-body">

                        <form method="post" action="{{ route('admin_user', [$user->id]) }}">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="form-group">
                                <label>Comment</label>
                                <textarea class="form-control" name="comment">{{ $user->comment }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Languages</label>
                                <select class="form-control" name="languages[]" multiple>
                                    @foreach ($languages as $language)
                                        <option value="{{ $language->id }}" @if ($user->languages()->find($language->id)) selected @endif >{{ $language->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Roles</label>
                                <select class="form-control" name="roles[]" multiple>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" @if ($user->roles()->find($role->id)) selected @endif >{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Concurrent Job Count</label>
                                <input class="form-control" type="number" name="job_limit" value="{{ $user->job_limit }}">
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="1" name="enable_custom_rates" @if ($user->customRates->enabled) checked @endif> Enable custom rates
                                </label>
                            </div>

                            <div class="form-group">
                                <label>Transcriber hourly rates</label>

                                <table class="table table-striped table-hover">
                                    <tr>
                                        <td>Turnaround time</td>
                                        <td>Price</td>
                                    </tr>

                                    {{-- 4 tats --}}
                                    @foreach ($tats as $tat)
                                        <tr>
                                            <td>{{ $tat->days }} days</td>
                                            <td><input class="form-control" type="text" name="transcriber_price_{{ $tat->days }}_tat" value="{{ round(\App\UserPricePerMinute::transcriber($tat, $user) * 60, 2) }}"/></td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>

                            <div class="form-group">
                                <label>Editor hourly rates</label>

                                <table class="table table-striped table-hover">
                                    <tr>
                                        <td>Turnaround time</td>
                                        <td>Price</td>
                                        <td>Price (with timestamping)</td>
                                    </tr>

                                    {{-- 4 tats --}}
                                    @foreach ($tats as $tat)
                                        <tr>
                                            <td>{{ $tat->days }} days</td>
                                            <td><input class="form-control" type="text" name="editor_price_{{ $tat->days }}_tat_no_timestamping" value="{{ round(\App\UserPricePerMinute::editor($tat, $timestampings->first(), $user) * 60, 2) }}"/></td>
                                            <td><input class="form-control" type="text" name="editor_price_{{ $tat->days }}_tat_with_timestamping" value="{{ round(\App\UserPricePerMinute::editor($tat, $timestampings->last(), $user) * 60, 2) }}"/></td>
                                        </tr>
                                    @endforeach
                                    {{--<tr>--}}
                                        {{--<td>1 days</td>--}}
                                        {{--<td><input class="form-control" type="text" name="editor_price_1_tat_no_timestamping" value="{{ round(\App\UserPrice::editorPricePerMinute($user->id, 1, 1) * 60, 2) }}"/></td>--}}
                                        {{--<td><input class="form-control" type="text" name="editor_price_1_tat_with_timestamping" value="{{ round(\App\UserPrice::editorPricePerMinute($user->id, 1, 2) * 60, 2) }}"/></td>--}}
                                    {{--</tr>--}}
                                    {{--<tr>--}}
                                        {{--<td>3 days</td>--}}
                                        {{--<td><input class="form-control" type="text" name="editor_price_3_tat_no_timestamping" value="{{ round(\App\UserPrice::editorPricePerMinute($user->id, 2, 1) * 60, 2) }}"/></td>--}}
                                        {{--<td><input class="form-control" type="text" name="editor_price_3_tat_with_timestamping" value="{{ round(\App\UserPrice::editorPricePerMinute($user->id, 2, 2) * 60, 2) }}"/></td>--}}
                                    {{--</tr>--}}
                                    {{--<tr>--}}
                                        {{--<td>7 days</td>--}}
                                        {{--<td><input class="form-control" type="text" name="editor_price_7_tat_no_timestamping" value="{{ round(\App\UserPrice::editorPricePerMinute($user->id, 3, 1) * 60, 2) }}"/></td>--}}
                                        {{--<td><input class="form-control" type="text" name="editor_price_7_tat_with_timestamping" value="{{ round(\App\UserPrice::editorPricePerMinute($user->id, 3, 2) * 60, 2) }}"/></td>--}}
                                    {{--</tr>--}}
                                    {{--<tr>--}}
                                        {{--<td>14 days</td>--}}
                                        {{--<td><input class="form-control" type="text" name="editor_price_14_tat_no_timestamping" value="{{ round(\App\UserPrice::editorPricePerMinute($user->id, 4, 1) * 60, 2) }}"/></td>--}}
                                        {{--<td><input class="form-control" type="text" name="editor_price_14_tat_with_timestamping" value="{{ round(\App\UserPrice::editorPricePerMinute($user->id, 4, 2) * 60, 2) }}"/></td>--}}
                                    {{--</tr>--}}
                                </table>
                            </div>

                            <button type="submit" class="btn btn-primary pull-right">Save</button>

                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>

@stop