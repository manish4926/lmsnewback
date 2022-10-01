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
    <h4 class="page-title">Form Conversion Report</h4>
    <ol class="breadcrumb">
      <li>
        <a href="#">Dashboard</a>
      </li>
      <li>
        <a href="#">View</a>
      </li>
      <li class="active">
        Form Conversion Report
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

        <form method="POST" action="{{ route('gdpiSourcereport') }}" class="inline-form"  data-parsley-validate>
        @csrf
        <input type="text" class="form-control datepicker" name="startdate" placeholder="Start Date" value="{{ !empty($startdate) ? date('d-m-Y', strtotime($startdate)) : date('d-m-Y', strtotime('-6 days'))}}" required="" style="width: 300px; display: inline;">
        <input type="text" class="form-control datepicker" name="enddate" placeholder="End Date" value="{{ !empty($enddate) ? date('d-m-Y', strtotime($enddate)) : date('d-m-Y')}}" required="" style="width: 300px; display: inline;">
        <input type="submit" class="btn btn-primary" value="Submit" name="submit"><br><br>
        </form>
        

        <table class="table table-striped table-bordered table-condensed word-breaker">
          <thead>
            <tr>
              <th>S. No.</th>
              <th>Reg Id</th>
              <th>Name</th>
              <th>Email</th>
              <th>Applied For</th>
              <th>Dated On</th>
              <th>First Source</th>
              {{-- <th>All Sources</th> --}}
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; ?>
            @foreach($admissionforms as $admissionform)
            <tr>
              <td>{{ $i++ }}</td>
              <td>{{ $admissionform->regid }}</td>
              <td>{{ $admissionform->name }}</td>
              <td>{{ $admissionform->email }}</td>
              <td>{{ $admissionform->programme_af }}</td>
              <td>{{ $admissionform->date }}</td>
              <td>{{ $admissionform->firstsource }}</td>
              {{-- <td>{{ !empty($admissionform->allsourcelist()) ? implode (", ", array_column($admissionform->allsourcelist(), 'source')) : 'Not Found' }}</td> --}}
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