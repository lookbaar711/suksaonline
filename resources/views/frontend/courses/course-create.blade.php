@extends('frontend.default')

<?php
    if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
        App::setLocale('en');
    }
    else{
        App::setLocale('th');
    }
?>

@section('css')
    <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/css/homepage.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/css/course-create.css') !!}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="{{ asset ('suksa/frontend/template/js/cleave.min.js') }}"></script>
    <style type="text/css">
      .date-icon {
        background: url(../../suksa/frontend/template/images/147083.png) no-repeat right 5px center;
        background-color: #FFFFFF;
      }
      .time-icon{
        background: url(../../suksa/frontend/template/images/icons/time.png) no-repeat right 5px center;
        background-color: #FFFFFF;
      }
    </style>

<!-- เวลา -->
            <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
            <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
            <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />

            <!-- วัน/เดือน/ปี พร้อม เวลา -->
            <!-- <link href="{!! asset ('suksa/frontend/template/css/bootstrap-datetimepicker.css') !!}" rel="stylesheet" type="text/css">
            <link href="{!! asset ('suksa/frontend/template/css/bootstrap-datetimepicker.min.css') !!}" rel="stylesheet" type="text/css"> -->
            <!-- วัน/เดือน/ปี พร้อม เวลา -->
<!-- เวลา -->

    @endsection
