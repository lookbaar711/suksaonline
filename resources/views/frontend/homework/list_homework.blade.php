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
      .btn-create{
        background-color: #40ba0d;
        border: none;
      }
      .btn-create:hover {
        color: #fff;
        background-color: #40ba0d;
        border: none;
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
      <div align="center" ><i class="fa fa-graduation-cap hat fa-3x" style="color: aquamarine;"></i><br><h3>{{trans('frontend/homework/title.create_homework')}}</h3></div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="{{url('homework/list')}}">{{trans('frontend/homework/title.create_homework')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$assignments->assignment_name}}</li>
            </ol>
        </nav>
        {{-- <hr> --}}
      <div class="">
        <table class="table table-striped table-responsive">
          <thead>
            <tr>
              <th scope="col" style="width: 8%;">{{trans('frontend/homework/title.no_title')}}</th>
              <th scope="col">{{trans('frontend/homework/title.theformat')}}</th>
              <th scope="col">{{trans('frontend/homework/title.Numberofclauses')}}</th>
              <th scope="col">{{trans('frontend/homework/title.score')}}</th>
              <th scope="col">{{trans('frontend/homework/title.manage')}}</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($lists as $list)
              <tr>
                  <td scope="row">{{$i++}}</td>
                  <td scope="row">{{HomeworkType($list->questions_type)}}</td>
                  <td scope="row">{{(!empty($list->questions)) ? count($list->questions) : '0'}}/30</td>
                  <td scope="row">{{(!empty($list->questions)) ? SumScoreHomework($list->questions) : 0}}</td>
                  <td class="text-left">
                    <div class="dropdown">
                        @if(date('Y-m-d H:i') >= date('Y-m-d H:i',strtotime($assignments->assignment_date_start.' '.$assignments->assignment_time_start)))
                        {{trans('frontend/homework/title.cannoteditorcreate')}}
                        @else
                        @if(!empty($list->questions))
                                <a href="{{url('homework/create/'.urlcreate($list->questions_type).'/'.$list->assignment_id.'/'.$list->_id)}}" class="btn btn-secondary btn-edit" type="button">
                                    {{trans('frontend/homework/title.edit')}}
                                </a>
                            @else
                            <a href="{{url('homework/create/'.urlcreate($list->questions_type).'/'.$list->assignment_id)}}" class="btn btn-secondary btn-create" type="button">
                                {{trans('frontend/homework/title.create')}}
                            </a>
                            @endif
                        @endif
                    </div>
                  </td>
              </tr>
            @endforeach
          </tbody>
        </table>

      </div>
    </div>
</section>

@stop

@push('scripts')
    <script>

    // $(document).ready( function () {
    //   setTimeout(function () {
    //     // tableList: function () {
    //         $('#table_homework').DataTable({
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
    //             // ajax: {
    //             //     "url": window.location.origin + '/request/request_detail/',
    //             //     "type": "post",
    //             //     "data": {
    //             //       'id' : $("input[name='id_request']").val(),
    //             //     },
    //             //     "headers": {
    //             //         'X-CSRF-TOKEN': $('input[name=_token]').val()
    //             //     },
    //             //     // success: function(data, textStatus, jqXHR)
    //             //     // {
    //             //     //     console.log(data); //*** returns correct json data
    //             //     // }
    //             // },
    //             columns: [
    //               { title: 'วันที่สั่ง'},
    //               { title: 'วิชา'},
    //               { title: 'รูปอาจารย์'},
    //               { title: 'รูปอาจารย์'},
    //
    //
    //             ]
    //         });
    //
    //     // },
    //
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
@endpush
