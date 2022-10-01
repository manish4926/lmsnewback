@extends('master')

@section('content')
@push('topscripts')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
<style type="text/css">
  .word-breaker tr td{
    word-wrap:break-word;
    overflow-wrap:break-word;
    word-break: break-all;
  }
</style>
@endpush
<!--body wrapper start-->
<div class="wrapper">

  <!--Start Page Title-->
  <div class="page-title-box">
    <h4 class="page-title">Letter Dispatch Report</h4>
    <ol class="breadcrumb">
      <li>
        <a href="#">Dashboard</a>
      </li>
      <li>
        <a href="#">View</a>
      </li>
      <li class="active">
        Letter Dispatch Report
      </li>
    </ol>
    <div class="clearfix"></div>
  </div>
  <!--End Page Title-->          
  @include('partials.alerts')

  <!--Start row-->
  <div class="row">
    <div class="col-md-12">
      <div class="white-box">

        
        <table class="table table-striped table-bordered table-condensed word-breaker">
          <thead>
            <tr>
              <th>GD / PI Date</th>
              <th>Fee Last Date</th>
              <th>Cycle</th>
              <th>Place</th>
              <th>PGDM - Gen</th>
              <th>Admission Taken</th>
              <th>PGDM - IB</th>
              <th>Letters Dispatch</th>
              <th>Admission Taken</th>
              <th>PGDM - RM</th>
              <th>Letters Dispatch</th>
              <th>Admission Taken</th>
            </tr>
          </thead>
          <tbody>
            {{-- @foreach($gdpireport as $list)
            <tr>
              <td>{{ $list['gd_pi_center'] }}</td>
              <td>{{ !empty($list['gd_pi_date']) ? date('Y-m-d', strtotime($list['gd_pi_date'])) : "Not Decided" }}</td>
              <td>{{ $list['total'] }}</td>
              <td>{{ $list['appeared'] }}</td>
              <td>{{ $list['percentage'] }} %</td>
              <td>{{ $list['absent'] }}</td>
              <td>{{ $list['form_sold'] }}</td>
              <td>Detail Not Available</td>
              <td onclick="updateremarks('{{ $list['gd_pi_date'] }}')">{{ $list['remarks'] }}</td>
              <td>{{ $list['totalappeared'] }}</td>
              
            </tr>
            @endforeach --}}
          </tbody>
        </table>

        
      </div>
    </div>
  </div>
  <!--End row-->



</div>
<!--End row-->

</div>
<!-- End Wrapper-->


@push('bottomscripts')
@endpush
@endsection