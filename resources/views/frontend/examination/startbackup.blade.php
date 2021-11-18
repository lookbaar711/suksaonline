@extends('frontend/default')

<?php
    if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
        App::setLocale('en');
        $lang = 'en';
    }
    else{
        App::setLocale('th');
        $lang = 'th';
    }
?>
@section('css')
<link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/css/profile.css') !!}">

@endsection

@section('content')
<section class="p-t-50 p-b-30">

    <div align="center" style="margin-top : 30px; margin-bottom : 30px;">
        <i class="fa fa-book hat fa-3x" style="color: aquamarine;"></i>
        <br>
        <h3>@lang('frontend/members/title.examination_start')</h3>
    </div>
    <div class="container">
        <div class="card-content">
            <div class="row p-b-2  p-t-20 p-l-10">
                <div class="col-sm-2">

                 <div class="circle-grid-profile">
                    <a href="">
                        <img id="myImage" class="circular_image" src="{{ asset ('suksa/frontend/template/images/icons/imgprofile_default.jpg') }}" onerror="this.onerror=null;this.src='http://localhost:8000/suksa/frontend/template/images/icons/blank_image.jpg';" style="background-size: cover; width: 100%;">
                    </a>
                </div>
            </div>
            <div class="col-sm-9  p-t-20">
                <p class="free3" id="course_name">Training Suksa Online</p>
                <p>คอร์สเรียน</p>
                <hr>
                aptitude_name_th,subject_name_th
                <p>ระดับชั้น,วิชา</p>
                02/04/2020, 15:35-19:55
                <p>วันที่เปิดสอน</p>
                <a href="">
                <p id="teacher" style="color: black;">Dev Dev</p>
                </a>
                <p>@lang('frontend/courses/title.course_owner')</p>
            </div>
        </div>
    </div>
    <div align="center" style="margin-top : 30px; margin-bottom : 30px;">
        <h3>เริ่มทำแบบทดสอบ</h3>
    </div>

</section>
@stop
