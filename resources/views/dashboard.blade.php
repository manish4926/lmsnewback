@extends('master')

@section('content')
@push('topscripts')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/morris-chart/morris.css') }}">
@endpush



<!--body wrapper start-->
<div class="wrapper">

  <!--Start Page Title-->
  <div class="page-title-box">
    <h4 class="page-title">Dashboard </h4>
    <ol class="breadcrumb">
      <li>
        <a href="#">Dashboard</a>
      </li>

      <li class="active">
        Dashboard 1
      </li>
    </ol>
    <div class="clearfix"></div>
  </div>
  <!--End Page Title-->          




  
  <div class="white-box">
    <h4 class="header-title">Admin Registraion</h4>
    <div class="button-wrap">
      <a href="{{ route('register') }}" class="btn btn-primary">Register New User</a>
      <a href="{{ route('assignroles') }}" class="btn btn-primary">Assign Roles</a>

    </div>
  </div>
  <!--End row-->
  <div class="white-box">
    <h4 class="header-title">Reports</h4>
    <div class="button-wrap">
      <a href="{{ route('downloadcompletewithremarksexcel') }}" class="btn btn-primary">Download Remarks Report</a>
      <a class="btn btn-primary center-margin" href="{{ route('downloadcompleteexcel') }}">Download Complete Excel</a>
      <a class="btn btn-primary center-margin" href="{{ route('downloaduniqueexcel') }}">Download Unique Excel</a>
      <a class="btn btn-primary center-margin" href="{{ route('formConversionReportExcel') }}">Form Conversion Report Excel</a>
      <hr>
      <a href="{{ route('counsellorprogress') }}" class="btn btn-primary">Counsellor Progress</a>
      {{-- <a href="{{ route('formcomparisionreport') }}" class="btn btn-primary">Form Comparision Report</a> --}}
      <a href="{{ route('dailyreport') }}" class="btn btn-primary">Daily Report</a>
      <a href="{{ route('gdpireport') }}" class="btn btn-primary">GD/PI Report</a>
      <a href="{{ route('gdpiSourcereport') }}" class="btn btn-primary">Form Conversion Report</a>
      {{-- <a href="{{ route('sourcequalityreport') }}" class="btn btn-primary">Source Quality Report</a> --}}
      <a href="{{ route('sourcequalityreporttwo') }}" class="btn btn-primary">Lead Quality Report</a>
      <a href="{{ route('sourcequalityreportthree') }}" class="btn btn-primary">Lead Flow Chart</a>
      <a href="{{ route('dailycounsellordata') }}" class="btn btn-primary">Counsellor Followup Record</a>
      <a href="{{ route('counsellorgraph') }}" class="btn btn-primary">Counsellor Graph</a>
      <a href="{{ route('dailyassigneddata') }}" class="btn btn-primary">Daily Assigned Data</a>
      <hr>
      <a href="{{ route('gdpiattendance') }}" class="btn btn-primary">GDPI Attendance</a>
      <a href="{{ route('admissionslist') }}" class="btn btn-primary">Admission Taken</a>
      <a href="{{ route('sourceadmissionrecord') }}" class="btn btn-primary">Source Admission Record</a>
      <hr>
      <a href="{{ route('automateqeryflow') }}" class="btn btn-primary">Auto Assign Data</a>
      
      @if($user->firstname == "Manish" OR $user->firstname == "Raj")
      <a href="{{ route('automateemailflow') }}" class="btn btn-primary">Email Automation</a>
      @endif
    </div>

  </div>


</div>


<!-- End Wrapper-->
@push('bottomscripts')


@endpush
@endsection