{{-- {{ dd(collect($members)->sortBy('member_fname')->groupBy('member_email')) }} --}}
  <style>

    table, th, td {
      border: 1px solid black;
      border-collapse: collapse;
    }

  </style>
 <h2> รายชื่อ ทั้งหมดของระบบ </h2>
  <table style="width:100%">
    <tr>
      <th>#</th>
      <th>ชื่อ</th>
      <th>นามสกุส</th>
      <th>สถานะ</th>
      <th>Email</th>
      <th>จำนวน ซ้ำ</th>
    </tr>
    @php
      $no = 1;
    @endphp
    @foreach (collect($members)->sortBy('member_fname')->groupBy('member_email') as $key => $value)
      <tr>
        @if (count($value) > 1)
          <td style="background-color: #ff000085;">{{$no++}}</td>
          <td style="background-color: #ff000085;">{{$value[0]['member_fname']}}</td>
          <td style="background-color: #ff000085;">{{$value[0]['member_lname']}}</td>
          <td style="background-color: #ff000085;">{{$value[0]['member_type']}}</td>
          <td style="background-color: #ff000085;">{{$key}}</td>
          <td style="background-color: #ff000085;">{{count($value)}}</td>
        @else
          <td>{{$no++}}</td>
          <td>{{$value[0]['member_fname']}}</td>
          <td>{{$value[0]['member_lname']}}</td>
          <td>{{$value[0]['member_type']}}</td>
          <td>{{$key}}</td>
          <td>{{count($value)}}</td>
        @endif
      </tr>
    @endforeach
  </table>
