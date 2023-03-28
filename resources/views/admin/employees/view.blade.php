@extends('layouts.admin')

@section('css')
    @isset($css)
        @foreach($css as $c)
            <link href="{{ asset('public/css/'.$c.'.css') }}" rel="stylesheet" type="text/css" />
        @endforeach
    @endisset
@endsection

@section('content')
<admin-view-employee :employee="{{ json_encode($employee) }}"
    :education-types="{{ json_encode($education_types) }}"
    :religions="{{ json_encode($religions) }}"
    :positions="{{ json_encode($positions) }}"
    :divisions="{{ json_encode($divisions) }}"
    :departments="{{ json_encode($departments) }}"
    :sections="{{ json_encode($sections) }}">
</admin-view-employee>
@endsection

@section('footer')
    @isset($js)
        @foreach($js as $j)
            <script src="{{ asset('public/js/'.$j.'.js') }}"></script>
        @endforeach
    @endisset
@endsection
