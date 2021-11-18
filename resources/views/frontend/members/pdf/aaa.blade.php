<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ $title }}</title>
        
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        @php 
            $host = $_SERVER['HTTP_HOST'];
        @endphp

    </head>

    <body class="hold-transition sidebar-mini">
        <div id="app" class="wrapper">              

                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">รหัส</th>
                      <th scope="col">ชื่อกลุ่มวิชา (TH)</th>
                      <th scope="col">ชื่อกลุ่มวิชา (EN)</th>
                    </tr>
                  </thead>
                  {{-- <tbody>

                    @foreach($aptitude as $p)
                    <tr>
                      <th scope="row">{{ $p->_id }}</th>
                      <td>{{ $p->aptitude_name_th }}</td>
                      <td>{{ $p->aptitude_name_en }}</td>
                      
                    </tr>
                    @endforeach
                    
                  </tbody> --}}
                </table>
        </div>
    </body>
</html>