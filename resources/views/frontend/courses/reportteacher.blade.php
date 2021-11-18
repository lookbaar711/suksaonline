<html>

<head>
	<title>Suksa Online</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="{{ asset('suksa/frontend/template/highcharts/highcharts.js')}}"></script>
    <script src="{{ asset('suksa/frontend/template/highcharts/highcharts-3d.js')}}"></script>
    <script type="text/javascript" src="{{ asset ('suksa/frontend/template/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset ('suksa/frontend/template/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset ('suksa/frontend/template/vendor/bootstrap/js/popper.js') }}"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/vendor/bootstrap/css/bootstrap.min.css') !!}">


	<style>
		@font-face {
			font-family: 'THSarabun';
			font-style: normal;
			font-weight: normal;
			src: url("{{ public_path('fonts/THSarabun.TTF') }}") format('truetype');
		}

		body {
			font-family: "THSarabun";
			font-size: 18px;
			/* font-weight: 900; */
		}
        .container-fluid {
            /* max-width: 1230px; */
        }
        .text-center {
			text-align: center;
		}
        .box-logo {
            padding: 15px;
        }
        .box-label {
            /* margin-bottom: 10px; */
            color: #0c3df3;
            margin-top: 20px;
        }
        .details-table {
            margin-left: auto;
            margin-right: auto;
            width: 100%;
            display: flex;
            margin-top: 10px;
        }
        .des-title , .des-text {
            float: left;
        }

        .des-text {
            color: #FE2424;
        }
        .table-bordered {
            margin-top: 20px;
            border: 1px solid #e9ecef;
        }
        .table-bordered thead th {
            border-bottom-width: 1px;
        }
        .table thead th {
        vertical-align: bottom;
        border-bottom: 1px solid #111;
        }
        .bordered th {
            border: 1px solid #111;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #111;
        }
        th.difficulty {
            width: 50%;
            color: #111;
        }
        th {
            color: #4caf50;
        }
        #container {
             width: 1000px;
             height: 500px;
             display:block;
             margin-bottom: 50px;
        }
        #curve_chart {
            width: 1000px;
            height: 500px;
        }
        #columnchart_values {
            width: 1000px;
             height: 500px;
             display:block;
        }
        @page {
            size: auto;
            margin:3.8mm;
        }
        @media print
        {

            body {
            -webkit-print-color-adjust: exact!important;
            }

            table.gridtable     { page-break-after: auto; }
            table.gridtable   tr    { page-break-inside:avoid;page-break-after: auto; }
            table.gridtable   td    { page-break-inside:avoid; page-break-after:auto }
            table.gridtable   thead { display:table-header-group }
            table.gridtable   tfoot { display:table-footer-group }
            .box-logo {
                width: 100%;
            }
            .box-label {
                width: 100%;
            }
            #container {

                display: contents;
                page-break-after: auto;
                page-break-inside:avoid;
             }

             #curve_chart{
                display: contents;
                page-break-after: auto;
                page-break-inside:avoid;
             }

             #columnchart_values {
                display: contents;
                page-break-after: auto;
                page-break-inside:avoid;
             }

             h4 {
                 font-size: 20px;
             }
            svg.highcharts-root {
                margin-left: -30px;
                width: 1000px;
                height: 380px;
             }

             #curve_chart div > svg {
                display: block;
                width: 1020px;
                height: 500px;
            }

            #columnchart_values svg.highcharts-root {
                width: 1000px;
                height: 360px;
            }


        }


	</style>
</head>

