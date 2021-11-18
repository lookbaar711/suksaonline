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
    .table th {
    background-color: #ffffff;
    border: 1px solid #e9ecef;
    text-align: center;
    }
     .table td {
    background-color: #f2f2f2;
    border: 1px solid #e9ecef;
    }
    .container-fluid {
        max-width: 1230px;
    }
    .padding-footer {
        padding: 1px;
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
    .delbtn {
        border: 1px solid transparent;
        padding: .5rem .75rem;
        font-size: 1rem;
        background-color: #dc3545;
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
    td.text-score {
    text-align: center;
    }
    .c_success {
    color: #52c123;
    }
    .c_wait {
    color: #ec7a18;
    }



</style>
@include('frontend.homework.pagination')
    {{-- icon header --}}
    <section class="p-t-50 p-b-65">
        <div align="center" style="margin-top : 10px; margin-bottom: 10px;">
            <i class="fa fa-book hat fa-3x" style="color: aquamarine;"></i><br>
            <h3>@lang('frontend/homework/title.checkedhomework')</h3>
        </div>
        <div class="container">
            {{-- <form method="post" action="{{url('homework/teacher')}}" id="filter_form" class="form-horizontal"> --}}
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group has-search">
                        <span class="fa fa-search form-control-feedback"></span>
                        <input type="text"  name="title" class="form-control  mb-2 filter" onkeyup="search_page()" value="{!! $title !!}" oncheck="this.form.submit()" placeholder="{{trans('frontend/homework/title.searchhomwork')}}" autocomplete="off" >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select id="selectsubject" class="form-control" style="padding-top: 4px;" name="subject" onchange="search_page()">
                            <option value="">- @lang('frontend/members/title.subject') -</option>
                            @foreach ($subjects as $item)
                                <option {{$subject != '' && $subject == $item->_id ? 'selected' : ''}} value="{{$item->_id}}">{{ (Auth::guard('members')->user()->member_lang == 'en') ? $item->subject_name_en :$item->subject_name_th}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select id="selectstatus" class="form-control" style="padding-top: 4px;" name="status" onchange="search_page()">
                            <option  value="">- @lang('frontend/homework/title.status') -</option>
                            <option {{($status == 1 && $status != '')  ? 'selected' : ''}} value="1">@lang('frontend/homework/title.ordered')</option>
                            <option {{($status == 2 && $status != '') ? 'selected' : ''}} value="2">@lang('frontend/homework/title.waitforinspection')</option>
                            <option {{($status == 3 && $status != '') ? 'selected' : ''}} value="3">@lang('frontend/homework/title.checked')</option>
                            <option {{($status == 5 && $status != '') ? 'selected' : ''}} value="5">{{trans('frontend/homework/title.not_have_detail')}}</option>
                        </select>
                    </div>
                </div>
            {{-- </form> --}}
            <div class="table-responsive">
                {{-- <table class="table table-striped table-responsive">
                    <thead>
                        <tr>
                        <th>{{trans('frontend/homework/title.namehomwork')}}</th>
                        <th>{{trans('frontend/members/title.subject')}}</th>
                        <th>{{trans('frontend/homework/title.classroom')}}</th>
                        <th>{{trans('frontend/homework/title.startdate')}}</th>
                        <th>{{trans('frontend/homework/title.duedate')}}</th>
                        <th>{{trans('frontend/homework/title.status')}}</th>
                        <th width="20%">{{trans('frontend/homework/title.manage')}}</th>
                        </tr>
                    </thead>

                    @foreach ($datas as $data)
                        <tr>
                            <td>{{$data->assignment_name}}</td>
                            <td>{{(Auth::guard('members')->user()->member_lang=='en') ? $data->getSubject->subject_name_en : $data->getSubject->subject_name_th}}</td>
                            <td>{{$data->student_classroom}}</td>
                            <td>{{date('d-m-Y',strtotime($data->assignment_date_start))}}</td>
                            <td>{{date('d-m-Y',strtotime($data->assignment_date_end))}}</td>
                            <td>{{HomeworkStatusTeacher($data->assignment_status)}}</td>
                            <td class="text-score">
                                <a href="{{url('homework/teacher/assignment/'.$data->_id)}}" class="btn btn-link link" target="_blank"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{trans('frontend/homework/title.detail')}}</a>
                                @if($data->assignment_date_start.' '.$data->assignment_time_start < date('Y-m-d H:i'))
                                    @if(count($data->getQuestions) == 0)
                                    <a href="{{url('homework/teacher/assignment/del/'.$data->_id)}}" class="btn btn-danger delbtn"><i class="fa fa-trash" aria-hidden="true"></i> {{trans('frontend/homework/title.delete')}}</a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table> --}}
                <table class="table table-striped table-responsive">
                    <thead>
                        <tr>
                        <th>{{trans('frontend/homework/title.no_title')}}</th>
                        <th>{{trans('frontend/homework/title.namehomwork')}}</th>
                        <th>{{trans('frontend/members/title.subject')}}</th>
                        <th>{{trans('frontend/homework/title.classroom')}}</th>
                        <th>{{trans('frontend/homework/title.startdate')}}</th>
                        <th>{{trans('frontend/homework/title.duedate')}}</th>
                        <th>{{trans('frontend/homework/title.status')}}</th>
                        <th width="20%">{{trans('frontend/homework/title.manage')}}</th>
                        </tr>
                    </thead>
                    <tbody id="datas">
                    </tbody>
                </table>
            </div>
            <div id="pagination"></div>
        </div>
    </section>

    <script>
        //  $('.filter').change( function () {
        //  	$('#filter_form').submit();
        //  });
    </script>
     <script src="/suksa/frontend/template/js/assignment-search.js"></script>
     <script type="text/javascript">
        var close_window = '{{ trans('frontend/courses/title.close_window') }}';
        var delsuccess = '{{ trans('frontend/homework/title.delsuccess') }}';
            @if($message = Session::get('delsuccess'))
            Swal.fire({
                type: 'success',
                title: delsuccess,
                confirmButtonColor: '#28a745',
                confirmButtonText : close_window
            })
            @endif

            let data = {'page': 1};

            searachteacher(data, '/homework/searach');

            async function search_page(page = 1) {
                let data = {
                    'page': page,
                    'title': $('[name="title"]').val(),
                    'status': $('[name="status"]').val(),
                    'subject': $('[name="subject"]').val()
                };

                await searachteacher(data, '/homework/searach');
            }

    </script>
@stop
