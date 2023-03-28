@extends('layouts.admin')

@section('css')
    @isset($css)
        @foreach($css as $c)
            <link href="{{ asset('public/css/'.$c.'.css') }}" rel="stylesheet" type="text/css" />
        @endforeach
    @endisset
@endsection

@section('content')
<admin-employees :branches="{{ json_encode($branches) }}"></admin-employees>
@endsection

@section('footer')
    @isset($js)
        @foreach($js as $j)
            <script src="{{ asset('public/js/'.$j.'.js') }}"></script>
        @endforeach
    @endisset
@endsection
