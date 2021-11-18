@extends('frontend/default')

<?php
    if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
        App::setLocale('en');
    }
    else{
        App::setLocale('th');
    }
?>

@section('content')
@section('css')
<link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/css/course.css') !!}">
@stop
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
      .btn-detail{
            background-color: #28a745;
            border-color: #40ba0d;
      }

    .btn-save{
        background: linear-gradient(to right, #17a7c0 0%, #7de442 100%);
        justify-content: center;
        align-items: center;
    }
    .btn-link:focus, .btn-link:hover {
        color: #f5f6f7;
        text-decoration: blink;
        background-color: #727b84;
        border-color: #6c757d;
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
    .table th {
    background-color: #ffffff;
    border: 1px solid #e9ecef;
    text-align: center;
    }
     .table td {
    background-color: #f2f2f2;
    border: 1px solid #e9ecef;
    }
  </style>
<section class="p-t-50 p-b-65">
  <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container">
      <div align="center" ><i class="fa fa-graduation-cap hat fa-3x" style="color: aquamarine;"></i><br><h3>{{trans('frontend/homework/title.homework')}}</h3></div>
      <hr>

      @if(Auth::guard('members')->user())
        @if(Auth::guard('members')->user()->member_role == "teacher")
          <div class="col-12">
            <div class="text-right" style="padding-bottom: 20px;">
                <a href="{{ route('homework.from_create') }}" class="btn bo-rad-23 btn-save"><object class="colorz" style="font-size: 16px;" > <i class="fa fa-plus" aria-hidden="true"></i>&nbsp;{{trans('frontend/homework/title.create_homework')}}</object> </a>
            </div>
          </div>
        @endif
      @endif
      {{-- <hr> --}}
      {{-- <table class="table table-bordered" id="table_homework"></table> --}}
      <div class="">
        <div class="table-responsive">
            <table id="table" class="table table-striped table-responsive">
            <thead>
                <tr>
                <th scope="col">{{trans('frontend/homework/title.no_title')}}</th>
                <th scope="col">{{trans('frontend/homework/title.namehomework')}}</th>
                <th scope="col">{{trans('frontend/homework/title.subjectname')}}</th>
                <th scope="col">{{trans('frontend/homework/title.classroom')}}</th>
                <th scope="col">{{trans('frontend/homework/title.assignment_date')}}</th>
                <th scope="col">{{trans('frontend/homework/title.homework_delivery_date')}}</th>
                <th scope="col">{{trans('frontend/homework/title.manage')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($assignments as $assignment)
                <tr>
                    <td>{{++$i}}</td>
                    <td class="text-center">{{$assignment->assignment_name}}</td>
                    <td class="text-center">{{$assignment->getSubject->subject_name_th}}</td>
                    <td class="text-center">{{$assignment->student_classroom}}</td>
                    <td class="text-center">{{ date('d/m/Y',strtotime($assignment->assignment_date_start))." ".$assignment->assignment_time_start }}</td>
                    <td class="text-center">{{ date('d/m/Y',strtotime($assignment->assignment_date_end))." ".$assignment->assignment_time_end }}</td>
                    <td class="text-center">
                        @if($assignment->assignment_date_start." ".$assignment->assignment_time_start < date('Y-m-d H:i'))
                            @if(count($assignment->getQuestions) == 0)
                            <a href="{{url('homework/teacher/assignment/del/'.$assignment->_id)}}" class="btn btn-danger delbtn"><i class="fa fa-trash" aria-hidden="true"></i>  {{trans('frontend/homework/title.delete')}}</a>
                            @else
                            <a href="{{url('homework/teacher/manage/'.$assignment->_id)}}" class="btn btn-link link" type="button" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                {{trans('frontend/homework/title.detail')}}
                            </a>
                            @endif

                        @else
                        <a href="{{url('homework/teacher/manage/'.$assignment->_id)}}" class="btn btn-link link" type="button" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            {{trans('frontend/homework/title.detail')}}
                        </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>
    </div>
    {{-- <div class="page"> --}}
        {{$assignments->links()}}
        @include('frontend.homework.pagination',['items' => $assignments])
    {{-- </div> --}}
        {{-- {!! $assignments->appends(\Input::all())->render() !!} --}}
      </div>
    </div>
</section>

@stop

@push('scripts')
    <script>
        // $('#table').DataTable({
        //     // "paging":   false,
        //     "ordering": false,
        //     // "info":     false
        // });
    // $(document).ready( function () {
    //   setTimeout(function () {

    //     // tableList: function () {
    //       table_homework.tableView = $('#table_homework').DataTable({
    //           ordering: true,
    //           processing: true,
    //           serverSide: true,
    //           bLengthChange: false,
    //           bInfo: false,
    //           bAutoWidth: false,
    //           bFilter: true,
    //           scrollX: true,
    //           language:
    //             {
    //                 paginate:
    //                 {
    //                     previous: "«",
    //                     next: "»",
    //                 }
    //             } ,
    //             ajax: {
    //                 "url": window.location.origin + '/homework/homework_tables/',
    //                 "type": "get",
    //                 // "data": {
    //                 //   'id' : $("input[name='id_request']").val(),
    //                 // },
    //                 // "headers": {
    //                 //     'X-CSRF-TOKEN': $('input[name=_token]').val()
    //                 // },
    //                 // success: function(data, textStatus, jqXHR)
    //                 // {
    //                 //     console.log(data); //*** returns correct json data
    //                 // }
    //             },
    //             columns: [
    //               { title: 'No.', data: function(a,b,c,d) {
    //                 return table_homework.tableView.page.info().start+d.row+1;
    //               },name:'aptitude_id'},
    //               { title: "{{trans('frontend/homework/title.namehomework')}}" ,data:'assignment_name' ,name: 'assignment_name' },
    //               { title: "{{trans('frontend/homework/title.subjectname')}}" ,data:'assignment_name' ,name: 'assignment_name' },
    //               { title: "{{trans('frontend/homework/title.classroom')}}" ,data:'assignment_name' ,name: 'assignment_name' },
    //               { title: "{{trans('frontend/homework/title.dateat')}}" ,data:'assignment_name' ,name: 'assignment_name' },
    //               { title: "{{trans('frontend/homework/title.manage')}}" ,data:'assignment_name' ,name: 'assignment_name' },
    //             ]
    //         });

    //     // },

    //   }, 1000);
    // } );

    async function footerBottom() {
        await removeFt0();
        windowHeight = $(window).height();
        footerHeight = $('#footer')[0].offsetTop + $('#footer')[0].offsetHeight;

        if (footerHeight <= windowHeight) {
            $("#footer").addClass("footer-0");
        }
    }

    function removeFt0() {
        return new Promise(function(resolve, reject) {
        $("#footer").removeClass("footer-0");
        resolve()
        }).then(function(){
            return true;
        })
    }
    </script>
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
    </script>
@endpush
