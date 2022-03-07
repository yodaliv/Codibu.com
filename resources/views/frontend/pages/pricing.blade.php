<div class="pricing-wrapper">
    <div class="container">
        <div class="row justify-content-center " id="pricing">
            <div class="pricing-heading text-center">
                <!--  <p class="text-uppercase">Pricing</p>-->
                <h1>Plans and Pricing</h1>
                <p>Explore codibu for free and choose a plan which is the best fit for you. Enjoy building amazing
                    website in a great wordpress environment with codibu. </p>
            </div>
            @foreach ($plans as $plan)
                <div class="pricing-table border-gradient {{ $loop->index ==0 ? 'active-gradient':'' }} ">
                    <h5>Start with</h5>
                    <div class="price">
                        $ {{ $plan->price }} <sub>/month</sub>
                    </div>
                    <h2>{{ $plan->name}}</h2>
                    <div class="list d-flex flex-column">
                        <h3><img src="{{ asset('images/icon/check-gradient.svg') }}" alt="">{{optional($plan->bundle)->cpuCount}} Core Processor</h3>
                            <h3><img src="{{ asset('images/icon/check-gradient.svg') }}" alt="">{{optional($plan->bundle)->ramSizeInGb}}GB Memory</h3>
                            <h3><img src="{{ asset('images/icon/check-gradient.svg') }}" alt="">{{optional($plan->bundle)->diskSizeInGb}} GB SSD Disk</h3>
                            <h3><img src="{{ asset('images/icon/check-gradient.svg') }}" alt="">{{optional($plan->bundle)->transferPerMonthInGb}} Transfer</h3>
                    </div>
                    <h3>
                        Plugins & themes: <br>
                        <b>{{$plan->download_limit}} downloads</b> per month
                    </h3>
                    <div class="free-trail-search">
                        <a href="" class="bg-sky hover-animation shadow">Get started</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="align-items-center d-flex payment-method-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-1 d-xl-block d-none"></div>
                <div class="col-xl-5 col-md-6 col-sm-12 mb-4 mb-md-0">
                    <div class="d-flex">
                        <div class="img">
                            <img src="{{ asset('images/payment/card.png') }}" alt="card">
                        </div>
                        <div class="text">
                            <h3>Accepted Payment Method</h3>
                            <div class="d-flex justify-content-between flex-wrap">
                                <img class="me-3 mb-3" src="{{ asset('images/payment/visa.png') }}" alt="card">
                                <img class="me-3 mb-3" src="{{ asset('images/payment/master.png') }}" alt="card">
                                <img class="me-3 mb-3" src="{{ asset('images/payment/paypal.png') }}" alt="card">
                                <img class="me-3 mb-3" src="{{ asset('images/payment/stripe.png') }}" alt="card">
                            </div>
                            <div class="border-bottom d-block d-md-none pt-3"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="d-flex">
                        <div class="img">
                            <img src="{{ asset('images/payment/ssl.png') }}" alt="card">
                        </div>
                        <div class="text">
                            <h3>SSL Secure Payment</h3>
                            <p>
                                We offer you SSL secure payment method. Your data is secured by most secure payment
                                gateway.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const pricingBox = document.querySelectorAll('.pricing-table');
    pricingBox.forEach((ele) => {
        ele.addEventListener('mouseover', function (e) {
            pricingBox.forEach(item => {
                item.classList.remove('active-gradient')
            })
            ele.classList.add('active-gradient');
        })
    })
</script>