@extends('backend.layouts/default')

{{-- Page title --}}
@section('title')
โรงเรียนในระบบ
@stop

{{-- page level styles --}}
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>โรงเรียน{{$school->school_name_th}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('backend') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li><a href="{{url('backend/school')}}"> จัดการโรงเรียน</a></li>
        <li class="active">นักเรียน {{$school->school_name_th}}</li>
        {{-- <li ></li> --}}
    </ol>
</section>
<style>
    .skin-josh .navbar_school {
    background-color: #ffff;
    }
    .navbar-header {
        float: inherit;
        margin-bottom: 5px;
        background-color: #a0a2a54a;
        border-radius: 4px;
    }
    .toggle-a{
        width: 100%;
    }
    button.btn.btn-success.btn_submit {
    float: left;
    }
    ul.pagination-make {
        margin-right: -6px;
        margin-left: -6px;
        display: flex;
        padding-left: 0;
        list-style: none;
        border-radius: .25rem;
        justify-content: flex-end!important;
    }

    .pagination-make li {
        color: #337ab7;
        padding: .5rem .75rem;
        margin: 0px;
        list-style-type: none;
        background-color: #fff;
        border: 1px solid #ddd;
    }

    li.page-active {
        background-color: #337ab7 !important;
        border-color: #337ab7 !important;
        color: #fff !important;
    }

    li.prev-page {
        margin-left: 0;
        border-top-left-radius: .25rem;
        border-bottom-left-radius: .25rem;
    }

    li.next-page {
        border-top-right-radius: .25rem;
        border-bottom-right-radius: .25rem;
    }
    .pagination-make .page-item {
        border-right: 0px;
        border-left: 0px;
    }
    .prev-page span {
    color: #777;
    }
    li.next-page {
    color: #777;
    }
</style>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left">
                    รายชื่อนักเรียนในโรงเรียน
                </h4>
                <div class="pull-right">
                    <button
                        onclick="createstudent()"
                        class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span>
                        เพิ่มนักเรียน
                    </button>

                </div>
            </div>
            <br />
            <div class="panel-body">
                <nav class="navbar navbar_school">
                    {{-- {{dd($students)}} --}}
                    @forelse ($students as $key => $data)
                        <div class="box-classroom">
                            <div class="navbar-header">
                            <a class="navbar-brand toggle-a" data-toggle="collapse" data-target="#demo_{{$data['num']}}">{{$key}}</a>
                            </div>
                            <div class="container-fluid">
                                <div id="demo_{{$data['num']}}" class="collapse">
                                    <table class="table table-bordered " id="table_{{$data['num']}}">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px;">ลำดับ</th>
                                                <th with="40%">ชื่อ</th>
                                                <th with="20%">นามสกุล</th>
                                                <th>Email</th>
                                                <th with="20%">เบอร์โทร</th>
                                                <th with="20%">จัดการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($data['data']) >= 1)
                                                @foreach ($data['data'] as $i => $student)
                                                <tr>
                                                    <td>{!! ++$i !!}</td>
                                                    <td id="fname_{{$data['num']}}_{{$i}}">{!! @$student['student_fname'] !!}</td>
                                                    <td id="lname_{{$data['num']}}_{{$i}}">{!! @$student['student_lname'] !!}</td>
                                                    <td id="email_{{$data['num']}}_{{$i}}">{!! @$student['student_email'] !!}</td>
                                                    <td id="tel_{{$data['num']}}_{{$i}}">{!! @$student['student_tel'] !!}</td>
                                                    <td>
                                                    <form action="{{ route('school.student.delete') }}" method="POST">
                                                            <a
                                                            onclick="editstudent('{{ @$student['student_email'] }}','{{@$student['student_fname']}}','{{@$student['student_lname']}}','{{ @$student['student_tel']}}','{{@$student['student_class']}}','{{@$student['student_room']}}')"
                                                            class="btn btn-responsive button-alignment btn-success"
                                                            >
                                                            <i class="fa fa-pencil-square-o"></i>แก้ไข
                                                            </a>

                                                        <input name="email" type="hidden" value="{{@$student['student_email']}}">
                                                        <input name="school_id" type="hidden" value="{{@$school_id}}">
                                                        {{ csrf_field() }}
                                                        <button
                                                        type="submit"
                                                        class="btn btn-danger">ลบ
                                                        </button>
                                                    </form>

                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    {{-- @foreach ($data['data'] as $item)
                                        {{dd($item)}}
                                    @endforeach --}}
                                </div>

                            </div>
                        </div>
                    @empty

                    @endforelse

                </nav>
                <div class="col-sm-12">
                    <div class="box-paginate">
                        <nav aria-label="pagination-pang" class="pb-4 mt-3">
                            @if ($students->hasPages())
                                <ul class="pagination-make">
                                        @if ($students->onFirstPage())
                                        <li class="prev-page disabled"><span>Previous</span></li>
                                    @else
                                    <a class="a-page" href="{{ $students->previousPageUrl() }}" rel="prev">
                                        <li class="prev-page">Previous</li>
                                    </a>
                                    @endif

                                    @if($students->currentPage() > 4)
                                    <a class="a-page" href="{{ $students->url(1) }}">
                                        <li class="hidden-xs page-item">1</li>
                                    </a>
                                    @endif
                                    @if($students->currentPage() > 5)
                                    <a class="a-page" href="{{ $students->url(2) }}">
                                        <li class="hidden-xs page-item">2</li>
                                    </a>
                                    @endif
                                    @if($students->currentPage() > 3)
                                        <li class="page-item"><span>...</span></li>
                                    @endif
                                    @foreach(range(1, $students->lastPage()) as $i)
                                        @if($i >= $students->currentPage() - 3 && $i <= $students->currentPage() + 3)
                                            @if ($i == $students->currentPage())
                                                <li class="page-active page-item">{{ $i }}</li>
                                            @else
                                            <a class="a-page" href="{{ $students->url($i) }}">
                                                <li class="page-item">{{ $i }}</li>
                                            </a>
                                            @endif
                                        @endif
                                    @endforeach
                                    @if($students->currentPage() < $students->lastPage() - 3)
                                        <li class="page-item"><span>...</span></li>
                                    @endif
                                    @if($students->currentPage() < $students->lastPage() - 3)
                                    <a class="a-page" href="{{ $students->url($students->lastPage()) }}">
                                        <li class="hidden-xs page-item">{{ $students->lastPage() }}</li>
                                    </a>
                                    @endif

                                    {{-- Next Page Link --}}
                                    @if ($students->hasMorePages())
                                    <a class="a-page" href="{{ $students->nextPageUrl() }}" rel="next">
                                        <li class="next-page">Next</li>
                                    </a>
                                    @else
                                        <li class="next-page disabled"><span>Next</span></li>
                                    @endif
                                </ul>

                                @endif
                            {{-- {!! $teacher_detail->appends(\Input::all())->render() !!} --}}
                        </nav>
                        {{-- {!! $students->appends(\Input::all())->render() !!} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- row-->
    {{-- create student --}}
    <div id="create_student" class="modal fade create_student" role="dialog">
    <form method="POST" action="{{route('school.student.create')}}">
            <div class="modal-dialog">
            <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">เพิ่มข้อมูลนักเรียนในโรงเรียน</h4>
                    </div>
                    <div class="modal-body">
                            {{ csrf_field() }}

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="title" class="col-sm-3 control-label">
                                        ชื่อ
                                        <span style="color: red; font-size: 15px;">* </span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text"  name="student_fname" id="student_fname_create" onkeyup="checkTextOnly(this.id)" class="form-control" placeholder="ชื่อ" value="" required>
                                        <span style="font-size: 10px; color: red;" id="warning_text_student_fname_create"></span>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="form-group">
                                    <label for="title" class="col-sm-3 control-label">
                                        นามสกุล
                                        <span style="color: red; font-size: 15px;">* </span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" name="student_lname" id="student_lname_create" class="form-control" onkeyup="checkTextOnly(this.id)" placeholder="นามสกุล" value="" required>
                                        <span style="font-size: 10px; color: red;" id="warning_text_student_lname_create"></span>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="form-group">
                                    <label for="title" class="col-sm-3 control-label">
                                        email
                                        <span style="color: red; font-size: 15px;">* </span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="email"  name="student_email" class="form-control" placeholder="test@mail.com" value="" required>

                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="form-group">
                                    <label for="title" class="col-sm-3 control-label">
                                        เบอร์โทร
                                        <span style="color: red; font-size: 15px;">* </span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text"  name="student_tel" class="form-control"  id="student_tel_create" minlength="10" onkeyup="checkNumberOnly(this.id)" maxlength="10" placeholder="0811111111" value="" required>
                                        <span style="font-size: 10px; color: red;" id="warning_text_student_tel_create"></span>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="form-group">
                                    <label for="title" class="col-sm-3 control-label">
                                        ระดับชั้น
                                        <span style="color: red; font-size: 15px;">* </span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" name="student_class" class="form-control" placeholder="ป.1" value="" required>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="form-group">
                                    <label for="title" class="col-sm-3 control-label">
                                        ห้องเรียน
                                        <span style="color: red; font-size: 15px;">* </span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text"  name="student_room" class="form-control" placeholder="1" value="" required>
                                    </div>
                                </div>
                                <input type="hidden"id="school_id" name="school_id" value="{{$school_id}}">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn_submit">
                            บันทึก
                        </button>
                     <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- edit student --}}
    <div id="edit_student" class="modal fade edit_student" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">แก้ไขข้อมูลนักเรียนในโรงเรียน</h4>
            </div>
            <div class="modal-body">
            <form method="POST" action="{{route('school.student.update')}}">
                    {{ csrf_field() }}

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="title" class="col-sm-3 control-label">
                                ชื่อ
                                <span style="color: red; font-size: 15px;">* </span>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" id="student_fname" name="student_fname" onkeyup="checkTextOnly(this.id)" class="form-control" placeholder="" value="" required>
                                <span style="font-size: 10px; color: red;" id="warning_text_student_fname"></span>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="form-group">
                            <label for="title" class="col-sm-3 control-label">
                                นามสกุล
                                <span style="color: red; font-size: 15px;">* </span>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" id="student_lname" name="student_lname" class="form-control" onkeyup="checkTextOnly(this.id)" placeholder="" value="" required>
                                <span style="font-size: 10px; color: red;" id="warning_text_student_lname"></span>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="form-group">
                            <label for="title" class="col-sm-3 control-label">
                                email
                                <span style="color: red; font-size: 15px;">* </span>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" id="student_email" name="student_email" class="form-control" placeholder="" value="" required>
                                <input type="hidden" id="student_old_email" name="student_old_email" class="form-control" placeholder="">
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="form-group">
                            <label for="title" class="col-sm-3 control-label">
                                เบอร์โทร
                                <span style="color: red; font-size: 15px;">* </span>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" id="student_tel" name="student_tel" onkeyup="checkNumberOnly(this.id)" class="form-control" placeholder="" value="" required>
                                <span style="font-size: 10px; color: red;" id="warning_text_student_tel"></span>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="form-group">
                            <label for="title" class="col-sm-3 control-label">
                                ระดับชั้น
                                <span style="color: red; font-size: 15px;">* </span>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="student_class" id="student_class" class="form-control" placeholder="ป.1" value="" required>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="form-group">
                            <label for="title" class="col-sm-3 control-label">
                                ห้องเรียน
                                <span style="color: red; font-size: 15px;">* </span>
                            </label>
                            <div class="col-sm-8">
                                <input type="text"  name="student_room" id="student_room" class="form-control" placeholder="1" value="" required>
                            </div>
                        </div>
                        <input type="hidden"id="school_id" name="school_id" value="{{$school_id}}">
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="submit" id="btn_submit" class="btn btn-success btn_submit">
                    บันทึก
                </button>

              <a type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</a>
            </div>
          </div>
        </form>

        </div>
    </div>

