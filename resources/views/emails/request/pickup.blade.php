@component('mail::layout')

    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img height="150" src="{{$img}}"/>
        @endcomponent
    @endslot

    <p style="color: #00008B;"><b>Physical ID is ready for pickup</p>
    <p>Good Day, Mr/Ms. {{ $data['firstname']}}  {{ $data['lastname']}}!</p><br>
    <p>We would like to inform you that your school ID is now ready for pickup.</p><br>
    <p>Your physical id is ready for pickup on {{ date("F jS, Y g:i a", strtotime($date)) }}</p><br>
    <p>Thank you.</p>

    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
        @endcomponent
    @endslot

@endcomponent