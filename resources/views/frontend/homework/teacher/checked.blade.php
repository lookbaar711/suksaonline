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
    background-color: #ffffff;
    border: 1px solid #e9ecef;
    text-align: center;
    }
     .table td {
    border: 1px solid #e9ecef;
    background-color: #f2f2f2;
    }
    .container-fluid {
        max-width: 1230px;
    }
    .link {
        border: 1px solid transparent;
        padding: .5rem .75rem;
        font-size: 1rem;
        background-color: #40ba0d;
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
    .fullname
    {
        display: inline-block;
    }
    .dot_rated {
    height: 15px;
    width: 15px;
    background-color: #FC8600;
    border-radius: 50%;
    display: inline-block;
    }
    .dot_straight {
    height: 15px;
    width: 15px;
    background-color: #40ba0d;
    border-radius: 50%;
    display: inline-block;
    }
    .export{
        float: right;
        margin-right: 15px;
        margin-bottom: 5px;
        color: white !important;
        /* display: none; */
    }
    td.text-score {
    text-align: center;
    }
    .btn-edit{
        color: #fff;
        background-color: #FC8600;
        border: none;
      }
      .btn-edit:hover {
        color: #fff;
        background-color: #FC8600;
        border: none;
    }

</style>
    {{-- icon header --}}
    <section class="p-t-10 p-b-20">
        <div class="container">
            <div align="center" style="margin-top : 10px; margin-bottom: 10px;">
                <i class="fa fa-book hat fa-3x" style="color: aquamarine;"></i><br>
                <h3>@lang('frontend/homework/title.checkedhomework')</h3>
            </div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item" aria-current="page"><a href="{{url('homework/teacher')}}">{{trans('frontend/homework/title.checkedhomework')}}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{$assignment->assignment_name}}</li>
                </ol>
            </nav>
            <hr>
            @if(date('Y-m-d') > date('Y-m-d H:i',strtotime($assignment->assignment_date_end.' '.$assignment->assignment_time_end)))
                <a href="{{url('homework/export/'.$assignment->_id)}}" class="btn btn-link link export"><i class="fa fa-file-excel-o" aria-hidden="true"></i> {{trans('frontend/homework/title.exportcheck')}}</a>
            @endif
        <div class="container-fluid">
            <table id="customers" class="table table-striped table-responsive">
                <tr>
                  <th>{{trans('frontend/homework/title.no_title')}}</th>
                  <th>{{trans('frontend/homework/title.namelastname')}}</th>
                    @if(!empty($types))
                        @foreach ($types as $type)
                        <th>{{HomeworkType($type->questions_type).'/'.trans('frontend/homework/title.score')}}</th>
                        @endforeach
                    @endif
                  {{-- <th>การจัดการ</th> --}}
                </tr>

                @foreach ($datas as $key => $data)
                {{-- {{dd($types)}} --}}
                    <tr>
                        <td class="text-score">{{($key+1)}}</td>
                        <td>
                            <div class="fullname">{{$data['fullname']}}</div>
                            @if(!empty($data['senddate']))
                                @if($data['senddate'] > date('Y-m-d H:i',strtotime($assignment->assignment_date_end.' '.$assignment->assignment_time_end)))
                                    <span class="dot_rated"></span>
                                @else
                                    <span class="dot_straight"></span>
                                @endif
                            @endif
                        </td>
                        {{-- {{ dd(collect($data['make'])->sortBy('questions_type')) }} --}}
                        @if(!empty($data['make']))
                            @foreach (collect($data['make'])->sortBy('questions_type') as $item)
                                @if($item)
                                    @if(isset($item->score))
                                    <td class="text-score"><a href="{{url('homework/checked/'.$item->assignment_id.'/'.$item->assigment_student_id.'/'.$item->assignment_questions_id)}}" class="btn btn-link link" target="_blank">{{$item->score." / ".collect($item->getAssignmentQuestion->questions)->sum('question_score') }}</a></td>
                                    @else
                                    <td class="text-score"><a href="{{url('homework/checked/'.$item->assignment_id.'/'.$item->assigment_student_id.'/'.$item->assignment_questions_id)}}" class="btn btn-link btn-edit" target="_blank">{{trans('frontend/homework/title.waitforinspection')}}</a></td>
                                    @endif
                                @else
                                <td class="text-score">{{trans('frontend/homework/title.studennotwork')}}</td>
                                @endif
                            @endforeach
                        @endif
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    </section>
    <section class="padding-footer">

    </section>

    {{-- end icon header --}}

@stop
