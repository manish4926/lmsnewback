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
    <h4 class="page-title">Daily Report</h4>
    <ol class="breadcrumb">
      <li>
        <a href="#">Dashboard</a>
      </li>
      <li>
        <a href="#">View</a>
      </li>
      <li class="active">
        Daily Report
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
        <form method="POST" action="{{ route('dailyreport') }}" class="inline-form"  data-parsley-validate>
          @csrf
        <input type="text" class="form-control datepicker" name="startdate" placeholder="Start Date" required="" style="width: 300px; display: inline;">
        <input type="text" class="form-control datepicker" name="enddate" placeholder="End Date" required="" style="width: 300px; display: inline;">
        <input type="submit" class="btn btn-primary" value="Submit" name="submit"><br><br>
        </form>

        
        <table class="table table-striped table-bordered table-condensed word-breaker">
          <thead>
            <tr>
              <th rowspan="3">Date</th>
              <th colspan="4">Enquiries</th>
              <th colspan="3">Forms Sale</th>
              <th colspan="3">Forms Sale</th>
              
            </tr>
            <tr>
              <th rowspan="2">No. of  Call Queries</th>
              <th rowspan="2">No. of Walk in</th>
              <th rowspan="2">No. of Mail Queries</th>
              <th rowspan="2">Total No. of Queries</th>
              <th rowspan="2">No. of Forms Sale in Cash </th>
              <th rowspan="2">No. of DD Received</th>
              <th rowspan="2">Total No. of Forms Sale</th>
              <th colspan="2">Forms Recd. from Mail  (Online Registration)</th>
              <th rowspan="2">Total No. of Filled Up Forms Received</th>
              
            </tr>
            <tr>
              <th>Online payment</th>
              <th>Part Time</th>
            </tr>
          </thead>
          <tbody>
            @foreach($dailyreportdata as $dailydata)

            <tr>
              <td>{{ $dailydata['date'] }}</td>
              <td>{{ $dailydata['calls'] }}</td>
              <td>{{ $dailydata['totalwalkin'] }}</td>
              <td>{{ $dailydata['email'] }}</td>
              <td>{{ $dailydata['total_queries'] }}</td>
              <td>{{ $dailydata['cashforms'] }}</td>
              <td>{{ $dailydata['ddreceived'] }}</td>
              <td>{{ $dailydata['total_form_sale'] }}</td>
              <td>{{ $dailydata['online_forms'] }}</td>
              <td>{{ $dailydata['online_parttime_forms'] }}</td>
              <td>{{ $dailydata['total_form_received'] }}</td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th>Total</th>
              <th>{{ array_sum(array_column($dailyreportdata,'calls')) }}</th>
              <th>{{ array_sum(array_column($dailyreportdata,'totalwalkin')) }}</th>
              <th>{{ array_sum(array_column($dailyreportdata,'email')) }}</th>
              <th>{{ array_sum(array_column($dailyreportdata,'total_queries')) }}</th>
              <th>{{ array_sum(array_column($dailyreportdata,'cashforms')) }}</th>
              <th>{{ array_sum(array_column($dailyreportdata,'ddreceived')) }}</th>
              <th>{{ array_sum(array_column($dailyreportdata,'total_form_sale')) }}</th>
              <th>{{ array_sum(array_column($dailyreportdata,'online_forms')) }}</th>
              <th>{{ array_sum(array_column($dailyreportdata,'online_parttime_forms')) }}</th>
              <th>{{ array_sum(array_column($dailyreportdata,'total_form_received')) }}</th>
            </tr>
          </tfoot>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
  $('.datepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      endDate: '0d',
      autoclose: true
  });
</script>
@endpush
@endsection