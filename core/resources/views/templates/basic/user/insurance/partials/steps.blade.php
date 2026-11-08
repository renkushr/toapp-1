@php $activeStep = (int) ($activeStep ?? 1); @endphp
<div class="app-pills mb-3">
    <span class="app-pill {{ $activeStep === 1 ? '' : '' }}" style="{{ $activeStep === 1 ? 'background:rgba(18,104,243,.18);color:#1268f3;' : '' }}">@lang('1. Profile')</span>
    <span class="app-pill" style="{{ $activeStep === 2 ? 'background:rgba(18,104,243,.18);color:#1268f3;' : '' }}">@lang('2. Survey')</span>
    <span class="app-pill" style="{{ $activeStep === 3 ? 'background:rgba(18,104,243,.18);color:#1268f3;' : '' }}">@lang('3. Products')</span>
</div>
