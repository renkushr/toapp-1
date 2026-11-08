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
.bg{
    background: url("{{ asset('assets/global/img/bg-app.png') }}") lightgray 50% / cover no-repeat;
    width: 390px;
height: 844px;
flex-shrink: 0;
}



</style>

@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="page-boxed bg">
   
     
        <div style="background-color:#ffffff;margin-top:156px;border-radius:30px">
        <div>
        <a href="{{ url()->previous() }}">
             <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none" style="margin-left:24px; margin-top:20px">
             <path d="M7 15H27C27.2652 15 27.5196 15.1054 27.7071 15.2929C27.8946 15.4804 28 15.7348 28 16C28 16.2652 27.8946 16.5196 27.7071 16.7071C27.5196 16.8946 27.2652 17 27 17H7C6.73478 17 6.48043 16.8946 6.29289 16.7071C6.10536 16.5196 6 16.2652 6 16C6 15.7348 6.10536 15.4804 6.29289 15.2929C6.48043 15.1054 6.73478 15 7 15Z" fill="#AEE1F4"/>
            <path d="M7.41402 16L15.708 24.292C15.8958 24.4798 16.0013 24.7344 16.0013 25C16.0013 25.2656 15.8958 25.5202 15.708 25.708C15.5202 25.8958 15.2656 26.0013 15 26.0013C14.7345 26.0013 14.4798 25.8958 14.292 25.708L5.29202 16.708C5.19889 16.6151 5.12501 16.5048 5.07459 16.3833C5.02418 16.2618 4.99823 16.1315 4.99823 16C4.99823 15.8685 5.02418 15.7382 5.07459 15.6167C5.12501 15.4952 5.19889 15.3849 5.29202 15.292L14.292 6.292C14.4798 6.10422 14.7345 5.99873 15 5.99873C15.2656 5.99873 15.5202 6.10422 15.708 6.292C15.8958 6.47977 16.0013 6.73445 16.0013 7C16.0013 7.26555 15.8958 7.52022 15.708 7.708L7.41402 16Z" fill="#AEE1F4"/>
        </svg>
        </a>
    </div>
         <div style="padding:50px">
           <div style="padding-top:20px">
             
                <span style="
font-family: Poppins;
font-size: 30px;
font-style: normal;
font-weight: 800;
line-height: normal;background: var(--light-blue, linear-gradient(180deg, #18ABCF 0%, #1E84F4 100%));
background-clip: text;
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;"> @lang('Recovery Password') </span>
              </div>
            <div>
          <p>@lang('Enter your registered email address')</p>
    </div>

    <form method="POST" action="{{ route('user.password.email') }}" class="verify-gcaptcha">
                    @csrf
                <div>
                    <div style="margin-bottom:40px;margin-top:40px" >
                        <label class="label">@lang('Email')</label>
                        <div style="margin-top:9px">
                        <input type="text"  name="value" value="{{ old('value') }}" required autofocus="off">
                        </div>
                    </div>
                         

                  <div style="color: #F4733B;

font-family: Poppins;
font-size: 12px;
font-style: italic;
font-weight: 500;
line-height: normal;text-align:right">
                
                   </div>

                    
                    <button type="submit" style="border-radius: 8px;width: 100%;margin-top:40px;
height: 60px;
flex-shrink: 0;
border: 1px solid var(--light-blue, #18ABCF);

background: var(--light-blue, linear-gradient(180deg, #18ABCF 0%, #1E84F4 100%));color: var(--white, #FFF);
font-family: Poppins;
font-size: 18px;
font-style: normal;
font-weight: 600;
line-height: normal;">@lang('Submit')</button>
                 
                       
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
