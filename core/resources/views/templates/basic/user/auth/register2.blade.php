<style>
    .box{
    width: 100%;
height: 50px;
flex-shrink: 0;
border-radius: 8px;
background: #FFF;
border:none;
/* light */
box-shadow: 2px 1px 8px 2px rgba(128, 206, 255, 0.40);

    }
.label{
    color: var(--black, #1E1E1E);
font-family: Poppins;
font-size: 16px;
font-style: normal;
font-weight: 600;
line-height: normal;
}

.center{
    text-align:center;
}

.textbox{
    width: 139px;
height: 42px;
flex-shrink: 0;
}
.btn-outline2 {
    border:solid;
    border-width: 1px;
    border-color: #16243E;
    color: #16243E;
 
  
}

.btn.gender {
    width: 100%;
    gap: 5px;
    --btn-h: 42px;
    --bs-btn-border-color: #252525;
    --bs-btn-color: #252525;
    --bs-btn-hover-bg: #2AD4DB;
    --bs-btn-hover-color: #023D82;
    --bs-btn-hover-border-color: #023D82;
    --bs-btn-active-border-color: #023D82;
    --bs-btn-border-radius: 5px;
}
.bday{
width:100%;
height: 42px;
flex-shrink: 0;
border-radius:5px;

}
.zoom{

    zoom:100%;
}

@media screen and (max-width: 600px) {
    .bday{
width:100%;
height: 42px;
flex-shrink: 0;
border-radius:5px;

}

@media screen and (max-height: 667px) {
    .zoom{

        zoom:80%;
    }

}


@media screen and (max-height: 740px) {
    .zoom{

        zoom:80%;
    }

}

}
</style>

@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="page-boxed zoom">
   
     
        <div>
            <div style="text-align:left;margin-top:-75px" >
            <a href="{{ route('user.register') }}" aria-label="@lang('Back')">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none" style="margin-left:24px">
<path d="M7 15H27C27.2652 15 27.5196 15.1054 27.7071 15.2929C27.8946 15.4804 28 15.7348 28 16C28 16.2652 27.8946 16.5196 27.7071 16.7071C27.5196 16.8946 27.2652 17 27 17H7C6.73478 17 6.48043 16.8946 6.29289 16.7071C6.10536 16.5196 6 16.2652 6 16C6 15.7348 6.10536 15.4804 6.29289 15.2929C6.48043 15.1054 6.73478 15 7 15Z" fill="#AEE1F4"/>
<path d="M7.41396 16L15.708 24.292C15.8957 24.4798 16.0012 24.7345 16.0012 25C16.0012 25.2656 15.8957 25.5202 15.708 25.708C15.5202 25.8958 15.2655 26.0013 15 26.0013C14.7344 26.0013 14.4797 25.8958 14.292 25.708L5.29196 16.708C5.19883 16.6151 5.12494 16.5048 5.07453 16.3833C5.02412 16.2618 4.99817 16.1315 4.99817 16C4.99817 15.8685 5.02412 15.7382 5.07453 15.6167C5.12494 15.4952 5.19883 15.3849 5.29196 15.292L14.292 6.292C14.4797 6.10423 14.7344 5.99874 15 5.99874C15.2655 5.99874 15.5202 6.10423 15.708 6.292C15.8957 6.47977 16.0012 6.73445 16.0012 7C16.0012 7.26555 15.8957 7.52023 15.708 7.708L7.41396 16Z" fill="#AEE1F4"/>
</svg>
            </a>
<p style="background: var(--light-blue, linear-gradient(180deg, #18ABCF 0%, #1E84F4 100%));
background-clip: text;
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;text-align:center;font-family: Poppins;
font-size: 20px;
font-style: normal;
font-weight: 700;
line-height: normal;margin-top:-20px">@lang('Create Account')</p>

<p style="text-align:center;color: #252525;

font-family: Poppins;
font-size: 16px;
font-style: normal;
font-weight: 400;
line-height: normal;margin-top:-20px">@lang('Please fill your information')</p>
            </div>
         <div style="padding:5% 50px">
      
            <form class="" method="POST" action="{{ route('user.register2') }}" >
                    @csrf
                <div>
                    <div style="margin-bottom:40px;" >
                    <div class="form-profile">
                            <p class="text-center" style="color: #252525;"><strong>@lang('Gender')</strong></p>

                            <div class="row g-lg-4 g-sm-3 g-2" >
                                <div class="col-6 pay" data-sex="1">
                                    <div class="btn  gender" style="border:solid;border-width:1px" >
                                        <img class="icons svg-js" src="../../img/icons/icon-male.svg" alt="">
                                        <span>@lang('Male')</span>
                                        
</div>
                                </div>
                                
                                <div class="col-6 pay" data-sex="2">
                                    <div class="btn  gender" style="border:solid;border-width:1px" >
                                        <img class="icons svg-js" src="../../img/icons/icon-female.svg" alt="">
                                        <span>@lang('Female')</span>
                                       
</div>
                                </div>
                            </div>
                            <input type="hidden" name="sex" value="" />

                        <div style="margin-bottom:40px;margin-top:20px" >
                        <label class="label">@lang('Date Of Birth')</label>
                       

                      
                        <div class="row g-lg-4 g-sm-3 g-2" >
                                <div class="col-4 pay" data-sex="1">
                                  
                                    <select name="cars" class="bday"  id="cars">
                                         <option>@lang('MM')</option>
                                     </select>
                                        

                                </div>
                                
                                <div class="col-4 pay" data-sex="2">
                                
                                    <select name="cars" class="bday"  id="cars">
                                         <option>@lang('DD')</option>
                                     </select>
                                       

                                </div>

                                <div class="col-4 pay" data-sex="2">
                               
                                    <select name="cars" class="bday"  id="cars">
                                         <option>@lang('YYYY')</option>
                                     </select>
                                       

                                </div>



                    <div style="margin-bottom:12px;margin-top:25%" >
                        <label class="label">@lang('ZIP code')</label>
                        <div style="margin-top:9px">
                        <input   type="text" class="form--control box" name="zip"  required>
                         </div>
                    </div>
                    <div style="margin-bottom:12px" >
                        <label class="label">@lang('Last 4 SSN')</label>
                        <div style="margin-top:9px">
                        <input  type="text" class="form--control box" name="ssn" required>
                         </div>
                    </div>

                    <div style="margin-bottom:12px" >
                        <label class="label">@lang('Phone No.')</label>
                        <div style="margin-top:9px">
                        <input  type="text" class="form--control box" name="mobile" required>
                         </div>
                    </div>


                    <span style="color: #636363;

text-align: center;
font-family: Poppins;
font-size: 14px;
font-style: normal;
font-weight: 400;
line-height: normal;margin-top:20px;margin-top:25%">@lang('Last Step')</span>
                    
                    <button type="submit" style="border-radius: 8px;width: 100%;
height: 60px;
flex-shrink: 0;
border: 1px solid var(--light-blue, #18ABCF);

background: var(--light-blue, linear-gradient(180deg, #18ABCF 0%, #1E84F4 100%));color: var(--white, #FFF);
font-family: Poppins;
font-size: 18px;
font-style: normal;
font-weight: 600;
line-height: normal;">@lang('Set Your Password')</button>
                 
                       
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
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

    $('.pay').on('click', function(){
      // alert('select gender')
       $('.pay').removeClass('active');
       $(this).addClass('active');
       const sex = $(this).data('sex');
       $('[name="sex"]').val(sex);
   });
 
   
</script>
 
@endpush