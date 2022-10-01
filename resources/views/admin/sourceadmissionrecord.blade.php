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
    <h4 class="page-title">Source Admission Record</h4>
    <ol class="breadcrumb">
      <li>
        <a href="#">Dashboard</a>
      </li>
      <li>
        <a href="#">View</a>
      </li>
      <li class="active">
        Source Admission Record
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

        
        
        <h4>Source Admission Record</h4>
        <div class="table-responsive">
          <table class="table table-striped table-bordered">
            @if(!empty($sourcedata))
            <thead>
              <tr>
                <th>Lead Remarks</th>
                
                @foreach(reset($sourcedata) as $key => $list)
                  <th>{{ ucfirst($key) }}</th>
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

@endpush
@endsection