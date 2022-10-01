@extends('master')

@section('content')
@push('topscripts')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">

<style type="text/css">
  /* Code based on this sample http://thecodeplayer.com/walkthrough/css3-family-tree */
body {
  overflow-x: auto;
}
/*Now the CSS*/
.tree * {
  margin: 0;
  padding: 0;
}

.tree ul {
  padding-top: 20px;
  position: relative;

  transition: all 0.5s;
  -webkit-transition: all 0.5s;
  -moz-transition: all 0.5s;
}

.tree li {
  float: left;
  text-align: center;
  list-style-type: none;
  position: relative;
  padding: 20px 5px 0 5px;

  transition: all 0.5s;
  -webkit-transition: all 0.5s;
  -moz-transition: all 0.5s;
}

/*We will use ::before and ::after to draw the connectors*/

.tree li::before,
.tree li::after {
  content: "";
  position: absolute;
  top: 0;
  right: 50%;
  border-top: 1px solid #8dc63f;
  width: 50%;
  height: 20px;
}
.tree li::after {
  right: auto;
  left: 50%;
  border-left: 1px solid #8dc63f;
}

/*We need to remove left-right connectors from elements without 
any siblings*/
.tree li:only-child::after,
.tree li:only-child::before {
  display: none;
}

/*Remove space from the top of single children*/
.tree li:only-child {
  padding-top: 0;
}

/*Remove left connector from first child and 
right connector from last child*/
.tree li:first-child::before,
.tree li:last-child::after {
  border: 0 none;
}

/*Adding back the vertical connector to the last nodes*/
.tree li:last-child::before {
  border-right: 1px solid #8dc63f;
  border-radius: 0 5px 0 0;
  -webkit-border-radius: 0 5px 0 0;
  -moz-border-radius: 0 5px 0 0;
}
.tree li:first-child::after {
  border-radius: 5px 0 0 0;
  -webkit-border-radius: 5px 0 0 0;
  -moz-border-radius: 5px 0 0 0;
}

/*Time to add downward connectors from parents*/
.tree ul ul::before {
  content: "";
  position: absolute;
  top: 0;
  left: 50%;
  border-left: 1px solid #8dc63f;
  width: 0;
  height: 20px;
}

.tree li a {
  border: 1px solid #8dc63f;
  padding: 1em 0.75em;
  text-decoration: none;
  color: #666767;
  font-family: arial, verdana, tahoma;
  font-size: 0.85em;
  display: inline-block;

  /*
  border-radius: 5px;
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
  */

  transition: all 0.5s;
  -webkit-transition: all 0.5s;
  -moz-transition: all 0.5s;
}

/* -------------------------------- */
/* Now starts the vertical elements */
/* -------------------------------- */

.tree ul.vertical,
ul.vertical ul {
  padding-top: 0px;
  left: 50%;
}

/* Remove the downward connectors from parents */
.tree ul ul.vertical::before {
  display: none;
}

.tree ul.vertical li {
  float: none;
  text-align: left;
}

.tree ul.vertical li::before {
  right: auto;
  border: none;
}

.tree ul.vertical li::after {
  display: none;
}

.tree ul.vertical li a {
  padding: 10px 0.75em;
  margin-left: 16px;
}

.tree ul.vertical li::before {
  top: -20px;
  left: 0px;
  border-bottom: 1px solid #8dc63f;
  border-left: 1px solid #8dc63f;
  width: 20px;
  height: 60px;
}

.tree ul.vertical li:first-child::before {
  top: 0px;
  height: 40px;
}

/* Lets add some extra styles */

div.tree > ul > li > ul > li > a {
  width: 11em;
}

div.tree > ul > li > a {
  font-size: 1em;
  font-weight: bold;
}

/* ------------------------------------------------------------------ */
/* Time for some hover effects                                        */
/* We will apply the hover effect the the lineage of the element also */
/* ------------------------------------------------------------------ */
.tree li a:hover,
.tree li a:hover + ul li a {
  background: #8dc63f;
  color: white;
  /* border: 1px solid #aaa; */
}
/*Connector styles on hover*/
.tree li a:hover + ul li::after,
.tree li a:hover + ul li::before,
.tree li a:hover + ul::before,
.tree li a:hover + ul ul::before {
  border-color: #aaa;
}

.ml-table tr td {
    width: 100px;
    /*font-size: 18px;*/
}

.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    min-width: 300px;
    width: 300px;
    font-size: 15px;
}

.table {
  margin-left: 20px;
  margin-top: 10px;
}

