@component('mail::layout')

    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img height="150" src="{{$img}}"/>
        @endcomponent
    @endslot

    <p style="color: #00008B;"><b>Physical ID Request</p>
    <p style="margin-bottom:6px;">Good Day, Mr/Ms. {{ $data['name']}}</p><br>
    <p style="margin-bottom:6px;">This is to acknowledge receipt of your request for student ID Below are the steps to guide you with process.</p>
    <p style="margin-bottom:3px;">1) Login to your child's School Information System (SIS) Portal</p>
    <p style="margin-bottom:3px;">2) Click The <span style="color:red">Payement tab</span> and select <span style="color:red">ID Fee</span></p>
    <p style="margin-bottom:3px;">3) Pay via OBMC accredited banks.</p>
    <p style="margin-bottom:3px;">4) Upload the proof of payment through your child's portal</p>
    <p style="margin-bottom:3px;">5) Wait for SMS notification receipt of your request</p><br><br>
    <p>Thank you.</p>

    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
        @endcomponent
    @endslot

@endcomponent