<h3 class="heading">Login with social media access</h3>

<div class="form-group">
    <a href="{{ route('social_login', ['facebook']) }}" class="auth-social auth-facebook">
        Sign in with Facebook
    </a>
</div>

<div class="form-group">
    <a href="{{ route('social_login', ['google']) }}" class="auth-social auth-google">
        Sign in with Google+
    </a>
</div>
