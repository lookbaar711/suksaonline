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
    div > .alert-secondary {
        color: #464a4e;
        background-color: white ;
        border-color: white ;
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

    .answer_desktop {
        margin-top: 150px;
    }
    .label_desktop {
        margin-left: 40px;
    }
    .matching_answer {
        width: 65%;
        float: right;
    }
    .decoration {
        text-align: center;
    }
    .decoration-inside{
        margin-left: 80px;
        height: 400px;
        width: 1px;
        display: block;
        background-color: #ccc;
    }
    div > .blank {
        margin-bottom: 10px;
    }
    @media (max-width: 991px){
        .answer_desktop {
        margin-top: 0px;
        margin-bottom: 5px;
    }
    .label_desktop {
        margin-left: 0px;
    }
    }
  </style>
<section class="p-t-50 p-b-65">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item" aria-current="page"><a href="{{url('homework/list')}}">{{trans('frontend/homework/title.create_homework')}}</a></li>
        <li class="breadcrumb-item" aria-current="page"><a href="{{url('homework/teacher/manage/'.$assignment->_id)}}">{{$assignment->assignment_name}}</a></li>
        <li class="breadcrumb-item active" aria-current="page">@lang('frontend/homework/title.matching')</li>
      </ol>
    </nav>
    <span class="red">*กรอกคำถาม-คำตอบของแต่ละข้อให้เหมือนกัน โดยทางระบบจะสุ่มคำตอบให้กับนักเรียนอัตโนมัติ</span>
    <hr>
    <form action="{{!empty($datas) ? route('homework.teacher.edit.matching') : route('homework.teacher.create.matching')}}" id="formsave" method="POST" class="" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="box">
        @if(!empty($datas))
        <div class="boxQueurion">
            @foreach ($datas->questions as $key => $data)
            <div class="alert alert-secondary num_question num_{{$data['question_on']}}" data-question="{{$data['question_on']}}" dataquestion="{{$data['question_on']}}"  data-id="{{$data['question_on']}}" role="alert">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-1">
                                <label for="exampleInputEmail1">{{trans('frontend/homework/title.no')}} <label class="question_num">{{$data['question_on']}}</label></label>
                            </div>
                            <div class="col-sm-1">
                                <div class="row">
                                    <input name="score_question[{{$data['question_on']}}]" id="score_question_{{$data['question_on']}}" class="form-control munber_input" type="text" value="{{$data['question_score']}}">
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <label >{{trans('frontend/homework/title.score')}} <span class="red">*</span></label>
                            </div>
                            <div class="col-sm-2">
                                {{-- <button type="button" class="btn btn-danger btn-sm col-12 remove_question" style="color: white; height: 38px;">
                                    {{trans('frontend/homework/title.delete')}}
                                </button> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="question_answer">
                            <label class="title_question">{{trans('frontend/homework/title.question')}}</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                    </div>
                    <div class="col-sm-5">
                        <div class="question_answer">
                            <label for="title_answer">{{trans('frontend/homework/title.response')}}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <textarea name="question[{{$data['question_on']}}]" id="question_{{$data['question_on']}}" class="editorTinyquestion form-control question editorTiny col-12">{{$data['question']}}</textarea>
                    </div>
                    <div class="col-sm-1">
                        <div class="decoration">
                            <div class="decoration-inside">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="answer_desktop">
                            <input name="answer[{{$data['question_on']}}]" id="answer_{{$data['question_on']}}" class="form-control munber_input matching_answer" type="hidden" value="{{$data['answer']}}">
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <textarea name="choice[{{$data['question_on']}}]" id="choice_{{$data['question_on']}}" class="editorTinyanswer form-control question editorTiny col-12">{{$data['choice']}}</textarea>
                    </div>
                </div>
                <div class="blank"></div>
                <div class="row">
                    @if(count($datas->questions) == $key+1)
                        <button type="button" class="btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 add_question" style="color: white; height: 38px;">
                            <label class="colorz" style="margin-top: 10px;">{{trans('frontend/homework/title.AddQuestions')}}</label>
                        </button>
                    @else
                        <button type="button" class="btn btn-danger btn-sm col-12 remove_question" style="color: white; height: 38px;">
                            {{trans('frontend/homework/title.delete')}}
                        </button>
                    @endif
                </div>
            </div>
            @endforeach
            <div class="add_question_text"></div>
        </div>
        @else
            <div class="boxQueurion">
                <div class="alert alert-secondary num_question num_1" data-question="1" data-id="1" dataquestion="1" role="alert">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-1">
                                    <label for="exampleInputEmail1">{{trans('frontend/homework/title.no')}} <label class="question_num">1</label></label>
                                </div>
                                <div class="col-sm-1">
                                    <div class="row">
                                        <input name="score_question[1]" id="score_question_1" class="form-control munber_input" type="text" value="">
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <label >{{trans('frontend/homework/title.score')}} <span class="red">*</span></label>
                                </div>
                                <div class="col-sm-2">
                                    {{-- <button type="button" class="btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 add_question" style="color: white; height: 38px;">
                                        <label class="colorz" style="margin-top: 10px;">{{trans('frontend/homework/title.AddQuestions')}}</label>
                                    </button> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="question_answer">
                                <label class="title_question">{{trans('frontend/homework/title.question')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-2">

                        </div>
                        <div class="col-sm-5">
                            <div class="question_answer">
                                <label for="title_answer">{{trans('frontend/homework/title.response')}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                        <textarea name="question[1]" id="question_1" class="editorTinyquestion form-control question editorTiny col-12"></textarea>
                        </div>
                        <div class="col-sm-1">
                            <div class="decoration">
                                <div class="decoration-inside">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="answer_desktop">
                                <input name="answer[1]" id="answer_1" class="form-control munber_input matching_answer" type="hidden" value="1">
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <textarea name="choice[1]" id="choice_1" class="editorTinyanswer form-control question editorTiny col-12"></textarea>
                        </div>
                    </div>
                    <div class="blank"></div>
                    <div class="row">
                        <button type="button" class="btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 add_question" style="color: white; height: 38px;">
                            {{trans('frontend/homework/title.AddQuestions')}}
                        </button>
                    </div>
                </div>

                <div class="row">
                </div>
                <div class="add_question_text"></div>
            </div>
        @endif
    </div>
        {{-- <div class=""> --}}
            <div class="row">
                <div class="col-sm-8"></div>
                <div class="col-sm-2 btn-moble">
                    <a href="{{url('homework/teacher/manage/'.$assignment->_id)}}" class="btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 btnsubmit btnclose" style="color: white;">
                    {{ trans('frontend/homework/title.colse') }}
                    </a>
                    <input type="hidden" name="assignment_id" value="{{$assignment->_id}}">
                    <input type="hidden" name="question_id" value="{{(!empty($datas) ? $datas->_id : '')}}">

                </div>
                <div class="col-sm-2 btn-moble">
                    <button type="submit" id="submit" class="btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 btnsubmit" style="color: white;">
                        {{ trans('frontend/homework/title.savedata') }}
                    </button>
                </div>
            </div>
        {{-- </div> --}}
    </form>
  </div>
</section>
<script src="{{ url('suksa/frontend/template/js/matching-create.js') }}"></script>
<script >
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
    var question = '{{trans('frontend/homework/title.question')}}';
    var btndel = '{{trans('frontend/homework/title.delete')}}';
    var assignments_30 = '{{trans('frontend/homework/title.assignments_30')}}';
    var the_system_save_data = '{{ trans('frontend/homework/title.the_system_save_data') }}';

    $('#submit').click(function(e){
        if($('.num_question').length >= 10)
        {
            ids = $('.num_question');
            ids.sort();
            $.each(ids, function (key, val) {
                id = key+1
                id_check = $('.num_'+id).attr('data-id');
                title_no = $('.num_'+id).attr('dataquestion')
                if($('#question_'+id_check+'_ifr').contents().find('body').children('p').children('img').length == 0 && $('#question_'+id_check+'_ifr').contents().find('body').text().trim().length == 0)
                {
                    e.preventDefault();
                    Swal.fire({
                        title: '<strong>'+pleasequestion+no_number+title_no+'</strong>',
                        type: 'error',
                        showCloseButton: false,
                        showCancelButton: false,
                        focusConfirm: false,
                        confirmButtonColor: '#28a745',
                        confirmButtonText: close_window,
                    })
                }else if($('#choice_'+id_check+'_ifr').contents().find('body').children('p').children('img').length == 0 && $('#choice_'+id_check+'_ifr').contents().find('body').text().trim().length == 0){
                    e.preventDefault();
                    Swal.fire({
                        title: '<strong>'+pleasequestion+no_number+title_no+'</strong>',
                        type: 'error',
                        showCloseButton: false,
                        showCancelButton: false,
                        focusConfirm: false,
                        confirmButtonColor: '#28a745',
                        confirmButtonText: close_window,
                    })
                }else if($('#answer_'+id_check).val() == null || $('#answer_'+id_check).val() == ''){
                    e.preventDefault();
                    Swal.fire({
                        title: '<strong>'+pleaseanswer+no_number+title_no+'</strong>',
                        type: 'error',
                        showCloseButton: false,
                        showCancelButton: false,
                        focusConfirm: false,
                        confirmButtonColor: '#28a745',
                        confirmButtonText: close_window,
                    })
                }else if($('#score_question_'+id_check).val() == null || $('#score_question_'+id_check).val() == ''){
                    e.preventDefault();
                    Swal.fire({
                        title: '<strong>'+pleasescore+no_number+title_no+'</strong>',
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
            });



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
