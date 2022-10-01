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
    <h4 class="page-title">GDPI Report</h4>
    <ol class="breadcrumb">
      <li>
        <a href="#">Dashboard</a>
      </li>
      <li>
        <a href="#">View</a>
      </li>
      <li class="active">
        GDPI Report
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
              <th>Center</th>
              <th>Date</th>
              <th>Applicants</th>
              <th>Appeared</th>
              <th>Percentage</th>
              <th>Absent</th>
              <th>Form Sold</th>{{-- Same Date --}}
              <th>Extra</th>{{-- Reattempt --}}
              <th>Remarks</th>
              <th>Total Appeared</th>
              
            </tr>
          </thead>
          <tbody>
            @foreach($gdpireport as $list)
            <tr>
              <td>{{ $list['gd_pi_center'] }}</td>
              <td>{{ !empty($list['gd_pi_date']) ? date('Y-m-d', strtotime($list['gd_pi_date'])) : "Not Decided" }}</td>
              <td>{{ $list['applicants'] }}</td>
              <td>{{ $list['appeared'] }}</td>
              <td>{{ $list['percentage'] }} %</td>
              <td>{{ $list['absent'] }}</td>
              <td>{{ $list['form_sold'] }}</td>
              <td onclick="updateremarks('{{ $list['gd_pi_date'] }}')">{{ $list['extraforms'] }}</td>
              <td onclick="updateremarks('{{ $list['gd_pi_date'] }}')">{{ $list['remarks'] }}</td>
              <td>{{ $list['totalappeared'] }}</td>
              
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
<div  id="modal-update-record" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update Remarks for <span id="remarksdate"></span></h4>
      </div>
      <form method="POST" action="{{ route('updatedgpiremarks') }}" class="form-horizontal"  data-parsley-validate>
        @csrf
      <div class="modal-body">
        
            <label>GDPI Date:</label>
            <input type="text" class="form-control" name="gdpidate" id="gdpidate" value="" readonly=""><br>
            <label>Extra Forms:</label>
            <input type="number" class="form-control" name="extraforms" value="" required="">
            <label>Remarks:</label>
            <input type="text" class="form-control" name="remarks" value="" required="">
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
<script type="text/javascript">
function updateremarks(date) {
  $('#remarksdate').val(date);
  $('#gdpidate').val(date);
  $('#modal-update-record').modal('show');
}
</script>
@endpush
@endsection