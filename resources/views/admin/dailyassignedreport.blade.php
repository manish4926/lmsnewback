@extends('master')

@section('content')
@push('topscripts')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
<style type="text/css">
  .specified {
    white-space: normal !important; 
    word-wrap: break-word;  
  }
</style>
@endpush
<!--body wrapper start-->
<div class="wrapper">

  <!--Start Page Title-->
  <div class="page-title-box">
    <h4 class="page-title">Daily Assigned Data</h4>
    <ol class="breadcrumb">
      <li>
        <a href="#">Dashboard</a>
      </li>
      <li>
        <a href="#">View</a>
      </li>
      <li class="active">
        Daily Assigned Data
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
        <form method="POST" action="{{ route('dailyassigneddata') }}" class="inline-form"  data-parsley-validate>
          @csrf
        <input type="text" class="form-control datepicker" name="startdate" placeholder="Start Date" value="{{ !empty($startdate) ? date('d-m-Y', strtotime($startdate)) : ''}}" required="" style="width: 300px; display: inline;">
        <input type="text" class="form-control datepicker" name="enddate" placeholder="End Date" value="{{ !empty($enddate) ? date('d-m-Y', strtotime($enddate)) : ''}}" required="" style="width: 300px; display: inline;">
        <select class="form-control" name="counsellor_id">
          <option value="">All Counsellors</option>
          <?php $counsellor_list = getCounsellorOnly(); ?>
          @foreach ($counsellors as $counsellor)
            <option value="{{ $counsellor->id }}" {{ $counsellor->id == $counsellor_id ? 'selected' : '' }} >{{ $counsellor->firstname}}</option>
          @endforeach
        </select>
        <input type="submit" class="btn btn-primary" value="Submit" name="submit"><br><br>
        </form>
        
        @if(!empty($followupassigneddata))
        <h4>Folloup Assigned Records</h4>
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>Date</th>
              <th>Counsellor Name</th>
              <th>Course</th>
              <th>Total Calls</th>
              <th>No. of Calls Added</th>
              <th>Source</th>
              <th>No. of Calls forwarded for 2nd follow up</th>
              <th>Source</th>
              <th>No. of Calls forwarded for 3rd follow up</th>
              <th>Source</th>
              <th>No. of Calls forwarded for 4th follow up</th>
              <th>Source</th>
              <th>No. of Calls forwarded for 5th follow up</th>
              <th>Source</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; ?>
            @foreach($followupassigneddata as $data)
            <tr class="odd gradeX">
              <td>{{ $data['date'] }}</td>
              <td>{{ $data['name'] }}</td>
              <td>{{ $data['course'] }}</td>
              <td>{{ $data['total'] }}</td>
              <td>{{ $data['firstcallassigned'] }}</td>
              <td class="specified"> @foreach($data['firstsource'] as $source) {{ $source."," }} @endforeach </td>
              <td>{{ $data['secondcallassigned'] }}</td>
              <td class="specified"> @foreach($data['secondsource'] as $source) {{ $source."," }} @endforeach </td>
              <td>{{ $data['thirdcallassigned'] }}</td>
              <td class="specified"> @foreach($data['thirdsource'] as $source) {{ $source."," }} @endforeach </td>
              <td>{{ $data['fourthcallassigned'] }}</td>
              <td class="specified"> @foreach($data['fourthsource'] as $source) {{ $source."," }} @endforeach </td>
              <td>{{ $data['fifthcallassigned'] }}</td>
              <td class="specified"> @foreach($data['fifthsource'] as $source) {{ $source."," }} @endforeach </td>

            </tr>
            @endforeach

          </tbody>
        </table>
        @endif

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