@extends('frontend/default')
@section('content')
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
      // dd($members);
  @endphp

  <style type="text/css">
    .container {
      max-width: 1240px;
    }
  </style>
<section class="p-t-50 p-b-65">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <input type="hidden" name="teacher_id" value="{{ $members['_id'] }}">
  <input type="hidden" name="teacher_fname" value="{{ $members['member_fname'] }}">
  <input type="hidden" name="teacher_lname" value="{{ $members['member_lname'] }}">
  <input type="hidden" name="teacher_email" value="{{ $members['member_email'] }}">

  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item" aria-current="page"><a href="{{url('homework/list')}}">{{trans('frontend/homework/title.create_homework')}}</a></li>
        <li class="breadcrumb-item" aria-current="page"> {{trans('frontend/homework/title.add_home_assignment')}}</li>
      </ol>
    </nav>

    <div class="form-row">
      <div class="form-row col-sm-12" id="">
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

    <div class="entry">
      <div class="form-row" >
          <div class="form-group col-md-4" >
            <label class="profile01">{{ trans('frontend/homework/title.classroom') }} : <span style="color: red; font-size: 20px;" >*</span></label>
            <select class="form-control required edit-group col-sm-10" name="select_student_level" id="select_student_level" style=" text-align: center; text-align-last: center; padding-top: 4px;" >
              @foreach (collect($school_detail['school_student'])->sortBy('student_classroom')->groupBy('student_classroom') as $key => $value)
                <option value="{{ $value }}">{{ $key }}</option>
              @endforeach
            </select>
          </div>
      </div>
    </div>


    <hr>
    <div class="entry">
      <div class="form-row" >
        <div class="form-group col-md-4" >
            <label for="dtp_input1" class="col-md control-label p-l-0">{{ trans('frontend/homework/title.assignment_date') }} : <span style="color: red; font-size: 20px;" >*</span></label>
           <input class="form-control daterange-input date_start" name="date_start" id="date_start" data-format="dd/mm/yyyy" type="text"  placeholder="@lang('frontend/homework/title.assignment_date')" autocomplete="off"  >
        </div>
        <div class="form-group col-md-3">
            <label for="dtp_input1" class="col-md control-label p-l-0">@lang('frontend/courses/title.start_time') : <span style="color: red; font-size: 20px;" >*</span></label>
            <div class="input-group date form_datetime col-md-12 p-l-0" style="padding-right: unset;" data-date="1979-09-16T05:25:07Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                <input class="form-control daterange-input2 time-icon start_time" size="16" id="datepicker02" type="text"  placeholder="@lang('frontend/courses/title.select_start_time')" name="time_start[]" autocomplete="off">
                {{-- <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span> --}}
            </div>
           <input type="hidden" id="dtp_input1" value="" />
        </div>

      </div>
    </div>

    <div class="entry">
      <div class="form-row" >
        <div class="form-group col-md-4" >
            <label for="dtp_input1" class="col-md control-label p-l-0">{{ trans('frontend/homework/title.homework_delivery_date') }} : <span style="color: red; font-size: 20px;" >*</span></label>
           <input class="form-control daterange-input date_end" name="date_end" id="date_end" data-format="dd/mm/yyyy" type="text"  placeholder="@lang('frontend/homework/title.homework_delivery_date')" autocomplete="off"  >
        </div>
        <div class="form-group col-md-3">
            <label for="dtp_input1" class="col-md control-label p-l-0">@lang('frontend/courses/title.end_time') : <span style="color: red; font-size: 20px;" >*</span></label>
            <div class="input-group date form_datetime col-md-12 p-l-0" style="padding-right: unset;" data-date="1979-09-16T05:25:07Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                <input class="form-control daterange-input2 time-icon end_time" size="16" id="datepicker03" type="text"  placeholder="@lang('frontend/courses/title.select_end_time')" name="time_end[]" autocomplete="off">
                {{-- <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span> --}}
            </div>
        </div>
      </div>
    </div>

    <div class="entry" id="add_text_date"></div>

    <div class="entry">
      <div class="form-row">
         <label class="col-sm-12">{{ trans('frontend/homework/title.homework_topics') }} : <span style="color: red; font-size: 20px;" >*</span></label>
           <div class="form-group col-md-12">
                 <input type="text" name="homework_name" id="homework_name" class="form-control" placeholder="{{ trans('frontend/homework/title.homework_topics') }}"  value="{{($name_course)}}"  required>
            </div>
      </div>
    </div>

    <div class="entry" >
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
    </div>
    <br>
    <hr>
    <div class="form-row">
      <div class="col-4"></div>
      <button type="button" class="btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-4 bo-rad-23 add_question colorz" id="homework_topics" style="color: white; height: 38px;" onclick="create_homework_topics()">
        <i class="spin"> </i> &nbsp; {{ trans('frontend/homework/title.add_home_assignment') }}
      </button>
      <div class="col-4"></div>
    </div>
  </div>
  <input type="hidden" id="datecheck" value="{{date('d/m/Y H:i')}}">
  {{-- <input type="hidden" id="timecheck" value="{{date('H:i')}}"> --}}