</section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>

<script>
    $(document).ready( function () {
        @foreach($students as $student)
            $('#table_{!!$student['num']!!}').DataTable();
        @endforeach
} );

</script>

<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content"></div>
  </div>
</div>

<script>
    function editstudent(email,fname,lname,tel,student_class,room) {
         $('#student_fname').val(fname);
         $('#student_lname').val(lname);
         $('#student_tel').val(tel);
         $('#student_email').val(email);
         $('#student_old_email').val(email);
         $('#student_class').val(student_class);
         $('#student_room').val(room);
        $('#edit_student').modal('show');

    }

    function checkTextOnly(field_id) {
        var regex = /\d+/g;
        var string = $('#'+field_id).val();
        var matches = string.match(regex);  // creates array from matches
        var current_lang = $('#current_lang').val();
        var please_enter_text_only = (current_lang=='en')?'Please enter text only.':'กรุณากรอกตัวอักษรเท่านั้น';

        if(matches!=null){ //number
            $('#'+field_id).val('');
            $('#warning_text_'+field_id).text(please_enter_text_only);
            return false;
        }
        else{
            if(!isNaN(string)){ //number
                $('#'+field_id).val('');
                $('#warning_text_'+field_id).text(please_enter_text_only);
                return false;
            }
        }
        $('#warning_text_'+field_id).text('');
    return true;
    }

    function checkNumberOnly(field_id) {
        var regex = /\d+/g;
        var string = $('#'+field_id).val();
        var matches = string.match(regex);  // creates array from matches
        var current_lang = $('#current_lang').val();
        var please_enter_number_only = (current_lang=='en')?'Please enter number only.':'กรุณากรอกตัวเลขเท่านั้น';

        if(matches==null){ //not number
            $('#'+field_id).val('');

            if((field_id!='member_idCard') || (field_id!='member_rate_start') || (field_id!='member_rate_end')){
                $('#warning_text_'+field_id).text(please_enter_number_only);
            }

            return false;
        }
        else{
            if(isNaN(string)){ //not number
                $('#'+field_id).val('');

                if((field_id!='member_idCard') || (field_id!='member_rate_start') || (field_id!='member_rate_end')){
                    $('#warning_text_'+field_id).text(please_enter_number_only);
                }
                return false;
            }
        }
        $('#warning_text_'+field_id).text('');
        return false;
    }

    function createstudent()
    {
        $('#create_student').modal('show');
    }

// $(function () {
// 	$('body').on('hidden.bs.modal', '.modal', function () {
// 		$(this).removeData('bs.modal');
// 	});
// });
</script>


@stop
