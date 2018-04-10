@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">

                {{-- transcriber --}}
                @if ($user->hasRole('transcriber'))
                    <div class="panel panel-default">
                        <div class="panel-heading"><b>Transcription Statistics</b></div>

                        <div class="panel-body">

                            <table class="table table-striped table-hover">
                                <tr>
                                    <td>Average rating</td>
                                    <td>
                                        <?php
                                        $transcription_rating = $user->transcriptionRating();
                                        ?>
                                        @if ($transcription_rating == 0)
                                            -
                                        @else
                                            {{ round($user->transcriptionRating(), 2) }}
                                        @endif

                                    </td>
                                </tr>
                                <tr>
                                    <td>Finished Jobs</td>
                                    <td>
                                        <a href="{{ route('finished_transcription_jobs') }}">{{ $user->audioSlices()->finished()->count() }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Refused Jobs</td>
                                    <td>
                                        {{ $canceled_jobs }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>Missed Deadline</td>
                                    <td>
                                        {{ $missed_deadline }}
                                    </td>
                                </tr>

                            </table>

                        </div>
                    </div>


                    <div class="panel panel-default">
                        <div class="panel-heading"><b>Transcription Languages</b></div>

                        <div class="panel-body">

                            <table class="table table-hover">
                                <tr>
                                    <th>Language</th>
                                    <th>Rating</th>
                                    <th>Delay</th>
                                </tr>
                                @foreach ($user->ratings as $rating)
                                    <tr>
                                        <td>{{ $rating->language->name }}</td>
                                        <td>{{ $rating->rating }}</td>
                                        <td>{{ round(App\RatingDelay::getDelay($rating->rating) / 60) }} minutes</td>
                                    </tr>
                                @endforeach
                            </table>

                        </div>
                    </div>


                    {{--<div class="panel panel-default">--}}
                        {{--<div class="panel-heading"><b>Transcription Rates</b></div>--}}
                        {{--<div class="panel-body">--}}

                            {{--<table class="table table-hover">--}}
                                {{--<tr>--}}
                                    {{--<th>Time Frame</th>--}}
                                    {{--<th>Amount</th>--}}
                                {{--</tr>--}}
                                {{--@foreach ($tats as $tat)--}}
                                    {{--<tr>--}}
                                        {{--<td>{{ $tat->days }}</td>--}}
                                        {{--<td>{{ round(\App\UserPricePerMinute::transcriber($tat, $user) * 60, 2) }} USD/hour</td>--}}
                                    {{--</tr>--}}
                                {{--@endforeach--}}
                            {{--</table>--}}

                        {{--</div>--}}
                    {{--</div>--}}


                @endif


                @if ($user->hasRole('editor'))
                    <div class="panel panel-default">
                        <div class="panel-heading"><b>Editing Statistics</b></div>

                        <div class="panel-body">

                            <table class="table table-striped table-hover">
                                <tr>
                                    <td>Average rating</td>
                                    <?php $editing_rating = round($user->editingRating(), 1); ?>
                                    <td>@if ($editing_rating) {{ $editing_rating }} @else - @endif</td>
                                </tr>
                                <tr>
                                    <td>Jobs</td>
                                    <td>
                                        <a href="{{ route('available_for_editing_jobs') }}">{{ $user->editorAudios()->finishedEditing()->count() }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jobs finished on time</td>
                                    <td>
                                        <a href="{{ route('finished_editing_jobs') }}">{{ $user->editorAudios()->finishedOnTime()->count() }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jobs in progress</td>
                                    <td>
                                        <a href="{{ route('in_progress_editing_jobs') }}">{{ $user->editorAudios()->inEditing()->count() }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Languages</td>
                                    <td>
                                        {!! implode('<br>', $user->languages->lists('name')) !!}
                                    </td>
                                </tr>

                            </table>

                        </div>
                    </div>


                    {{--<div class="panel panel-default">--}}
                        {{--<div class="panel-heading"><b>Editing Rates</b></div>--}}
                        {{--<div class="panel-body">--}}

                            {{--<table class="table table-hover">--}}
                                {{--<tr>--}}
                                    {{--<th>Time Frame</th>--}}
                                    {{--<th>Without timestamping</th>--}}
                                    {{--<th>With Timestamping</th>--}}
                                {{--</tr>--}}
                                {{--@foreach ($tats as $tat)--}}
                                    {{--<tr>--}}
                                        {{--<td>{{ $tat->days }}</td>--}}
                                        {{--<td>{{ round(\App\UserPricePerMinute::editor($tat, $timestampings->first(), $user) * 60, 2) }} USD/hour</td>--}}
                                        {{--<td>{{ round(\App\UserPricePerMinute::editor($tat, $timestampings->last(), $user) * 60, 2) }} USD/hour</td>--}}
                                    {{--</tr>--}}
                                {{--@endforeach--}}
                            {{--</table>--}}

                        {{--</div>--}}
                    {{--</div>--}}

                @endif








                <div class="panel panel-default">
                    <div class="panel-heading"><b>Money Transactions</b></div>

                    <div class="panel-body">


                        {{--<table class="table table-striped table-hover">--}}
                            {{--<tr>--}}
                                {{--<td>Earned since last payment</td>--}}
                                {{--<td><b>${{ $not_paid_earnings }}</b></td>--}}
                            {{--</tr>--}}
                        {{--</table>--}}

                        @if (!$user->paypal_email)
                            To receive money, set PayPal email <a class="btn btn-danger btn-xs" href="{{ route('profile', [$user->id]) }}">Set email</a>
                        @endif
                        <br><br>

                        <table class="table table-striped table-hover">

                            <tr>
                                <th>Period</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th></th>
                            </tr>

                            {{-- earning till now --}}
                            <tr>
                                <td>{{ $earned_till_now_from }} - now</td>
                                <td>${{ round($earnings_till_now, 2) }}</td>
                                <td></td>
                                <td></td>
                            </tr>

                            {{-- for last week (or more) earnings --}}
                            <?php
                            $salary_from = \App\Salary::from($user);
                            $salary_till = \App\Salary::till($user);
                            $salary_amount = \App\Salary::amount($user, $salary_from, $salary_till);
                            ?>
                            @if ($salary_amount)
                                <tr>
                                    <td>{{ $salary_from }} - {{ $salary_till }}</td>
                                    <td>${{ $salary_amount }}</td>
                                    <td>
                                        @if ($salary_amount != 0)
                                            Not paid
                                        @endif
                                        @if ($salary_amount != 0 && Auth::user()->hasRole('admin'))
                                            <form method="post" action="{{ route('admin_salaries_pay', [$user->id]) }}">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <button class="btn btn-primary btn-xs">Pay</button>
                                            </form>
                                        @else

                                        @endif
                                    </td>
                                    <td></td>
                                </tr>
                            @endif

                            {{-- already earned --}}
                            @foreach ($transactions as $transaction)
                                @if ($transaction->type === 'worker')
                                    <tr>
                                        <td>
                                            {{ $transaction->worker_payment_from }} - {{ $transaction->worker_payment_till }}
                                        </td>
                                        <td>${{ -$transaction->amount }}</td>
                                        <td>
                                            Paid
                                        </td>
                                        <td><a class="btn btn-primary btn-xs" href="{{ url('/') }}/worker/{{ $user->id }}/payment-details/{{ $transaction->id }}">More Info</a></td>
                                    </tr>
                                @endif
                            @endforeach

                        </table>

                    </div>
                </div>


            </div>
        </div>
    </div>

@stop