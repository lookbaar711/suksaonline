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
        /* .title_questions > p {
            display: inline;
        } */
        p {
            color: black;
        }
        .box-check-s {
            margin-top: 10px;
        }
        .score-label{
            font-size: 18px;
            color: #007bff;
        }
        .ext-question {
            font-size: '16px';
            margin-left: '10px';
        }
        .ext-tititle p {
        font-size: 22px;
        }
        .ext-choice {
        margin-left: 30px;
        margin-top: 0px;
        margin-bottom: 5px;
        }
        strong {
          /* display: inline; */
        }
        p {
          display: inline;
          word-wrap: break-word;
        }
        .st h1, .st h2, .st h3, .st h4, .st h5 {
            display: inline;
            /* display: block !important;
            margin-top: -10px !important; */
        }
        span {
          /* display: inline; */
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
        .question_score {
            display: inline;
            color: #ec7a18;
        }
    </style>
@endsection

@section('content')
<section class="p-t-50 p-b-30">
    <div class="container">

        <div align="center" style="margin-top : 30px; margin-bottom : 30px;">
            <i class="fa fa-book hat fa-3x" style="color: aquamarine;"></i>
            <br>
            <h3>@lang('frontend/homework/title.makehomework'){{trans('frontend/homework/title.longanswer')}}</h3>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item" aria-current="page"><a href="{{url('homework/assignment')}}">{{trans('frontend/homework/title.makehomework')}}</a></li>
              <li class="breadcrumb-item" aria-current="page"><a href="{{url('assignment/list/'.$questions->assignment_id)}}">{{$questions->getAssignment->assignment_name}}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{trans('frontend/homework/title.longanswer')}}({{trans('frontend/homework/title.assay')}})</li>
            </ol>
        </nav>
        <hr>
            <div class="container-fluid" id="font-ext">
                {{ csrf_field() }}
                @forelse ($questions->questions as $key => $item)
                <div class="question-matching">

                  {{-- <div class="ext-tititle">
                      <div class="row">
                          <div class="ext-question">
                                {{$item['question_on']}}. {!!$item['question']!!} <p style="color: #ec7a18;"> {{ "(".$item['question_score']." ".trans('frontend/homework/title.score').")" }} </p>
                          </div>
                      </div>
                  </div> --}}

                    <div class="row">
                      <label class="title_questions">
                          <div class="number_question">{{$item['question_on']}}.</div>
                      </label>
                      <div class="st">
                          {!!$item['question']!!}
                      </div>
                          <div class="question_score">&ensp; {{ "(".$item['question_score']." ".trans('frontend/homework/title.score').")" }}</div>
                        {{-- <label class="title_questions">
                            <div class="number_question">
                              {{$item['question_on']}}. {!!$item['question']!!} <p style="color: #ec7a18; display: inline;"> {{ "(".$item['question_score']." ".trans('frontend/homework/title.score').")" }} </p>
                            </div>
                        </label> --}}
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                                <textarea class="form-control" rows="15" disabled>{{(!empty($studemake) && $studemake->questions_make[$key]['question_on'] == $item['question_on'] ? $studemake->questions_make[$key]['select_answer'] : '')}}</textarea>
                        </div>
                    </div>
                    <div class="box-check-s">
                        <div class="row">
                            <div class="col-sm-5">
                                <label class="score-label"> คะแนนที่ได้ : {{$studemake->teacher_check[$key]['select']}} คะแนน</label>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                @endforelse
            </div>
    </div>
</section>
<script>
    $('.inputnumber').keyup(function(){

    })
</script>
@stop
