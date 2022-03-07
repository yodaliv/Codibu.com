@component('mail::message')
# Subject: Site <a href="https://{{$site->domain}}">{{$site->title}}</a> is due to expire in {{$diff}} days

Dear {{$site->user->name}},

This is a reminder that the site listed below is scheduled to expire soon.

Site Name - Expiry Date - Description

--------------------------------------------------------------

<a href="https://{{$site->domain}}">{{$site->domain}}</a> - {{\Carbon\Carbon::parse($site->lastPaymentHistory->end_date)->format('jS M Y')}} - Expires in {{$diff}} Days

To renew your site,
@component('mail::button', ['url' => url("/renew-payment?site_id={$site->id}")])
    Renew
@endcomponent

If you have any questions, please reply to this email. Thank you for using our site services.


Thanks,<br>
{{ config('app.name') }} Team
<br/>
<a href="https://codibu.com">www.codibu.com</a>
@endcomponent
