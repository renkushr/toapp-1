@extends($activeTemplate . 'layouts.master')
@section('content')
    @php
        $kyc = getContent('kyc.content', true);
    @endphp
    <body>
<div class="preload">
    <span class="loader"></span>
</div>
    
<div class="page page-dark">    
    <header class="header"> 
        <div class="container-fluid">
            <button class="btn btn-icon navbar-toggle" type="button">
                <span class="group">
                    <span></span>
                    <span></span>
                    <span></span>
                </span> 
            </button>
            <div class="navbar-brand">
                <a href="index.html">
                    <img src="../../img/logo.svg" alt="">
                </a>
            </div>
            <ul class="nav nav-general d-none d-sm-flex">
                <li class="nav-lang dropdown">
                    <a href="#" data-bs-toggle="dropdown" data-bs-display="static">
                        EN
                    </a>
                    <ul class="dropdown-menu">
                        <li class="active"><a href="#">EN</a></li>
                        <li><a href="#">TH</a></li> 
                        <li><a href="{{ route('user.logout') }}">Logout</a></li> 
                    </ul>
                </li>
            </ul> 
        </div><!--container-fluid-->
    </header>

    <div class="navbar-slider">
        <ul class="nav nav-sidebar">
            <li class="active"><a class="nav-title" href="index.html">Home</a></li>
            <li>
                <span class="nav-title nav-toggle" href="#">Products</span>
                <div class="accordion-collapse">
                    <ul class="nav nav-sub">
                        <li><a href="#">Lorem ipsum</a></li>
                        <li><a href="#">Lorem ipsum</a></li>
                        <li><a href="#">Lorem ipsum</a></li>
                    </ul>
                </div>
            </li>
            <li><a class="nav-title" href="#">Articles</a></li>
            <li><a class="nav-title" href="#">@lang('Contact us')</a></li>
        </ul>
    </div>

    <div class="section p-0">
        <div class="container">
            <form class="card card-form" method="post" action="your-result.html"> 
                <div class="card-form-step">
                    <ul class="nav nav-step">
                        <li>
                            <a class="item" href="personal-information.html">
                                <div class="number">1.</div>
                                <h6>Personal<br> 
                                    Information</h6>
                            </a>
                        </li>

                        <li class="active">
                            <a class="item" href="short-survey.html">
                                <div class="number">2.</div>
                                <h6>Short<br>
                                    Survey</h6>
                            </a>
                        </li>

                        <li>
                            <a class="item" href="your-result.html">
                                <div class="number">3.</div>
                                <h6>Your <br>
                                    Result</h6>
                            </a>
                        </li>
                    </ul>
                </div><!--card-form-step-->

                <div class="card-body text-center">
                    <div class="boxed" style="--max-width:330px">
                        <div class="row check-lists gy-sm-4 gy-3">
                            <div class="col-12">
                                <h1 class="title-md">How’s your health?</h1>
                            </div>
    
                            <div class="col-12 radio d-flex">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="health-check" id="standard" checked>
                                    <label class="form-check-label" for="standard">
                                        Standard
                                    </label>
                                </div>
    
                                <div class="form-check ms-auto">
                                    <input class="form-check-input" type="radio" name="health-check" id="healthy" checked>
                                    <label class="form-check-label" for="healthy">
                                        Healthy
                                    </label>
                                </div>
                            </div><!--col-12--> 

                            <div class="col-12 d-flex">
                                <div class="form-check mx-auto">
                                    <input class="form-check-input" type="checkbox" value="" id="smoking">
                                    <label class="form-check-label" for="smoking">
                                        I’m smoking <span class="nowrap">(Current nicotine use)</span>
                                    </label>
                                </div>
                            </div><!--col-12--> 
                        </div><!--row-->
                    </div>
                    

                    <div class="boxed mt-4 pt-sm-2" style="--max-width:435px">
                        <h1 class="title-md mb-3">What’s your interested?</h1>

                        <div class="buttons pay">
                            <button class="btn btn-outline btn-pay active" type="button">
                                <span>Annual pay insurance</span>
                            </button>
                            <button class="btn btn-outline btn-pay" type="button">
                                <span>Monthly pay insurance</span>
                            </button>
                        </div>
                    </div><!--boxed-->

                    <div class="boxed mt-sm-5 mt-4 pt-2 pt-sm-0" style="--max-width:760px">
                        <h1 class="title-md mb-3">The premium you expected </h1>

                        <div class="row card-pricing-lists">
                            <div class="col-sm-4">
                                <div class="card-pricing active">
                                    <h3 class="card-title">Term Life </h3>
                                    <div class="card-body">
                                        <p>Start from</p>
                                        <h2>$50</h2>
                                        <p>Per year</p>
                                    </div>
                                </div><!--card-pricing-->
                            </div>

                            <div class="col-sm-4">
                                <div class="card-pricing">
                                    <h3 class="card-title">Whole Life</h3>
                                    <div class="card-body">
                                        <p>Start from</p>
                                        <h2>$100</h2>
                                        <p>Per year</p>
                                    </div>
                                </div><!--card-pricing-->
                            </div>

                            <div class="col-sm-4">
                                <div class="card-pricing">
                                    <h3 class="card-title">IUL</h3>
                                    <div class="card-body">
                                        <p>Start from</p>
                                        <h2>$200</h2>
                                        <p>Per year</p>
                                    </div>
                                </div><!--card-pricing-->
                            </div>
                        </div><!--row-->
                         
                        <div class="buttons mt-sm-4 my-2 flex-column gap-3">
                            <a class="btn btn-link fs-18" href="personal-information.html">
                                <span>Back</span>
                            </a>
                            <button class="btn btn-primary fs-18" type="submit">
                                <span>Here’s your details</span>
                            </button>
                        </div>
                    </div><!--boxed--> 
                </div><!--card-body-->
            </form><!--card-form-->
        </div><!--container-->
    </div><!--section-->

    <footer class="footer" data-aos="fade-in" data-aos-offet="0">
        <div class="container">
            <hr>

            <div class="row">
                <div class="col-md-8">
                    <div class="logo">
                        <img class="w-100" src="../../img/logo.svg" alt="">
                    </div>
                    <p>&copy; {{ date('Y') }} TONKOW TEK LLP. @lang('All rights reserved.')</p>
                </div>
                                                <div class="col-md-4 d-flex justify-content-end flex-column">
                    @php $supportEmail = trim((string) (gs('support_email') ?: config('toapp.support_email', 'tonkowtek@to-app.com'))); @endphp
                    @if($supportEmail !== '')
                        <p class="contact-item mb-0">
                            <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </footer>
     
</div><!--page-->

<script src="../../js/jquery-3.4.1.min.js"></script>  
<script src="../../js/bootstrap/popper.min.js"></script>
<script src="../../js/bootstrap/bootstrap.min.js"></script>    
<script src="../../js/jquery.fancybox.js"></script> 
<script src="../../js/aos.js"></script>      
<script src="../../js/jquery.scrollbar.js"></script> 
<script src="../../js/custom.js"></script>     
 
<script>
    // var myModal = new bootstrap.Modal(document.getElementById('projectModal1'))
    // myModal.show();  

    $('.btn-pay').on('click', function(){
        $('.btn-pay').removeClass('active');
        $(this).addClass('active');
    });

    $('.card-pricing').on('click', function(){
        $('.card-pricing').removeClass('active');
        $(this).addClass('active');
    });
  
</script>
 



