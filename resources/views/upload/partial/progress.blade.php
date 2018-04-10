<div class="steps">
    <div class="step active @if ($step === 1){{ 'current' }}@endif">
        <a @if ($step > 1)href="{{ route('upload') }}"@endif>Add files</a>
    </div>
    <div class="step @if ($step >= 2){{ 'active' }}@endif @if ($step === 2){{ 'current' }}@endif">Sign in</div>
    <div class="step @if ($step >= 3){{ 'active' }}@endif @if ($step === 3){{ 'current' }}@endif">Payment</div>
    <div class="step @if ($step === 4){{ 'current' }}@endif">Order placement</div>
</div>