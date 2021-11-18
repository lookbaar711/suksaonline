@extends('frontend/default')


@section('css')
    <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/css/profile.css') !!}">
    <style>
        #font-ext {
            font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif !important;
            font-size: 18px;
        }
        .container-fluid {
        max-width: 1230px;
        }
        .ext-btn {
        margin-bottom: 30px;
        margin-top: 30px;
        margin-left: auto;
        margin-right: auto;
        }
        .btn-submit {
        border-radius: 23px;
        width: 320px;
        height: 40px;
        background: linear-gradient(to right, #17a7c0 0%, #7de442 100%);
        color: white;
        font-size: 20px;
        }
        .question-matching {
            padding: 15px;
        }
        .box-tureorfalse{
            padding: 10px;
        }
        img.img-tureorfalse {
            margin-left: 5px;
            margin-right: 5px;
        }
        .number_question {
            display: inline;
            margin-right: 10px;
        }
        .title_questions > p {
            display: inline;
        }
        strong {
          display: inline;
        }
        p {
          display: inline;
          word-wrap: break-word;
        }
        h1, h2, h3, h4, h5 {
          display: inline;
        }
        span {
          display: inline;
        }
        .st p > img {
            display: flex;
        }
        img {
            vertical-align: text-top;
            border-style: none;
        }
        .text_question {
            display: inline-flex;
        }
    </style>
@endsection

@section('content')
<section class="p-t-50 p-b-30">
    <div class="container">

        <div align="center" style="margin-top : 30px; margin-bottom : 30px;">
            <i class="fa fa-book hat fa-3x" style="color: aquamarine;"></i>
            <br>
            <h3>@lang('frontend/homework/title.checkedhomework'){{trans('frontend/homework/title.longanswer')}}</h3>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item" aria-current="page"><a href="{{url('homework/teacher')}}">{{trans('frontend/homework/title.checkedhomework')}}</a></li>
              <li class="breadcrumb-item" aria-current="page"><a href="{{url('homework/teacher/assignment/'.$questions->assignment_id)}}">{{$questions->getAssignment->assignment_name}}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{trans('frontend/homework/title.longanswer')}}</li>
            </ol>
        </nav>
        <hr>

        <form action="{{route('homework.teachercheck')}}" method="post" id="form_pretest" enctype="multipart/form-data">
            <div class="container-fluid" id="font-ext">
                {{ csrf_field() }}
                @forelse ($questions->questions as $key => $item)
                {{-- {{ dd($item) }} --}}
                <div class="question-matching">
                    <div class="row">
                      <label class="title_questions">
                          <div class="number_question">{{$item['question_on']}}.</div>
                      </label>
                      <div class="st">
                          {!!$item['question']!!}
                      </div>
                          <div class="question_score">&ensp; <p style="color: #ec7a18;"> {{ "(".$item['question_score']." ".trans('frontend/homework/title.score').")" }} </p></div>
                        {{-- <label class="title_questions">
                            <div class="number_question">{{$item['question_on']}}.</div><p>{!!$item['question']!!} </p><p style="color: #ec7a18;"> {{ "(".$item['question_score']." ".trans('frontend/homework/title.score').")" }} </p>
                        </label> --}}
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                                <textarea
                                class="form-control"
                                disabled>{{(!empty($studemake) && $studemake->questions_make[$key]['question_on'] == $item['question_on'] ? $studemake->questions_make[$key]['select_answer'] : '')}}</textarea>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="form-inline col-6">
                            <div class="form-group col-12">
                                {{ trans('frontend/homework/title.to_rate')." : " }} &nbsp;<span style="color: red; font-size: 20px;" > * </span> &nbsp;
                                <input type="text" class="form-control col-3" onkeyup="question_check(this,{{ $item['question_score'] }})" name="question_check[]" min="0" max="{{ $item['question_score'] }}"
                                value="{{(!empty($studemake) ? $studemake->teacher_check[$key]['select'] : '')}}" required>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                @endforelse
                <div class="row">
                    <div class="ext-btn">
                        <div class="col-sm-12">
                            <input type="hidden" name="assignment_id" value="{{$questions->assignment_id}}">
                            <input type="hidden" name="assignment_questions_id" value="{{$questions->_id}}">
                            <input type="hidden" name="student_id" value="{{$studemake->assigment_student_id}}">
                            <button type="submit" class="btn btn-submit">@lang('frontend/homework/title.checkedhomework')</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<script>
    var score = '{{trans('frontend/homework/title.score')}}';
    var max_score = '{{trans('frontend/homework/title.max_score')}}';
    let question_check = (e , max) =>{
    if (e.value > max) {
        Swal.fire({
                type: 'info',
                title: max_score+" "+ max +" "+score,
                confirmButtonColor: '#28a745',
                confirmButtonText : close_window
            })
      e.value = max
    }
  }
</script>
@stop
