@extends('master')

@section('content')
@push('topscripts')
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/morris-chart/morris.css') }}">
@endpush
    


        <!--body wrapper start-->
        <div class="wrapper">
              
          <!--Start Page Title-->
           <div class="page-title-box">
                <h4 class="page-title">Counsellor Graph </h4>
                <ol class="breadcrumb">
                    <li>
                        <a href="#">Dashboard</a>
                    </li>
                    
                    <li class="active">
                        Counsellor Graph
                    </li>
                </ol>
                <div class="clearfix"></div>
             </div>
              <!--End Page Title-->          
           
                 
              
                  
                <!--Start row-->  
                <div class="row">
                       <div class="col-md-12">
                        <div class="white-box">
                          <div id="chart_div" style="height: 500px;"></div>
                        </div>
                      </div>
					
                   </div>
                   <!--End row-->
			   
			    </div>
        <!-- End Wrapper-->
@push('bottomscripts')
<script src="{{ asset('plugins/jquery-sparkline/jquery.charts-sparkline.js') }}"></script>
<script src="{{ asset('plugins/morris-chart/morris.js') }}"></script>
<script src="{{ asset('plugins/morris-chart/raphael-min.js') }}"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var google_data = {!! json_encode($google_array) !!};
        

        var data = google.visualization.arrayToDataTable(google_data);

        var options = {
          title : 'Counsellor Daily Calling Graph',
          vAxis: {title: 'Calls'},
          hAxis: {title: 'Days'},
          seriesType: 'bars',
          series: {5: {type: 'line'}}
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>

@endpush
@endsection