@extends('app')

@section('title')
    401 error
@stop

@section('error_code')
    401
@stop

@section('error_message')
    unauthorized access
@stop


@include('errors.partial.error')