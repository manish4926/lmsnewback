@extends('master')

@section('content')
@push('topscripts')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/morris-chart/morris.css') }}">
@endpush



<!--body wrapper start-->
<div class="wrapper">

  <!--Start Page Title-->
  <div class="page-title-box">
    <h4 class="page-title">Progress </h4>
    <ol class="breadcrumb">
      <li>
        <a href="#">Dashboard</a>
      </li>

      <li class="active">
        Counsellor Progress
      </li>
    </ol>
    <div class="clearfix"></div>
  </div>
  <!--End Page Title-->          


<!--Start row-->  
  <div class="row">



    <!-- Start inbox widget-->
    <div class="col-md-12">
      <div class="white-box">
        <div id="visualization" style="margin: 1em"> </div>
        <h2 class="header-title"> Progress </h2>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Counsellor Name</th>
                <th>Total Assigned</th>
                <th>Total Completed</th>
                <th>Status</th>
                <th colspan="2">Progress</th>

              </tr>
            </thead>
            <tbody>
              <?php $i = 0; ?>
              @foreach($counsellorprogress as $progress)
              <?php $i++; ?>
              <tr>
                <td>{{ $i }}</td>
                <td><a href="{{ route('counsellorfollowuplist',['id' => $progress->id, 'name' => strtolower($progress->firstname), 'followupnum' => 1 ]) }}"> {{ $progress->firstname." ".$progress->lastname  }}</a></td>
                <td><span class="badge badge-primary">{{ $progress->totalassigned }}</span></td>
                <td><span class="badge badge-info">{{ $progress->totalcompleted }}</span></td>
                @if($progress->perc <= 30)
                <td><span class="label label-danger">Started</span></td>
                @elseif($progress->perc >= 31 && $progress->perc <= 85)
                <td><span class="label label-warning">In Progress</span></td>
                @elseif($progress->perc >= 86 && $progress->perc <= 99)
                <td><span class="label label-info">Almost Completed</span></td>
                @elseif($progress->perc == 100)
                <td><span class="label label-success">Completed</span></td>

                @endif
                <td><div class="progress progress-striped progress-sm">
                  <div class="progress-bar 
                  @if($progress->perc <= 30) progress-bar-danger 
                  @elseif($progress->perc >= 31 && $progress->perc <= 85) progress-bar-warning 
                  @elseif($progress->perc >= 86 && $progress->perc <= 99) progress-bar-info 
                  @elseif($progress->perc == 100) progress-bar-success @endif" style="width: {{ $progress->perc }}%;"></div>
                </div></td>
                <td>{{ $progress->perc }}% Completed</td>
              </tr>
              @endforeach

            </tbody>
          </table>
        </div>

      </div>
    </div>

    <div class="col-md-12">
      <div class="white-box">
        <div id="chart_div" style="height: 500px;"></div>
      </div>
    </div>

  </div>
  <!--End row-->




</div>



<!-- End Wrapper-->
@push('bottomscripts')


@endpush
@endsection