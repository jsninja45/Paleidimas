@section('content')
    <div class="error-page">
        <div class="container">
            <div class="error-code">@yield('error_code')</div>
            <div class="error-message">@yield('error_message')</div>
            <a class="home-link" href="{{ url('/') }}">Go to home page</a>
        </div>
    </div>
@stop