@section('content')
<style>
    @keyframes ldio-rpinwye8j0b {
   0% { transform: rotate(0deg) }
   50% { transform: rotate(180deg) }
   100% { transform: rotate(360deg) }
 }
 .ldio-rpinwye8j0b div {
   position: absolute;
   animation: ldio-rpinwye8j0b 1s linear infinite;
   margin: auto;
   width: 160px;
   height: 160px;
   top: 30%;
   left: 43%;
   z-index: 100;
   border-radius: 50%;
   box-shadow: 0 4px 0 0 #569c37;
   transform-origin: 80px 82px;
 }
 .loadingio-eclipse {

     width: 100%;
     height: 100%;
     overflow: hidden;
     top: 0;
     left: 0;
     position: fixed;
     display: block;
     opacity: 0.7;
     margin-left: auto;
     margin-right: auto;
     background-color: rgba(115, 115, 115, 0.7);
     z-index: 99;
     text-align: center;
 }
 .ldio-rpinwye8j0b {
   width: 100%;
   height: 100%;
   position: relative;
   transform: translateZ(0) scale(1);
   backface-visibility: hidden;
   transform-origin: 0 0; /* see note above */
 }
 .ldio-rpinwye8j0b div { box-sizing: content-box; }
 @media (max-width: 1440px){
        .ldio-rpinwye8j0b div {
            position: absolute;
            animation: ldio-rpinwye8j0b 1s linear infinite;
            margin: auto;
            width: 160px;
            height: 160px;
            top: 50%;
            left: 45%;
            z-index: 100;
            border-radius: 50%;
            box-shadow: 0 4px 0 0 #569c37;
            transform-origin: 80px 82px;
        }
    }
 @media (max-width: 991px){
        .ldio-rpinwye8j0b div {
            position: absolute;
            animation: ldio-rpinwye8j0b 1s linear infinite;
            margin: auto;
            width: 160px;
            height: 160px;
            top: 50%;
            left: 38%;
            z-index: 100;
            border-radius: 50%;
            box-shadow: 0 4px 0 0 #569c37;
            transform-origin: 80px 82px;
        }
    }
 @media (max-width: 414px){
        .ldio-rpinwye8j0b div {
            position: absolute;
            animation: ldio-rpinwye8j0b 1s linear infinite;
            margin: auto;
            width: 160px;
            height: 160px;
            top: 50%;
            left: 27%;
            z-index: 100;
            border-radius: 50%;
            box-shadow: 0 4px 0 0 #569c37;
            transform-origin: 80px 82px;
        }
    }
    .btn-block-uplode{
      background: linear-gradient(to right, #17a7c0 0%, #7de442 100%);
      justify-content: center;
      align-items: center;
      border-color: #fefeff;
      color: white;
    }
</style>
@php

      $c_price='';
      $s_limit='';
      $name_course='';
      $details='';
      $img_course='';
      $files='';
      $g_course='';
      $status_course='';
      $subject_status='';
      $datepicker_1=[];
      $datepicker_3=[];
      $datepicker_3=[];
      $pubblic_privete='';
      $student_course=[];
      $id='';

  // -------------------------------------------------------------------//
    if(!empty($course)){
      $c_price= $course['course_price'];//ราคา
      $s_limit= $course['course_student_limit'];//จำกัดนักเรียน
      $name_course= $course['course_name'];//ชื่อคอร์ส
      $details= $course['course_detail'];//ข้อมูล detail
      $img_course= $course['course_img'];//ภาพปก
      $files= $course['course_file'];//ไฟล์
      $g_course= $course['course_group'];//เลือกกลุ่มวิชา
      $status_course= $course['course_status'];//เลือกเปิด
      $subject_status= $course['course_subject'];//เลือกกลุ่มการศึกษา
      $datepicker_1= $course['course_date'];//เลือกเวลา
      $datepicker_2= $course['time_start'];//เลือกเวลา
      $datepicker_3= $course['time_end'];//เลือกเวลา
      $pubblic_privete=$course['course_category'];//เลือกเวลา pubblic_private
      $student_course=$course['course_student'];//เพิ่มนักเรียน
      $id=$course['_id'];

    }

@endphp
<div class="container">


<section class="p-t-20 p-b-20">
  <div class="container">
    <div class="row">
      <div class="col"></div>
      <div class="col-sm"></div>
      <div class="col-sm">
        <label style="text-align: right;  font-size: 18px;" ><a href="{{ url('courses/all/') }}" style="font-size: 18px;">@lang('frontend/courses/title.course_online') / </a><label style="color: darkgray;">@lang('frontend/courses/title.create_course_button')</label>
      </div>
    </div>
  </div>
</section>
<meta name="csrf-token" content="{{ csrf_token() }}">

<section class="p-b-65">
  <div class="container">
    <div class="tab-content" id="myTabContent">
        @if (!empty($id))
        <form class="box1" name="form1" action="{{ url('courses/course_edit') }}" method="POST" onSubmit="JavaScript:return fncSubmit();" enctype="multipart/form-data">
          {{ method_field('PUT') }}
          <input type="hidden" name="id" value="{{$id}}">
        @else
        <form class="box1" name="form1" action="{{ url('courses') }}" method="POST"  onSubmit="JavaScript:return fncSubmit();" enctype="multipart/form-data">
        @endif
          {{ csrf_field() }}

           <label class="profile01" style="font-size: 30px; font-weight: bold; color: #6ab22a;">@lang('frontend/courses/title.create_course')</label>
           <div class="form-row">
              <label class="col-sm-12 p-t-12">@lang('frontend/courses/title.select_course_type') :</label>
              <div class="form-group col-sm-12 radio-toolbar p-l-0">
                    <input type="radio" id="radioPublic" name="course_category" class="public course_category" onchange="dis(); showc1();
                    setVisibility('sub3', 'none');
                    setVisibility('sub4', 'none');
                    setVisibility('sub5', 'none');
                    setVisibility('sub6', 'inline');
                    setVisibility('sub7', '');
                    setVisibility('sub8', 'none');
                    setVisibility('course_private_add_studen', 'none');
                    setVisibility('text_specify_email', 'none');
                    setVisibility('text_specify_school', 'none');
                    setVisibility('course_school_add_studen', 'none');"
                    value="Public" required  {{($pubblic_privete == 0) ? 'checked' : '' }}>
                    <label for="radioPublic" id="colorfafa" style="color:#ffffff"  ><i class="fa fa-globe fa-lg "  aria-hidden="true"></i>&nbsp;@lang('frontend/courses/title.course_public')</label>

                    <input type="radio" id="radioPrivate" name="course_category" class="private course_category"  onchange="dis2(); showc2();
                    setVisibility('sub3', 'none');
                    setVisibility('sub4', 'inline');
                    setVisibility('sub5', 'inline');
                    setVisibility('sub6', 'none');
                    setVisibility('sub7', '');
                    setVisibility('sub8', 'none');
                    setVisibility('course_private_add_studen', 'inline');
                    setVisibility('text_specify_email', 'inline');
                    setVisibility('text_specify_school', 'none');
                    setVisibility('course_school_add_studen', 'none');"
                    value="Private" required  {{($pubblic_privete == 1) ? 'checked' : '' }}>
                    <label for="radioPrivate" id="colorfafa2"  ><i class="fa fa-lock fa-lg"  aria-hidden="true" ></i>&nbsp;@lang('frontend/courses/title.course_private')</label>

                    <input type="radio" id="radioSchool" name="course_category" class="school course_category"  onchange="dis3(); showc3();
                    setVisibility('sub3', 'inline');
                    setVisibility('sub4', 'none');
                    setVisibility('sub5', 'inline');
                    setVisibility('sub6', 'none');
                    setVisibility('sub7', 'none');
                    setVisibility('sub8', '');
                    setVisibility('course_private_add_studen', 'none');
                    setVisibility('text_specify_email', 'none');
                    setVisibility('text_specify_school', 'inline');
                    setVisibility('course_school_add_studen', 'inline');"
                    value="School" required  {{($pubblic_privete == 2) ? 'checked' : '' }}>
                    <label for="radioSchool" id="colorfafa3"  ><i class="fa fa-school fa-lg"  aria-hidden="true" ></i>&nbsp;School</label>
              </div>
            </div>

            <div class="form-row">
              <div class="form-row col-sm-12" id="sub8" style="display: none;">
                  <label class="profile01 col-sm-auto">@lang('frontend/courses/title.school') :</label>
                  <div class="form-group col-sm-auto">
                    <label>
                        @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                          {{ ($school_detail['school_name_en'] != '')?$school_detail['school_name_en'] : '-' }}
                        @else
                          {{ ($school_detail['school_name_th'] != '')?$school_detail['school_name_th'] : '-' }}
                        @endif
                    </label>
                  </div>
                  <input type="hidden" name="member_school" id="member_school" value="{{ $school_detail['school_id'] }}" />
              </div>
            </div>
            @php
              // dd(collect($school_detail['school_student'])->sortBy('student_room')->groupBy('student_classroom'));
            @endphp
           <div class="col-sm-10 p-l-0" id="sub5" style="display: none;">
             <div id="myRepeatingFields6">
              <label class="col-md control-label p-l-0" id="text_specify_email">@lang('frontend/courses/title.specify_email') : <span style="color: red; font-size: 20px;" >*</span></label>
              <label class="col-md control-label p-l-0" id="text_specify_school">@lang('frontend/courses/title.specify_school') : <span style="color: red; font-size: 20px;" >*</span></label>
              <div class="entry" id="course_private_add_studen">
                <div class="form-row">
                    <div class="form-group col-md-6" >
                        <input type="email" name="course_student[]" class="course_student form-control" id="emailstudents" name="emailstudents"  aria-describedby="emailHelp"placeholder="@lang('frontend/courses/title.enter_email')">
                        <div id="listmail"></div>
                    </div>
                    <div class="form-group col-md-1 p-t-0" style="padding-left: 0;"  >
                        <button type="button" class="btn btn-dark btn-md btn-add6 form-control">@lang('frontend/courses/title.add_button') +</button>
                    </div>
                </div>
              </div>

              <div class="entry" id="course_school_add_studen">
                <div class="form-row" >
                    <div class="form-group col-md-6" >
                      <select class="form-control required edit-group col-sm-10" name="select_student_level" id="select_student_level" style=" text-align: center; text-align-last: center; padding-top: 4px;" >
                        @foreach (collect($school_detail['school_student'])->sortBy('student_classroom')->groupBy('student_classroom') as $key => $value)
                          <option value="{{ $value }}">{{ $key }}</option>
                        @endforeach
                      </select>
                      {{-- <input type="hidden" name="student_detail" value="{{ collect($school_detail['school_student'])->sortBy('student_classroom')->groupBy('student_classroom') }}"> --}}

                        {{-- <input type="email" name="course_student[]" class="course_student form-control" id="emailstudents" name="emailstudents"  aria-describedby="emailHelp"placeholder="@lang('frontend/courses/title.enter_email')"> --}}
                    </div>
                </div>
              </div>

               @foreach ($student_course as $i => $items)
                <div class="entry">
                  <div class="row">
                    <div class="form-group col-sm-6" >
                    <input type="email" name="course_student[]" class="form-control" id="emailstudents"name="emailstudents" aria-describedby="emailHelp" value="{{($items)}}">
                    </div>
                    <div class="form-group col-md-1 p-t-0" style="padding-left: 0;"  >
                      <button type="button" class="btn btn-danger  btn-remove  form-control" id="{{$i}}" style="width:90px;">@lang('frontend/courses/title.remove_button')</button>
                    </div>
                  </div>
                </div>
               @endforeach
             </div>



           </div>

           <div class="form-row">
              <div class="form-row col-sm-12" id="sub7">
                <label class="profile01 col-sm-auto">@lang('frontend/courses/title.study_fee') :</label>
                <div class="form-group col-sm-auto">
                    <label class="container-radio">@lang('frontend/courses/title.free_type')
                        <input type="radio" name="course_type" value="0" onclick="show1(); clearText();" required checked  {{ ($c_price == 0) ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                    </label>
                </div>

                <div class="form-group col-sm-auto">
                    <label class="container-radio">@lang('frontend/courses/title.fee_type')
                        <input type="radio" name="course_type" value="1" onclick="show2();" required min="1" {{ ($c_price > 0) ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                    </label>
                </div> &nbsp;&nbsp;

                <div class="form-group col-sm-3"id="div1" style="margin-top: -10px;">
                  @if($c_price > 0)
                      <input type="text" name="course_price" class="form-control " id="course_price" value="{!! $c_price !!}" placeholder="@lang('frontend/courses/title.enter_course_price')" data-type="currency">
                  @else
                      <input type="text" name="course_price" class="form-control " id="course_price" value="" placeholder="@lang('frontend/courses/title.enter_course_price')" data-type="currency" disabled>
                  @endif

                </div>
              </div>

              <div class="form-group col-sm-12" id="sub6">
                <label class="profile01">@lang('frontend/courses/title.number_of_students') : <span style="color: red; font-size: 20px;" >*</span></label>
                <input type="text"  class="form-control col-sm-4" name="course_student_limit"  id="student_limit" onKeyPress="CheckNumm();"  type="number"  value="{{($s_limit)}}" min="1" max="100" placeholder="0"  required>
              </div>
           </div>
           <hr>

           <div id="myRepeatingFields5">
              <div class="entry">
                <div class="form-row" >
                  <div class="form-group col-md-4" >
                      <label for="dtp_input1" class="col-md control-label p-l-0">@lang('frontend/courses/title.open_course_date') : <span style="color: red; font-size: 20px;" >*</span></label>
                     <input class="form-control daterange-input date-icon course_date" name="course_date[]" id="datepicker01" type="text"  placeholder="@lang('frontend/courses/title.select_open_course_date')" autocomplete="off"  >
                  </div>
                  <div class="form-group col-md-3">
                      <label for="dtp_input1" class="col-md control-label p-l-0">@lang('frontend/courses/title.start_time') : <span style="color: red; font-size: 20px;" >*</span></label>
                      <div class="input-group date form_datetime col-md-12 p-l-0" style="padding-right: unset;" data-date="1979-09-16T05:25:07Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                          <input class="form-control daterange-input2 time-icon start_time" size="16" id="datepicker02" type="text"  placeholder="@lang('frontend/courses/title.select_start_time')" autocomplete="off" name="time_start[]">
                          <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                         <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                      </div>
                     <input type="hidden" id="dtp_input1" value="" />
                  </div>
                  <div class="form-group col-md-3">
                      <label for="dtp_input1" class="col-md control-label p-l-0">@lang('frontend/courses/title.end_time') : <span style="color: red; font-size: 20px;" >*</span></label>
                      <div class="input-group date form_datetime col-md-12 p-l-0" style="padding-right: unset;" data-date="1979-09-16T05:25:07Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                          <input class="form-control daterange-input2 time-icon end_time" size="16" id="datepicker03" type="text"  placeholder="@lang('frontend/courses/title.select_end_time')"  autocomplete="off" name="time_end[]">
                          <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                         <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                      </div>
                  </div>
                  <div class="form-group col-md-2 p-t-0" style="padding-left: 0;"  >
                      <label class="profile01  p-t-0 p-b-0" > &nbsp; &nbsp; <span style="color: red; font-size: 20px;" >&nbsp;</span></label>
                      <button type="button" class="btn btn-dark btn-md btn-add5 form-control">@lang('frontend/courses/title.add_button')</button>
                  </div>
                </div>
              </div>
              @php
                $i = 0;
                $count_select_date = count($datepicker_1);
              @endphp
              @foreach ($datepicker_1 as $item)
              <div class="entry">
                  {{-- <div class="container"> --}}
                      <div class="form-row">
                          <div class="form-group col-md-4" >
                              <label for="dtp_input1" class="col-md control-label p-l-0">@lang('frontend/courses/title.open_course_date') : <span style="color: red; font-size: 20px;">*</span></label>
                             <input class="form-control daterange-input date-icon course_date" id="datepicker001"  name="course_date[]" type="text" value="{{date('d/m/Y', strtotime($item['date']))}}" placeholder="@lang('frontend/courses/title.select_open_course_date')" autocomplete="off" readonly>
                          </div>
                          <div class="form-group col-md-3">
                              <label for="dtp_input1" class="col-md control-label p-l-0">@lang('frontend/courses/title.start_time') : <span style="color: red; font-size: 20px;">*</span></label>
                              <div class="input-group date form_datetime col-md-12 p-l-0" style="padding-right: unset;" data-date="1979-09-16T05:25:07Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                              <input class="form-control daterange-input2 time-icon" size="16" id="datepicker002" type="text" placeholder="@lang('frontend/courses/title.select_start_time')" name="time_start[]" autocomplete="off"   value="{{date('H:i', strtotime($item['time_start']))}} " readonly>
                          </div>
                        </div>
                          <div class="form-group col-md-3">
                              <label for="dtp_input1" class="col-md control-label p-l-0">@lang('frontend/courses/title.end_time') : <span style="color: red; font-size: 20px;">*</span></label>
                              <div class="input-group date form_datetime col-md-12 p-l-0" style="padding-right: unset;" data-date="1979-09-16T05:25:07Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                              <input class="form-control daterange-input2 time-icon" size="16" id="datepicker003" type="text" placeholder="@lang('frontend/courses/title.select_end_time')" autocomplete="off" name="time_end[]"  value="{{date('H:i', strtotime($item['time_end']))}}" readonly>
                          </div>
                        </div>
                          <div class="form-group col-md-2">
                              <label class="profile01 p-t-0 p-b-0">&nbsp;&nbsp;<span style="color: red; font-size: 20px;">&nbsp;</span></label>
                              <button type="button" class="btn btn-danger btn-remove  form-control" >@lang('frontend/courses/title.remove_button')</button>
                          </div>
                      </div>
                  {{-- </div> --}}
              </div>
                @if($i == ($count_select_date-1))
                  <input type="hidden" name="last_end_hour" id="last_end_hour" value="{{ ((intval(date('H', strtotime($item['time_end'])))+1) == 24)?0:(intval(date('H', strtotime($item['time_end'])))+1) }}">
                  <input type="hidden" name="last_end_minute" id="last_end_minute" value="{{ intval(date('i', strtotime($item['time_end']))) }}">
                @endif

                @php
                  $i++;
                @endphp

              @endforeach

              @if(count($datepicker_1)==0)
                <input type="hidden" name="last_end_hour" id="last_end_hour">
                <input type="hidden" name="last_end_minute" id="last_end_minute">
              @endif
           </div>

           <div class="form-row">
              <label class="col-sm-12">@lang('frontend/courses/title.course_name') : <span style="color: red; font-size: 20px;" >*</span></label>
                <div class="form-group col-md-12">
                      <input type="text" name="course_name" id="course_name" class="form-control" placeholder="@lang('frontend/courses/title.enter_course_name')"   value="{{($name_course)}}"  required>
                 </div>
           </div>

           <div class="form-row">
              <div class="form-group col-sm-6">
                 <label class="profile01">@lang('frontend/courses/title.aptitude') : <span style="color: red; font-size: 20px;" >*</span></label>
                   @if (!empty($id))
                    <select class="form-control required edit-group col-sm-10" name="course_group" id="course_group" style=" text-align: center; text-align-last: center; padding-top: 4px;" onchange="subject(this.value)">
                      <option value="">@lang('frontend/courses/title.select')</option>

                      @foreach (array_keys($subject_detail) as $key)
                        @if(count($subject_detail[$key]) > 0)
                            @if($g_course == $key)
                              <option value="{{ $key }}" selected>
                            @else
                              <option value="{{ $key }}">
                            @endif

                            @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                              {{ $subject_detail[$key]['aptitude_name_en'] }}
                            @else
                              {{ $subject_detail[$key]['aptitude_name_th'] }}
                            @endif
                          </option>
                        @endif
                      @endforeach
                    </select>
                 @else
                   <select class="form-control required edit-group col-sm-auto" name="course_group" id="course_group" style=" text-align: center; text-align-last: center; padding-top: 4px;" onchange="subject(this.value)">
                      <option value="">@lang('frontend/courses/title.select')</option>

                      @foreach (array_keys($subject_detail) as $key)
                        @if(count($subject_detail[$key]) > 0)
                            @if($g_course == $key)
                              <option value="{{ $key }}" selected>
                            @else
                              <option value="{{ $key }}">
                            @endif

                            @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                              {{ $subject_detail[$key]['aptitude_name_en'] }}
                            @else
                              {{ $subject_detail[$key]['aptitude_name_th'] }}
                            @endif
                              </option>
                        @endif
                      @endforeach
                    </select>
                 @endif
              </div>

              <div class="form-group col-md-6" id="subject">
                  <label class="profile01">@lang('frontend/courses/title.subject') : <span style="color: red; font-size: 20px;" >*</span></label>
                  @if (!empty($id))
                  <select class="form-control" name="course_subject" id="course_subject" style=" text-align: center; text-align-last: center; padding-top: 4px;"  required>  <option value="{{ $subject_status}}" readonly>@lang('frontend/courses/title.select')</option>
                    @foreach ($subjects_list as $key => $value)
                      @if($subject_status == $key)
                        <option value="{{ $key }}" selected>
                      @else
                        <option value="{{ $key }}">
                      @endif

                      @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                        {{ $value['subject_name_en'] }}
                      @else
                        {{ $value['subject_name_th'] }}
                      @endif
                        </option>
                    @endforeach
                   </select>
                @else
                  <select class="form-control" name="course_subject" id="course_subject" style=" text-align: center; text-align-last: center; padding-top: 4px;" required>
                    <option value="{{ $subject_status}}">@lang('frontend/courses/title.select')</option>
                 </select>
                @endif
              </div>
           </div>

           <div class="form-row">
             <label class="col-sm-12">@lang('frontend/courses/title.course_info') : <span style="color: red; font-size: 20px;" >*</span></label>
                <div class="form-group col-md-12">
                   <textarea class="form-control" name="course_detail" id="course_detail" rows="10" placeholder="@lang('frontend/courses/title.enter_course_info')" required>{{($details)}}</textarea>
                </div>
           </div>

           <div class="form-row">
              <div class="col-sm-6">
                  <label>@lang('frontend/courses/title.course_cover') : <span style="color: red; font-size: 20px;" >*</span></label>
                  <p>@lang('frontend/courses/title.course_cover_recommend') :</p>
                  @if (!empty($id))
                    <label for="file-upload" class="custom-file-upload btn btn-dark btn-block"style="text-align-last: center;" >&nbsp;@lang('frontend/courses/title.image_file') : {{$img_course}} </label>
                  @else
                    <label for="file-upload" class="custom-file-upload btn btn-dark btn-block"style="text-align-last: center;" ><i class="fa fa-upload" aria-hidden="true"></i>&nbsp;@lang('frontend/courses/title.upload_file') </label>
                  @endif
                  <input id="file-upload" name='course_img' type="file" style="display:none;"    accept="image/x-png,image/gif,image/jpeg">
                  <input  name='img2' type="hidden" style="display:none;" value="{{$img_course}}"  accept="image/x-png,image/gif,image/jpeg">
              </div>

              <div class="col-sm-6">
                  <label>@lang('frontend/courses/title.attach_file') : <span style="color: red; font-size: 20px;" >&nbsp;</span></label>
                  <p>@lang('frontend/courses/title.file_support') :</p>
                  @if (!empty($id))
                    <label for="course_file" class="custom-file-upload btn btn-dark btn-block" style="text-align-last: center;" >&nbsp;@lang('frontend/courses/title.doc_file') : {{$files}}</label>
                  @else
                    <label for="course_file" class="custom-file-upload btn btn-dark btn-block" style="text-align-last: center;" ><i class="fa fa-upload" aria-hidden="true"></i>&nbsp;@lang('frontend/courses/title.upload_file') </label>
                  @endif
                  <input id="course_file" name="course_file" type="file" style="display:none;"   accept="application/pdf,application/vnd.ms-powerpoint">
                  <input  name='file2' type="hidden" style="display:none;" value="{{$files}}"  accept="application/pdf,application/vnd.ms-powerpoint">
              </div>
           </div>

           <div class="col check_course">
              <input type="checkbox" class="option-input " id="check_course" name="course_status"  value="open" {{ ($status_course == "open") ? 'checked' : '' }}  ><span style="font-size: 20px;">@lang('frontend/courses/title.open_now')</span>
              <br><br>
           </div>

           <hr class="p-b-5">






           <div class="col-md-12" id="sub4" style="display: none;">
              <div class="col-sm-12 p-l-0 p-r-0" >
                  <p class="dotted" >
                      <i class="fa fa-warning" style="font-size:26px;color:red;padding-left: 10px;padding-top: 5px;"></i> &nbsp;<label style="font-size: 20px; color: #E23939; font-weight: bold;">@lang('frontend/courses/title.note')</label>
                    <br>
                      <label style="padding-left: 10px; color: black;">
                        1. @lang('frontend/courses/title.note_1')
                        <br> - @lang('frontend/courses/title.note_1_detail_1')
                        <br> - @lang('frontend/courses/title.note_1_detail_2_1')
                        <br> @lang('frontend/courses/title.note_1_detail_2_2')
                        <br> - @lang('frontend/courses/title.note_1_detail_3_1')
                        <br> @lang('frontend/courses/title.note_1_detail_3_2')
                      </label>
                  </p>
              </div>
           </div>

           <div class="form-row" id="sub3" style="display: none;">
              <div class="col-md-12">
                <label class="col-md control-label p-l-0">@lang('frontend/courses/title.pretest') : <span style="color: red; font-size: 20px;" >*</span>
                  <u><a href="{{ url('suksa/frontend/template/ex_course/สร้างเเบบทดสอบ.xlsx') }}" class="btn btn-donwload"><i class="fa fa-download" aria-hidden="true"></i> @lang('frontend/courses/title.sample_file')</a></u>
                </label>
              </div>

              <div class="form-row col-sm-6">
                <div class="col-sm-6">
                  <p>@lang('frontend/courses/title.The_file_only_supports') :</p>
                  <label for="quiz_file" class="custom-file-upload btn btn-dark btn-block" style="text-align-last: center;" ><i class="fa fa-upload" aria-hidden="true"></i>&nbsp;@lang('frontend/courses/title.upload_file') </label>
                  <input id="quiz_file" name="quiz_file" type="file" style="display:none;" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                </div>
                <div class="col-sm-6">
                  <p style="margin-top: 25px;"></p>

                  <button type="button" class="btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12" style="color: white; height: 38px;" onclick="up_quiz_file();">
                    <label class="colorz" style="margin-top: 10px;"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;@lang('frontend/courses/title.Import_data') </label>
                  </button>
                </div>
              </div>
              {{-- <div class="col-md-6">
                <label class="col-md control-label p-l-0">@lang('frontend/courses/title.The_file_only_supports') : <span style="color: red; font-size: 20px;" >*</span></label>
                <label for="quiz_file" class="custom-file-upload btn btn-dark btn-block" style="text-align-last: center;" ><i class="fa fa-upload" aria-hidden="true"></i>&nbsp;@lang('frontend/courses/title.upload_file') </label>
                <input id="quiz_file" name="quiz_file" type="file" style="display:none;" accept="application/vnd.ms-excel">
                <button type="button" class="btn btn-success flex-c-m size2 bo-rad-23 s-text3 bgwhite hov1 trans-0-5 col-12" onclick="up_quiz_file();">นำเข้าข้อมูล</button>

              </div> --}}

              <hr>
              @for ($i=0; $i < 10; $i++)
                @php
                  $y = 0;
                @endphp

                <div class="form-group row ">
                 <label for="staticEmail" class="col col-form-label">@lang('frontend/courses/title.no') {{ $i+1 }}.</label>
                 <div class="col-md-11">
                    <input name="image_question_{{ $i+1 }}" type="file" id="question_{{ $i+1 }}_upload" accept=".png, .jpg, .jpeg"  class="hidden" onchange="">
                    <textarea name="question_{{ $i+1 }}" id="question_{{ $i+1 }}"class="form-control question editorTiny" placeholder="@lang('frontend/courses/title.question') {{ $i+1 }}" ></textarea>

                 </div>
               </div>
               <div class="form-group row ">
                 <label class="col col-form-label"> </label>
                 <div class="col-md-11">
                   <div class="form-group">
                       <div class="row">
                         <div class="form-row col-md-6">
                           <div class="col-md-2">
                             <input class="question" type="radio" name="answer_{{ $i+1 }}" value="1" required> A
                           </div>
                           <div class="form-group col-md-10">
                             <input type="text" name="answer_text_{{ $i.$y+1 }}"   class="form-control question" autocomplete="off" placeholder="@lang('frontend/courses/title.answer') 1" required>
                           </div>
                         </div>
                         <div class="form-row col-md-6">
                           <div class="col-md-2">
                             <input class="question" type="radio" name="answer_{{ $i+1 }}" value="2" required> B
                           </div>
                           <div class="form-group col-md-10">
                             <input type="text" name="answer_text_{{ $i.$y+2 }}"   class="form-control question" autocomplete="off" placeholder="@lang('frontend/courses/title.answer') 2" required>
                           </div>
                         </div>
                       </div>
                       <div class="row">
                         <div class="form-row col-md-6">
                           <div class="col-md-2">
                             <input class="question" type="radio" name="answer_{{ $i+1 }}" value="3" required> C
                           </div>
                           <div class="form-group col-md-10">
                             <input type="text" name="answer_text_{{ $i.$y+3 }}"  class="form-control question" autocomplete="off" placeholder="@lang('frontend/courses/title.answer') 3" required>
                           </div>
                         </div>
                         <div class="form-row col-md-6">
                           <div class="col-md-2">
                             <input class="question" type="radio" name="answer_{{ $i+1 }}" value="4" required> D
                           </div>
                           <div class="form-group col-md-10">
                             <input type="text" name="answer_text_{{ $i.$y+4 }}"   class="form-control question" autocomplete="off" placeholder="@lang('frontend/courses/title.answer') 4" required>
                           </div>
                         </div>
                       </div>
                   </div>
                 </div>
               </div>
              @endfor
              <hr class="p-b-5">
           </div>

           <div class="col-md-12">
              <div class="container">
                <div class="row">
                  <div class="col-sm"></div>
                    <div class="col-sm">
                        <button type="submit" onclick="return chk()" class="flex-c-m size2 bo-rad-23 s-text3 bgwhite hov1 trans-0-5 col-12 save_course"><object class="colorz" style="font-size: 20px;">@lang('frontend/courses/title.save_button')</object></button>
                    </div>
                  <div class="col-sm"></div>
                </div>
              </div>
           </div>

        </form>
      </div>
    </div>
</section>
</div>
<script src="{{ url('suksa/frontend/template/tinymce5.0.2/tinymce.min.js') }}"></script>
<script>

  function up_quiz_file() {
    let quiz_file = $("#quiz_file").val();
    var Please_attach_a_test_file = '{{ trans('frontend/courses/title.Please_attach_a_test_file') }}';
    if(quiz_file == ""){
      Swal.fire({
        type: 'info',
        title: Please_attach_a_test_file,
        showCancelButton: false,
        focusConfirm: false,
        confirmButtonColor: '#28a745',
        confirmButtonText: "ตกลง",
      })
    }else {
      Swal.showLoading();
      var form_data = new FormData();
      form_data.append('file', $('#quiz_file')[0].files[0]);
      $.ajax({
        url: window.location.origin + "/upload/Up_quiz_file/",
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'POST',
        success:function(data) {
          Swal.close();
          // console.log(data);
          var n = 0;
          $.each(data ,function(kel, value) {
            // console.log('textarea#question_'+ (kel+1) +'');
            $('#question_'+ (kel+1) +'_ifr').contents().find('body').text(value.question);
            // $('textarea#question_'+(kel+1)+'').val(value.question);

            if (value.question_true == 1) {
              $('input[name=answer_'+(kel+1)+'][value=1]').prop('checked', true);
            }
            if (value.question_true == 2) {
              $('input[name=answer_'+(kel+1)+'][value=2]').prop('checked', true);
            }
            if (value.question_true == 3) {
              $('input[name=answer_'+(kel+1)+'][value=3]').prop('checked', true);
            }
            if (value.question_true == 4) {
              $('input[name=answer_'+(kel+1)+'][value=4]').prop('checked', true);
            }

            $.each(value[0], function(index, el) {

              a = kel == 0 ? "":kel ;
              // console.log('input[name=answer_text_'+a+'1]');
              if (index == "answer_a") {
                $('input[name=answer_text_'+(a)+'1]').val(el);
              }
              if (index == "answer_b") {
                $('input[name=answer_text_'+(a)+'2]').val(el);
              }
              if (index == "answer_c") {
                $('input[name=answer_text_'+(a)+'3]').val(el);
              }
              if (index == "answer_d") {
                $('input[name=answer_text_'+(a)+'4]').val(el);
              }

            });

            $("#quiz_file").val("");

          });

          // if (data == "success") {
          //   Swal.fire({
          //     type: 'success',
          //     title: text_delete_course,
          //     text: text_this_course_has_been_deleted,
          //     showConfirmButton: false,
          //     timer: 1500
          //   })
          //   setTimeout(function () {
          //       location.reload();
          //   }, 2500);
          // }else {
          //   Swal.fire({
          //     type: 'info',
          //     title: this_course_cannot_be_deleted,
          //     showCancelButton: false,
          //     focusConfirm: false,
          //     confirmButtonColor: '#28a745',
          //     confirmButtonText: ok,
          //   })
          // }

        },
      });
    }

  }

  $('#quiz_file').change(function() {
      var i = $(this).prev('label').clone();
      var file = $('#quiz_file')[0].files[0].name;
      $(this).prev('label').text(file);
  });

  $('.hidden').hide();

  $(document).ready(function(){
         tinyinit('#question_1');
         tinyinit('#question_2');
         tinyinit('#question_3');
         tinyinit('#question_4');
         tinyinit('#question_5');
         tinyinit('#question_6');
         tinyinit('#question_7');
         tinyinit('#question_8');
         tinyinit('#question_9');
         tinyinit('#question_10');
    });

  function tinyinit(name){

        tinymce.init({
       selector: name,
        plugins:'preview autolink autosave save directionality visualblocks visualchars fullscreen advlist lists imagetools textpattern quickbars',
        height: "380",
        toolbar: "alignleft aligncenter alignright alignjustify",
        // enable title field in the Image dialog
        image_title: true,
        quickbars_insert_toolbar : 'quickimage',
        content_style: "body { font-family: Arial; }",
        // enable automatic uploads of images represented by blob or data URIs
        automatic_uploads: true,
        relative_urls : false,
        remove_script_host : false,
        convert_urls : true,
        // add custom filepicker only to Image dialog
        file_picker_types: 'image',
       file_picker_callback: function (callback, value, meta) {
          if (meta.filetype == 'image') {
            $(name+'_upload').trigger('click');
            $(name+'_upload').on('change', function () {
              var file = this.files[0];
              var reader = new FileReader();
              var formData = new FormData();
              // console.log(file);
              formData.append('picture',this.files[0]);
              $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
              }
          });
               $.ajax({
                  type: "post",
                  url: "{!! URL::to('courses/uploadImage/') !!}",
                  data : formData,
                  dataType : 'json',
                  contentType: false,
                  processData: false,
                  success: function (result) {
                      callback("{{url('storage/courses/test')}}"  + '/' + result.status);
                  }
                  });
            });
          }
        }
      });
      }
</script>
<script> //เลือกวิชา
function subject(value){
    var subject_text = '{{ trans('frontend/courses/title.subject') }}';
    var select_text = '{{ trans('frontend/courses/title.select') }}';

    if(value!=''){
        var subject =[];
        $.ajax({
          method: 'GET',
          dataType: 'json',
          url: '{{ url('/courses/getsubject') }}'+'/'+value,
          success: function(data) {

              select = '<label class="profile01">'+subject_text+' : <span style="color: red; font-size: 20px; " >*</span></label><select class="form-control" name="course_subject" id="course_subject" required style="text-align: center; text-align: center;  text-align-last: center; padding-top: 4px;"><option value="" selected readonly >'+select_text+'</option>';
              var option = '';
              for(i=0; i<data.length; i++){
                  option += '<option value="'+data[i]['subject_id']+'">'+data[i]['subject_name']+'</option><br>';

              }
              document.getElementById("subject").innerHTML = select+option+'</select>';
              //console.log(data);

          },
          error: function(data) {
              console.log('error');
          }
        });
    }
    else{
        $('#course_subject').val('');
        $('#course_subject').html('<option value="">'+select_text+'</option>');
    }
}
</script>

<script>
    $('.loadingio-eclipse').hide();
$(function() {

  $(".question").attr("disabled", "disabled"); //ปิดแบบสอบถาม

  $( ".public" ).click(function() {
    $('input[name=course_status]').attr('checked', false);

    $(".check_course").css("display", "inline");

    $(".question").attr("disabled", "disabled"); //ปิดแบบสอบถาม
  });

  $( ".private" ).click(function() {
    $('input[name=course_status]').attr('checked', true);

    $(".check_course").css("display", "none");

    $(".question").attr("disabled", "disabled"); //ปิดแบบสอบถาม
  });

  $( ".school" ).click(function() {
    $('input[name=course_status]').attr('checked', true);

    $(".check_course").css("display", "none");

    $(".question").removeAttr("disabled", "disabled"); //เปิดแบบสอบถาม
  });
});

function show1(){
  // document.getElementById('div1').style.display ='none';
  // document.getElementById('div2').style.display ='none';
  //   document.getElementById('div4').style.display ='block';
  document.getElementById('course_price').disabled = true;
}

function show2(){
  // document.getElementById('div1').style.display = 'block';
  // document.getElementById('div2').style.display = 'block';
  //   document.getElementById('div4').style.display ='none';
  document.getElementById('course_price').disabled = false;
}

function chk()
{

  var please_enter_email = '{{ trans('frontend/courses/title.please_enter_email') }}';
  var please_select_open_course_date = '{{ trans('frontend/courses/title.please_select_open_course_date') }}';
  var please_select_course_time_match = '{{ trans('frontend/courses/title.please_select_course_time_match') }}';
  var please_enter_require_field = '{{ trans('frontend/courses/title.please_enter_require_field') }}';
  var please_enter_coins_1 = '{{ trans('frontend/courses/title.please_enter_coins_1') }}';
  var please_enter_coins_2 = '{{ trans('frontend/courses/title.please_enter_coins_2') }}';

  var please_enter_student_limit = '{{ trans('frontend/courses/title.please_enter_student_limit') }}';
  var please_enter_course_price = '{{ trans('frontend/courses/title.please_enter_course_price') }}';


  var please_enter_course_name = '{{ trans('frontend/courses/title.please_enter_course_name') }}';
  var please_select_course_group = '{{ trans('frontend/courses/title.please_select_course_group') }}';
  var please_select_course_subject = '{{ trans('frontend/courses/title.please_select_course_subject') }}';
  var please_enter_course_detail = '{{ trans('frontend/courses/title.please_enter_course_detail') }}';
  var please_select_course_cover = '{{ trans('frontend/courses/title.please_select_course_cover') }}';

  var can_not_create_school_course = '{{ trans('frontend/courses/title.can_not_create_school_course') }}';
  var please_select_school_in_your_profile = '{{ trans('frontend/courses/title.please_select_school_in_your_profile') }}';
  var the_system_is_creating_a_course = '{{ trans('frontend/courses/title.the_system_is_creating_a_course') }}';

  var close_window = '{{ trans('frontend/courses/title.close_window') }}';
  var text_swal = '';
  // $(".save_course").prop('disabled', true);

  //เช็คการเลือกรูปภาพหน้าปกคอร์สเรียน
  //อัพรูป เก่า กับ รูปใหม่
  let img = $("#file-upload").val();
  if($('input[name="img2"]').val() && !$("#file-upload").val()){
    img=true
  }
  if(img==''){
    text_swal = please_select_course_cover;
    // Swal.fire({
    //     type: 'error',
    //     title: please_select_course_cover,
    //     // showCloseButton: true,
    //     showCancelButton: false,
    //     focusConfirm: false,
    //     confirmButtonColor: '#28a745',
    //     confirmButtonText: close_window,
    // });
    // return false;
  }
  //อัพรูป เก่า กับ รูปใหม่

  //อัพ เก่า กับ ไฟล์ ใหม่
  let fis = $("#course_file").val();
  if($('input[name="file2"]').val() && !$("#course_file").val()){
    fis=true
  }

  // ----------------------------------------------------------------

  //เช็คการกรอกเนื้อหาคอร์สเรียน
  if($('#course_detail').val()==''){
    text_swal = please_enter_course_detail;
    // Swal.fire({
    //     type: 'error',
    //     title: please_enter_course_detail,
    //     // showCloseButton: true,
    //     showCancelButton: false,
    //     focusConfirm: false,
    //     confirmButtonColor: '#28a745',
    //     confirmButtonText: close_window,
    // });
    // return false;
  }

  // ----------------------------------------------------------------

  //เช็คการเลือกวิชา
  if($('#course_subject').val()==''){
    text_swal = please_select_course_subject;

    // Swal.fire({
    //     type: 'error',
    //     title: please_select_course_subject,
    //     // showCloseButton: true,
    //     showCancelButton: false,
    //     focusConfirm: false,
    //     confirmButtonColor: '#28a745',
    //     confirmButtonText: close_window,
    // });
    // return false;
  }

  // ----------------------------------------------------------------

  //เช็คการเลือกกลุ่มการศึกษา
  if($('#course_group').val()==''){
    text_swal = please_select_course_group;
    //
    // Swal.fire({
    //     type: 'error',
    //     title: please_select_course_group,
    //     // showCloseButton: true,
    //     showCancelButton: false,
    //     focusConfirm: false,
    //     confirmButtonColor: '#28a745',
    //     confirmButtonText: close_window,
    // });
    // return false;
  }

  // ----------------------------------------------------------------

  //เช็คการกรอกชื่อคอร์ส
  if($('#course_name').val()==''){
    text_swal = please_enter_course_name;

    // Swal.fire({
    //     type: 'error',
    //     title: please_enter_course_name,
    //     // showCloseButton: true,
    //     showCancelButton: false,
    //     focusConfirm: false,
    //     confirmButtonColor: '#28a745',
    //     confirmButtonText: close_window,
    // });
    // return false;
  }

  // ----------------------------------------------------------------


  //เช็คโรงเรียน เมื่อเลือกประเภทคอร์สเป็น School
  if($('.course_category:checked').val()=='School'){
    if($('#member_school').val() == ''){
      // text_swal = please_select_open_course_date;

      Swal.fire({
          type: 'error',
          title: can_not_create_school_course,
          html:
                please_select_school_in_your_profile,
          // showCloseButton: true,
          showCancelButton: false,
          focusConfirm: false,
          confirmButtonColor: '#28a745',
          confirmButtonText: close_window,
      });

      return false;
    }

    if($('.course_category:checked').val() != 'School'){
      var student_email = $('.course_student').map(function(i, el) {
          return el.value;
      });
      if(student_email[1] === undefined){
        Swal.fire({
            type: 'error',
            title: please_enter_email,
            // showCloseButton: true,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
        return false;
      }
    }

        if($('input[name="answer_1"]:checked').prop("checked") == null){
            var answer_text_1 = 'กรุณาเลือกคำตอบข้อที่ 1';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }

        if($('input[name="answer_2"]:checked').prop("checked") == null){

            var answer_text_1 = 'กรุณาเลือกคำตอบข้อที่ 2';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_3"]:checked').prop("checked") == null){

            var answer_text_1 = 'กรุณาเลือกคำตอบข้อที่ 3';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_4"]:checked').prop("checked") == null){

            var answer_text_1 = 'กรุณาเลือกคำตอบข้อที่ 4';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_5"]:checked').prop("checked") == null){

            var answer_text_1 = 'กรุณาเลือกคำตอบข้อที่ 5';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_6"]:checked').prop("checked") == null){

            var answer_text_1 = 'กรุณาเลือกคำตอบข้อที่ 6';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_7"]:checked').prop("checked") == null){

            var answer_text_1 = 'กรุณาเลือกคำตอบข้อที่ 7';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_8"]:checked').prop("checked") == null){

            var answer_text_1 = 'กรุณาเลือกคำตอบข้อที่ 8';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_9"]:checked').prop("checked") == null){

            var answer_text_1 = 'กรุณาเลือกคำตอบข้อที่ 9';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_10"]:checked').prop("checked") == null){

            var answer_text_1 = 'กรุณาเลือกคำตอบข้อที่ 10';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('#question_1_ifr').contents().find('body').text().trim().length == 0){
            var answer_text_1 = 'กรุณากรอกคำถามข้อที่ 1 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('#question_2_ifr').contents().find('body').text().trim().length == 0){
            var answer_text_1 = 'กรุณากรอกคำถามข้อที่ 2 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('#question_3_ifr').contents().find('body').text().trim().length == 0){
            var answer_text_1 = 'กรุณากรอกคำถามข้อที่ 3 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('#question_4_ifr').contents().find('body').text().trim().length == 0){
            var answer_text_1 = 'กรุณากรอกคำถามข้อที่ 4 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('#question_5_ifr').contents().find('body').text().trim().length == 0){
            var answer_text_1 = 'กรุณากรอกคำถามข้อที่ 5 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('#question_6_ifr').contents().find('body').text().trim().length == 0){
            var answer_text_1 = 'กรุณากรอกคำถามข้อที่ 6 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('#question_7_ifr').contents().find('body').text().trim().length == 0){
            var answer_text_1 = 'กรุณากรอกคำถามข้อที่ 7 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('#question_8_ifr').contents().find('body').text().trim().length == 0){
            var answer_text_1 = 'กรุณากรอกคำถามข้อที่ 8 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('#question_9_ifr').contents().find('body').text().trim().length == 0){
            var answer_text_1 = 'กรุณากรอกคำถามข้อที่ 9 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('#question_10_ifr').contents().find('body').text().trim().length == 0){
            var answer_text_1 = 'กรุณากรอกคำถามข้อที่ 10 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_1"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 1 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_2"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 1 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_3"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 1 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_4"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 1 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_11"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 2 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }

        if($('input[name="answer_text_12"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 2 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_13"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 2 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_14"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 2 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_21"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 3 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_22"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 3 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_23"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 3 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_24"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 3 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_24"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 3 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_31"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 4 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_32"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 4 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_33"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 4 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_34"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 4 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_41"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 5 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_42"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 5 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_43"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 5 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_44"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 5 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_51"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 6 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_52"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 6 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_53"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 6 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_54"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 6 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_61"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 7 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_62"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 7 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_63"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 7 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_64"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 7 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_71"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 8 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_72"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 8 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_73"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 8 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_74"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 8 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_81"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 9 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_82"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 9 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_83"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 9 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_84"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 9 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_91"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 10 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_92"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 10 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_93"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 10 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }
        if($('input[name="answer_text_94"]').val() == ''){
            var answer_text_1 = 'กรุณากรอกตัวเลือกของข้อที่ 10 ให้ครบ';
            Swal.fire({
                type: 'error',
                title: answer_text_1,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            return false;
        }





    }


  // ----------------------------------------------------------------

  //เช็คการกรอกจำนวนผู้เข้าเรียน เมื่อเลือกประเภทคอร์สเป็น Public
  if($('.course_category:checked').val()=='Public'){
    if(($('#student_limit').val()=='') || ($('#student_limit').val()=='0')){
      text_swal = please_enter_student_limit;

      // Swal.fire({
      //     type: 'error',
      //     title: please_enter_student_limit,
      //     // showCloseButton: true,
      //     showCancelButton: false,
      //     focusConfirm: false,
      //     confirmButtonColor: '#28a745',
      //     confirmButtonText: close_window,
      // });
      // return false;
    }
  }

  // ----------------------------------------------------------------

  //เช็คการกรอกราคาคอร์ส เมื่อเลือกค่าเรียนแบบ มีค่าใช้จ่าย
  if($("input[name=course_type]:checked").val()=='1'){ //มีค่าใช้จ่าย
    if($('#course_price').val()==''){
      text_swal = please_enter_course_price;

      // Swal.fire({
      //     type: 'error',
      //     title: please_enter_course_price,
      //     // showCloseButton: true,
      //     showCancelButton: false,
      //     focusConfirm: false,
      //     confirmButtonColor: '#28a745',
      //     confirmButtonText: close_window,
      // });
      // return false;
    }
  }

  // ----------------------------------------------------------------

  //เช็คการเลือกวันที่สอน อย่างน้อย 1 วัน
  var date_study = $('.course_date').map(function(i, el) {
    return el.value;
  });
  if(date_study[1] == ""){
    text_swal = please_select_open_course_date;

    // Swal.fire({
    //     type: 'error',
    //     title: please_select_open_course_date,
    //     // showCloseButton: true,
    //     showCancelButton: false,
    //     focusConfirm: false,
    //     confirmButtonColor: '#28a745',
    //     confirmButtonText: close_window,
    // });
    // return false;
  }

  var h_start = parseInt($("#h_start").val());
  var m_start = parseInt($("#m_start").val());

  var h_end = parseInt($("#h_end").val());
  var m_end = parseInt($("#m_end").val());
  if(h_start > h_end){
    if(h_start == h_end){
      if(m_start >= m_end){
        sweet_alert(please_select_course_time_match);

        return false;
      }
    }
    sweet_alert(please_select_course_time_match);
    return false;
  }else{
    if(h_start == h_end){
      if(m_start >= m_end){
        sweet_alert(please_select_course_time_match);
        return false;
      }
    }
  }

  // ----------------------------------------------------------------


  //เช็คการกรอก email เมื่อเลือกประเภทคอร์สเป็น Private
  if($('.course_category:checked').val()=='Private'){
    var student_email = $('.course_student').map(function(i, el) {
        return el.value;
    });
    if(student_email[1] === undefined){
      text_swal = please_enter_email;

      // Swal.fire({
      //     type: 'error',
      //     title: please_enter_email,
      //     // showCloseButton: true,
      //     showCancelButton: false,
      //     focusConfirm: false,
      //     confirmButtonColor: '#28a745',
      //     confirmButtonText: close_window,
      // });
      // return false;
    }
  }

  //เช็ค radio



  // ----------------------------------------------------------------


  if (text_swal != '') {
    Swal.fire({
        type: 'error',
        title: text_swal,
        // showCloseButton: true,
        showCancelButton: false,
        focusConfirm: false,
        confirmButtonColor: '#28a745',
        confirmButtonText: close_window,
    });
    return false;
  }else {


    // console.log($('input[name="answer_1"]:checked').val());
    // $('.loadingio-eclipse').show();
      Swal.fire({
         title: the_system_is_creating_a_course,
         allowOutsideClick: false,
         allowEscapeKey: false,
         onBeforeOpen: () => {
           Swal.showLoading()
        },
     });
    return true;
    $(".save_course").prop('disabled', true);
  }

}

function sweet_alert(text){
  var close_window = '{{ trans('frontend/courses/title.close_window') }}';
  Swal.fire({
      title: '<strong><h3>'+text+'</h3></u></strong>',
      type: 'info',
      // showCloseButton: true,
      showCancelButton: false,
      focusConfirm: false,
      confirmButtonColor: '#28a745',
      confirmButtonText: close_window,
    });
  }

  // Jquery Dependency

$("input[data-type='currency']").on({
    keyup: function() {
      formatCurrency($(this));
    },
    blur: function() {
      formatCurrency($(this), "blur");
    }
});

function formatNumber(n) {
  // format number 1000000 to 1,234,567
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}

function formatCurrency(input, blur) {
  // appends $ to value, validates decimal side
  // and puts cursor back in right position.


  // get input value
  var input_val = input.val();

  // don't validate empty input
  if (input_val === "") { return; }

  // original length
  var original_len = input_val.length;

  // initial caret position
  var caret_pos = input.prop("selectionStart");

  // check for decimal
  if (input_val.indexOf(".") >= 0) {

    // get position of first decimal
    // this prevents multiple decimals from
    // being entered
    var decimal_pos = input_val.indexOf(".");

    // split number by decimal point
    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos);

    // add commas to left side of number
    left_side = formatNumber(left_side);

    // validate right side
    right_side = formatNumber(right_side);

    // On blur make sure 2 numbers after decimal
    if (blur === "blur") {
      right_side += "00";
    }

    // Limit decimal to only 2 digits
    right_side = right_side.substring(0, 2);

    // join number by .
    input_val = left_side + "." + right_side;

  } else {
    // no decimal entered
    // add commas to number
    // remove all non-digits
    input_val = formatNumber(input_val);
    input_val = input_val;

    // final formatting
    if (blur === "blur") {
      input_val += "";
    }
  }

  // send updated string to input
  input.val(input_val);

  // put caret back in the right position
  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  input[0].setSelectionRange(caret_pos, caret_pos);
}

function test(){
  var please_select_course_time_match = '{{ trans('frontend/courses/title.please_select_course_time_match') }}';
  var close_window = '{{ trans('frontend/courses/title.close_window') }}';

  var h_start = parseInt($("#h_start").val());
  var m_start = parseInt($("#m_start").val());

  var h_end = parseInt($("#h_end").val());
  var m_end = parseInt($("#m_end").val());
  if(h_start > h_end){
    if(h_start == h_end){
      if(m_start >= m_end){
        alert(please_select_course_time_match);
      }
    }
    alert(please_select_course_time_match);
  }else{
    if(h_start == h_end){
      if(m_start >= m_end){
        alert(please_select_course_time_match);
      }
    }
  }
  return false;
}

$("input[data-type='currency']").on({
    keyup: function() {
      formatCurrency($(this));
    },
    blur: function() {
      formatCurrency($(this), "blur");
    }
});

function formatNumber(n) {
  // format number 1000000 to 1,234,567
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}

function formatCurrency(input, blur) {
  // appends $ to value, validates decimal side
  // and puts cursor back in right position.

  // get input value
  var input_val = input.val();

  // don't validate empty input
  if (input_val === "") { return; }

  // original length
  var original_len = input_val.length;

  // initial caret position
  var caret_pos = input.prop("selectionStart");

  // check for decimal
  if (input_val.indexOf(".") >= 0) {

    // get position of first decimal
    // this prevents multiple decimals from
    // being entered
    var decimal_pos = input_val.indexOf(".");

    // split number by decimal point
    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos);

    // add commas to left side of number
    left_side = formatNumber(left_side);

    // validate right side
    right_side = formatNumber(right_side);

    // On blur make sure 2 numbers after decimal
    if (blur === "blur") {
      right_side += "00";
    }

    // Limit decimal to only 2 digits
    right_side = right_side.substring(0, 2);

    // join number by .
    input_val = left_side + "." + right_side;

  } else {
    // no decimal entered
    // add commas to number
    // remove all non-digits
    input_val = formatNumber(input_val);
    input_val = input_val;

    // final formatting
    if (blur === "blur") {
      input_val += "";
    }
  }

  // send updated string to input
  input.val(input_val);

  // put caret back in the right position
  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  input[0].setSelectionRange(caret_pos, caret_pos);
}

</script>

<script src="{{ asset ('suksa/frontend/template/js/profileuser.js') }}"></script>
<script src="{{ asset ('suksa/frontend/template/js/datetime.js') }}"></script>
<script language="javascript">
function CheckNumm(){
  var please_enter_number_only = '{{ trans('frontend/courses/title.please_enter_number_only') }}';
  var close_window = '{{ trans('frontend/courses/title.close_window') }}';

  if(event.keyCode < 48 || event.keyCode > 57){
    event.returnValue = false;
    Swal.fire({
         type: 'error',
        title: please_enter_number_only,
        // showCloseButton: true,
        showCancelButton: false,
        focusConfirm: false,
        confirmButtonColor: '#28a745',
        confirmButtonText: close_window,
    })
    }
  }
</script>

<script>
  // numeral
  var cleaveNumeral = new Cleave('#course_price', {
      numeral: true,
      numeralThousandsGroupStyle: 'thousand'
  });
</script>

<script>
  $(function() {
    var day = [];
      var count_date = 0;
    <?php
      if(!empty($course)){
        foreach ($course->course_date as $key => $value) {

    ?>
    day.push('{{ date('m/d/Y', strtotime($value['date'])) }}');
    count_date++;
    <?php
        }
      }else{


    ?>
    var data_study = new Date();
    var count_date = 0;
    <?php
      }
    ?>
      var data_study = new Date();
      var today = new Date();

      $(document).on('click', '.btn-add5', function(e) {
          var txt1 = $('#datepicker01').val();
          var txt2 = $('#datepicker02').val();
          var txt3 = $('#datepicker03').val();

          var selectedDate = $("#datepicker01").val();

          var initial = selectedDate.split(/\//);
          initial = [ initial[1], initial[0], initial[2] ].join('/');
          var inputDate = new Date(initial);

          var now = data_study;

          var h_start = parseInt($("#datepicker02").val().substring(0, 2));
          var m_start = parseInt($("#datepicker02").val().substring(3, 5));

          var h_end = parseInt($("#datepicker03").val().substring(0, 2));
          var m_end = parseInt($("#datepicker03").val().substring(3, 5));

          var please_enter_open_datetime = '{{ trans('frontend/courses/title.please_enter_open_datetime') }}';
          var please_select_valid_open_course_date = '{{ trans('frontend/courses/title.please_select_valid_open_course_date') }}';
          var please_select_course_time_match = '{{ trans('frontend/courses/title.please_select_course_time_match') }}';

          var close_window = '{{ trans('frontend/courses/title.close_window') }}';

          //กรณีไม่เลือกค่าใดค่าหนึ่ง
          if(txt1=='' || txt2=='' || txt3==''){
              Swal.fire({
                type: 'error',
                title: please_enter_open_datetime,
                // showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
                //text: 'Something went wrong!',
              })
              return false;
          }

          //กรณียังไม่ได้เลือกวันที่เลยสักวัน
          if(count_date==0){
            //กรณีเลือกเวลาที่เปิดสอนของวันนี้ น้อยกว่าเวลาปัจจุบันของวันนี้
            if(inputDate.setHours(0,0,0,0) < now.setHours(0,0,0,0)){
              Swal.fire({
                  type: 'error',
                  title: please_select_valid_open_course_date,
                  // showCloseButton: true,
                  showCancelButton: false,
                  focusConfirm: false,
                  confirmButtonColor: '#28a745',
                  confirmButtonText: close_window,
                  //text: 'Something went wrong!',
                })
                // console.log(now);

              return false;
            }

            //กรณีเลือกเวลาเริ่ม และสิ้นสุด เท่ากับเวลาปัจจุบัน
            if(inputDate.setHours(0,0,0,0) == now.setHours(0,0,0,0)){
              //ถ้าชั่วโมงของวันที่เลือก น้อยกว่าชั่วโมงปัจจุบัน
              if(h_start < today.getHours()){
                  //ถ้าชั่วโมงของวันที่เลือก เท่ากับชั่วโมงปัจจุบัน
                  if(h_start == today.getHours()){
                      //ถ้านาทีของวันที่เลือก น้อยกว่าหรือเท่ากับนาทีปัจจุบัน
                      if(m_start <= today.getMinutes()){
                        alert_error(please_select_course_time_match);
                        return false;
                      }
                  }
                  alert_error(please_select_course_time_match);
                  return false;
              }
              //ถ้าชั่วโมงของวันที่เลือก มากกว่าหรือเท่ากับชั่วโมงปัจจุบัน
              else{
                  //ถ้าชั่วโมงของวันที่เลือก เท่ากับชั่วโมงปัจจุบัน
                  if(h_start == today.getHours()){
                      //ถ้านาทีของวันที่เลือก น้อยกว่าหรือเท่ากับนาทีปัจจุบัน
                      if(m_start <= today.getMinutes()){
                        alert_error(please_select_course_time_match);
                        return false;
                      }
                  }
              }
            }
            day.push(initial);

            var h1_end = 0;
            h1_end = h_end+1;

            if(h1_end == 24){
              h1_end = 0;
            }

            $("#last_end_hour").val(h1_end);
            $("#last_end_minute").val(m_end);
          }
          else { //กรณีมีการเลือกวันที่เปิดสอนไปแล้ว
            //วันที่เลือกเปิดสอนล่าสุด
            var last_day = new Date(day[day.length-1]);

            //กรณีเลือกวันที่เปิดสอนปัจจุบัน น้อยกว่าวันที่เลือกเปิดสอนล่าสุด
            if(inputDate.setHours(0,0,0,0) < last_day.setHours(0,0,0,0)){
                Swal.fire({
                    type: 'error',
                    title: please_select_valid_open_course_date,
                    // showCloseButton: true,
                    showCancelButton: false,
                    focusConfirm: false,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: close_window,
                    //text: 'Something went wrong!',
                  });

                $('#datepicker01').val('');
                $('#datepicker02').val('');
                $('#datepicker03').val('');

                return false;
            }
            //กรณีเลือกวันที่เปิดสอนปัจจุบัน มากกว่าหรือเท่ากับวันที่เลือกเปิดสอนล่าสุด
            else{
              //$("#last_end_hour").val()
              //$("#last_end_minute").val()
              //alert(h_start+ '--' +$("#last_end_hour").val());

              //last_end_hour เท่ากับเวลาปิดคอร์สล่าสุด บวก 1 ชม.
              var last_end_hour = $("#last_end_hour").val();
              var last_end_minute = $("#last_end_minute").val();

              //กรณีเลือกวันที่เปิดสอนปัจจุบัน เท่ากับวันที่เลือกเปิดสอนล่าสุด
              if(inputDate.setHours(0,0,0,0) == last_day.setHours(0,0,0,0)){

                //ถ้าชั่วโมงของวันที่เลือก น้อยกว่าเวลาปิดคอร์สล่าสุด บวก 1 ชม.
                if(h_start < last_end_hour){
                    //ถ้าชั่วโมงของวันที่เลือก เท่ากับเวลาปิดคอร์สล่าสุด บวก 1 ชม.
                    if(h_start == last_end_hour){
                        //ถ้านาทีของวันที่เลือก น้อยกว่าหรือเท่ากับนาทีปัจจุบัน
                        if(m_start <= last_end_minute){
                          alert_error(please_select_course_time_match+' ------ นาทีของวันที่เลือก น้อยกว่าหรือเท่ากับนาทีปัจจุบัน');

                          $('#datepicker01').val('');
                          $('#datepicker02').val('');
                          $('#datepicker03').val('');

                          return false;
                        }
                    }
                    alert_error(please_select_course_time_match+' ------ ชั่วโมงของวันที่เลือก น้อยกว่าชั่วโมงปัจจุบัน');

                    $('#datepicker01').val('');
                    $('#datepicker02').val('');
                    $('#datepicker03').val('');

                    return false;
                }
                //ถ้าชั่วโมงของวันที่เลือก มากกว่าหรือเท่ากับเวลาปิดคอร์สล่าสุด บวก 1 ชม.
                else{
                    //ถ้าชั่วโมงของวันที่เลือก เท่ากับเวลาปิดคอร์สล่าสุด บวก 1 ชม.
                    if(h_start == last_end_hour){
                        //ถ้านาทีของวันที่เลือก น้อยกว่าหรือเท่ากับนาทีปัจจุบัน
                        if(m_start <= last_end_minute){
                          alert_error(please_select_course_time_match+' ------ นาทีของวันที่เลือก น้อยกว่าหรือเท่ากับนาทีปัจจุบัน');

                          $('#datepicker01').val('');
                          $('#datepicker02').val('');
                          $('#datepicker03').val('');

                          return false;
                        }
                    }
                }
              }
            }

            day.push(initial);

            var h1_end = 0;
            h1_end = h_end+1;

            if(h1_end == 24){
              h1_end = 0;
            }

            $("#last_end_hour").val(h1_end);
            $("#last_end_minute").val(m_end);
          }



          if(h_start > h_end){
              if(h_start == h_end){
                  if(m_start >= m_end){
                    alert_error(please_select_course_time_match);
                    return false;
                  }
              }
              alert_error(please_select_course_time_match);
              return false;
          }
          else{
              if(h_start == h_end){
                  if(m_start >= m_end){
                    alert_error(please_select_course_time_match);
                    return false;
                  }
              }
          }


          var remove_button = '{{ trans('frontend/courses/title.remove_button') }}';

          e.preventDefault();
          var controlForm = $('#myRepeatingFields5'),
          currentEntry = $(this).parents('.entry:first');

          $(currentEntry.clone()).appendTo(controlForm).find('input').attr('readonly', 'readonly');

          currentEntry.find('input').val('');

          controlForm.find('.entry:not(:first) .btn-add5')
          .removeClass('btn-add5').addClass('btn-remove')
          .removeClass('btn-dark').addClass('btn-danger')
          .attr('id', count_date)
          .html(remove_button);

          count_date++;
          // console.log(day);
          // console.log(count_date);
        }).on('click', '.btn-remove', function(e) {
          e.preventDefault();
          var contentPanelId = jQuery(this).attr("id");

          day.splice(contentPanelId,1);


          var inps = document.getElementsByName('time_end[]');
          var last_end_hour = 0;
          var last_end_minute = 0;

          for (var i = 0; i <inps.length; i++) {
              var inp = inps[i];
              //alert("time_end["+i+"].value="+inp.value);
          }

          last_end_hour = parseInt(inp.value.substring(0, 2)) + 1;
          last_end_minute = parseInt(inp.value.substring(3, 5));

          if(last_end_hour == 24){
              last_end_hour = 0;
          }

          //alert(last_end_hour);
          $("#last_end_hour").val(last_end_hour);
          $("#last_end_minute").val(last_end_minute);


          $(this).parents('.entry:first').remove();
          count_date--;

          // console.log(day);
          // console.log(count_date);
          return false;

      });

    });

</script>
<script>
    $(function() {
      var student_email_chk = [];
      var count_student_email = 0;
      <?php
        if(!empty($course)){
          foreach ($course->course_student as $key => $value) {
      ?>
      student_email_chk.push('{{ $value }}');
      count_student_email++;
      <?php
          }
        }else{
      ?>
      var student_email_chk = [];
      var count_student_email = 0;
      <?php
        }
      ?>

      var invalid_email = '{{ trans('frontend/courses/title.invalid_email') }}';
      var email_selected = '{{ trans('frontend/courses/title.email_selected') }}';
      var please_enter_require_field = '{{ trans('frontend/courses/title.please_enter_require_field') }}';
      var remove_button6 = '{{ trans('frontend/courses/title.remove_button') }}';

        $(document).on('click', '.btn-add6', function(e) {
            var txt11 = $('#emailstudents').val();
            var email = [];
            $.ajax({
                method: 'GET',
                url: window.location.origin +'/members/get_email_student',
                dataType: 'json',
                async: false,
                success: function(data) {
                    email = data;
                },
                error: function(data) {
                    //error
                }
            });
            //console.log(email);
            const result = email.find(fruit => fruit.member_email === txt11);
            //console.log(result);
            if (typeof result == 'undefined') {
                Swal.fire({
                  type: 'error',
                  confirmButtonColor: '#28a745',
                  title: invalid_email,
                  //text: 'Something went wrong!',
                })
                return false;
            }

            for(i=0; i<student_email_chk.length; i++){
              if(student_email_chk[i]==txt11){
                Swal.fire({
                    type: 'error',
                    confirmButtonColor: '#28a745',
                    title: email_selected,
                    //text: 'Something went wrong!',
                  })
                return false;
              }
            }
            student_email_chk.push(txt11);

            if(txt11==''){
                Swal.fire({
                  type: 'error',
                  title: please_enter_require_field,
                  //text: 'Something went wrong!',
                })
                return false;
            }
            else if ($('[name="course_student[]"]').length <= 100){
                e.preventDefault();
                var controlForm = $('#myRepeatingFields6'),
                currentEntry = $(this).parents('.entry:first');

                $(currentEntry.clone()).appendTo(controlForm).find('input').attr('readonly', 'readonly');
                currentEntry.find('input').val('');
                controlForm.find('.entry:not(:first) .btn-add6')
                .removeClass('btn-add6').addClass('btn-remove')
                .removeClass('btn-dark').addClass('btn-danger')
                .attr('id', count_student_email)
                .html(remove_button6);
                // console.log(remove_button6);
                count_student_email++;
            }
          }).on('click', '.btn-remove', function(e) {
            e.preventDefault();
            var contentPanelId = jQuery(this).attr("id");

          student_email_chk.splice(contentPanelId,1);
            $(this).parents('.entry:first').remove();
            count_student_email--;
            return false;

        });

      });

</script>

<script>
    // define variables
    var nativePicker = document.querySelector('.nativeTimePicker');
    //var fallbackPicker = document.querySelector('.fallbackTimePicker');
    //var fallbackLabel = document.querySelector('.fallbackLabel');

    var hourSelect = document.querySelector('#hour');
    var minuteSelect = document.querySelector('#minute');

    // hide fallback initially
    //fallbackPicker.style.display = 'none';
    //fallbackLabel.style.display = 'none';

    // test whether a new date input falls back to a text input or not
    var test = document.createElement('input');
    test.type = 'time';
    // if it does, run the code inside the if() {} block
    if(test.type === 'text') {
      // hide the native picker and show the fallback
      nativePicker.style.display = 'none';
      //fallbackPicker.style.display = 'block';
      //fallbackLabel.style.display = 'block';

      // populate the hours and minutes dynamically
      populateHours();
      populateMinutes();
    }

    function populateHours() {
      // populate the hours <select> with the 6 open hours of the day
      for(var i = 12; i <= 18; i++) {
        var option = document.createElement('option');
        option.textContent = i;
        hourSelect.appendChild(option);
      }
    }

    function populateMinutes() {
      // populate the minutes <select> with the 60 hours of each minute
      for(var i = 0; i <= 59; i++) {
        var option = document.createElement('option');
        option.textContent = (i < 10) ? ("0" + i) : i;
        minuteSelect.appendChild(option);
      }
    }

    // make it so that if the hour is 18, the minutes value is set to 00
    // — you can't select times past 18:00
     function setMinutesToZero() {
       if(hourSelect.value === '18') {
         minuteSelect.value = '00';
       }
     }

     //hourSelect.onchange = setMinutesToZero;
     //minuteSelect.onchange = setMinutesToZero;
  </script>

<script>

    function showc1(){
      document.getElementById("colorfafa").style.color = "white";
      document.getElementById("colorfafa2").style.color = "black";
      document.getElementById("colorfafa3").style.color = "black";
    }
    function showc2(){
      document.getElementById("colorfafa").style.color = "black";
      document.getElementById("colorfafa2").style.color = "white";
      document.getElementById("colorfafa3").style.color = "black";
    }
    function showc3(){
      document.getElementById("colorfafa").style.color = "black";
      document.getElementById("colorfafa2").style.color = "black";
      document.getElementById("colorfafa3").style.color = "white";
    }

    function dis(){
      $("#student_limit").removeAttr("disabled", "disabled");

    }
    function dis2(){
      $("#student_limit").attr("disabled", "disabled");
      $(".question").attr("disabled", "disabled");
    }
    function dis3(){
      if($('#member_school').val() == ''){
        var can_not_create_school_course = '{{ trans('frontend/courses/title.can_not_create_school_course') }}';
        var please_select_school_in_your_profile = '{{ trans('frontend/courses/title.please_select_school_in_your_profile') }}';
        var close_window = '{{ trans('frontend/courses/title.close_window') }}';

        Swal.fire({
            type: 'error',
            title: can_not_create_school_course,
            html:
                  please_select_school_in_your_profile,
            // showCloseButton: true,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
      }

      $("#student_limit").attr("disabled", "disabled");
      $(".question").removeAttr("disabled", "disabled");
    }



</script>

<!-- ปิดปุ่มแก้ไชเมื่อเลือก tab -->
<script language="JavaScript">
    function setVisibility(id, visibility) {
    document.getElementById(id).style.display = visibility;
    }

  $('#datepicker01').datepicker({
      uiLibrary: 'bootstrap4',
      format: 'dd/mm/yyyy',
      autoclose: true,
      todayBtn: true,
  });


    var t = false

    $('#student_limit').focus(function () {
        var $this = $(this)

        t = setInterval(

        function () {
            var set_limit = 100;
            if (($this.val() < 1 || $this.val() > set_limit) && $this.val().length != 0) {
                if ($this.val() < 1) {
                    $this.val(1)
                }

                var please_enter_number_of_students_up_to_10 = '{{ trans('frontend/courses/title.please_enter_number_of_students_up_to_10') }}';
                var close_window = '{{ trans('frontend/courses/title.close_window') }}';

                if ($this.val() > set_limit) {
                    $this.val(set_limit)
                    Swal.fire({
                      type: 'error',
                      title: '<label style="font-size: 22px;">'+please_enter_number_of_students_up_to_10+'</label>',
                      // showCloseButton: true,
                      showCancelButton: false,
                      focusConfirm: false,
                      confirmButtonColor: '#28a745',
                      confirmButtonText: close_window,
                })
                }
                $('p').fadeIn(1000, function () {
                    $(this).fadeOut(500)
                })
            }
        }, 50)
    })

    $('#student_limit').blur(function () {
        if (t != false) {
            window.clearInterval(t)
            t = false;

        }

    })

    function clearText()
    {
      document.getElementById('course_price').value = "";
    }

    // function fncSubmit(){

    //   if(document.form1.course_date.value == "")
    //   {
    //     Swal.fire({
    //           type: 'error',
    //           title: 'กรุณากรอกข้อมูลให้ครบ',
    //           // showCloseButton: true,
    //           showCancelButton: false,
    //           focusConfirm: false,
    //           confirmButtonColor: '#28a745',
    //           confirmButtonText: 'ปิดหน้าต่าง',
    //           //text: 'Something went wrong!',
    //         })
    //     document.form1.course_date.focus();
    //     return false;
    //   }
    //   if(document.form1.time_start.value == "")
    //   {
    //     Swal.fire({
    //           type: 'error',
    //           title: 'กรุณากรอกข้อมูลให้ครบ',
    //           // showCloseButton: true,
    //           showCancelButton: false,
    //           focusConfirm: false,
    //           confirmButtonColor: '#28a745',
    //           confirmButtonText: 'ปิดหน้าต่าง',
    //           //text: 'Something went wrong!',
    //         })
    //     document.form1.time_start.focus();
    //     return false;
    //   }
    //   if(document.form1.time_end.value == "")
    //   {
    //     Swal.fire({
    //           type: 'error',
    //           title: 'กรุณากรอกข้อมูลให้ครบ',
    //           // showCloseButton: true,
    //           showCancelButton: false,
    //           focusConfirm: false,
    //           confirmButtonColor: '#28a745',
    //           confirmButtonText: 'ปิดหน้าต่าง',
    //           //text: 'Something went wrong!',
    //         })
    //     document.form1.time_end.focus();
    //     return false;
    //   }
    //   if(document.form1.course_name.value == "")
    //   {
    //     Swal.fire({
    //           type: 'error',
    //           title: 'กรุณากรอกข้อมูลให้ครบ',
    //           // showCloseButton: true,
    //           showCancelButton: false,
    //           focusConfirm: false,
    //           confirmButtonColor: '#28a745',
    //           confirmButtonText: 'ปิดหน้าต่าง',
    //           //text: 'Something went wrong!',
    //         })
    //     document.form1.course_name.focus();
    //     return false;
    //   }
    //   if(document.form1.course_group.value == "")
    //   {
    //     Swal.fire({
    //           type: 'error',
    //           title: 'กรุณากรอกข้อมูลให้ครบ',
    //           // showCloseButton: true,
    //           showCancelButton: false,
    //           focusConfirm: false,
    //           confirmButtonColor: '#28a745',
    //           confirmButtonText: 'ปิดหน้าต่าง',
    //           //text: 'Something went wrong!',
    //         })
    //     document.form1.course_group.focus();
    //     return false;
    //   }
    //   if(document.form1.course_subject.value == "")
    //   {
    //     Swal.fire({
    //           type: 'error',
    //           title: 'กรุณากรอกข้อมูลให้ครบ',
    //           // showCloseButton: true,
    //           showCancelButton: false,
    //           focusConfirm: false,
    //           confirmButtonColor: '#28a745',
    //           confirmButtonText: 'ปิดหน้าต่าง',
    //           //text: 'Something went wrong!',
    //         })
    //     document.form1.course_subject.focus();
    //     return false;
    //   }
    //   if(document.form1.course_detail.value == "")
    //   {
    //     Swal.fire({
    //           type: 'error',
    //           title: 'กรุณากรอกข้อมูลให้ครบ',
    //           // showCloseButton: true,
    //           showCancelButton: false,
    //           focusConfirm: false,
    //           confirmButtonColor: '#28a745',
    //           confirmButtonText: 'ปิดหน้าต่าง',
    //           //text: 'Something went wrong!',
    //         })
    //     document.form1.course_detail.focus();
    //     return false;
    //   }

    //   document.form1.submit();
    // }



  function alert_error(title){
  var close_window = '{{ trans('frontend/courses/title.close_window') }}';
  Swal.fire({
        type: 'error',
        title: title,
        showCancelButton: false,
        focusConfirm: false,
        confirmButtonColor: '#28a745',
        confirmButtonText: close_window,
    })
}
</script>
<!-- เคลียร์ -->
@stop
