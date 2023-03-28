<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ config('app.name', 'Laravel') }} - ID REQEST </title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <style>
    .header {
        position: absolute;
        top: -2%;
        left: 47%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
        width: 75%;
        color:white;
    }
    .caption {
        position: absolute;
        z-index: 999;
        margin: 0 auto;
        left: 21%;
        right: 0%;        
        text-align: left;
        top: 1%; /* Adjust this value to move the positioned div up and down */
        font-family: Arial,sans-serif;
        color: white;
        width: 75%; /* Set the width of the positioned div */
        line-height: 2px;
    }
    .address {
        position: absolute;
        top: 3%;
        left: 45%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
        color:white;
    }
     .grade_level {
        position: absolute;
        top: 6%;
        left: 55%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
        color:white;
        width: 50%;
    }
     .sy {
        position: absolute;
        top: 17%;
        left: 33%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
        color:black;
    }
     .std_img {
        position: absolute;
        top: 15%;
        left: 54.8%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
        color:black;
    }
    .centered {
        position: absolute;
        top: 23.6%;
        left: 54.7%;
        transform: translate(-50%, -50%);
        color:white;
    }
    .name {
        position: absolute;
        top: 27%;
        left: 51.3%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
        width: 40%;
    }
    .branch {
        position: absolute;
        top: 30%;
        left: 63%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
    }
    .site {
        position: absolute;
        top: 34%;
        left: 46%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
        color:white;
    }
    .container {
        position: relative;
        text-align: center;
        color: white;
        font-family: Arial,sans-serif;
    }


    .header_back {
        position: absolute;
        top: -3%;
        left: 49%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
        width: 75%;
        color:white;
    }

    .address_back {
        position: absolute;
        top: 19%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
    }

    .mobile {
        position: absolute;
        top: 24%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
    }
    .text1 {
        position: absolute;
        z-index: 999;
        margin: 0 auto;
        left: 30%;
        right: 0;        
        text-align: left;
        top: 63%; /* Adjust this value to move the positioned div up and down */
        font-family: Arial,sans-serif;
        color: black;
        width: 54%; /* Set the width of the positioned div */
        line-height: 2px;
        height: 50px;
    }
    .signatory_name {
        position: absolute;
        z-index: 999;
        margin: 0 auto;
        left: 36%;
        right: 0;        
        text-align: left;
        top: 17%; /* Adjust this value to move the positioned div up and down */
        font-family: Arial,sans-serif;
        color: black;
        width: 54%; /* Set the width of the positioned div */
        line-height: 2px;
        height: 50px;
    }
    .registrar {
        position: absolute;
        z-index: 999;
        margin: 0 auto;
        left: 39%;
        right: 0;        
        text-align: left;
        top: 19%; /* Adjust this value to move the positioned div up and down */
        font-family: Arial,sans-serif;
        color: black;
        width: 54%; /* Set the width of the positioned div */
        line-height: 2px;
        height: 50px;
    }
    .digital_sig {
        position: absolute;
        z-index: 999;
        margin: 0 auto;
        left: 25.6%;
        right: 0;        
        text-align: left;
        top: -3.6%; /* Adjust this value to move the positioned div up and down */
        font-family: Arial,sans-serif;
        color: black;
        width: 75%; /* Set the width of the positioned div */
        line-height: 1px;
        height: 50px;
    }
    .digital_sig-1 {
        position: absolute;
        z-index: 999;
        margin: 0 auto;
        left: 25.7%;
        right: 0;        
        text-align: left;
        top: 15%; /* Adjust this value to move the positioned div up and down */
        font-family: Arial,sans-serif;
        color: black;
        width: 75%; /* Set the width of the positioned div */
        line-height: 1px;
        height: 50px;
    }
    .text {
        position: absolute;
        z-index: 999;
        margin: 0 auto;
        left: 14%;
        right: 0;        
        text-align: left;
        top: 0%; /* Adjust this value to move the positioned div up and down */
        font-family: Arial,sans-serif;
        color: black;
        width: 60%; /* Set the width of the positioned div */
        line-height: 2px;
    }
    .text h2 {
         text-align: center;
         color:red;
    }
    .qr {
        position: absolute;
        z-index: 999;
        margin: 0 auto;
        left: 0%;
        right: 0;        
        text-align: left;
        top: 22.6%; /* Adjust this value to move the positioned div up and down */
        font-family: Arial,sans-serif;
        width: 90%; /* Set the width of the positioned div */
        line-height: 1px;
    }
    
    .emp-qr {
        position: absolute;
        z-index: 999;
        margin: 0 auto;
        left: -5%;
        right: 0;        
        text-align: left;
        top: 29.4%; /* Adjust this value to move the positioned div up and down */
        font-family: Arial,sans-serif;
        width: 75%; /* Set the width of the positioned div */
        line-height: 1px;
    }
    .emp-telno {
        position: absolute;
        top: 2%;
        left: 35%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
        color:black;
    }


    .emp-telno-1 {
        position: absolute;
        top: 4.5%;
        left: 35%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
        color:black;
    }
    .emp-info {
        position: absolute;
        top: 7%;
        left: 48%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
        color:white;
    }
    .emp-sss {
        position: absolute;
        top: 9.5%;
        left: 30%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
        color:black;
    }

    .emp-tin {
        position: absolute;
        top: 11.5%;
        left: 29.5%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
        color:black;
    }
    .emp-blood {
        position: absolute;
        top: 13.5%;
        left: 34%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
        color:black;
    }
    .emp-incase {
        position: absolute;
        top: -2%;
        left: 48%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
        color:white;
    }
        </style>
    </head>
    <body>
        @if ($data['grade_name'] == 'GRADE 11' || $data['grade_name'] == 'GRADE 12')
        <div class="container">
            <div class="col">
                <img src="{{ $data['id_front_path']}}" alt="Snow" style="width: 270px; height: 408px;">
                <div class="address" style="top:4%!important;left:47%!important;"><span style="font-size:12.5px;color:black;">{{ $data['campus'] }}</span></div>
                <div class="grade_level"style="left:48%!important;"><span style="font-size:13px;color:black;">{{ isset($data['grade_name']) ? $data['grade_name'] : $data['position'] }}</span></div>
                <div class="sy" style="top:30%!important;">SY 2021-2022</div>
                <div class="std_img" style="top:13.8%!important;left:47.7%!important;"><img style="object-fit:cover;" src="{{ $data['std_pic'] }}" width="120" height="120"/></div>
                <div class="centered" style="color:black;top:21%!important;left:48%!important;margin-bottom:12px;"><span style="font-size:14px;">{{ $data['id'] }}</span></div>
                <div class="name"style="top:24.5%!important;left:48%!important;"><span style="font-size:14px; color: black !important;">{{ $data['fullname'] }}</span></div>
                <div class="branch"style="left:63%!important;"><span style="font-size:15px; color: #005477 !important;">{{ $data['campus'] }}</span></div>
            </div>
            <br>
        </div>
        @else
        <div class="container">
            <div class="col">
                <img src="{{ $data['id_front_path']}}" alt="Snow" style="width: 270px; height: 408px;">
                <div class="header">OB MONTESSORI CENTER</div>
                <div class="caption"><span style="font-size:11px;">The Pioneer of Montessori Education in the Philippines</span></div>
                <div class="address"><span style="font-size:12.5px;">{{ $data['campus'] }}</span></div>
                <div class="grade_level"><span style="font-size:12px;">{{ isset($data['grade_name']) ? $data['grade_name'] : $data['position'] }}</span></div>
                @if ($data['type'] == 'Employee')
                <div class="sy">SINCE 1966</div>
                @else
                <div class="sy">SY 2021-2022</div>
                @endif
                <div class="std_img"><img style="object-fit:cover;" src="{{ $data['std_pic'] }}" width="120" height="120"/></div>
                <div class="centered"><span style="font-size:14px;">{{ $data['id'] }}</span></div>
                <div class="name"><span style="font-size:14px; color: black !important;">{{ $data['fullname'] }}</span></div>
                <div class="branch"><span style="font-size:14px; color: #005477 !important;">{{ $data['campus'] }}</span></div>
                <div class="site"><span style="font-size:18px;">www.obmontessori.edu.ph</span></div>
            </div>
            <br>
        </div>
        @endif
        @if ($data['type'] == 'Employee')
        <div class="container">
            <div class="emp-incase"><span style="font-size:14px;">In case of emergency</span></div>
            <img src="{{ $data['id_back_path']}}" alt="back" style="width: 270px; height: 408px; margin-left:12px;">
            <div class="emp-telno"><span style="font-size:14px;">Telephone No</span></div>
            <div class="emp-telno-1"><span style="font-size:14px;">{{$data['mobile_number']}}</span></div>
            <div class="emp-info">Employee Detail</div>
            <div class="emp-sss"><span style="font-size:14px;">SSS</span></div>
            <div class="emp-tin"><span style="font-size:14px;">TIN</span></div>
            <div class="emp-blood"><span style="font-size:14px;">Blood Type</span></div>
            <div class="digital_sig-1">
                <img style="object-fit:cover;  display: none !important;" width="250" height="123" src="{{$data['back_content']}}"/>
            </div>
            <div class="emp-qr" style="text-align: center; ">
                <img style="border: 6px solid white;" src="data:image/svg;base64, {!! base64_encode(QrCode::format('svg')->errorCorrection('H')->size(50)->generate($data['id'])) !!}" />
            </div>
        </div>
        @else
        <div class="container">
            <img src="{{ $data['id_back_path']}}" alt="back" style="width: 273px; height: 408px;">
            <div class="digital_sig">
                <img style="object-fit:cover;" width="270" height="270" src="{{$data['back_content']}}"/>
            </div>
            <div class="qr" style="text-align: center; ">
                <img style="border: 6px solid white;" src="data:image/svg;base64, {!! base64_encode(QrCode::format('svg')->errorCorrection('H')->size(120)->generate($data['id'])) !!}" />
            </div>
        </div>
        @endif
    </body>
</html>
