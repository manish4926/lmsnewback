@extends('master')

@section('content')
@push('topscripts')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
@endpush
<!--body wrapper start-->
<div class="wrapper">

  <!--Start Page Title-->
  <div class="page-title-box">
    <h4 class="page-title">Form Comparision Report</h4>
    <ol class="breadcrumb">
      <li>
        <a href="#">Dashboard</a>
      </li>
      <li>
        <a href="#">View</a>
      </li>
      <li class="active">
        Form Comparision Report
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
         
         {{--<form method="POST" action="{{ route('formconversionreport') }}" class="inline-form"  data-parsley-validate>
          @csrf
        <input type="text" class="form-control datepicker" name="startdate" placeholder="Start Date" required="" style="width: 300px; display: inline;">
        <input type="text" class="form-control datepicker" name="enddate" placeholder="End Date" required="" style="width: 300px; display: inline;">
        <input type="submit" class="btn btn-primary" value="Submit" name="submit"><br><br>
        </form> --}}

        <code>
          /**<br>
           * Enquiry Status<br>
           * Call defined as Home Page enquiry + Google + Facebook + helpline + landline not from shiksha, carieer 360 or mbauniverse<br>
           * Email defined as Inner Page enquiry<br>
           * Walkin refer to walkin only<br>
           * Chat refer to chat only<br>
           */
        </code>
        
        @if(!empty($currentyeardata))
        <h4>Form Conversion Report</h4>
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th colspan="11"><center>STATUS - 23-05-18</center></th>
            </tr>
            <tr>
              <th><center>YEAR</center></th>
              <th colspan="5"><center>FORM SOLD</center></th>
              <th colspan="5"><center>ENQUIRY STATUS</center></th>
            </tr>
            <tr>
              <th></th>
              <th>CASH</th>
              <th>DD</th>
              <th>ONLINE</th>
              <th>TOTAL SALE</th>
              <th></th>
              <th>CALLS</th>
              <th>WALKIN</th>
              <th>EMAIL</th>
              <th>CHATS</th>
              <th>TOTAL</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>{{ $currentyeardata['formsale']['year']."-".($currentyeardata['formsale']['year']+2) }}<br>(Current Year)</strong></td>
              <td>{{ $currentyeardata['formsale']['Cash'] }}</td>
              <td>{{ $currentyeardata['formsale']['DD'] }}</td>
              <td>{{ $currentyeardata['formsale']['Online'] }}</td>
              <td>{{ $currentyeardata['formsale']['total'] }}</td>
              <td></td>
              <td>{{ $currentyeardata['enquirystatus']['calls'] }}</td>
              <td>{{ $currentyeardata['enquirystatus']['walkin'] }}</td>
              <td>{{ $currentyeardata['enquirystatus']['email'] }}</td>
              <td>{{ $currentyeardata['enquirystatus']['chats'] }}</td>
              <td>{{ $currentyeardata['enquirystatus']['total'] }}</td>
            </tr>
            <tr>
              <td><strong>2017-19<br>(Last Year)</strong></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><strong>DIFFERENCE</strong></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><strong>PERCENTAGE <br>(DECLINE/INCREASE)</strong></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>

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