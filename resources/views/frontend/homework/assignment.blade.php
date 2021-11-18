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
    .c-orange {
        color: #f39b4f;
    }
    .c-red {
        color: #f15463;
    }
    .c-green {
    color: #52c123;
    }
</style>
<style>
    div.page {
        float: right;
    }
    .pagination {
        display:inline-block;
        padding-left:0;
        margin:20px 0;
        border-radius:4px
    }
    .pagination>li {
        display:inline
    }
    .pagination>li>a,.pagination>li>span {
        position:relative;
        float:left;
        padding:6px 12px;
        margin-left:-1px;
        line-height:1.42857143;
        color:#3d3537;
        text-decoration:none;
        background-color:#fff;
        border:1px solid #ddd
    }
    .pagination>li:first-child>a,.pagination>li:first-child>span {
        margin-left:0;
        border-top-left-radius:4px;
        border-bottom-left-radius:4px
    }
    .pagination>li:last-child>a,.pagination>li:last-child>span {
        border-top-right-radius:4px;
        border-bottom-right-radius:4px
    }
    .pagination>li>a:focus,.pagination>li>a:hover,.pagination>li>span:focus,.pagination>li>span:hover {
        z-index:3;
        color:#23527c;
        background-color:#eee;
        border-color:#ddd
    }
    .pagination>.active>a,.pagination>.active>a:focus,.pagination>.active>a:hover,.pagination>.active>span,.pagination>.active>span:focus,.pagination>.active>span:hover {
        z-index:2;
        color:#fff;
        cursor:default;
        background-color:#3d3537;
        border-color:#3d3537
    }
    .pagination>.disabled>a,.pagination>.disabled>a:focus,.pagination>.disabled>a:hover,.pagination>.disabled>span,.pagination>.disabled>span:focus,.pagination>.disabled>span:hover {
        color:#777;
        cursor:not-allowed;
        background-color:#fff;
        border-color:#ddd
    }
    .pagination-lg>li>a,.pagination-lg>li>span {
        padding:10px 16px;
        font-size:18px;
        line-height:1.3333333
    }
    .pagination-lg>li:first-child>a,.pagination-lg>li:first-child>span {
        border-top-left-radius:6px;
        border-bottom-left-radius:6px
    }
    .pagination-lg>li:last-child>a,.pagination-lg>li:last-child>span {
        border-top-right-radius:6px;
        border-bottom-right-radius:6px
    }
    .pagination-sm>li>a,.pagination-sm>li>span {
        padding:5px 10px;
        font-size:12px;
        line-height:1.5
    }
    .pagination-sm>li:first-child>a,.pagination-sm>li:first-child>span {
        border-top-left-radius:3px;
        border-bottom-left-radius:3px
    }
    .pagination-sm>li:last-child>a,.pagination-sm>li:last-child>span {
        border-top-right-radius:3px;
        border-bottom-right-radius:3px
    }
    </style>
    @include('frontend.homework.pagination')
<section class="p-t-50 p-b-65">
    <div class="container">
            <div align="center" style="margin-top : 10px; margin-bottom: 10px;">
                <i class="fa fa-book hat fa-3x" style="color: aquamarine;"></i><br>
                <h3>@lang('frontend/homework/title.makehomework')</h3>
            </div>
        <hr>
        {{-- <form method="POST" action="{{url('homework/assignment')}}" id="filter_form" class="form-horizontal">
            {{ csrf_field() }} --}}
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group has-search">
                    <span class="fa fa-search form-control-feedback"></span>
                    <input type="text"  name="title" class="form-control  mb-2 filter"  onkeyup="search_page()" value="{!! $title !!}" oncheck="this.form.submit()" placeholder="{{trans('frontend/homework/title.searchhomwork')}}" autocomplete="off" >
                    </div>
                </div>
                <div class="col-sm-4">
                    <select id="selectsubject" class="form-control" style="padding-top: 4px;" name="subject"  onchange="search_page()">
                        <option value="">- @lang('frontend/members/title.subject') -</option>
                        @forelse ($subjects as $item)
                            <option {{($subject == $item->_id) ? 'selected' : ''}} value="{{$item->_id}}">{{ (Auth::guard('members')->user()->member_lang == 'en') ? $item->subject_name_en :$item->subject_name_th}}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
                <div class="col-sm-4">
                    <select id="selectstatus" class="form-control" style="padding-top: 4px;" name="status"  onchange="search_page()">
                        <option  value="">- @lang('frontend/homework/title.status') -</option>
                        <option {{($status == 0 && $status != '')  ? 'selected' : ''}} value="0">@lang('frontend/homework/title.notcompleteyet')</option>
                        <option {{($status == 1 && $status != '') ? 'selected' : ''}} value="1">@lang('frontend/homework/title.studennotwork')</option>
                        <option {{($status == 2 && $status != '') ? 'selected' : ''}} value="2">@lang('frontend/homework/title.alreadydone')</option>
                    </select>
                </div>
            </div>
        {{-- </form> --}}
        <div class="table-responsive">
            {{-- <table class="table table-striped">

                <tr>
                <th>{{trans('frontend/homework/title.namehomwork')}}</th>
                <th>{{trans('frontend/members/title.subject')}}</th>
                <th>{{trans('frontend/homework/title.duedate')}}</th>
                <th>{{trans('frontend/homework/title.status')}}</th>
                <th>{{trans('frontend/homework/title.manage')}}</th>
                </tr>
                @forelse ($datas as $data)
                    <tr>
                        <td>{{$data->getAssignment->assignment_name}}</td>
                        <td>{{(Auth::guard('members')->user()->member_lang=='en') ? $data->getAssignment->getSubject->subject_name_en : $data->getAssignment->getSubject->subject_name_th}}</td>
                        <td>{{date('d-m-Y',strtotime($data->getAssignment->assignment_date_end))}}</td>
                        <td>{!!HomeworkStatusStuden($data->send_assignment_status)!!}</td>
                        <td style="text-align: center;">
                            <a href="{{url('assignment/list/'.$data->assignment_id)}}" class="btn btn-link link" target="_blank"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{trans('frontend/homework/title.detail')}}</a>
                        </td>
                    </tr>
                    @empty

                @endforelse
            </table> --}}

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{trans('frontend/homework/title.no_title')}}</th>
                        <th>{{trans('frontend/homework/title.namehomwork')}}</th>
                        <th>{{trans('frontend/members/title.subject')}}</th>
                        <th>{{trans('frontend/homework/title.duedate')}}</th>
                        <th>{{trans('frontend/homework/title.status')}}</th>
                        <th>{{trans('frontend/homework/title.manage')}}</th>
                    </tr>
                </thead>
                <tbody id="datas">
                </tbody>
            </table>

        </div>
        <div id="pagination"></div>
    </div>
</section>

    <script src="/suksa/frontend/template/js/assignment-search.js"></script>
    <script>

        // $('.filter').change( function () {
            // $('#filter_form').submit();
        // });
        let data = {'page': 1};

        pagination(data, '/assignment/searach');

        async function search_page(page = 1) {
            let data = {
                'page': page,
                'title': $('[name="title"]').val(),
                'status': $('[name="status"]').val(),
                'subject': $('[name="subject"]').val()
            };

            await pagination(data, '/assignment/searach');
        }
   </script>


@stop
