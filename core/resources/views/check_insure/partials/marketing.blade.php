<div class="section section-slogan" data-aos="fade-in">
    <div class="container">
        <h3 class="text-white textrow"><span data-aos="fade-up">Enjoy the life. Enjoy your loves</span></h3>
    </div>
</div>

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
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <img class="img-to" data-aos="fade-up" src="{{ asset('check-insure/img/thumb/photo-1000x480.jpg') }}" alt="">
            </div>
        </div>
    </div>
</div>

<div class="section section-followus pb-0">
    <div class="container">
        <div class="followus-boxed" data-aos="fade-in">
            <img class="icons question" data-aos="fade-up" src="{{ asset('check-insure/img/icons/icon-question.png') }}" alt="">
            <h2 class="text-white">@lang('Do you need help from an expert?')</h2>
            @include('partials.site_contact_links', [
                'variant' => 'default',
                'showEmail' => true,
                'showLabel' => false,
            ])
        </div>
    </div>
</div>
