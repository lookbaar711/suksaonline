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
    <h1>โรงเรียน</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('backend') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li><a href="#"> จัดการโรงเรียน</a></li>
        <li class="active">โรงเรียน</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left">
                    รายชื่อโรงเรียนในระบบ
                </h4>
                <div class="pull-right">
                    <a href="{{ url('backend/school/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> เพิ่มโรงเรียน</a>
                </div>
            </div>
            <br />
            <div class="panel-body">
                <div class="table-responsive">
                <table class="table table-bordered " id="table">
                    <thead>
                        <tr>
                            <th style="width: 10px;">ลำดับ</th>
                            <th with="40%">ชื่อโรงเรียน</th>
                            <th with="20%">Email</th>
                            <th>เบอร์โทร</th>
                            <th with="20%">วันเวลาที่ลงทะเบียน</th>
                            <th with="20%">นักเรียน</th>
                            <th with="20%">อาจารย์</th>
                            <th style="width: 50px;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                            @if(count($schools) >= 1)
                            @foreach ($schools as $i => $school)
                            <tr>
                                <td>{!! ++$i !!}</td>
                                <td>{!! $school->school_name_th !!}</td>
                                <td>{!! $school->school_email !!}</td>
                                <td>{!! $school->school_tell !!}</td>
                                <td>{!! $school->created_at->format('d-m-Y H:i') !!}</td>
                                <td><a href="{{url('backend/school/student/'.$school->id)}}" class="btn btn-success">จัดการ</a></td>
                                <td><a href="{{url('backend/school/teacher/'.$school->id)}}" class="btn btn-success">จัดการ</a></td>
                                <td align="center">
                                    <form action="{{ route('school.schooldestroy',$school->id) }}" method="POST">
                                    <a href="{{ url('backend/school/edit', $school->id) }}" class="btn btn-responsive button-alignment btn-success"><i class="fa fa-pencil-square-o"></i>แก้ไข</a>
                                        <input name="_method" type="hidden" value="DELETE">
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
<script>
$(function () {
	$('body').on('hidden.bs.modal', '.modal', function () {
		$(this).removeData('bs.modal');
	});
});
</script>


@stop
