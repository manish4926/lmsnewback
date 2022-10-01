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
    <h4 class="page-title">Admissions</h4>
    <ol class="breadcrumb">
      <li>
        <a href="#">Dashboard</a>
      </li>
      <li>
        <a href="#">View</a>
      </li>
      <li class="active">
        Admissions
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

        <a class="btn btn-primary center-margin" onclick="addadmission()">Add New Admission</a><br><br>
        <table class="table table-striped table-bordered table-condensed word-breaker">
          <thead>
            <tr>
              <th>S. No.</th>
              <th>Reg No</th>
              <th>Name</th>
              <th>Course</th>
              <th>Fee Last Date</th>
              <th>Amount Paid</th>
              <th>Balance Amount</th>
              <th>Date</th>{{-- Same Date --}}
              <th>Hostel Fee</th>{{-- Reattempt --}}
              <th>Admission Date</th>
              <th>GD/PI Date</th>
              
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; ?>
            @foreach($admission as $list)
            <tr>
              <td>{{ $i++ }}</td>
              <td onclick="updateadmissionmodal('{{ $list['regid'] }}')">{{ $list['regid'] }}</td>
              <td>{{ $list['name'] }}</td>
              <td>{{ $list['course'] }}</td>
              <td>{{ $list['fee_last_date'] }}</td>
              <td>{{ $list['amount_paid'] }}</td>
              <td>{{ $list['balance_amount'] }}</td>
              <td>{{ $list['xdate'] }}</td>
              <td>{{ $list['hostel_fee'] }}</td>
              <td>{{ date('d-m-Y', strtotime($list['admission_date'])) }}</td>
              <td>{{ date('d-m-Y', strtotime($list['gdpi_date'])) }}</td>
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


<!-- Small Modal -->
<div  id="modal-add-admission" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Admission</h4>
      </div>
      
      <div class="modal-body">
        <label>Please Enter Registration Id</label>
        <input type="text" class="form-control" name="registration_id" id="registration_id" value=""><br>
      </div>
      <div class="modal-footer">
        <input type="button" class="btn btn-primary" onclick="addadmissionsubmit()" value="Submit">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
 <!-- END Small Modal -->


 <!-- Small Modal -->
<div  id="modal-update-admission" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Admission</h4>
      </div>
      
      <form method="POST" action="{{ route('admissionssubmit') }}" class="form-horizontal"  data-parsley-validate>
        @csrf
      <div class="modal-body">
        <label>Registration Id</label>
        <input type="text" class="form-control" name="reg_id" id="reg_id" value="" readonly=""><br>
        <label>Student Name</label>
        <input type="text" class="form-control" name="student_name" id="student_name" value="" readonly=""><br>
        <label>Course</label>
        <select class="form-control" name="course" id="course" required="">
          <option disabled="" selected="">Program Interested in</option>
          <option value="PGDM">PGDM</option>
          <option value="PGDM-IB">PGDM-IB</option>
          <option value="PGDM-RM">PGDM-RM</option>
          <option value="PGDM-PT">PGDM-PT</option>
          <option value="BBA">BBA</option>
          <option value="BCA">BCA</option>
          <option value="MCA">MCA</option>
          <option value="FPM programme">FPM programme</option>
        </select><br>
        <label>Fee Last Date</label>
        <input type="text" class="form-control datepicker1" name="fee_last_date" id="fee_last_date" value=""><br>
        <label>Amount Paid</label>
        <input type="number" class="form-control" name="amount_paid" id="amount_paid" value=""><br>
        <label>Balance Amount</label>
        <input type="number" class="form-control" name="balance_amount" id="balance_amount" value=""><br>
        <label>Date</label>
        <input type="text" class="form-control datepicker" name="xdate" id="xdate" value=""><br>
        <label>Hostel Fee</label>
        <input type="text" class="form-control" name="hostel_fee" id="hostel_fee" value=""><br>
        <label>Admission Date</label>
        <input type="text" class="form-control datepicker" name="admission_date" id="admission_date" value=""><br>
        <label>GDPI Date</label>
        <input type="text" class="form-control" name="gdpi_date" id="gdpi_date" value="" readonly=""><br>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary" name="updatesubmit" value="Submit">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>

  </div>
</div>
 <!-- END Small Modal -->

@push('bottomscripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
  $('.datepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      endDate: '0d',
      autoclose: true
  });

  $('.datepicker1').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true
  });
</script>


<script type="text/javascript">
function addadmission() {
  $('#modal-add-admission').modal('show');
}

function addadmissionsubmit() {
  var regid = $('#registration_id').val();

  $.ajaxSetup({
    headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
  });
  $.ajax({
      type  : 'GET',
      url   : '{{ route('checkadmissionform') }}',
      data : {regid: regid , '_token': $('input[name=_token]').val()},
    success: function(result){
      var output = "";
      console.log(result);
      if(result == 'false') {
        alert('Invalid Registration Id');
      } 
      else if(result == 'exist') {
        alert('Admission Already Exist');
      }
      else {
        $('#modal-update-admission').modal('show');      
        $('#reg_id').val(regid);
        $('#student_name').val(result['name']);
        $('#gdpi_date').val(result['gdpidate']);
        //console.log(result['email']);
      }
      //$('#userdetail').html(output);
    }           
  });
  
  
}


function updateadmissionmodal(regid) {
  $.ajaxSetup({
    headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
  });
  $.ajax({
      type  : 'GET',
      url   : '{{ route('getadmissionsdata') }}',
      data : {regid: regid , '_token': $('input[name=_token]').val()},
    success: function(result){
      var output = "";
      console.log(result);
      
      $('#modal-update-admission').modal('show');      
      $('#reg_id').val(regid);
      $('#student_name').val(result['name']);
      //$('#course option[value=result['course']]').attr('selected','selected');
      $('#course').val(value=result['course']);
      $('#fee_last_date').val(result['fee_last_date']);
      $('#amount_paid').val(result['amount_paid']);
      $('#balance_amount').val(result['balance_amount']);
      $('#xdate').val(result['xdate']);
      $('#hostel_fee').val(result['hostel_fee']);
      $('#admission_date').val(result['admissiondate']);
      $('#gdpi_date').val(result['gdpidate']);
        //console.log(result['email']);
      //$('#userdetail').html(output);
    }           
  });
}
</script>
@endpush
@endsection