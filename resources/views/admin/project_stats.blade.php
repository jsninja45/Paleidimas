@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Income and expenses</div>

                    <div class="panel-body">

                        <table class="table table-striped table-hover">
                            <tr>
                                <th>Month</th>
                                <th>Income</th>
                                <th>Expenses</th>
                                <th>Profit</th>
                            </tr>
                            @foreach ($months as $k => $month)
                                <tr>
                                    <td>{{ $k }}</td>
                                    <td>${{ $month['income'] }}</td>
                                    <td>${{ $month['expenses'] }}</td>
                                    <td>${{ $month['profit'] }} ({{ round($month['profit_percents'], 2) }}%)</td>
                                </tr>
                            @endforeach
                        </table>

                    </div>
                </div>

            </div>

        </div>
    </div>

@stop