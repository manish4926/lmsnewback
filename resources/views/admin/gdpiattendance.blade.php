@extends('master')

@section('content')
@push('topscripts')
<link rel="stylesheet" type="text/css" href="{{ asset('css/lc_switch.css') }}">
<style type="text/css">
  .lcs_switch {
    width: 100px;
  }

  .lcs_label {
    width: 63px;
  }

  .lcs_switch.lcs_on .lcs_cursor {
    left: 76px;
  }
</style>
@endpush
<!--body wrapper start-->
<div class="wrapper">

  <!--Start Page Title-->
  <div class="page-title-box">
    <h4 class="page-title">GDPI Attendance</h4>
    <ol class="breadcrumb">
      <li>
        <a href="#">Dashboard</a>
      </li>
      <li>
        <a href="#">View</a>
      </li>
      <li class="active">
        GDPI Attendance
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
        <form method="POST" action="{{ route('gdpiattendance') }}" class="form-horizontal"  data-parsley-validate>
          @csrf
          <div class="col-md-4">
          <select class="form-control" name="gd_pi_date">
          <option value="" disabled="" selected="">Select GDPI Date</option>
          @foreach($gdpidates as $date)
          <option value="{{ $date->gd_pi_date }}">{{ date('d-m-Y', strtotime($date->gd_pi_date)) }}</option>
          @endforeach
          </select>
          </div>
          <div class="col-md-4">
          <select class="form-control" name="gd_pi_center">
          <option value="" disabled="" selected="">Select Location</option>
          @foreach($gdpilocations as $location)
          <option value="{{ $location->gd_pi_center }}">{{ $location->gd_pi_center }}</option>
          @endforeach
          </select>
          </div>
          <div class="col-md-4">
          <button type="submit" class="btn btn-primary">Search</button>
          </div>
          </form>
          <br><br><br>
      </div>

      <div class="white-box">

        
        <table class="table table-striped table-bordered table-condensed word-breaker">
          <thead>
            <tr>
              <th>Reg. No.</th>
              <th>Student Name</th>
              <th>Email Id</th>
              <th>Phone No.</th>
              <th>GDPI Date Assigned</th>
              <th>Mark Attendance</th>
            </tr>
          </thead>
          <tbody>
            @foreach($studentlist as $list)
            <tr>
              <td>{{ $list['regid'] }}</td>
              <td>{{ $list['name'] }}</td>
              <td>{{ $list['email'] }}</td>
              <td>{{ $list['phone'] }}</td>
              <td>{{ date('d-m-Y', strtotime($list['gd_pi_date'])) }}</td> 
              <td><p><input type="checkbox" name="studentid[]" value="{{ $list['regid'] }}" class="lcs_check" autocomplete="off" /></p></td>
              
            </tr>
            @endforeach
          </tbody>
        </table>
        <button class="btn btn-primary" onclick="submitattendance();">Submit Attendance</button>
      </div>
    </div>
  </div>
  <!--End row-->



</div>
<!--End row-->

</div>
<!-- End Wrapper-->

<div  id="student-attendance-model" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
    <form method="POST" action="{{ route('gdpiattendancesubmit') }}"  data-parsley-validate>
    @csrf
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Submit Attendance</h4>
      </div>
      <div class="modal-body">
        <div id="">Total Students : <span id="studentscount">200</span></div><hr>
        <div class="form-group">
          <label class="control-label">Select GDPI Date</label>
          <select class="form-control" name="gd_pi_date" required="">
          <option value="" disabled="" selected="">Select GDPI Date</option>
          @foreach($gdpidates as $date)
          <option value="{{ $date->gd_pi_date }}">{{ date('d-m-Y', strtotime($date->gd_pi_date)) }}</option>
          @endforeach
          </select>

          <label class="control-label">Select GDPI Center</label>
          <select class="form-control" name="gd_pi_center">
          <option value="" disabled="" selected="">Select Location</option>
          @foreach($gdpilocations as $location)
          <option value="{{ $location->gd_pi_center }}">{{ $location->gd_pi_center }}</option>
          @endforeach
          </select>
          <input type="hidden" name="studentlist" id="studentsarray">
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>

  </div>
</div>

@push('bottomscripts')
    
<script src="{{ asset('js/lc_switch.js') }}"></script>
    <script type="text/javascript">
    
        $('input').lc_switch('Present', 'Absent');
    
        // triggered each time a field changes status
        $(document).on('.lcs_check', function() {
            var status  = ($(this).is(':checked')) ? 'checked' : 'unchecked',
                num     = $(this).val(); 
                console.log(status);
        });
    
    </script>

    <script type="text/javascript">
      function submitattendance() {
        var studentsarray = [];
        
        $("input:checkbox[name='studentid[]']:checked").each(function(){
            studentsarray.push($(this).val());
        });
        
        
        
        if(studentsarray.length <= 0) {
          alert('Please Select Students attended');
        }
        else {
          //Open Modal And Pass Values to Form
          /*event.preventDefault();*/
          /*jQuery.noConflict();*/
          var studentscount = studentsarray.length;
          var studentsarray = JSON.stringify(studentsarray);
          console.log(studentsarray);
          $('#studentscount').html(studentscount);
          $('#studentsarray').val(studentsarray);
          $('#student-attendance-model').modal('show');
        }
        
    }
    </script>
@endpush
@endsection