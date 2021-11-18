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

  @endphp

<style type="text/css">
    .container {
      max-width: 1240px;
    }
    .red{
        color: red;
    }
    .btnsubmit {
    border-radius: 23px;
    width: 320px;
    height: 45px;
    font-size: 20px;
    font-family: 'Kanit', sans-serif;
    }
    .btnclose{
        background: linear-gradient(to right, #c04617 0%, #dc3545 100%);
        font-size: 20px;
    }
    button.btn.flex-c-m.s-text3.bgwhite.hov1.trans-0-5.col-12.add_question {
    background: #444472;
}
  </style>
<section class="p-t-50 p-b-65">
  <div class="container">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item" aria-current="page"><a href="{{url('homework/list')}}">{{trans('frontend/homework/title.create_homework')}}</a></li>
          <li class="breadcrumb-item" aria-current="page"><a href="{{url('homework/teacher/manage/'.$assignment->_id)}}">{{$assignment->assignment_name}}</a></li>
          <li class="breadcrumb-item active" aria-current="page">@lang('frontend/homework/title.shotanswer')</li>
        </ol>
    </nav>

    <hr>
    <form action="{{!empty($datas) ? route('homework.teacher.edit.shotanswer') : route('homework.teacher.create.shotanswer')}}" id="formsave" method="POST" class="" enctype="multipart/form-data">
        {{csrf_field()}}
        @if(!empty($datas))
        <div class="boxQueurion">
            @foreach ($datas->questions as $key => $data)
                <div class="alert alert-secondary num_question num_{{$data['question_on']}}" data-question="{{$data['question_on']}}" data-id="{{$data['question_on']}}" dataquestion="{{$data['question_on']}}" role="alert">
                    <label for="exampleInputEmail1"><label class="question_num">{{trans('frontend/homework/title.no')}} {{$data['question_on']}}</label></label></br></br>
                    <div class="form-group">
                        {{-- <input name="image_question" type="file" id="question_1_upload" accept=".png, .jpg, .jpeg"  class="hidden" onchange=""> --}}
                        <textarea name="question[{{$data['question_on']}}]" id="question_{{$data['question_on']}}" class="form-control question editorTiny col-12">{{$data['question']}}</textarea>
                    </div>
                    <div class="form-group">
                        <label >{{trans('frontend/homework/title.response')}} :<span class="red">*</span></label>
                        <input name="answer[{{$data['question_on']}}]" id="answer_{{$data['question_on']}}" class="form-control" value="{{$data['answer']}}" type="text">
                    </div>
                    <div class="form-group">
                        <label >{{trans('frontend/homework/title.score')}} :<span class="red">*</span></label>
                        <input name="score_question[{{$data['question_on']}}]" id="score_question_{{$data['question_on']}}" value="{{$data['question_score']}}" class="form-control munber_input" type="text">
                    </div>
                    <br>
                    @if(count($datas->questions) == $key+1)
                    <button type="button" class="btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 add_question" style="color: white; height: 38px;">
                        <label class="colorz" style="margin-top: 10px;">{{trans('frontend/homework/title.AddQuestions')}}</label>
                    </button>
                    @else
                    <button type="button" class="btn btn-danger btn-sm col-12 remove_question" style="color: white; height: 38px;">
                        <label class="colorz" style="margin-top: 10px;">{{trans('frontend/homework/title.delete')}}</label>
                    </button>
                    @endif
                </div>
            @endforeach
            <div class="add_question_text"></div>
        </div>
        @else
        <div class="boxQueurion">
            <div class="alert alert-secondary num_question num_1" data-question="1" data-id="1" dataquestion="1" role="alert">
                <label for="exampleInputEmail1"><label class="question_num">{{trans('frontend/homework/title.no')}} 1</label></label></br></br>
                <div class="form-group">
                    {{-- <input name="image_question" type="file" id="question_1_upload" accept=".png, .jpg, .jpeg"  class="hidden" onchange=""> --}}
                    <textarea name="question[1]" id="question_1" class="form-control question editorTiny col-12"></textarea>
                </div>
                <div class="form-group">
                    <label >{{trans('frontend/homework/title.response')}} :<span class="red">*</span></label>
                    <input name="answer[1]" id="answer_1" class="form-control" type="text">
                </div>
                <div class="form-group">
                    <label >{{trans('frontend/homework/title.score')}} :<span class="red">*</span></label>
                    <input name="score_question[1]" id="score_question_1" class="form-control munber_input" type="text">
                </div>
                <br>
                <button type="button" class="btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 add_question" style="color: white; height: 38px;">
                    <label class="colorz" style="margin-top: 10px;">{{trans('frontend/homework/title.AddQuestions')}}</label>
                </button>
            </div>
            <div class="add_question_text"></div>
        </div>
        @endif


        <div class="row">
            {{-- <div class="col-sm-12"> --}}
            <div class="col-sm-8"></div>
            <div class="col-sm-2">
                <a href="{{url('homework/teacher/manage/'.$assignment->_id)}}" class="btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 btnsubmit btnclose" style="color: white;">
                {{ trans('frontend/homework/title.colse') }}
                </a>
                <input type="hidden" name="assignment_id" value="{{$assignment->_id}}">
                <input type="hidden" name="question_id" value="{{(!empty($datas) ? $datas->_id : '')}}">

            </div>
            <div class="col-sm-2">
                <button type="submit" id="submit" class="btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 btnsubmit" style="color: white;">
                    {{ trans('frontend/homework/title.savedata') }}
                </button>
            </div>
            {{-- <div class="col-sm-2"></div> --}}

            {{-- </div> --}}

        </div>
    </form>
  </div>
</section>
<script src="{{ url('suksa/frontend/template/js/shotanswer-create.js') }}"></script>
<script>
     var close_window = '{{ trans('frontend/layouts/modal.close_window') }}';
    var pleasequestion = ' {{trans('frontend/homework/title.pleasequestion')}}';
    var pleaseanswer = ' {{trans('frontend/homework/title.pleaseanswer')}}';
    var pleasescore = ' {{trans('frontend/homework/title.pleasescore')}}';
    var notlow = ' {{trans('frontend/homework/title.notlow')}}';
    var loading = '{{ trans('frontend/homework/title.loading') }}';
    var no_number = '{{trans('frontend/homework/title.no')}}';
    var response = '{{trans('frontend/homework/title.response')}}';
    var score = '{{trans('frontend/homework/title.score')}}';
    var addquestions = '{{trans('frontend/homework/title.AddQuestions')}}';
    var btndel = '{{trans('frontend/homework/title.delete')}}';
    var assignments_30 = '{{trans('frontend/homework/title.assignments_30')}}';
    var the_system_save_data = '{{ trans('frontend/homework/title.the_system_save_data') }}';
        $('#submit').click(function(e){
            if($('.num_question').length >= 1)
            {
                id = $('.num_question').last().attr('data-id');
                if($('#question_'+id+'_ifr').contents().find('body').children('p').children('img').length == 0 && $('#question_'+id+'_ifr').contents().find('body').text().trim().length == 0)
                {
                    e.preventDefault();
                    Swal.fire({
                        title: '<strong>'+pleasequestion+'</strong>',
                        type: 'error',
                        showCloseButton: false,
                        showCancelButton: false,
                        focusConfirm: false,
                        confirmButtonColor: '#28a745',
                        confirmButtonText: close_window,
                    })
                }else if($('#answer_'+id).val() == null || $('#answer_'+id).val() == ''){
                    e.preventDefault();
                    Swal.fire({
                        title: '<strong>'+pleaseanswer+'</strong>',
                        type: 'error',
                        showCloseButton: false,
                        showCancelButton: false,
                        focusConfirm: false,
                        confirmButtonColor: '#28a745',
                        confirmButtonText: close_window,
                    })
                }else if($('#score_question_'+id).val() == null || $('#score_question_'+id).val() == ''){
                    e.preventDefault();
                    Swal.fire({
                        title: '<strong>'+pleasescore+'</strong>',
                        type: 'error',
                        showCloseButton: false,
                        showCancelButton: false,
                        focusConfirm: false,
                        confirmButtonColor: '#28a745',
                        confirmButtonText: close_window,
                    })
                }else{
                    Swal.fire({
                        title: the_system_save_data,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        onBeforeOpen: () => {
                        Swal.showLoading()
                        },
                    });
                 $('#formsave').submit();
                }

            }else{
                e.preventDefault();
                Swal.fire({
                        title: '<strong>'+notlow+'</strong>',
                        type: 'error',
                        showCloseButton: false,
                        showCancelButton: false,
                        focusConfirm: false,
                        confirmButtonColor: '#28a745',
                        confirmButtonText: close_window,
                    })
            }
        });
</script>
@stop
