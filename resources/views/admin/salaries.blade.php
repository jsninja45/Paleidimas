@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Salaries</b></div>

                    <div class="panel-body">

                        <div class="alert alert-info">
                            <b>red</b> - never received money
                        </div>

                        <table class="table table-striped table-hover">
                            <tr>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Payment from</th>
                                <th>Payment till</th>
                                <th>Amount</th>
                                <th>PayPal email</th>
                                <th></th>
                            </tr>
                            @foreach($users as $user)
                                <?php
                                $received_payments = $user->transactions()->paid()->exists();;
                                ?>

                                <tr class="@if (!$received_payments) danger @endif">
                                    <td><a target="_blank" href="{{ route('admin.user.edit', [$user->id]) }}">{{ $user->email }}</a></td>
                                    <td>
                                        @foreach($user->roles as $k => $role)@if ($k != 0), @endif{{ $role->name }}@endforeach
                                    </td>
                                    <td>@if (strtotime($user->data['from']) - strtotime($user->data['till']) == 1) - @else {{ $user->data['from'] }} @endif</td>
                                    <td>{{ $user->data['till'] }}</td>
                                    <td>${{ $user->data['amount'] }} @if ($user->data['bonus'] != 0) ({{ ($user->data['amount'] - $user->data['bonus']) . ' ' . prefixNumberWithSign($user->data['bonus']) }}) @endif</td>
                                    <td>{{ $user->paypal_email }}</td>
                                    <td>
                                        @if (strtotime($user->data['from']) - strtotime($user->data['till']) == 1)
                                            paid
                                        @else
                                            @if ($user->data['amount'] > 0)
                                                <form method="post" action="{{ route('admin_salaries_pay', [$user->id]) }}">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <button class="btn btn-primary btn-xs">Pay</button>
                                                </form>
                                            @endif
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        </table>

                    </div>
                </div>

            </div>

        </div>
    </div>

@stop