@php $activeStep = (int) ($activeStep ?? 1); @endphp
<div class="card-form-step">
    <ul class="nav nav-step">
        <li class="{{ $activeStep === 1 ? 'active' : '' }}">
            <a class="item" href="{{ route('checkinsure.profile') }}">
                <div class="number">1.</div>
                <h6>Personal<br>Information</h6>
            </a>
        </li>
        <li class="{{ $activeStep === 2 ? 'active' : '' }}">
            <a class="item" href="{{ session('checkinsure_profile_step') ? route('checkinsure.survey') : '#' }}">
                <div class="number">2.</div>
                <h6>Short<br>Survey</h6>
            </a>
        </li>
        <li class="{{ $activeStep === 3 ? 'active' : '' }}">
            <a class="item" href="{{ session('checkinsure_criteria') ? route('checkinsure.results') : '#' }}">
                <div class="number">3.</div>
                <h6>Your<br>Result</h6>
            </a>
        </li>
    </ul>
</div>
