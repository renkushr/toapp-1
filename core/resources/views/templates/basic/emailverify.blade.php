@extends($activeTemplate . 'layouts.frontend')
@section('content')
<body>
    <div class="preload">
        <span class="loader"></span>
    </div>
        
    <div class="page ">    
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
    
        <div class="section section-insure-form">
            <div class="insure-form-boxed">
                <div class="row">
                    <div class="cols left">
                        <img data-aos="fade-up" src="img/thumb/photo-1025x715.jpg" alt="">
                    </div>
                    <div class="cols right">
                        <form class="form form-insure text-white">
                            <div class="form-header">
                                <h1 class="h1" data-aos>
                                    <span class="text-highlight">Find your insurance</span>
                                </h1>
                                <p class="m-0">
                                    <strong>A smarter choice for your family , <span class="nowrap">Check your insurance</span></strong>
                                </p>
                            </div>
    
                            <div class="form-body pt-0">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="title">Verify code</label>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>  
                                </div><!--row-->
                            </div>
    
                            <div class="form-footer text-center">
                                <div class="row g-4">
                                    <div class="col-12">
                                         <p class="m-0">Check your email and click the link to activate account</p>
                                    </div> 
                                    <div class="col-12">
                                        <div class="buttons flex-column gap-2 p-0">
                                            <button class="btn btn-primary" type="submit">
                                                <span>Verify Email</span>
                                            </button>
    
                                            <button class="btn btn-link resend" type="button">
                                                <img class="icons icon-resend svg-js" src="img/icons/icon-resend.svg" alt="">
                                                Resend 
                                                
                                            </button>
                                        </div>
                                    </div>
    
                                   
                                </div><!--row-->
                            </div>
                        </form>
                    </div>
                </div><!--row-->
            </div> 
        </div><!--section-->
    
        <div class="section section-slogan" data-aos="fade-in">
            <div class="container">
                <h3 class="text-white textrow"><span data-aos="fade-up">Enjoy the life. Enjoy your loves</span></h3>
            </div> 
        </div><!--section-->
    
        <div class="section section-about">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="about-lists">
                            <div class="row">
                                <div class="col-12" data-aos="fade-in">
                                    <h2 class="h1">About Insurance</h2>
                                    <p>Insurance is a financial product designed to protect individuals, families, and businesses from potential financial losses. By purchasing an insurance policy, the insured pays regular premiums to an insurance company, which in turn agrees to cover specific risks, providing financial compensation in the event of loss, damage, or liability.</p>
                                </div>  
        
                                <div class="col-12" data-aos="fade-in">
                                    <h2 class="h1">It’s about planing</h2>
                                    <p>In essence, insurance acts as a safety net, offering financial security and stability in the face of life's uncertainties. By understanding and investing in the right insurance policies, individuals and businesses can ensure they are well-prepared for unexpected events.</p>
        
                                    <div class="buttons">
                                        <a class="btn btn-primary w-210 me-auto" href="#">
                                            <span>Read more</span>
                                        </a>
                                    </div>
                                </div>  
                            </div><!--row-->
                        </div>   
                    </div>
    
                    <div class="col-lg-6">
                        <img class="img-to" data-aos="fade-up" src="img/thumb/photo-1000x480.jpg" alt="">
                    </div>
                </div><!--row-->
            </div><!--container-->
        </div><!--section-->
    
        <div class="section section-followus pb-0">
            <div class="container">
                <div class="followus-boxed" data-aos="fade-in">
                    <img class="icons question" data-aos="fade-up" src="img/icons/icon-question.png" alt="">
                    <h2 class="text-white">Do you need a help <br class="d-block d-sm-none">from expert?</h2>
    
                    <div class="followus">
                        <a class="icons icon-line" href="#" target="_blank"></a>
                        <a class="icons icon-messenger" href="#" target="_blank"></a>
                        <a class="icons icon-email" href="#" target="_blank"></a>
                    </div>
                </div><!--followus-boxed-->
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
  
</script>
 
</body>