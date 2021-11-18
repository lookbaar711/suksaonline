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
        .ext-tititle p {
        font-size: 22px;
        }
        .ext-choice {
        margin-left: 30px;
        margin-top: 0px;
        margin-bottom: 5px;
        }
        .radio {
        position: relative;
        display: block;
        margin-top: 10px;
        margin-bottom: 10px;
        }
        .label-choice {
        min-height: 20px;
        padding-left: 20px;
        margin-bottom: 0;
        font-weight: 400;
        cursor: pointer;
        max-width: 100%;
        }
        .radio input[type=radio] {
        position: absolute;
        margin-left: -20px;
        margin-top: 4px;
        }
        .btn-submit {
        border-radius: 23px;
        width: 320px;
        height: 40px;
        background: linear-gradient(to right, #17a7c0 0%, #7de442 100%);
        color: white;
        font-size: 20px;
        font-family: 'Kanit', sans-serif;
        }
        .ext-question {
            font-size: '16px';
            margin-left: '10px';
        }
        .ext-question p {
            font-size: '16px';
            display: inline;
        }
        p {
            color: black;
        }
        .question_score {
            display: inline;
            color: #ec7a18;
        }
    </style>
@endsection

@section('content')
<section>
    <div class="container">

        <div align="center" style="margin-top : 30px; margin-bottom : 30px;">
            <i class="fa fa-book hat fa-3x" style="color: aquamarine;"></i>
            <br>
            <h3>@lang('frontend/homework/title.makehomework'){{trans('frontend/homework/title.multiplechoice')}}</h3>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item" aria-current="page"><a href="{{url('homework/assignment')}}">{{trans('frontend/homework/title.makehomework')}}</a></li>
              <li class="breadcrumb-item" aria-current="page"><a href="{{url('assignment/list/'.$questions->assignment_id)}}">{{$questions->getAssignment->assignment_name}}</a></li>
              <li class="breadcrumb-item active" aria-current="page">@lang('frontend/homework/title.multiplechoice')</li>
            </ol>
        </nav>
        <hr>

        <form action="{{route('homework.storechoice')}}" method="post" id="form_pretest" enctype="multipart/form-data">
            <div class="container-fluid" id="font-ext">
                    {{ csrf_field() }}
                    @forelse ($questions->questions as $key => $question)
                    <div class="ext-tititle">
                        <div class="row">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-11">
                                <div class="ext-question">
                                    {{$question['question_on'] .'. '}}{!!$question['question']!!}  <div class="question_score">( {!! $question['question_score']!!} {{ trans('frontend/homework/title.score') }} )</div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="ext-choice">
                            <div class="row">
                                <div class="col-sm-1"></div>
                                <div class="col-sm-4">
                                    <div class="radio">
                                    <label class="label-choice">
                                        <input type="radio" required
                                        name="question_on[{{$question['question_on']}}]"
                                        value="1"
                                        {{(!empty($edits) && $question['question_on']  == $edits->questions_make[$key]['question_on'] && $edits->questions_make[$key]['select_answer'] == '1' ? 'checked="checked"' :'')}}
                                        >
                                        {{$question['choice_1']}}
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3"></div>
                                <div class="col-sm-4">
                                    <div class="radio">
                                        <label class="label-choice"><input type="radio" required
                                            name="question_on[{{$question['question_on']}}]"
                                            {{( !empty($edits) && $question['question_on']  == $edits->questions_make[$key]['question_on'] && $edits->questions_make[$key]['select_answer'] == '2' ? 'checked="checked"' :'')}}
                                            value="2">
                                            {{$question['choice_2']}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-1"></div>
                                <div class="col-sm-4">
                                    <div class="radio">
                                        <label class="label-choice"><input type="radio" required
                                            name="question_on[{{$question['question_on']}}]"
                                            {{( !empty($edits) && $question['question_on']  == $edits->questions_make[$key]['question_on'] && $edits->questions_make[$key]['select_answer'] == '3' ? 'checked="checked"' :'')}}
                                            value="3">
                                            {{$question['choice_3']}}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-3"></div>
                                <div class="col-sm-4">
                                    <div class="radio">
                                        <label class="label-choice"><input type="radio" required
                                            name="question_on[{{$question['question_on']}}]"
                                            {{( !empty($edits) && $question['question_on']  == $edits->questions_make[$key]['question_on'] && $edits->questions_make[$key]['select_answer'] == '4' ? 'checked="checked"' : '')}}
                                            value="4">{{$question['choice_4']}}
                                        </label>
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
                                <button type="submit" class="btn btn-submit">@lang('frontend/homework/title.btn-send')</button>
                            </div>
                        </div>
                    </div>
            </div>
        </form>
    </div>

</section>
@stop
