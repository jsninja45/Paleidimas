@extends('app')

@section('title'){{ 'Page not found' }}@stop
@section('error_code'){{ '404' }}@stop
@section('error_message'){{ 'page not found' }}@stop


@include('errors.partial.error')