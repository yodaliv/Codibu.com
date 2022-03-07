
@component('mail::layout')
    @slot('header')
        @component('mail::header', ['url' => '#'])
            <h1 style="text-align:center">TopRankOn</h1>
        @endcomponent
    @endslot

    {!! $email_content !!}

    @slot('footer')
        @component('mail::footer')
            © {{date('Y')}} {{env('APP_NAME')}}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent
