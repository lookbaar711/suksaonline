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
        .number_question {
            display: inline;
            margin-right: 10px;
        }
        .title_questions > p {
            display: inline;
        }
        p {
            color: black;
        }
    </style>
@endsection

@section('content')
<section class="p-t-50 p-b-30">
    <div class="container">

        <div align="center" style="margin-top : 30px; margin-bottom : 30px;">
            <i class="fa fa-book hat fa-3x" style="color: aquamarine;"></i>
            <br>
            <h3>@lang('frontend/homework/title.checkedhomework'){{trans('frontend/homework/title.shotanswer')}}</h3>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item" aria-current="page"><a href="{{url('homework/teacher')}}">{{trans('frontend/homework/title.checkedhomework')}}</a></li>
              <li class="breadcrumb-item" aria-current="page"><a href="{{url('homework/teacher/assignment/'.$questions->assignment_id)}}">{{$questions->getAssignment->assignment_name}}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{trans('frontend/homework/title.shotanswer')}}</li>
            </ol>
        </nav>
        <hr>

        {{-- <form action="{{route('homework.storeshotanswer')}}" method="post" id="form_pretest" enctype="multipart/form-data"> --}}
            <div class="container-fluid" id="font-ext">
                    {{ csrf_field() }}
                    @forelse ($questions->questions as $key => $item)
                    <div class="question-matching">
                        <div class="row">

                            <label class="title_questions">
                                <div class="number_question">{{$item['question_on']}}.</div>

                            </label>
                            <div class="st">
                                {!!$item['question']!!}
                            </div>
                            <p style="color: #ec7a18;"> {{ "(".$item['question_score']." ".trans('frontend/homework/title.score').")" }} </p>
                        </div>
                        <div class="col-md-10">
                            <div class="row">
                                    <textarea
                                    class="form-control"
                                    style="color:
                                    {{
                                        (!empty($studemake) && $questions_test[$key]['question_on'] == $item['question_on'] && $questions_test[$key]['check'] == 'true' ? '#28a745' :'red')
                                    }}"
                                    disabled>{!!(!empty($studemake) && $studemake->questions_make[$key]['question_on'] == $item['question_on'] ? $studemake->questions_make[$key]['select_answer'] : '')!!}</textarea>
                            </div>
                        </div>
                    </div>
                    @empty
                    @endforelse
                    {{-- <div class="row">
                        <div class="ext-btn">
                            <div class="col-sm-12">
                                <input type="hidden" name="assignment_id" value="{{$questions->assignment_id}}">
                                <input type="hidden" name="assignment_questions_id" value="{{$questions->_id}}">
                                <button type="submit" class="btn btn-submit">@lang('frontend/homework/title.btn-send')</button>
                            </div>
                        </div>
                    </div> --}}
            </div>
        {{-- </form> --}}
    </div>
</section>
<script>
    $('.inputnumber').keyup(function(){

    })
</script>
@stop
