@extends('backend.layouts/default')

{{-- Page title --}}
@section('title')
นักเรียนในระบบ
@stop

{{-- page level styles --}}
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>นักเรียน</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('backend') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li><a href="#"> จัดการสมาชิก</a></li>
        <li class="active">นักเรียน</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h4 class="panel-title"> <i class="livicon" data-name="user" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    รายชื่อนักเรียนในระบบ
                </h4>
            </div>
            <br />
            <div class="panel-body">
                <div class="table-responsive">
                <table class="table table-bordered " id="table">
                    <thead>
                        <tr>
                            <th style="width: 10px;">ลำดับ</th>
                            <th with="40%">ชื่อ-นามสกุล</th>
                            <th with="20%">Email</th>
                            <th>เบอร์โทร</th>
                            <th with="20%">วันเวลาที่ลงทะเบียน</th>
                            <th style="width: 50px;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                            {{-- @if(count($users) >= 1)
                            @foreach ($users as $i => $user)
                            <tr>
                                <td>{!! ++$i !!}</td>
                                <td>{!! $user->member_fname ." ". $user->member_lname !!}</td>
                                <td>{!! $user->member_email !!}</td>
                                <td>{!! $user->member_tell !!}</td>
                                <td>{!! $user->created_at->format('d-m-Y H:i') !!}</td>
                                <td align="center">
                                    <form action="{{ route('users.destroy',$user->id) }}" method="POST">
                                    <input name="_method" type="hidden" value="DELETE">
                                            {{ csrf_field() }}
                                    <button type="submit" class="btn btn-danger"><i class="livicon" data-name="user-ban" data-size="15" data-c="#fff" data-hc="#fff" data-loop="true"  style="width: 50px; height: 50px;">
                                            </i>ลบ</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @endif --}}
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
        $('#table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax":{
                        "url": "{{ route('users.serverside') }}",
                        "dataType": "json",
                        "type": "POST",
                        "data":{ _token: "{{csrf_token()}}"}
            },
            "columns": [
                    { "data": "id" },
                    { "data": "fullname" },
                    { "data": "email" },
                    { "data": "tel" },
                    { "data": "created_at" },
                    { "data": "options" }
                ]
        });
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
