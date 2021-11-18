<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<body>
<ul id="menu" class="page-sidebar-menu">
    <li {!! (Request::is('backend') ? 'class="active"' : '') !!}>
        <a href="{{ route('backend') }}">
            <i class="livicon" data-name="home" data-size="18" data-c="#418BCA" data-hc="#418BCA"
               data-loop="true"></i>
            <span class="title">Dashboard</span>
        </a>
    </li>
    <li {!! (Request::is('backend/members/new') || Request::is('backend/members/all') || Request::is('user_profile') || Request::is('backend/members/*') || Request::is('deleted_members') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="members" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">จัดการ อาจารย์</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('backend/members/new') ? 'class="active" ' : '') !!}>
                <a href="{{ URL::to('backend/members/new') }}">
                    <i class="fa fa-angle-double-right"></i>
                    อาจารย์รออนุมัติ
                </a>
            </li>
            <li {!! (Request::is('backend/members/all') || Request::is('backend/members/all/*') || Request::is('backend/members/show/*') ? 'class="active" ' : '') !!}>
                <a href="{{ URL::to('backend/members/all') }}">
                    <i class="fa fa-angle-double-right"></i>
                    อาจารย์
                </a>
            </li>
        </ul>
    </li>
    <li {!! (Request::is('backend/users/*') || Request::is('backend/user/all') || Request::is('user') ||  Request::is('deleted_user') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="members" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">จัดการ นักเรียน</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('backend/users/*') ? 'class="active" ' : '') !!}>
                <a href="{{ url('backend/users/index') }}">
                    <i class="fa fa-angle-double-right"></i>
                    นักเรียน
                </a>
            </li>
        </ul>
    </li>
    <li {!! (Request::is('backend/subjects')  || Request::is('backend/subjects/*')  || Request::is('backend/groups') || Request::is('backend/groups/*')  ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="notebook" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">จัดการ ความถนัด</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('backend/subjects') || Request::is('backend/subjects/*') ? 'class="active" ' : '') !!}>
                <a href="{{ URL::to('backend/subjects') }}">
                    <i class="fa fa-angle-double-right"></i>
                    วิชา
                </a>
            </li>
            <li {!! (Request::is('backend/groups') || Request::is('backend/groups/*') ? 'class="active" ' : '') !!}>
                <a href="{{ URL::to('backend/groups') }}">
                    <i class="fa fa-angle-double-right"></i>
                    กลุ่มความถนัด
                </a>
            </li>
        </ul>
    </li>
    <li {!! (Request::is('backend/coins/*') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="notebook" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">จัดการ Coins</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('backend/coins/fill') ? 'class="active" ' : '') !!}>
                <a href="{{ URL::to('backend/coins/fill') }}">
                    <i class="fa fa-angle-double-right"></i>
                    เติม Coins
                </a>
            </li>
            <li {!! (Request::is('backend/coins/revoke') ? 'class="active" ' : '') !!}>
                <a href="{{ URL::to('backend/coins/revoke') }}">
                    <i class="fa fa-angle-double-right"></i>
                    ถอน Coins
                </a>
            </li>
            <li {!! (Request::is('backend/coins/refund') ? 'class="active" ' : '') !!}>
                <a href="{{ URL::to('backend/coins/refund') }}">
                    <i class="fa fa-angle-double-right"></i>
                    ขอคืนเงิน
                </a>
            </li>
        </ul>
    </li>
    <li {!! (Request::is('backend/classroom') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="notebook" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">จัดการ ห้องเรียน</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('backend/classroom') ? 'class="active" ' : '') !!}>
                <a href="{{ URL::to('backend/classroom') }}">
                    <i class="fa fa-angle-double-right"></i>
                    ห้องเรียน
                </a>
            </li>
        </ul>
    </li>
    <li {!! (Request::is('backend/courses') || Request::is('backend/courses/*') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="notebook" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">จัดการ คอร์สเรียน</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('backend/courses') || Request::is('backend/courses/*') ? 'class="active" ' : '') !!}>
                <a href="{{ URL::to('backend/courses') }}">
                    <i class="fa fa-angle-double-right"></i>
                    คอร์สเรียน
                </a>
            </li>
        </ul>
    </li>
    <li {!! (Request::is('backend/banks') || Request::is('backend/banks/*') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="notebook" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">จัดการ ธนาคาร</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('backend/banks') || Request::is('backend/banks/*') ? 'class="active" ' : '') !!}>
                <a href="{{ URL::to('backend/banks') }}">
                    <i class="fa fa-angle-double-right"></i>
                    บัญชีธนาคาร
                </a>
            </li>
        </ul>
    </li>
    <li {!! (Request::is('backend/events') || Request::is('backend/events/*') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="notebook" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">จัดการ อีเว้นท์</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('backend/events') || Request::is('backend/events/*') ? 'class="active" ' : '') !!}>
                <a href="{{ URL::to('backend/events') }}">
                    <i class="fa fa-angle-double-right"></i>
                    อีเว้นท์
                </a>
            </li>
        </ul>
    </li>
    <li {!! (Request::is('backend/school') || Request::is('backend/school/*') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="notebook" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">จัดการ โรงเรียน</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('backend/school') ? 'class="active" ' : '') !!}>
                <a href="{{ URL::to('backend/school') }}">
                    <i class="fa fa-angle-double-right"></i>
                    โรงเรียน
                </a>
            </li>
            <li {!! (Request::is('backend/school/import/student') ? 'class="active" ' : '') !!}>
                <a href="{{ URL::to('backend/school/import/student') }}">
                    <i class="fa fa-angle-double-right"></i>
                        นำเข้าข้อมูลนักเรียน
                </a>
            </li>
            <li {!! (Request::is('backend/school/import/teacher') ? 'class="active" ' : '') !!}>
                <a href="{{ URL::to('backend/school/import/teacher') }}">
                    <i class="fa fa-angle-double-right"></i>
                        นำเข้าข้อมูลครู
                </a>
            </li>
        </ul>
    </li>
    <!-- Menus generated by CRUD generator -->
    @include('backend/layouts/menu')
</ul>
</body>
