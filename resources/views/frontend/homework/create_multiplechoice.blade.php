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
    .form-check-input {
        position: absolute;
        margin-top: .25rem;
        margin-left: -0.25rem;
    }
    .red{
        color: red;
    }
    .btnclose{
        background: linear-gradient(to right, #c04617 0%, #dc3545 100%);
    }
    button.btn.flex-c-m.s-text3.bgwhite.hov1.trans-0-5.col-12.add_question {
      background: #444472;
    }
    .btnsubmit {
      border-radius: 23px;
      width: 320px;
      height: 45px;
      font-size: 20px;
      font-family: 'Kanit', sans-serif;
    }
  </style>
<section class="p-t-50 p-b-65">
  <div class="container">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item" aria-current="page"><a href="{{url('homework/list')}}">{{trans('frontend/homework/title.create_homework')}}</a></li>
        <li class="breadcrumb-item" aria-current="page"><a href="{{url('homework/teacher/manage/'.$assignment->_id)}}">{{$assignment->assignment_name}}</a></li>
        <li class="breadcrumb-item active" aria-current="page"> {{trans('frontend/homework/title.multiplechoice')}}</li>
      </ol>
    </nav>

    <br>
    @if (!empty($datas))

    <div class="form-row">
      <div class="col-md-12">
        <label class="col-md control-label p-l-0">{{trans('frontend/homework/title.homework_example')}} : <span style="color: red; font-size: 20px;" >*</span>
          <u><a href="{{ url('suksa/frontend/template/ex_course/สร้างการบ้านปรนัย.xlsx') }}" class="btn btn-donwload"><i class="fa fa-download" aria-hidden="true"></i> @lang('frontend/courses/title.sample_file')</a></u>
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

          <button type="button" class="btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12" style="color: white; height: 38px;" onclick="multiplechoice_extarea.up_quiz_edit_file()">
            <label class="colorz" style="margin-top: 10px;"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;@lang('frontend/courses/title.Import_data') </label>
          </button>
        </div>
      </div>
    </div>

    @else

    <div class="form-row">
      <div class="col-md-12">
        <label class="col-md control-label p-l-0">{{trans('frontend/homework/title.homework_example')}} : <span style="color: red; font-size: 20px;" >*</span>
          <u><a href="{{ url('suksa/frontend/template/ex_course/สร้างการบ้านปรนัย.xlsx') }}" class="btn btn-donwload"><i class="fa fa-download" aria-hidden="true"></i> @lang('frontend/courses/title.sample_file')</a></u>
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

          <button type="button" class="btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12" style="color: white; height: 38px;" onclick="multiplechoice_extarea.up_quiz_file()">
            <label class="colorz" style="margin-top: 10px;"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;@lang('frontend/courses/title.Import_data') </label>
          </button>
        </div>
      </div>
    </div>

    @endif

    <br>

    <form action="{{!empty($datas) ? route('homework.teacher.edit.multiplechoice') : route('homework.teacher.create.multiplechoice')}}" method="POST" id="multiplechoice" class="" enctype="multipart/form-data">
      {{csrf_field()}}
      @php
          $i = 1;
      @endphp
      @if(!empty($datas))
              @foreach ($datas->questions as $key => $data)
              {{-- {{ dd($data) }} --}}
              {{-- @php
                  print_r('<pre>');
                  print_r($data['answer']);
                  print_r('<pre>');
              @endphp --}}
              <div class="boxQueurion">
                  <div class="alert alert-secondary num_question num_{{$data['question_on']}}" data-question="{{$data['question_on']}}" dataquestion="{{$data['question_on']}}" role="alert">
                      <label for="exampleInputEmail1">{{trans('frontend/homework/title.no')}} <label>{{$data['question_on']}}</label></label></br></br>
                  <div class="form-group">
                      <input name="image_question" type="file" id="question_{{$data['question_on']}}_upload" accept=".png, .jpg, .jpeg"  class="hidden" onchange="">
                      <textarea name="question[{{$data['question_on']}}]" id="question_{{$data['question_on']}}" class="textarea{{$data['question_on']}} editorTinyquestion form-control question editorTiny col-12">{{$data['question']}}</textarea>
                  </div>

                  <div class="form-group">
                    <div class="row">
                      <div class="form-row col-md-6">
                        <div class="col-1">
                          <div class="form-check">
                            <input class="form-check-input question" type="radio" name="answer{{ $i }}" id="gridRadios1{{ $i }}" value="1" {{ $data['answer'] == '1' ? 'checked' : '' }} required>
                            <label class="form-check-label" for="gridRadios1{{ $i }}">A</label>
                          </div>
                        </div>
                        <div class="form-group col-md-10">
                          <input type="text" name="answer_texta[]" class="form-control question" value=" {{$data['choice_1']}}" autocomplete="off" placeholder="@lang('frontend/courses/title.answer') 1" required>
                        </div>
                      </div>
                      <div class="form-row col-md-6">
                        <div class="col-1">
                          <div class="form-check">
                            <input class="form-check-input question" type="radio" name="answer{{ $i }}" id="gridRadios2{{ $i }}" value="2" {{ $data['answer'] == '2' ? 'checked' : '' }}  required>
                            <label class="form-check-label" for="gridRadios2{{ $i }}">B</label>
                          </div>
                        </div>
                        <div class="form-group col-md-10">
                          <input type="text" name="answer_textb[]" class="form-control question" value=" {{$data['choice_2']}}" autocomplete="off" placeholder="@lang('frontend/courses/title.answer') 2" required>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-row col-md-6">
                        <div class="col-1">
                          <div class="form-check">
                            <input class="form-check-input question" type="radio" name="answer{{ $i }}" id="gridRadios3{{ $i }}" value="3" {{ $data['answer'] == '3' ? 'checked' : '' }}  required>
                            <label class="form-check-label" for="gridRadios3{{ $i }}">C</label>
                          </div>
                        </div>
                        <div class="form-group col-md-10">
                          <input type="text" name="answer_textc[]" class="form-control question" value=" {{$data['choice_3']}}" autocomplete="off" placeholder="@lang('frontend/courses/title.answer') 3" required>
                        </div>
                      </div>
                      <div class="form-row col-md-6">
                        <div class="col-1">
                          <div class="form-check">
                            <input class="form-check-input question" type="radio" name="answer{{ $i }}" id="gridRadios4{{ $i }}" value="4" {{ $data['answer'] == '4' ? 'checked' : '' }}  required>
                            <label class="form-check-label" for="gridRadios4{{ $i }}">D</label>
                          </div>
                        </div>
                        <div class="form-group col-md-10">
                          <input type="text" name="answer_textd[]" class="form-control question" value=" {{$data['choice_4']}}" autocomplete="off" placeholder="@lang('frontend/courses/title.answer') 4" required>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                      <label >{{trans('frontend/homework/title.score')}} : <span style="color: red; font-size: 20px;" >*</span></label>
                      <input name="score_question[{{$data['question_on']}}]" required class="form-control" type="text" value=" {{$data['question_score']}}">
                  </div>
                  <br>
                  @if (count($datas->questions) == ($key+1))
                  <button type="button" class="btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 add_question" style="color: white; height: 38px;">
                    <label class="colorz" style="margin-top: 5px;">{{trans('frontend/homework/title.AddQuestions')}}</label>
                 </button>
                  @else
                  <button type="button" class="btn btn-danger btn-sm col-12 remove_question" style="color: white; height: 38px;">
                    <label class="colorz" style="margin-top: 5px;">{{trans('frontend/homework/title.delete')}}</label>
                </button>
                  @endif
              </div>
              @php
                  $i++;
              @endphp
          @endforeach
          <div class="add_question_text"></div>
      @else
          <div class="boxQueurion">
              <div class="alert alert-secondary num_question num_1" data-question="1" dataquestion="1" role="alert">
                  <label for="exampleInputEmail1">{{trans('frontend/homework/title.no')}} <label>1</label> </label></br></br>
              <div class="form-group">
                  <input name="image_question" type="file" id="question_1_upload" accept=".png, .jpg, .jpeg"  class="hidden" onchange="">
                  <textarea name="question[]" id="question_1" class="textarea1 editorTinyquestion form-control question editorTiny col-12"></textarea>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="form-row col-md-6">
                    <div class="col-1">
                      <div class="form-check">
                        <input class="form-check-input question" type="radio" name="answer1" id="gridRadios1" value="1" required>
                        <label class="form-check-label" for="gridRadios1">A</label>
                      </div>
                    </div>
                    <div class="form-group col-md-10">
                      <input type="text" name="answer_texta[]" class="form-control question" autocomplete="off" placeholder="@lang('frontend/courses/title.answer') 1" required>
                    </div>
                  </div>
                  <div class="form-row col-md-6">
                    <div class="col-1">
                      <div class="form-check">
                        <input class="form-check-input question" type="radio" name="answer1" id="gridRadios2" value="2" required>
                        <label class="form-check-label" for="gridRadios2">B</label>
                      </div>
                    </div>
                    <div class="form-group col-md-10">
                      <input type="text" name="answer_textb[]" class="form-control question" autocomplete="off" placeholder="@lang('frontend/courses/title.answer') 2" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-row col-md-6">
                    <div class="col-1">
                      <div class="form-check">
                        <input class="form-check-input question" type="radio" name="answer1" id="gridRadios3" value="3" required>
                        <label class="form-check-label" for="gridRadios3">C</label>
                      </div>
                    </div>
                    <div class="form-group col-md-10">
                      <input type="text" name="answer_textc[]" class="form-control question" autocomplete="off" placeholder="@lang('frontend/courses/title.answer') 3" required>
                    </div>
                  </div>
                  <div class="form-row col-md-6">
                    <div class="col-1">
                      <div class="form-check">
                        <input class="form-check-input question" type="radio" name="answer1" id="gridRadios4" value="4" required>
                        <label class="form-check-label" for="gridRadios4">D</label>
                      </div>
                    </div>
                    <div class="form-group col-md-10">
                      <input type="text" name="answer_textd[]" class="form-control question" autocomplete="off" placeholder="@lang('frontend/courses/title.answer') 4" required>
                    </div>
                  </div>
                </div>
              </div>


              <div class="form-group">
                  <label >{{trans('frontend/homework/title.score')}} : <span style="color: red; font-size: 20px;" >*</span></label>
                  <input name="score_question[]" required class="form-control munber_input" type="text">
              </div>
              <br>
              <button type="button" class="btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 add_question" style="color: white; height: 38px;">
                <label class="colorz" style="margin-top: 5px;">{{trans('frontend/homework/title.AddQuestions')}}</label>
             </button>

              </div>
              <div class="add_question_text"></div>
          </div>
      @endif
      <div class="row">
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
            <i class="spin"> </i> &nbsp;{{ trans('frontend/homework/title.savedata') }}
        </button>
        </div>
    </div>

  </form>


  </div>
</section>
<script src="{{ url('suksa/frontend/template/js/multiplechoice-create.js?v=1.5') }}"></script>
<script >
 var close_window = '{{ trans('frontend/layouts/modal.close_window') }}';
 var score = '{{ trans('frontend/layouts/modal.score') }}';
 var pleasequestion = ' {{trans('frontend/homework/title.pleasequestion')}}';
 var pleaseanswer = ' {{trans('frontend/homework/title.pleaseanswer')}}';
 var pleasescore = ' {{trans('frontend/homework/title.pleasescore')}}';
 var please_select_an_answer = ' {{trans('frontend/homework/title.please_select_an_answer')}}';
 var notlow = ' {{trans('frontend/homework/title.notlow')}}';
 var loading = '{{ trans('frontend/homework/title.loading') }}';
 var no_number = '{{trans('frontend/homework/title.no')}}';
 var response = '{{trans('frontend/homework/title.response')}}';
 var score = '{{trans('frontend/homework/title.score')}}';
 var addquestions = '{{trans('frontend/homework/title.AddQuestions')}}';
 var remove_button = '{{trans('frontend/homework/title.remove_button')}}';
 var btndel = '{{trans('frontend/homework/title.delete')}}';
 var assignments_30 = '{{trans('frontend/homework/title.assignments_30')}}';
 var the_system_save_data = '{{ trans('frontend/homework/title.the_system_save_data') }}';

 $('#submit').click(function(e){

    var ck = true;
    var input = true;
    var score_question = true;
    var text_total = $('.num_question').length;

    var question_id=[];
    $('[name="question[]"]').each(function() {
      question_id.push($(this).attr('id'));
    });

    $.each(question_id, function(index, el) {
      // console.log(index);

      if ($('#'+el+'_ifr').contents().find('body').text().trim().length == 0) {
        Swal.fire({
            title: '<strong>'+pleasequestion+'</strong>',
            type: 'error',
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
        e.preventDefault();
        // console.log("12");
        return false;
      }
    });



    for (var i = 1; i <= text_total; i++) {


      if ($('[name="score_question['+i+']"]').val() == "") {
        score_question = false;
      }

      if ($("input[name='answer"+i+"']").length > 0) {
        if ($("input[name='answer"+i+"']:checked").val() == "" || $("input[name='answer"+i+"']:checked").val() == undefined) {
          // console.log("11111");
          Swal.fire({
              title: '<strong>'+please_select_an_answer+'</strong>',
              type: 'error',
              showCloseButton: false,
              showCancelButton: false,
              focusConfirm: false,
              confirmButtonColor: '#28a745',
              confirmButtonText: close_window,
          });
          e.preventDefault();
          // console.log("13");
          return false;
        }
      }



    }

    for (var y = 0; y < text_total; y++) {
      if ($("input[name='answer"+i+"']").length > 0) {
        if ($("input[name='answer"+i+"']:checked").val() == "" || $("input[name='answer"+i+"']:checked").val() == undefined) {
          // console.log("2222");
          Swal.fire({
              title: '<strong>'+please_select_an_answer+'</strong>',
              type: 'error',
              showCloseButton: false,
              showCancelButton: false,
              focusConfirm: false,
              confirmButtonColor: '#28a745',
              confirmButtonText: close_window,
          });
          e.preventDefault();
          // console.log("14");
          return false;
        }
      }

    }



    if (text_total < 10) {
      Swal.fire({
                title: '<strong>'+notlow+'</strong>',
                type: 'error',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            e.preventDefault();
            console.log("15");
            return false;
    }
    if (input == true) {
        $('[name="answer_texta[]"]').each(function() {
            if ($(this).val() == "") {
                input = false;
                e.preventDefault();
            }
        });
    }
    if (input == true) {
        $('[name="answer_textb[]"]').each(function() {
            if ($(this).val() == "") {
                input = false;
                e.preventDefault();
            }
        });
    }
    if (input == true) {
        $('[name="answer_textc[]"]').each(function() {
            if ($(this).val() == "") {
                input = false;
                e.preventDefault();
            }
        });
    }
    if (input == true) {
        $('[name="answer_textd[]"]').each(function() {
            if ($(this).val() == "") {
                input = false;
                e.preventDefault();
            }
        });
    }

    $('[name="score_question[]"]').each(function() {
        if ($(this).val() == "") {
            Swal.fire({
                title: '<strong>'+pleasescore+'</strong>',
                type: 'error',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
            e.preventDefault();
            score_question = false;
            return false;
        }
    });
    // console.log(input);

    if (input != true) {
        Swal.fire({
            title: '<strong>'+pleasequestion+'</strong>',
            type: 'error',
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
        e.preventDefault();
        return false;
    }else{
      if (score_question == false) {
        Swal.fire({
            title: '<strong>'+pleasescore+'</strong>',
            type: 'error',
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
        e.preventDefault();
        return false;
      } else {
        Swal.fire({
            title: the_system_save_data,
            allowOutsideClick: false,
            allowEscapeKey: false,
            onBeforeOpen: () => {
            Swal.showLoading()
            },
        });
        return true;
      }


    }
 });

</script>
@stop
