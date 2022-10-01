@extends('master')

@section('content')
@push('topscripts')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
@endpush
<!--body wrapper start-->
<div class="wrapper">

  <!--Start Page Title-->
  <div class="page-title-box">
    <h4 class="page-title">Source Quality Report</h4>
    <ol class="breadcrumb">
      <li>
        <a href="#">Dashboard</a>
      </li>
      <li>
        <a href="#">View</a>
      </li>
      <li class="active">
        Source Quality Report
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

        <code>
          /**<br>
         * This Data Does not Contain <br>
         * BBA, BCA, MCA, Fellowship Programme in Management, FPM, FPM programme<br>
         */
        </code>
        
        <h4>Form Conversion Report</h4>
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th rowspan="2">Source</th>
              <th rowspan="2">Total Queries</th>
              <th rowspan="2">Unique Queries</th>
              <th colspan="11">Remarks</th>
            </tr>
            <tr>
              <th>Not Interested</th>
              <th>Switch Off</th>
              <th>Not Rechable</th>
              <th>Concerned Person Not Available</th>
              <th>Call Back Later</th>
              <th>Call Disconnected</th>
              <th>Wrong Number</th>
              <th>Request to Call</th>
              <th>Call Not Picked</th>
              <th>Others</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($sourcedata as $key => $data)
            {{-- {{ empty($data['total']) ? dd($data) : ""}} --}}
            <tr>
              <td><strong>{{ $key }}</strong></td>
              <td>{{ $data['total'] }}</td>
              <td>{{ $data['unique'] }}</td>
              <td>{{ $data['remarks']['Not Interested'] }}</td>
              <td>{{ $data['remarks']['Switch Off'] }}</td>
              <td>{{ $data['remarks']['Not Reachable'] }}</td>
              <td>{{ $data['remarks']['Concerned Person Not Available'] }}</td>
              <td>{{ $data['remarks']['Call Back Later'] }}</td>
              <td>{{ $data['remarks']['Call Disconnected'] }}</td>
              <td>{{ $data['remarks']['Wrong Number'] }}</td>
              <td>{{ $data['remarks']['Request to Call'] }}</td>
              <td>{{ $data['remarks']['Call Not Picked'] }}</td>
              <td>{{ $data['remarks']['Others'] }}</td>
              <td>{{ $data['remarks']['total'] }}</td>
            </tr>
            @endforeach

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