@extends('layouts.admin')

@section('css')
    @isset($css)
        @foreach($css as $c)
            <link href="{{ asset('public/css/'.$c.'.css') }}" rel="stylesheet" type="text/css" />
        @endforeach
    @endisset
@endsection

@section('content')
    <id-setup />
@endsection

@section('footer')
    @isset($js)
        @foreach($js as $j)
            <script src="{{ asset('public/js/'.$j.'.js') }}"></script>
        @endforeach
    @endisset
@endsection
