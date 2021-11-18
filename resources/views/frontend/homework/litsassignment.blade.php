@extends('frontend/default')

@section('content')
<style>
    .has-search .form-control {
      padding-left: 2.375rem;
    }

    .has-search .form-control-feedback {
        position: absolute;
        z-index: 2;
        display: block;
        width: 2.375rem;
        height: 2.375rem;
        line-height: 2.375rem;
        text-align: center;
        pointer-events: none;
        color: #aaa;
    }

    .container-fluid {
        max-width: 1230px;
    }
    .padding-footer {
        padding: 1px;
    }
    .table th {
    border: 1px solid #e9ecef;
    background-color: #ffffff;
    text-align: center;
    }
     .table td {
    border: 1px solid #e9ecef;
    background-color: #f2f2f2;
    }
    .make {
        border: 1px solid transparent;
        padding: .5rem .75rem;
        font-size: 1rem;
        background-color: #40ba0d;
        line-height: 1.25;
        border-radius: .25rem;
        transition: all .15s ease-in-out;
        color: #f5f6f7;
    }
    .assay {
        border: 1px solid transparent;
        padding: .5rem .75rem;
        font-size: 1rem;
        background-color: #109fe6;
        line-height: 1.25;
        border-radius: .25rem;
        transition: all .15s ease-in-out;
        color: #f5f6f7;
    }
    .edit {
        border: 1px solid transparent;
        padding: .5rem .75rem;
        font-size: 1rem;
        background-color: #FC8600;
        line-height: 1.25;
        border-radius: .25rem;
        transition: all .15s ease-in-out;
        color: #f5f6f7;
    }
    .btn-link:focus, .btn-link:hover {
        color: #f5f6f7;
        text-decoration: blink;
        background-color: #727b84;
        border-color: #6c757d;
    }
    .score-studen {
        color: #ec7a18;
    }
    td.text-score {
    text-align: center;
    }
    .wait_twork{
        color: #26ad2b;
    }
    .wait_color{
        color: #ec7a18;
    }
    .wait_notwork{
        color: #f44336;
    }
</style>

    <section class="p-t-10 p-b-20">
        <div class="container">

        <div align="center" style="margin-top : 10px; margin-bottom: 10px;">
            <i class="fa fa-book hat fa-3x" style="color: aquamarine;"></i><br>
            <h3>@lang('frontend/homework/title.makehomework')</h3>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item" aria-current="page"><a href="{{url('homework/assignment')}}">{{trans('frontend/homework/title.makehomework')}}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{$assignment->assignment_name}}</li>
            </ol>
        </nav>
        <hr>
            <table class="table table-striped table-responsive">
                <tr>
                  <th width='35%;'>{{trans('frontend/homework/title.type')}}</th>
                  <th width='30%;'>{{trans('frontend/homework/title.score')}}</th>
                  <th width='35%;'>{{trans('frontend/homework/title.manage')}}</th>
                </tr>
                @foreach ($questions as $data)
                    <tr>
                        <td>{{HomeworkType($data->questions_type)}}</td>
                        <td class="text-score">
                            @if($assignment->assignment_date_end.' '.$assignment->assignment_time_end >= date('Y-m-d H:i'))
                                <div class="wait_color">{{trans('frontend/homework/title.waitforinspection')}}</div>
                            @else
                                @if (!empty($data->getStudentMakemember))
                                    @if (isset($data->getStudentMakemember->score))
                                    <div class="wait_twork">{{$data->getStudentMakemember->score.'/'.collect($data->questions)->sum('question_score')}}</div>
                                    @elseif ($data->getStudentMakemember->score == null)
                                    <div class="wait_color"> {{ trans('frontend/homework/title.waitforinspection') }} </div>
                                    @else
                                    <div class="wait_notwork"> {{trans('frontend/homework/title.studennotwork')}} </div>
                                    @endif
                                @else
                                    <div class="wait_notwork"> {{trans('frontend/homework/title.studennotwork')}} </div>
                                @endif
                            @endif
                        </td>
                        <td class="text-score">
                            @if(date('Y-m-d H:i',strtotime($assignment->assignment_date_start.' '.$assignment->assignment_time_start)) > date('Y-m-d H:i'))
                                {{trans('frontend/homework/title.maketotime')}}
                            @else
                                @if(date('Y-m-d H:i') <= date('Y-m-d H:i',strtotime($assignment->assignment_date_end.' '.$assignment->assignment_time_end)))
                                        @if(!empty($data->getStudentMakemember))
                                        <a href="{{url('homework/make/'.$data->_id)}}" target="_blank" class="btn btn-link edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>{{trans('frontend/homework/title.edit')}}</a>
                                        @else
                                        <a href="{{url('homework/make/'.$data->_id)}}" target="_blank" class="btn btn-link make"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>{{trans('frontend/homework/title.makehomework')}}</a>
                                        @endif
                                @else
                                    @if(empty($data->getStudentMakemember))
                                    <a href="{{url('homework/make/'.$data->_id)}}" target="_blank" class="btn btn-link make"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>{{trans('frontend/homework/title.makehomework')}}</a>
                                    @else
                                    <a href="{{url('assignment/assay/'.$data->assignment_id.'/'.$data->_id)}}" class="btn btn-link assay">{{trans('frontend/homework/title.assay')}}</a>
                                    @endif
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </section>
    <section class="padding-footer">

    </section>
    <script type="text/javascript">
        var close_window = '{{ trans('frontend/courses/title.close_window') }}';
        var please_login = '{{ trans('frontend/layouts/modal.please_login') }}';
        var homework_success = '{{ trans('frontend/homework/title.homework_success') }}';
            @if($message = Session::get('makehomework'))
            Swal.fire({
                type: 'success',
                title: homework_success,
                confirmButtonColor: '#28a745',
                confirmButtonText : close_window
            })
            @endif
    </script>

@stop
