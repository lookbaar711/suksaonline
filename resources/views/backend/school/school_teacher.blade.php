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
<style>
    /* .skin-josh .navbar_school {
    background-color: #ffff;
    } */
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
</style>
<section class="content-header">
<h1>โรงเรียน{{$teachers->school_name_th}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('backend') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li><a href="{{url('backend/school')}}"> จัดการโรงเรียน</a></li>
        <li class="active">โรงเรียน</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left">
                    รายชื่อครูในโรงเรียน
                </h4>
                <div class="pull-right">
                    <button
                        onclick="createteacher()"
                        class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span>
                        เพิ่มครู
                    </button>

                </div>
            </div>
            <br />
            <div class="panel-body">
                <div class="table-responsive">
                <table class="table table-bordered " id="table">
                    <thead>
                        <tr>
                            <th style="width: 10px;">ลำดับ</th>
                            <th with="40%">ชื่อ</th>
                            <th with="20%">นามสกุล</th>
                            <th>เบอร์โทร</th>
                            <th with="20%">Email</th>
                            <th style="width: 50px;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                            @if($teachers)
                            @foreach ($teachers['school_teacher'] as $i => $teacher)
                            <tr>
                                <td>{!! ++$i !!}</td>
                                <td>{!! @$teacher['teacher_fname'] !!}</td>
                                <td>{!! @$teacher['teacher_lname'] !!}</td>
                                <td>{!! @$teacher['teacher_tel'] !!}</td>
                                <td>{!! @$teacher['teacher_email'] !!}</td>
                                <td align="center">

                                    <form action="{{ route('school.teacher.delete') }}" method="POST">

                                        <a
                                    onclick="editteacher('{{@$teacher['teacher_fname']}}','{{@$teacher['teacher_lname']}}','{{@$teacher['teacher_tel']}}','{{@$teacher['teacher_email']}}')"
                                    class="btn btn-responsive button-alignment btn-success"
                                    >
                                    <i class="fa fa-pencil-square-o"></i>แก้ไข
                                    </a>

                                        <input name="email" type="hidden" value="{{@$teacher['teacher_email']}}">
                                        <input name="school_id" type="hidden" value="{{@$school_id}}">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-danger">ลบ</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>    <!-- row-->

    {{-- create teacher --}}
    <div id="create_teacher" class="modal fade create_teacher" role="dialog">
        <form method="POST" action="{{route('school.teacher.create')}}">
                <div class="modal-dialog">
                <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">เพิ่มข้อมูลครูในโรงเรียน</h4>
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
                                            <input type="text"  name="teacher_fname" id="teacher_fname_create" onkeyup="checkTextOnly(this.id)" class="form-control" placeholder="ชื่อ" value="" required>
                                            <span style="font-size: 10px; color: red;" id="warning_text_teacher_fname_create"></span>
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
                                            <input type="text" name="teacher_lname" class="form-control" placeholder="นามสกุล" onkeyup="checkTextOnly(this.id)" value="" required>
                                            <span style="font-size: 10px; color: red;" id="warning_text_teacher_lname_create"></span>
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
                                            <input type="email"  name="teacher_email" class="form-control" placeholder="test@mail.com" value="" required>

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
                                            <input type="text"  name="teacher_tel" id="teacher_tel_create" class="form-control" minlength="10" maxlength="10" onkeyup="checkNumberOnly(this.id)" placeholder="0811111111" value="" required>
                                            <span style="font-size: 10px; color: red;" id="warning_text_teacher_tel_create"></span>
                                        </div>
                                    </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <input type="hidden"id="school_id" name="school_id" value="{{$school_id}}">
                            <button type="submit" class="btn btn-success btn_submit">
                                บันทึก
                            </button>
                         <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    {{-- edit teacher --}}
    <div id="edit_teacher" class="modal fade edit_teacher" role="dialog">
        <form method="POST" action="{{route('school.teacher.update')}}">
            <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">แก้ไขข้อมูลครูในโรงเรียน</h4>
                </div>
                <div class="modal-body">
                    <form>
                        {{ csrf_field() }}

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="title" class="col-sm-3 control-label">
                                    ชื่อ
                                    <span style="color: red; font-size: 15px;">* </span>
                                </label>
                                <div class="col-sm-8">
                                    <input type="text" id="teacher_fname" name="teacher_fname" onkeyup="checkTextOnly(this.id)" class="form-control" placeholder="" value="" required>
                                    <span style="font-size: 10px; color: red;" id="warning_text_teacher_fname"></span>
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
                                    <input type="text" id="teacher_lname" name="teacher_lname" class="form-control" onkeyup="checkTextOnly(this.id)"  placeholder="" value="" required>
                                    <span style="font-size: 10px; color: red;" id="warning_text_teacher_lname"></span>
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
                                    <input type="text" id="teacher_email" name="teacher_email" class="form-control" placeholder="" value="" required>
                                    <input type="hidden" id="teacher_old_email" name="teacher_old_email" class="form-control" placeholder="">
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
                                    <input type="text" id="teacher_tel" name="teacher_tel" class="form-control"onkeyup="checkNumberOnly(this.id)" placeholder="" value="" required>
                                    <span style="font-size: 10px; color: red;" id="warning_text_teacher_tel"></span>
                                </div>
                            </div>
                            <input type="hidden"id="school_id" name="school_id" value="{{$school_id}}">
                        </div>
                    </div>
                </form>

                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-success btn_submit">บันทึก</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
                </div>
            </div>

            </div>
        </form>
    </div>

</section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>

<script>
    $(document).ready( function () {
    $('#table').DataTable();
} );

</script>

<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content"></div>
  </div>
</div>
{{-- '{{@$teacher['teacher_fname']}}','{{@$teacher['teacher_lname']}}','{{@$teacher['teacher_tel']}}','{{@$teacher['teacher_email']}}' --}}
<script>
    function editteacher(fname,lname,tel,email) {
         $('#teacher_fname').val(fname);
         $('#teacher_lname').val(lname);
         $('#teacher_tel').val(tel);
         $('#teacher_email').val(email);
         $('#teacher_old_email').val(email);
        $('#edit_teacher').modal('show');
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

    function createteacher()
    {
        $('#create_teacher').modal('show');
    }

</script>
{{-- <script>
$(function () {
	$('body').on('hidden.bs.modal', '.modal', function () {
		$(this).removeData('bs.modal');
	});
});
</script> --}}


@stop
