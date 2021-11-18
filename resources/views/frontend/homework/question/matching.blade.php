@extends('frontend/default')


@section('css')
    <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/css/profile.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/css/responsivehomeworkmatching.css') !!}" >
    <style>
        .font-ext {
            font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif !important;
            font-size: 18px;
            /* max-width: 990px; */

            margin-left: 75px;;
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
        font-family: 'Kanit', sans-serif;
        }
        .question-matching {
            padding: 15px;
        }
        input.form-control.inputnumber {
            margin-top: 10px;
        }
        .number_question {
            display: inline;
            margin-right: 10px;
        }
        .ext-question > p {
            display: inline;
            text-overflow: ellipsis;
            word-wrap: break-word;
            overflow: hidden;
            max-height: 3.6em;
            line-height: 1.8em;
        }
        .choice-question > p {
            display: inline;
            text-overflow: ellipsis;
            word-wrap: break-word;
            overflow: hidden;
            max-height: 3.6em;
            line-height: 1.8em;
        }
        p {
            color: black;
        }
        .box-matcing{
            display: flex;
            /* padding: 15px; */
        }
        .box-question , .box-answe {
            width: 50%;
        }
        .input-answer {
            margin-right: 20px;
            width: 8%;
        }
        .choice-question {
            width: 80%;
            /* padding: 15px */
        }
        .box-answe {
            display: flex;
            margin-left: 50px;
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
              <li class="breadcrumb-item active" aria-current="page">@lang('frontend/homework/title.matching')</li>
            </ol>
          </nav>
          <hr>
        {{-- <div align="center" style="margin-top : 30px; margin-bottom : 30px;">
            <i class="fa fa-book hat fa-3x" style="color: aquamarine;"></i>
            <br>
            <h3>@lang('frontend/homework/title.homework')</h3>
        </div> --}}

        <form action="{{route('homework.storematching')}}" method="post" id="form_pretest" enctype="multipart/form-data">
            <div class="font-ext" id="font-ext">
                    {{ csrf_field() }}
                    @forelse ($questions->questions_studen as $key => $item)
                    <div class="question-matching">
                        <div class="col-sm-12">
                            <div class="box-matcing">
                                <div class="box-question">
                                    <div class="ext-question">
                                       <div class="number_question">{{$item['question_on']}}.</div><p>{!!$item['question']!!}</p>
                                    </div>
                                </div>
                                <div class="box-answe">
                                        <input type="hidden" name="question_on[]" value="{{$item['question_on']}}">
                                        <div class="input-answer">
                                            <input type="text"
                                            name="select_answer[]"
                                            required
                                            value="{{ !empty($edits) ? $edits->questions_make[$key]['select_answer'] : ''}}"
                                            class="form-control inputnumber">
                                        </div>
                                        <div class="choice-question"> {!!$item['choice']!!} <div class="question_score">{{ "(".$item['question_score']." ".trans('frontend/homework/title.score').")" }}</div></div>
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
