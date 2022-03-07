<div class="contact-wrapper" id="contact">
    <div class="d-flex flex-wrap">
        <div class="left-item">
            <p>CONTACT US</p>
            <h1>Get in touch</h1>
            <p>
                We would love to hear from you! Contact us and we will get back to you within&nbsp<b>24&nbsphours.</b>
            </p>

        </div>
        <div class="right-item ms-auto">
            <h3>Send us a message</h3>

            <form action="">
                <div class="group-input">
                    <label for="full-name" class="form-label">Full Name*</label>
                    <div class="border-gradient">
                        <input type="text" class="control-form " id="full-name" placeholder="Your Name">
                        <img class="position-icon cursor-pointer"
                             src="{{ asset('images/icon/check-gradient.svg') }}" alt="">
                    </div>
                </div>
                <div class="group-input">
                    <label for="email" class="form-label">Email address*</label>
                    <div class="border-gradient">
                        <input type="email" class="control-form " id="email" placeholder="yourmail@mail.com">
                        <img class="position-icon cursor-pointer"
                             src="{{ asset('images/icon/check-gradient.svg') }}" alt="">
                    </div>
                </div>
                <div class="group-input">
                    <label for="message" class="form-label">Message*</label>
                    <div class="border-gradient">
                        <textarea name="message" class="control-form" id="message" placeholder="write your message here&nbsp;..."></textarea>
                        <img class="position-icon cursor-pointer"
                             src="{{ asset('images/icon/check-gradient.svg') }}" alt="">
                    </div>
                </div>
                <div class="d-flex flex-wrap align-items-center group-input free-trail-search icon-insert">
                    <button class="bg-sky hover-animation" type="submit">
                        <img class="me-3" src="{{ asset('images/icon/send.svg') }}" alt="">
                        Send Message
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
