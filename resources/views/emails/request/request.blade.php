@component('mail::layout')

    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img height="150" src="{{$img}}"/>
        @endcomponent
    @endslot

    <p style="color: #00008B;"><b>Physical ID Request</p>
    <p>{{ $data['name']}} is Requesting Physical ID</p><br>
    @if ($data['type'] == 'Student')
    <p>Grade Name: {{ $data['branch_account']['grade_level_info']['grade_name'] }}</p>
    @endif
    @if ($data['type'] == 'Employee')
    <p>Position: {{ $data['position'] }}</p>
    @endif
    <p>Campus: {{ $data['branch_name']}}</p>

    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
        @endcomponent
    @endslot

@endcomponent