<body>

    <div class="" id="htmltest">
        <div class="container">
            <div class="text-center">
                <div class="box-logo">
                    <img src="{{ asset($cousres->image_url_logo) }}" height="130">
                    @if($cousres->image_school)
                    <img src="{{ asset($cousres->image_school) }}" height="130">
                    @endif
                </div>


            </div>
            <div class="box-label">
                <h4>แบบรายงานผลการจัดการเรียนการสอนผ่านห้องเรียนออนไลน์ผ่านระบบ Suksa Online Learning Platform</h4>
            </div>
            <div class="col-sm-12 col-xs-12">
                <div class="row">
                    <div class="details-table">
                        <div class="col-sm-4 col-xs-12">
                            <div class="des-title">
                                สถานศึกษา:
                            </div>
                        </div>
                        <div class="col-sm-8 col-xs-12">
                            <div class="des-text">
                                {{ $cousres->getMember->getMemberShcool->school_name_th }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="details-table">
                        <div class="col-sm-4 col-xs-12">
                            <div class="des-title">
                                ระดับชั้น:
                            </div>
                        </div>
                        <div class="col-sm-8 col-xs-12">
                            <div class="des-text">
                                {{ $cousres->getaAtitude->aptitude_name_th }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="details-table">
                        <div class="col-sm-4 col-xs-12">
                            <div class="des-title">
                                ห้องเรียน:
                            </div>
                        </div>
                        <div class="col-sm-8 col-xs-12">
                            <div class="des-text">
                                @if(count($cousres->classroom) > 0)
                                    @foreach ($cousres->classroom as $classroom)
                                        {{$classroom}}
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="details-table">
                        <div class="col-sm-4 col-xs-12">
                            <div class="des-title">
                                วันที่:
                            </div>
                        </div>
                        <div class="col-sm-8 col-xs-12">
                            <div class="des-text">
                                {{ date('d-m-Y',strtotime($cousres->course_date_start)) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="details-table">
                        <div class="col-sm-4 col-xs-12">
                            <div class="des-title">
                                เวลา:
                            </div>
                        </div>
                        <div class="col-sm-8 col-xs-12">
                            <div class="des-text">
                                {{ $cousres->course_time_start }} - {{ $cousres->course_time_end }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="details-table">
                        <div class="col-sm-4 col-xs-12">
                            <div class="des-title">
                                วิชา:
                            </div>
                        </div>
                        <div class="col-sm-8 col-xs-12">
                            <div class="des-text">
                                {{ $cousres->getSubject->subject_name_th }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="details-table">
                        <div class="col-sm-4 col-xs-12">
                            <div class="des-title">
                                จุดประสงค์การเรียน:
                            </div>
                        </div>
                        <div class="col-sm-8 col-xs-12">
                            <div class="des-text">
                                {{ $cousres->course_detail }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="details-table">
                        <div class="col-sm-4 col-xs-12">
                            <div class="des-title">
                                ครูผู้สอน:
                            </div>
                        </div>
                        <div class="col-sm-8 col-xs-12">
                            <div class="des-text">
                                {{ $cousres->getMember->member_fname.' '.$cousres->getMember->member_lname }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="details-table">
                        <div class="col-sm-4 col-xs-12">
                            <div class="des-title">
                                จำนวนนักเรียน:
                            </div>
                        </div>
                        <div class="col-sm-8 col-xs-12">
                            <div class="des-text">
                                {{ count($cousres->getClassroom->classroom_student) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="details-table">
                        <div class="col-sm-4 col-xs-12">
                            <div class="des-title">
                                เข้าเรียน:
                            </div>
                        </div>
                        <div class="col-sm-8 col-xs-12">
                            <div class="des-text">
                                {{ $cousres->studen_inroom }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="details-table">
                        <div class="col-sm-4 col-xs-12">
                            <div class="des-title">
                                ขาดเรียน:
                            </div>
                        </div>
                        <div class="col-sm-8 col-xs-12">
                            <div class="des-text">
                                {{ $cousres->studen_noinroom }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="details-table">
                        <div class="col-sm-4 col-xs-12">
                            <div class="des-title">
                                รายชื่อนักเรียนที่ขาดเรียน:
                            </div>
                        </div>
                        <div class="col-sm-8 col-xs-12">
                            <div class="des-text">
                                {{ ($cousres->studenname_noinroom ? $cousres->studenname_noinroom : '-') }}
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="details-table">
                        <div class="col-sm-4 col-xs-12">
                            <div class="des-title">
                                ความใส่ใจต่อการเรียนโดยรวม:
                            </div>
                        </div>
                        <div class="col-sm-8 col-xs-12">
                            <div class="des-text">
                                98%
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
            <div class="box-label">
                <h4>แบบประเมินรายวิชา</h4>
            </div>
            {{-- ตารางแสดงรายชื่อนักเรียนและคะแนนทำข้อสอบ --}}
            <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>รายชื่อนักเรียน</th>
                    <th>แบบทดสอบก่อนเรียน</th>
                    <th>แบบทดสอบหลังเรียน</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($cousres->studen_test as $studen_test)
                    {{-- {{dd($studen_test)}} --}}
                        <tr>
                            <td>{{$studen_test['fullname']}}</td>
                            <td>{{$studen_test['pretest']}}</td>
                            <td>{{$studen_test['makeposttest']}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="table table-bordered">
                <thead>
                  <tr>
                    <th></th>
                    <th>min</th>
                    <th>max</th>
                    <th>average</th>
                    <th>S.D.</th>
                  </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>แบบทดสอบก่อนเรียน</td>
                        <td>{{$cousres->min_pretest}}</td>
                        <td>{{$cousres->max_pretest}}</td>
                        <td>{{$cousres->average_pretest}}</td>
                        <td>{{$cousres->sd_pretest}}</td>
                    </tr>
                    <tr>
                        <td>แบบทดสอบหลังเรียน</td>
                        <td>{{$cousres->min_posttest}}</td>
                        <td>{{$cousres->max_posttest}}</td>
                        <td>{{$cousres->average_posttest}}</td>
                        <td>{{$cousres->sd_posttest}}</td>
                    </tr>

                </tbody>
            </table>
            <br>
            <div class="box-label">
                <h4>แบบประเมินความยากง่ายของข้อสอบ Pretest-Posttest</h4>
            </div>

            <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="difficulty">ข้อสอบ</th>
                    <th class="difficulty">ความยากง่ายของข้อสอบ</th>
                  </tr>
                </thead>
                <tbody>
                    @forelse($cousres->difficultys as $difficulty)
                    <tr>
                        <td>{!!$difficulty['question']!!}</td>
                        <td>{{$difficulty['P']}}</td>
                    </tr>
                    @empty

                    @endforelse
                </tbody>
            </table>
            <br>
            <br>


            <div class="row">
                <div id="container" ></div>
                {{-- <div id="container" style="width: 1000px; height: 500px; display: block;"></div> --}}
            </div>

            <div class="row">
                <div id="columnchart_values"></div>

            </div>

            <div class="row">

                <div id="curve_chart"></div>

            </div>




        </div>
    </div>


    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

            Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            width : 1080,
            title: {
                text: null,
            },
            subtitle: {
                text: null,
            },
            xAxis: {
                categories: JSON.parse('{!!$cousres->charts_categories!!}'),
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                text: null,
                }
            },
            credits : {
                enabled:false,
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y} คน</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                pointPadding: 0.2,
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y}'
                }
                }
            },
            series: [
                JSON.parse('{!!$cousres->charts_data_pretest!!}')
                ,JSON.parse('{!!$cousres->charts_data_posttest!!}')]
            });
    </script>

    <script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ['Year', 'average',{ role: 'annotation' }],
        ['pretest',  {!!$cousres->average_pretest!!}, {!!$cousres->average_pretest!!}],
        ['posttest',  {!!$cousres->average_posttest!!}, {!!$cousres->average_posttest!!}],
      ]);

      var options = {
        title: '',
        curveType: 'function',
        legend: { position: 'bottom' },
        vAxis: {
            viewWindowMode:'explicit',
            viewWindow: {
            min:0,
            max:10
            }
        }

      };

      var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

      chart.draw(data, options);
    }
  </script>

<script type="text/javascript">
    Highcharts.chart('columnchart_values', {
        chart: {
            type: 'column'
        },
        title: {
            text: null
        },
        xAxis: {
            categories: ['GrowthRate']
        },
        yAxis: {
            title: {
            text: null,
            }
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'GrowthRate',
            data: [{!!$cousres->growth_rate!!}],
            enableMouseTracking: false
        }]
        });

//     google.charts.load("current", {packages:['corechart']});
//     google.charts.setOnLoadCallback(drawChart);
//     function drawChart() {
//       var data = google.visualization.arrayToDataTable([
//         ["Element", "Density" ,{ role: 'annotation' }],
//         ["GrowthRate", {!!$cousres->growth_rate!!},{!!$cousres->growth_rate!!}]
//       ]);

//       var view = new google.visualization.DataView(data);
//       var options = {
//         title: "",
//         width: 1080,
//         height: 400,
//         legend: { position: "none" },
//       };
//       var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
//       chart.draw(view, options);
//   }
  </script>
<script>
    setTimeout(function(){ window.print() }, 2000)
</script>

</body>

</html>
