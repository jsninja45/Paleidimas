@if($errors->has())
    @foreach ($errors->all() as $error)
        <div class="form-group">
            <div class="bg-danger">{{ $error }}</div>
        </div>
    @endforeach
@endif

@if(Session::has('success'))
    <div class="alert-box alert-success">
        <h2>{{ Session::get('success') }}</h2>
    </div>
@endif

@if (Session::has('error'))
    <div class="alert alert-danger">
        <ul><li>{{ Session::get('error') }}</li></ul>
    </div>
@endif
