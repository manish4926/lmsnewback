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
    <h4 class="page-title">Counsellor Followup Record</h4>
    <ol class="breadcrumb">
      <li>
        <a href="#">Dashboard</a>
      </li>
      <li>
        <a href="#">View</a>
      </li>
      <li class="active">
        Counsellor Followup Record
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
        <form method="POST" action="{{ route('dailycounsellordata') }}" class="inline-form"  data-parsley-validate>
          @csrf
        <input type="text" class="form-control datepicker" name="startdate" placeholder="Start Date" value="{{ !empty($startdate) ? date('d-m-Y', strtotime($startdate)) : ''}}" required="" style="width: 300px; display: inline;">
        <input type="text" class="form-control datepicker" name="enddate" placeholder="End Date" value="{{ !empty($enddate) ? date('d-m-Y', strtotime($enddate)) : ''}}" required="" style="width: 300px; display: inline;">
        <input type="submit" class="btn btn-primary" value="Submit" name="submit"><br><br>
        </form>
        
        @if(!empty($counsellorfollowupbydate))
        <h4>{{ ucfirst($user->firstname) }} Counselling Data</h4>
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>SNO</th>
              <th>Name</th>
              <th>Date</th>
              <th>First Followup</th>
              <th>Second Followup</th>
              <th>Third Followup</th>
              <th>Fourth Followup</th>
              <th>Fifth Followup</th>
              <th>Open</th>
              <th>Closed</th>
              <th>Completed</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; ?>
            @foreach($counsellorfollowupbydate as $followup)
            <tr class="odd gradeX">
              <td>{{ $i++ }}</td>
              <td>{{ $followup['name'] }}</td>
              <td>{{ $followup['startdate'] .'-'. $followup['enddate'] }}</td>
              <td class ="{{ !empty($followup['first']) ? 'info' : '' }}">{{ $followup['first'] }}</td>
              <td class ="{{ !empty($followup['second']) ? 'info' : '' }}">{{ $followup['second'] }}</td>
              <td class ="{{ !empty($followup['third']) ? 'info' : '' }}">{{ $followup['third'] }}</td>
              <td class ="{{ !empty($followup['fourth']) ? 'info' : '' }}">{{ $followup['fourth'] }}</td>
              <td class ="{{ !empty($followup['fifth']) ? 'info' : '' }}">{{ $followup['fifth'] }}</td>
              <td class ="{{ !empty($followup['open']) ? 'info' : '' }}">{{ $followup['open'] }}</td>
              <td class ="{{ !empty($followup['closed']) ? 'info' : '' }}">{{ $followup['closed'] }}</td>
              <td class ="{{ !empty($followup['completed']) ? 'info' : '' }}">{{ $followup['completed'] }}</td>
              <td class ="{{ !empty($followup['total']) ? 'info' : '' }}">{{ $followup['total'] }}</td>
            </tr>
            @endforeach

          </tbody>
        </table>

        <h4>{{ ucfirst($user->firstname) }} MAT Counselling Data</h4>
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>SNO</th>
              <th>Name</th>
              <th>Date</th>
              <th>First Followup</th>
              <th>Second Followup</th>
              <th>Third Followup</th>
              <th>Fourth Followup</th>
              <th>Fifth Followup</th>
              <th>Open</th>
              <th>Closed</th>
              <th>Completed</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; ?>
            @foreach($counsellorfollowupbydateMAT as $followup)

            <tr class="odd gradeX">
              <td>{{ $i++ }}</td>
              <td>{{ $followup['name'] }}</td>
              <td>{{ $followup['startdate'] .'-'. $followup['enddate'] }}</td>
              <td class ="{{ !empty($followup['first']) ? 'info' : '' }}">{{ $followup['first'] }}</td>
              <td class ="{{ !empty($followup['second']) ? 'info' : '' }}">{{ $followup['second'] }}</td>
              <td class ="{{ !empty($followup['third']) ? 'info' : '' }}">{{ $followup['third'] }}</td>
              <td class ="{{ !empty($followup['fourth']) ? 'info' : '' }}">{{ $followup['fourth'] }}</td>
              <td class ="{{ !empty($followup['fifth']) ? 'info' : '' }}">{{ $followup['fifth'] }}</td>
              <td class ="{{ !empty($followup['open']) ? 'info' : '' }}">{{ $followup['open'] }}</td>
              <td class ="{{ !empty($followup['closed']) ? 'info' : '' }}">{{ $followup['closed'] }}</td>
              <td class ="{{ !empty($followup['completed']) ? 'info' : '' }}">{{ $followup['completed'] }}</td>
              <td class ="{{ !empty($followup['total']) ? 'info' : '' }}">{{ $followup['total'] }}</td>
            </tr>
            @endforeach

          </tbody>
        </table>
        @endif

        @if(!empty($allfollowupbydate))
        <h4>All Counseller Data</h4>
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>SNO</th>
              <th>Name</th>
              <th>Date</th>
              <th>First Followup</th>
              <th>Second Followup</th>
              <th>Third Followup</th>
              <th>Fourth Followup</th>
              <th>Fifth Followup</th>
              <th>Open</th>
              <th>Closed</th>
              <th>Completed</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; ?>
            @foreach($allfollowupbydate as $followup)

            <tr class="odd gradeX">
              <td>{{ $i++ }}</td>
              <td>{{ $followup['name'] }}</td>
              <td>{{ $followup['startdate'] .'-'. $followup['enddate'] }}</td>
              <td class="{{ !empty($followup['first']) ? 'info' : '' }}">{{ $followup['first'] }}</td>
              <td class="{{ !empty($followup['second']) ? 'info' : '' }}">{{ $followup['second'] }}</td>
              <td class="{{ !empty($followup['third']) ? 'info' : '' }}">{{ $followup['third'] }}</td>
              <td class="{{ !empty($followup['fourth']) ? 'info' : '' }}">{{ $followup['fourth'] }}</td>
              <td class="{{ !empty($followup['fifth']) ? 'info' : '' }}">{{ $followup['fifth'] }}</td>
              <td class ="{{ !empty($followup['open']) ? 'info' : '' }}">{{ $followup['open'] }}</td>
              <td class ="{{ !empty($followup['closed']) ? 'info' : '' }}">{{ $followup['closed'] }}</td>
              <td class ="{{ !empty($followup['completed']) ? 'info' : '' }}">{{ $followup['completed'] }}</td>
              <td class="{{ !empty($followup['total']) ? 'info' : '' }}">{{ $followup['total'] }}</td>

            </tr>
            @endforeach

          </tbody>
        </table>

        <h4>MAT Counseller Data</h4>
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>SNO</th>
              <th>Name</th>
              <th>Date</th>
              <th>First Followup</th>
              <th>Second Followup</th>
              <th>Third Followup</th>
              <th>Fourth Followup</th>
              <th>Fifth Followup</th>
              <th>Open</th>
              <th>Closed</th>
              <th>Completed</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; ?>
            @foreach($allfollowupbydateMAT as $followup)

            <tr class="odd gradeX">
              <td>{{ $i++ }}</td>
              <td>{{ $followup['name'] }}</td>
              <td>{{ $followup['startdate'] .'-'. $followup['enddate'] }}</td>
              <td class="{{ !empty($followup['first']) ? 'info' : '' }}">{{ $followup['first'] }}</td>
              <td class="{{ !empty($followup['second']) ? 'info' : '' }}">{{ $followup['second'] }}</td>
              <td class="{{ !empty($followup['third']) ? 'info' : '' }}">{{ $followup['third'] }}</td>
              <td class="{{ !empty($followup['fourth']) ? 'info' : '' }}">{{ $followup['fourth'] }}</td>
              <td class="{{ !empty($followup['fifth']) ? 'info' : '' }}">{{ $followup['fifth'] }}</td>
              <td class ="{{ !empty($followup['open']) ? 'info' : '' }}">{{ $followup['open'] }}</td>
              <td class ="{{ !empty($followup['closed']) ? 'info' : '' }}">{{ $followup['closed'] }}</td>
              <td class ="{{ !empty($followup['completed']) ? 'info' : '' }}">{{ $followup['completed'] }}</td>
              <td class="{{ !empty($followup['total']) ? 'info' : '' }}">{{ $followup['total'] }}</td>
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