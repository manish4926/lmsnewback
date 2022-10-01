@extends('master')

@section('content')
@push('topscripts')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
<style type="text/css">
  .table tr th,.table tr td {
    min-width: 150px;
    width: 150px;
  }
  .no-data {
    text-align: left;
    font-weight: bold;
  }
</style>
@endpush
<!--body wrapper start-->
<div class="wrapper">

  <!--Start Page Title-->
  <div class="page-title-box">
    <h4 class="page-title">Lead Quality Report</h4>
    <ol class="breadcrumb">
      <li>
        <a href="#">Dashboard</a>
      </li>
      <li>
        <a href="#">View</a>
      </li>
      <li class="active">
        Lead Quality Report
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

        <form method="POST" action="{{ route('sourcequalityreporttwo') }}" class="inline-form"  data-parsley-validate>
          @csrf
        <select class="form-control" name="search">
          <option value="" disabled="" selected="">Select Source</option>
          <option>All</option>
          @foreach($sources as $source)
          <option value="{{ $source->source }}">{{ $source->source }}</option>
          @endforeach
        </select>
                        
        <input type="text" class="form-control datepicker" name="startdate" placeholder="Start Date" value="{{ !empty($startdate) ? date('d-m-Y', strtotime($startdate)) : ''}}" required="" style="width: 300px; display: inline;">
        <input type="text" class="form-control datepicker" name="enddate" placeholder="End Date" value="{{ !empty($enddate) ? date('d-m-Y', strtotime($enddate)) : ''}}" required="" style="width: 300px; display: inline;">
        <input type="submit" class="btn btn-primary" value="Submit" name="submit"><br><br>
        </form>

        <code>
          /**<br>
         * This Data Does not Contain <br>
         * BBA, BCA, MCA, Fellowship Programme in Management, FPM, FPM programme<br>
         */
        </code>
        
        <h4>Lead Quality Report</h4>
        <div class="table-responsive">
          <table class="table table-striped table-bordered">
            @if(!empty($sourcedata))
            <thead>
              <tr>
                <th>Lead Remarks</th>
                
                @foreach(reset($sourcedata) as $key => $list)
                  <th>{{ $key }}</th>
                @endforeach
                
              </tr>
            </thead>
            <tbody>
              @foreach($sourcedata as $key => $val)
              <tr>
                <td>{{ $key }}</td>
                @foreach(reset($sourcedata) as $k => $list)
                  <td>{{ $val[$k] }}</td>
                @endforeach
                
              </tr>
              
              @endforeach
              

            </tbody>
            @else
            <tr class="no-data">
                <td colspan="4">No Record Found</td>
            </tr>
            @endif
          </table>
        </div>

        
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