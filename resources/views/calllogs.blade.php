@extends('master')

@section('content')
@push('topscripts')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
@endpush

<!--body wrapper start-->
<div class="wrapper">

  <!--Start Page Title-->
  <div class="page-title-box">
    <h4 class="page-title">Call Logs</h4>
    <ol class="breadcrumb">
      <li>
        <a href="#">Dashboard</a>
      </li>
      <li>
        <a href="#">List</a>
      </li>
      <li class="active">
        Call Logs
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
      <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="{{ $followupnum == 1 ? 'active' : ''}}"><a href="{{ route('calllogs', ['followupnum' => 1]) }}">First </a></li>
          <li role="presentation" class="{{ $followupnum == 2 ? 'active' : ''}}"><a href="{{ route('calllogs', ['followupnum' => 2]) }}">Second</a></li>
          <li role="presentation" class="{{ $followupnum == 3 ? 'active' : ''}}"><a href="{{ route('calllogs', ['followupnum' => 3]) }}">Third</a></li>
          <li role="presentation" class="{{ $followupnum == 4 ? 'active' : ''}}"><a href="{{ route('calllogs', ['followupnum' => 4]) }}">Fourth</a></li>
          <li role="presentation" class="{{ $followupnum == 5 ? 'active' : ''}}"><a href="{{ route('calllogs', ['followupnum' => 5]) }}">Fifth</a></li>
        </ul>

      <form method="POST" action="{{ route('calllogs',['followupnum' => $followupnum]) }}"  class="inline-form" data-parsley-validate>
        @csrf
        <input type="text" class="form-control datepicker" name="startdate" placeholder="Start Date" value="{{ !empty($startdate) ? date('d-m-Y', strtotime($startdate)) : ''}}" required="" style="width: 300px; display: inline;">
        <input type="text" class="form-control datepicker" name="enddate" placeholder="End Date" value="{{ !empty($enddate) ? date('d-m-Y', strtotime($enddate)) : ''}}" required="" style="width: 300px; display: inline;">
        <input type="submit" class="btn btn-primary" value="Submit" name="submit"><br><br>
      </form>

      <h2 class="header-title">Select Fields</h2>


      <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
       <thead>
         <tr>
          <th>ID</th>
          <th>Call Time</th>
          @if($user->hasRole('Admin'))
          <th>Counsellor Name</th>
          @endif
          <th>Follow Up</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Source</th>
          <th></th>
        </tr>
      </thead>
      <tbody>

       @foreach($list as $forms)
       <tr class="odd gradeX {{ $forms->status == 1 ? 'success' : ''}}">
        <td>{{ $forms->formid }}</td>
        <td>{{ $forms->updated_at }}</td>
        @if($user->hasRole('Admin')) <td>{{ $counsellors[$forms->counsellor_id]['firstname'] }}</td> @endif
        <td>{{ followuptext($forms->level) }}</td>
        <td>{{ $forms->name }}</td>
        <td onclick="opendetail('{{ $forms->form_email }}')">{{ $forms->form_email }}</td>
        <td>{{ $forms->phone }}</td>
        <td>{{ $forms->source }}</td>
        <td><a target="_blank" href="{{ route('followupdetail',['id' => $forms->formid])}}">View</a></td>
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
</script>
@endpush
@endsection