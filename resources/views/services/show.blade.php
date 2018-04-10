@extends('app')

@section('title'){{ $service->title }}@stop
@section('bodyClass'){{ 'footer-no-margin service-page' }}@stop
@section('breadcrumb')
    <span><a href="{{ route('services') }}">Audio transcription service</a></span>
@stop

@section('content')
    <div class="service-body">
        <div class="container">
            <h1>{{ $service->title }}</h1>

            {!! $service->content !!}
        </div>
    </div>

    {{-- Upload section --}}
    @include('pages.partial.upload_section')
@stop