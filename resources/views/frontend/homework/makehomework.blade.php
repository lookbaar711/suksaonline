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
        }
        .ext-question {
            font-size: '16px';
            margin-left: '10px';
        }
    </style>
@endsection

@section('content')
<section class="p-t-50 p-b-30">
    <div class="container">

        <div align="center" style="margin-top : 30px; margin-bottom : 30px;">
            <i class="fa fa-book hat fa-3x" style="color: aquamarine;"></i>
            <br>
            <h3>@lang('frontend/homework/title.homework')</h3>
        </div>

        <form action="{{route('examination.posttest')}}" method="post" id="form_pretest" enctype="multipart/form-data">
            <div class="container-fluid" id="font-ext">
                    {{ csrf_field() }}
                    @forelse ($questions->questions as $question)
                    <div class="ext-tititle">
                        <div class="row">
                            <div class="ext-question">
                                {{-- {{$question['question_no'] .'. '}}{!!str_replace(["<p>","</p>"],"",$question['question'])!!} --}}
                                {{-- {{$question['question_no'] .'. '}}{!!$question['question']!!} --}}
                                {{$question['question_no'] .'. '}}{!!substr($question['question'],3)!!}
                            </div>
                        </div>
                        {{-- <p>{{$question['question_no'] .'. '. $question['question']}}</p> --}}
                        </div>
                        <div class="ext-choice">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="radio">
                                    <label class="label-choice"><input type="radio" required name="question_no[{{$question['question_no']}}]" value="1">{{$question['choice_1']}}</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="radio">
                                        <label class="label-choice"><input type="radio" required name="question_no[{{$question['question_no']}}]"  value="2">{{$question['choice_2']}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="radio">
                                        <label class="label-choice"><input type="radio" required name="question_no[{{$question['question_no']}}]"  value="3">{{$question['choice_3']}}</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="radio">
                                        <label class="label-choice"><input type="radio" required name="question_no[{{$question['question_no']}}]"  value="4">{{$question['choice_4']}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        @endforelse
                        <div class="row">
                            <div class="ext-btn">
                                <div class="col-sm-12">
                                    {{-- <input hidden name="course_id" id="course_id" value="{{$datas->course_id}}"> --}}
                                <button type="submit" class="btn btn-submit">@lang('frontend/examination/examination.btn-posttest')</button>
                                {{-- <button type="submit" class="btn btn-submit">Send the quiz after class</button> --}}
                            </div>
                        </div>
                    </div>
            </div>
        </form>
    </div>

</section>
@stop