</section>

<script>

    var assignment_date = ' {{trans('frontend/homework/title.assignment_date')}}';
    var homework_delivery_date = ' {{trans('frontend/homework/title.homework_delivery_date')}}';
    var start_time = ' {{trans('frontend/homework/title.start_time')}}';
    var end_time = ' {{trans('frontend/homework/title.end_time')}}';
    var homework_topics = '{{ trans('frontend/homework/title.homework_topics') }}';
    var aptitude_t = '{{trans('frontend/courses/title.aptitude')}}';
    var subject_t = '{{trans('frontend/courses/title.subject')}}';
    var close_window = '{{ trans('frontend/layouts/modal.close_window') }}';
    var enter_the_start_time = '{{trans('frontend/homework/title.enter_the_start_time')}}';
    var enter_the_assignment = '{{trans('frontend/homework/title.enter_the_assignment')}}';
    var please = '{{trans('frontend/homework/title.please')}}';
    var the_assignment_date = '{{trans('frontend/homework/title.the_assignment_date')}}';
    var start_date_over = '{{trans('frontend/homework/title.start_date_over')}}';


    setTimeout(function () {
      $('.date_start').datetimepicker({
           format: 'dd/mm/yyyy',
           minView: "month",
           language: "fr",
           autoclose: true,
       });
    }, 2500);

    setTimeout(function () {
      $('.date_end').datetimepicker({
           format: 'dd/mm/yyyy',
           minView: "month",
           language: "fr",
           autoclose: true,
       });
    }, 2500);

    function subject(value){
        var subject_text = `{{ trans('frontend/courses/title.subject') }}`;
        var select_text = `{{ trans('frontend/courses/title.select') }}`;

        if(value!=''){
            var subject =[];
            $.ajax({
              method: 'GET',
              dataType: 'json',
              url: `{{ url('/courses/getsubject') }}`+`/`+value,
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

    function create_homework_topics() {

      var text = '';
      if ( $('#date_start').val() == "") {
        text += " "+assignment_date;
      }
      if ( $('#datepicker02').val() == "") {
        text += " "+start_time;
      }
      if ( $('#date_end').val() == "") {
        text += " "+homework_delivery_date;
      }
      if ( $('#datepicker03').val() == "") {
        text += " "+end_time;
      }
      if ( $('input[name="homework_name"]').val() == "") {
        text += " "+homework_topics;
      }
      if ( $('[name="course_group"]').val() == "") {
        text += " "+aptitude_t;
      }
      if ( $('[name="course_subject"]').val() == "") {
        text += " "+subject_t;
      }
      var dt = $('#date_start').val().split("/");
      var date_input = dt[2] +"-"+ dt[1] +"-"+ dt[0];
      var time_s = $('#datepicker02').val();

      var dt2 = $('#date_end').val().split("/");
      var date_input2 = dt2[2] +"-"+ dt2[1] +"-"+ dt2[0];
      var time_e = $('#datepicker03').val();

      var n1 = new Date(date_input+" : "+time_s); // วันที่ เวลา ให้การบ้าน
      var n3 = new Date(date_input2+" : "+time_e); // วันที่ เวลา ส่งการบ้าน
      var n2 = new Date(); // วันที่ปัจจุบัน
      // console.log(n1,n2);

      if ( n1 >= n2) { // วันที่ เวลา ให้การบ้าน มากกว่า วันที่ปัจจุบัน
        if(n1 > n3){ // วันที่ เวลา ให้การบ้าน มากกว่า วันที่ เวลา ส่งการบ้าน
          Swal.fire({
                      type: 'warning',
                      title: start_date_over,
                      confirmButtonColor: '#28a745',
                      confirmButtonText : close_window,
                      });
          return false;
        }

      }
      else { // วันที่ เวลา ให้การบ้าน น้อยกว่า วันที่ปัจจุบัน
        Swal.fire({
            type: 'warning',
            title: start_date_over,
            confirmButtonColor: '#28a745',
            confirmButtonText : close_window,
            });
        return false;
      }


      //
      // if ( $('#date_start').val() > $('#date_end').val()) {
      //   Swal.fire({
      //               type: 'warning',
      //               title: enter_the_assignment,
      //               confirmButtonColor: '#28a745',
      //               confirmButtonText : close_window,
      //               });
      //   return false;
      // }

      // if ( $('#date_start').val()+''+$('#datepicker02').val() > $('#date_end').val()+''+$('#datepicker03').val() ) {
      //   Swal.fire({
      //               type: 'warning',
      //               title: enter_the_start_time,
      //               confirmButtonColor: '#28a745',
      //               confirmButtonText : close_window,
      //               });
      //   return false;
      // }

      if (text != '') {
        Swal.fire({
                    type: 'warning',
                    title: please+' '+text,
                    confirmButtonColor: '#28a745',
                    confirmButtonText : close_window,
                    });
      }
      else {
        var date_s = $('#date_start').val();
        var date_start = date_s.split("/");

        var date_e = $('#date_end').val();
        var date_end = date_e.split("/");

        var data = {
        "subject_id": $('[name="course_subject"]').val(),
        "assignment_name": $('input[name="homework_name"]').val(),
        "assignment_detail": "",
        "assignment_date_start": date_start[0]+'-'+date_start[1]+'-'+date_start[2],
        "assignment_time_start": $('#datepicker02').val(),
        "assignment_date_end": date_end[0]+'-'+date_end[1]+'-'+date_end[2],
        "assignment_time_end": $('#datepicker03').val(),
        "assignment_status": 0,
        "assignment_teacher": {
            "teacher_id": $('input[name="teacher_id"]').val(),
            "teacher_fname": $('input[name="teacher_fname"]').val(),
            "teacher_lname": $('input[name="teacher_lname"]').val(),
            "teacher_email": $('input[name="teacher_email"]').val()
        },
        "teacher_id": $('input[name="teacher_id"]').val(),
        "student_classroom": $('#select_student_level option:selected').text(),
        "aptitude_id": $('[name="course_group"]').val(),
        "assignment_student" : $('[name="select_student_level"]').val(),
        }

        $('#homework_topics').prop('disabled', true);
        $('.spin').addClass('fa fa-spinner fa-spin');

        $.ajax({
          url: window.location.origin + '/homework/from_create/create_homework_topics',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: 'post',
          data: {
            'data': data,
          },
          dataType: "json",
          success: function(data) {
              // console.log(data);

            if (data.status == true) {
              Swal.fire({
                type: 'success',
                title: 'บันทึกสำเร็จ',
                showConfirmButton: false,
                timer: 1500
              })
            }

            setTimeout(function () {
                window.location.href = '/homework/list';
              }, 2000);

          }
        });
      }
    }

</script>

@stop