</style>
@endpush
<!--body wrapper start-->
<div class="wrapper" >

  <!--Start Page Title-->
  <div class="page-title-box">
    <h4 class="page-title">Lead Flow Chart</h4>
    <ol class="breadcrumb">
      <li>
        <a href="#">Dashboard</a>
      </li>
      <li>
        <a href="#">View</a>
      </li>
      <li class="active">
        Lead Flow Chart
      </li>
    </ol>
    <div class="clearfix"></div>
  </div>
  <!--End Page Title-->          
  @include('partials.alerts')

  <!--Start row-->
  <div class="row">
    <div class="col-md-12">
      <div class="white-box" style="width: 10000px;">

        <code>
          /**<br>
         * This Data Does not Contain <br>
         * BBA, BCA, MCA, Fellowship Programme in Management, FPM, FPM programme<br>
         */
        </code><br><br>

        <form method="POST" action="{{ route('sourcequalityreportthree') }}" class="inline-form"  data-parsley-validate>
          @csrf
        <select class="form-control" name="search">
          <option value="" disabled="" selected="">Select Source</option>
          <option>All</option>
          @foreach($sources as $source)
          <option value="{{ $source->source }}">{{ $source->source }}</option>
          @endforeach
        </select>
                        
        <input type="text" class="form-control datepicker" name="startdate" placeholder="Start Date" value="{{ !empty($startdate) ? date('d-m-Y', strtotime($startdate)) : ''}}" required="" style="width: 300px; display: inline;">
        <input type="text" class="form-control datepicker" name="enddate" placeholder="End Date" value="{{ !empty($enddate) ? date('d-m-Y', strtotime($enddate)) : ''}}" required="" style="width: 300px; display: inline;">
        <input type="submit" class="btn btn-primary" value="Submit" name="submit"><br><br>
        </form>
        
        <h4>Lead Flow Chart</h4>
        <div class="tree" style="width: 10000px;">
          <ul>
              <li>
                  <a href="#">Total Leads: {{ $totalleads }}</a>
                  <ul>
                  @php RecursiveWrite($trees) @endphp
                  </ul>
                  
                  
              </li>
          </ul>
        </div>

        
      </div>

      <div class="white-box">
        <div>
          
          <ul>
            <li>Total Leads: {{ $totalleads }}</li>
            <li>
              <ul>
                <li>
                  @php RecursiveWriteSection($trees) @endphp
                </li>
              </ul>
            </li>
          </ul>
          
        
        </div>
        {{-- <table class="table table-bordered ml-table">
          <tr>
            <td>Total Leads: {{ $totalleads }}</td>
          </tr>
          <tr>
            <td>
              @php RecursiveWriteTable($trees) @endphp
            </td>
          </tr>
        </table> --}}
      </div>
    </div>
  </div>
  <!--End row-->



</div>
<!--End row-->

</div>
<!-- End Wrapper-->

@php
function RecursiveWrite($array) {
    echo "<ul>";
    //dd($array);
    foreach ($array as $key => $vals) {
        //echo $vals['id'] . "\n";
      /*if($key == 'count') {
        continue;
      }*/
      if(is_int($key)): // AND $key <= 1
      echo "
        <li>
          <a href='#'>Followup {$key}</a>
          <ul>";

          if(!empty($vals['junk_leads'])) {
            echo "<li><a href='#'>Junk Leads: {$vals['junk_leads']['count'] }</a>
            ";
              RecursiveWrite($vals['junk_leads']); 
            echo "</li>";
            
          }
          if(!empty($vals['positive_leads'])) {
            echo "<li><a href='#'>Positive Leads: {$vals['positive_leads']['count'] }</a>";
              RecursiveWrite($vals['positive_leads']); 
            echo "</li>";
            
          }
          if(!empty($vals['not_interested_leads'])) {
            echo "<li><a href='#'>Not Interested Leads: {$vals['not_interested_leads']['count'] }</a>";
              RecursiveWrite($vals['not_interested_leads']); 
            echo "</li>";
            
          }
          if(!empty($vals['not_called'])) {
            echo "<li><a href='#'>Not Called Leads: {$vals['not_called']['count'] }</a>";
              //RecursiveWrite($vals['not_called']); 
            echo "</li>";
            
          }
      echo "
        </ul>
      </li>

       ";
      endif;
    }
    echo "</ul>";
}

function RecursiveWriteTable($array) {
    echo "<table class='table table-bordered'><tr>";
    
    foreach ($array as $key => $vals) {
      
      if(is_int($key)): // AND $key <= 1
        $print_key = $key + 1;
      echo "
        <td>
          <b>Followup {$print_key}</b>
          <tr>";

          if(!empty($vals['junk_leads'])) {
            echo "<td>Junk Leads: {$vals['junk_leads']['count'] }";
              RecursiveWriteTable($vals['junk_leads']); 
            echo "</td>";
            
          }
          if(!empty($vals['positive_leads'])) {
            echo "<td>Positive Leads: {$vals['positive_leads']['count'] }";
              RecursiveWriteTable($vals['positive_leads']); 
            echo "</td>";
            
          }
          if(!empty($vals['not_interested_leads'])) {
            echo "<td>Not Interested Leads: {$vals['not_interested_leads']['count'] }";
              RecursiveWriteTable($vals['not_interested_leads']); 
            echo "</td>";
            
          }
          if(!empty($vals['not_called'])) {
            echo "<td>Not Called Leads: {$vals['not_called']['count'] }";
              //RecursiveWriteTable($vals['not_called']); 
            echo "</td>";
            
          }
      echo "
        </tr>
      </td>

       ";
      endif;
    }
    echo "</tr></table>";
}

function RecursiveWriteSection($array) {
  echo "<ul>";
    
    foreach ($array as $key => $vals) {
      
      if(is_int($key)): // AND $key <= 1
        $print_key = $key + 1;
      echo "
        <li>
          <b>Followup {$print_key}</b>
          <ul>";

          if(!empty($vals['junk_leads'])) {
            echo "<li>Junk Leads: {$vals['junk_leads']['count'] }";
              RecursiveWriteSection($vals['junk_leads']); 
            echo "</li>";
            
          }
          if(!empty($vals['positive_leads'])) {
            echo "<li>Positive Leads: {$vals['positive_leads']['count'] }";
              RecursiveWriteSection($vals['positive_leads']); 
            echo "</li>";
            
          }
          if(!empty($vals['not_interested_leads'])) {
            echo "<li>Not Interested Leads: {$vals['not_interested_leads']['count'] }";
              RecursiveWriteSection($vals['not_interested_leads']); 
            echo "</li>";
            
          }
          if(!empty($vals['not_called'])) {
            echo "<li>Not Called Leads: {$vals['not_called']['count'] }";
              //RecursiveWriteSection($vals['not_called']); 
            echo "</li>";
            
          }
      echo "
        </ul>
      </li>

       ";
      endif;
    }
    echo "</ul>";
}
@endphp

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

