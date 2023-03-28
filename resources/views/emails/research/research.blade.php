@component('mail::spud_template')
    <p style="color: #00008B;"><b>Physical ID Request</p>
    <p>{{ $data['name']}} is Requesting Physical ID</p><br>
    <p>Grade Name: {{ $data['branch_account']['grade_level_info']['grade_name']}}</p>
    <p>Campus: {{ $data['branch_name']}}</p>
@endcomponent