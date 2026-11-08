@extends($activeTemplate . 'layouts.frontend')
@section('content')
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
                        <img src="img/logo.svg" alt="">
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
                <form class="card card-form" method="post" action="short-survey.html"> 
                    <div class="card-form-step">
                        <ul class="nav nav-step">
                            <li class="active">
                                <a class="item" href="personal-information.html">
                                    <div class="number">1.</div>
                                    <h6>Personal<br> 
                                        Information</h6>
                                </a>
                            </li>
    
                            <li>
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
                        <div class="boxed" style="--max-width:340px">
                            <h1 class="h1" data-aos>
                                <span class="text-highlight">Hello John Doe</span>
                            </h1>
    
                            <h2 class="title-md mt-sm-2" data-aos="fade-in" data-aos-delay="200">Please fill your information </h2>
    
                            <div class="form-profile">
                                <p class="text-center" style="color: #252525;"><strong>You are</strong></p>
    
                                <div class="row g-lg-4 g-sm-3 g-2">
                                    <div class="col-6">
                                        <button class="btn btn-outline gender" type="button">
                                            <img class="icons svg-js" src="img/icons/icon-male.svg" alt="">
                                            <span>male</span>
                                        </button>
                                    </div>
                                    
                                    <div class="col-6">
                                        <button class="btn btn-outline gender" type="button">
                                            <img class="icons svg-js" src="img/icons/icon-female.svg" alt="">
                                            <span>female</span>
                                        </button>
                                    </div>
                                </div>
    
                                <div class="boxed">
                                    <div class="row g-lg-4 g-3">  
                                        <div class="col-lg-7">  
                                            <div class="form-group">
                                                <label class="title">Date of Birth</label>
                                                <div class="group">
                                                    <span class="icons icon-arrow-down right"></span>
                                                    <input type="text" class="form-control calendar" placeholder="MM/DD/YYYY">
                                                </div>
                                            </div>
        
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="title">Hight  (ft.)</label>
                                                <input type="text" class="form-control" >
                                            </div>
                                        </div>
        
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="title">Weight (ib.)</label>
                                                <input type="text" class="form-control" >
                                            </div>
                                        </div>
        
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="title">ZIP code</label>
                                                <input type="text" class="form-control" >
                                            </div>
                                        </div>
        
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="title">Last 4 SSN</label>
                                                <input type="text" class="form-control" >
                                            </div>
                                        </div>
        
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="title">Phone Number</label>
                                                <input type="text" class="form-control" >
                                            </div>
                                        </div> 
                                    </div><!--row-->
                                </div><!--boxed-->
                                
                                <div class="buttons">
                                    <button class="btn btn-primary fs-18 w-140" type="submit">
                                        <span>NEXT</span>
                                    </button>
                                </div>
    
                            </div><!--form-profile-->
                        </div> 
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
                            <img class="w-100" src="img/logo.svg" alt="">
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

<script src="../js/jquery-3.4.1.min.js"></script>  
<script src="../js/bootstrap/popper.min.js"></script>
<script src="../js/bootstrap/bootstrap.min.js"></script>    
<script src="../js/jquery.fancybox.js"></script> 
<script src="../js/aos.js"></script>       
<script src="../js/jquery.scrollbar.js"></script> 
<script src="../js/custom.js"></script>     
 
<script>
    // var myModal = new bootstrap.Modal(document.getElementById('projectModal1'))
    // myModal.show();  
    $(document).ready(function(){ 
        $('.btn.gender').on('click', function(){
            $('.btn.gender').removeClass('active');
            $(this).addClass('active');
        });

        $('input.calendar').datepicker({
            language: "th",   
            inline: true, 
            todayHighlight: true, 
            format: "mm/dd/yyyy", 
        });
    });
   
</script>
 
</body>