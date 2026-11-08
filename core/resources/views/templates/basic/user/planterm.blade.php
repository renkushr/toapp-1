@extends($activeTemplate . 'layouts.master')
@section('content')
    @php
        $kyc = getContent('kyc.content', true);
    @endphp
    <body>
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
                                </ul>
                            </li>
                        </ul> 
                    </div><!--container-fluid-->
                </header>
            
                <div class="navbar-slider">
                    <ul class="nav nav-sidebar">
                        <li class="active"><a class="nav-title" href="#">Home</a></li>
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
            @if($plan)
                <div class="section p-0">
                    <div class="container">
                        <form class="card card-form infos" method="post" action="#">  
                            <div class="card-body w-100">
                                <div class="d-block text-center">
                                    <h1 class="title-md">Term Life</h1>
                                    <p class="fs-18 mt-1" style="color: #636363;">Monthly payment</p>
                                </div>
                                
                                <div class="card-article">
                                    <div class="scrollbar-inner">
                                    @php echo $plan->term  @endphp
                                    </div><!--scrollbar-inner--> 
                                </div>
            
                                <div class="boxed text-center my-4 pt-2" style="--max-width:505px">
                                    <h2 class="title-sm mb-4">Choose your premium </h2>
            
                                    <div class="row button-price-lists">
                                        <div class="col-3">
                                            <button class="btn btn-outline price active" type="button">
                                                <span>${{$plan->annualprice}}</span>
                                            </button>
                                        </div>
            
                                        <div class="col-3">
                                            <button class="btn btn-outline price" type="button">
                                                <span>${{$plan->monthprice}}</span>
                                            </button>
                                        </div>
            
                                        
            
                                      
            
                                        
            
                                        
            
                                        
                                    </div>
            
                                    <div class="buttons mt-sm-2 flex-column gap-3">
                                        <p class="m-0 fs-14" style="color: #636363;">You choose : Monthly Term Life $50</p>
                                        <a class="btn btn-primary w-200 fs-18"  href="https://tawk.to/chat/66b6f6df0cca4f8a7a742387/1i4tb8mlf">
                                            <span>Live chat</span>
                                            </a>
            
                                        <a class="btn btn-link fs-18 mt-3" href="your-result.html">
                                            <span>Back</span>
                                        </a>
                                    </div>
            
                                </div>
                            </div><!--card-body-->
                        </form><!--card-form-->
                    </div><!--container-->
                </div><!--section-->
            @endif
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
    @endsection        
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
            
                $('.btn.price').on('click', function(){
                    $('.btn.price').removeClass('active');
                    $(this).addClass('active');
                });
              
            </script>
             
            </body>


