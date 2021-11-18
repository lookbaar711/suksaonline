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
        .inputnumber{
            padding: 8px;
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
        .ext-question > p {
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
<section class="p-t-50 p-b-30">
    <div class="container">

        <div align="center" style="margin-top : 30px; margin-bottom : 30px;">
            <i class="fa fa-book hat fa-3x" style="color: aquamarine;"></i>
            <br>
            <h3>@lang('frontend/homework/title.makehomework'){{trans('frontend/homework/title.matching')}}</h3>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item" aria-current="page"><a href="{{url('homework/assignment')}}">{{trans('frontend/homework/title.makehomework')}}</a></li>
              <li class="breadcrumb-item" aria-current="page"><a href="{{url('assignment/list/'.$questions->assignment_id)}}">{{$questions->getAssignment->assignment_name}}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{trans('frontend/homework/title.matching')}}({{trans('frontend/homework/title.assay')}})</li>
            </ol>
        </nav>
        <hr>
        {{-- <form action="{{route('homework.storematching')}}" method="post" id="form_pretest" enctype="multipart/form-data"> --}}
            <div class="container-fluid" id="font-ext">
                    {{ csrf_field() }}
                    @forelse ($questions->questions_studen as $key => $item)
                    <div class="question-matching">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-5">
                                    <div class="ext-question">
                                        <div class="number_question">{{$item['question_on']}}.</div><p>{!!$item['question']!!} </p>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <input type="hidden" name="question_on[]" value="{{$item['question_on']}}">
                                            <div class="col-sm-2">
                                                <input type="text"
                                                disabled
                                                name="select_answer[]"
                                                value="{{!empty($studemake) && $studemake->questions_make[$key]['question_on'] == $item['question_on'] ? $studemake->questions_make[$key]['select_answer'] : ''}}"
                                                style="background-color:
                                                {{
                                                    (!empty($studemake) && $studemake->questions_make[$key]['question_on'] == $item['question_on'] && $item['answer'] == $studemake->questions_make[$key]['select_answer'] ? '#28a745' :'red')
                                                }}
                                                "
                                                class="form-control inputnumber">
                                            </div>
                                            <div class="col-sm-10"> {!!$item['choice']!!}  <div class="question_score">{{ "(".$item['question_score']." ".trans('frontend/homework/title.score').")" }}</div></div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-sm-1"></div> --}}
                            </div>
                        </div>
                    </div>
                    @empty

                    @endforelse
{{--
                    <div class="row">
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
    $(document).on('keyup', '.inputnumber', function(event) {
    // $('.inputnumber').keyup(function(){
        var input = $(this).val();
		    //console.log(input);
		    var regex = new RegExp('^[0-9]+$');
		    if(regex.test(input)) {
		      }else {
		          $(this).val('');
		      }
    })
</script>
@stop
