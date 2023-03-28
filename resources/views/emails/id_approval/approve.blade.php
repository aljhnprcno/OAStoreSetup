
@component('mail::layout')

    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img height="150" src="{{$img}}"/>
        @endcomponent
    @endslot

    <p style="color: #00008B;"><b>Physical ID Request has been Approved!</b></p>
    <p>Good Day, Mr/Ms. {{ $data['firstname']}} {{ $data['lastname']}}!</p><br>
    <p>Please be informed that your request for student ID has been approved and now in process. Our Campus representative will comunicate with you as soon as the ID becomes available and Ready for pickup.</p>
    <br><br>
    <p>Thank you.</p>

    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
        @endcomponent
    @endslot

@endcomponent